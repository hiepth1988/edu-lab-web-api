<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Concerns\EnforcesPublishPermission;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    use EnforcesPublishPermission;

    public function index(): JsonResponse
    {
        $projects = Project::with(['translations', 'metrics.translations'])
            ->latest()
            ->get();

        return response()->json(['data' => $projects]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $project = Project::create([
            'status' => $data['status'],
            'featured_image' => $data['featured_image'] ?? null,
            'published_at' => $data['published_at'] ?? ($data['status'] === 'published' ? now() : null),
        ]);

        $this->syncTranslations($project, $data['translations']);
        $this->syncMetrics($project, $data['metrics'] ?? []);
        $this->syncSectionImages($project, $data['section_images'] ?? []);

        return response()->json(['data' => $this->loadProject($project)], 201);
    }

    public function show(Project $project): JsonResponse
    {
        return response()->json(['data' => $this->loadProject($project)]);
    }

    public function update(Request $request, Project $project): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $project->update([
            'status' => $data['status'],
            'featured_image' => $data['featured_image'] ?? null,
            'published_at' => $data['published_at'] ?? $project->published_at,
        ]);

        $this->syncTranslations($project, $data['translations']);
        $this->syncMetrics($project, $data['metrics'] ?? []);
        $this->syncSectionImages($project, $data['section_images'] ?? []);

        return response()->json(['data' => $this->loadProject($project->fresh())]);
    }

    public function destroy(Project $project): JsonResponse
    {
        $project->delete();

        return response()->json(null, 204);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'status' => ['required', 'in:draft,published'],
            'featured_image' => ['nullable', 'string', 'max:2048'],
            'published_at' => ['nullable', 'date'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
            'translations.*.excerpt' => ['nullable', 'string'],
            'translations.*.problem' => ['nullable', 'string'],
            'translations.*.solution_text' => ['nullable', 'string'],
            'translations.*.result' => ['nullable', 'string'],
            'translations.*.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.*.meta_description' => ['nullable', 'string'],
            'metrics' => ['array'],
            'metrics.*.value' => ['required_with:metrics', 'string', 'max:255'],
            'metrics.*.translations' => ['array'],
            'section_images' => ['array'],
            'section_images.*.section' => ['required_with:section_images', 'in:problem,solution,result'],
            'section_images.*.image_url' => ['required_with:section_images', 'string', 'max:2048'],
        ]);
    }

    private function syncTranslations(Project $project, array $translations): void
    {
        foreach ($translations as $locale => $payload) {
            if (empty($payload['title'])) {
                continue;
            }

            $project->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $payload['title'],
                    'slug' => $payload['slug'] ?? Str::slug($payload['title']),
                    'excerpt' => $payload['excerpt'] ?? null,
                    'problem' => $payload['problem'] ?? null,
                    'solution_text' => $payload['solution_text'] ?? null,
                    'result' => $payload['result'] ?? null,
                    'meta_title' => $payload['meta_title'] ?? null,
                    'meta_description' => $payload['meta_description'] ?? null,
                ]
            );
        }
    }

    private function syncMetrics(Project $project, array $metrics): void
    {
        $project->metrics()->delete();

        foreach ($metrics as $index => $metric) {
            if (empty($metric['value'])) {
                continue;
            }

            $model = $project->metrics()->create(['value' => $metric['value'], 'sort_order' => $index]);

            foreach ($metric['translations'] ?? [] as $locale => $payload) {
                if (empty($payload['label'])) {
                    continue;
                }

                $model->translations()->create(['locale' => $locale, 'label' => $payload['label']]);
            }
        }
    }

    private function syncSectionImages(Project $project, array $images): void
    {
        $project->sectionImages()->delete();

        $sortOrders = [];

        foreach ($images as $image) {
            if (empty($image['section']) || empty($image['image_url'])) {
                continue;
            }

            $section = $image['section'];
            $sortOrders[$section] = ($sortOrders[$section] ?? -1) + 1;

            $project->sectionImages()->create([
                'section' => $section,
                'image_url' => $image['image_url'],
                'sort_order' => $sortOrders[$section],
            ]);
        }
    }

    private function loadProject(Project $project): Project
    {
        return $project->load(['translations', 'metrics.translations', 'sectionImages']);
    }
}
