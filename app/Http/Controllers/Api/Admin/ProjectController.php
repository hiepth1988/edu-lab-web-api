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
        $projects = Project::with(['translations', 'category.translations', 'metrics.translations'])
            ->latest()
            ->get();

        return response()->json(['data' => $projects]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $project = Project::create([
            'category_id' => $data['category_id'] ?? null,
            'status' => $data['status'],
            'featured_image' => $data['featured_image'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
            'published_at' => $data['published_at'] ?? ($data['status'] === 'published' ? now() : null),
        ]);

        $this->syncTranslations($project, $data['translations']);
        $this->syncMetrics($project, $data['metrics'] ?? []);
        $this->syncSolutionModules($project, $data['solution_modules'] ?? []);
        $this->syncGalleryImages($project, $data['gallery_images'] ?? []);
        $this->syncRelatedProjects($project, $data['related_project_ids'] ?? []);

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
            'category_id' => $data['category_id'] ?? null,
            'status' => $data['status'],
            'featured_image' => $data['featured_image'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
            'published_at' => $data['published_at'] ?? $project->published_at,
        ]);

        $this->syncTranslations($project, $data['translations']);
        $this->syncMetrics($project, $data['metrics'] ?? []);
        $this->syncSolutionModules($project, $data['solution_modules'] ?? []);
        $this->syncGalleryImages($project, $data['gallery_images'] ?? []);
        $this->syncRelatedProjects($project, $data['related_project_ids'] ?? []);

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
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published'],
            'featured_image' => ['nullable', 'string', 'max:2048'],
            'is_featured' => ['boolean'],
            'published_at' => ['nullable', 'date'],

            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
            'translations.*.excerpt' => ['nullable', 'string'],

            'translations.*.hero_eyebrow' => ['nullable', 'string', 'max:255'],
            'translations.*.hero_cta_label' => ['nullable', 'string', 'max:255'],
            'translations.*.hero_cta_url' => ['nullable', 'string', 'max:2048'],
            'translations.*.hero_badges' => ['array'],
            'translations.*.hero_badges.*.icon' => ['nullable', 'string', 'max:100'],
            'translations.*.hero_badges.*.label' => ['nullable', 'string', 'max:255'],
            'translations.*.hero_stats' => ['array'],
            'translations.*.hero_stats.*.value' => ['nullable', 'string', 'max:100'],
            'translations.*.hero_stats.*.label' => ['nullable', 'string', 'max:255'],

            'translations.*.snapshot_items' => ['array'],
            'translations.*.snapshot_items.*.icon' => ['nullable', 'string', 'max:100'],
            'translations.*.snapshot_items.*.label' => ['nullable', 'string', 'max:255'],
            'translations.*.snapshot_items.*.value' => ['nullable', 'string', 'max:255'],

            'translations.*.scale_heading' => ['nullable', 'string', 'max:255'],
            'translations.*.scale_description' => ['nullable', 'string'],
            'translations.*.scale_stats' => ['array'],
            'translations.*.scale_stats.*.value' => ['nullable', 'string', 'max:100'],
            'translations.*.scale_stats.*.label' => ['nullable', 'string', 'max:255'],

            'translations.*.challenges_heading' => ['nullable', 'string', 'max:255'],
            'translations.*.challenges_description' => ['nullable', 'string'],
            'translations.*.challenges' => ['array'],
            'translations.*.challenges.*.icon' => ['nullable', 'string', 'max:100'],
            'translations.*.challenges.*.color' => ['nullable', 'in:primary,secondary,gold'],
            'translations.*.challenges.*.title' => ['nullable', 'string', 'max:255'],
            'translations.*.challenges.*.description' => ['nullable', 'string'],
            'translations.*.challenges.*.wide' => ['boolean'],

            'translations.*.feature_map_heading' => ['nullable', 'string', 'max:255'],
            'translations.*.feature_groups' => ['array'],
            'translations.*.feature_groups.*.title' => ['nullable', 'string', 'max:255'],
            'translations.*.feature_groups.*.badge_label' => ['nullable', 'string', 'max:100'],
            'translations.*.feature_groups.*.features' => ['array'],
            'translations.*.feature_groups.*.features.*' => ['nullable', 'string', 'max:255'],

            'translations.*.journey_heading' => ['nullable', 'string', 'max:255'],
            'translations.*.journey_steps' => ['array'],
            'translations.*.journey_steps.*.title' => ['nullable', 'string', 'max:255'],
            'translations.*.journey_steps.*.description' => ['nullable', 'string'],

            'translations.*.gallery_heading' => ['nullable', 'string', 'max:255'],
            'translations.*.gallery_categories' => ['array'],
            'translations.*.gallery_categories.*.key' => ['nullable', 'string', 'max:100'],
            'translations.*.gallery_categories.*.label' => ['nullable', 'string', 'max:255'],

            'translations.*.architecture_heading' => ['nullable', 'string', 'max:255'],
            'translations.*.architecture_layers' => ['array'],
            'translations.*.architecture_layers.*.icon' => ['nullable', 'string', 'max:100'],
            'translations.*.architecture_layers.*.title' => ['nullable', 'string', 'max:255'],
            'translations.*.architecture_layers.*.subtitle' => ['nullable', 'string', 'max:255'],

            'translations.*.tech_stack_groups' => ['array'],
            'translations.*.tech_stack_groups.*.title' => ['nullable', 'string', 'max:255'],
            'translations.*.tech_stack_groups.*.items' => ['array'],
            'translations.*.tech_stack_groups.*.items.*' => ['nullable', 'string', 'max:255'],

            'translations.*.results_heading' => ['nullable', 'string', 'max:255'],
            'translations.*.results' => ['array'],
            'translations.*.results.*.icon' => ['nullable', 'string', 'max:100'],
            'translations.*.results.*.color' => ['nullable', 'in:primary,secondary,gold'],
            'translations.*.results.*.value' => ['nullable', 'string', 'max:100'],
            'translations.*.results.*.label' => ['nullable', 'string', 'max:255'],

            'translations.*.lessons_quote' => ['nullable', 'string'],
            'translations.*.lessons_citation' => ['nullable', 'string', 'max:255'],

            'translations.*.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.*.meta_description' => ['nullable', 'string'],
            'translations.*.og_image' => ['nullable', 'string', 'max:2048'],
            'translations.*.canonical_url' => ['nullable', 'string', 'max:2048'],

            'metrics' => ['array'],
            'metrics.*.value' => ['required_with:metrics', 'string', 'max:255'],
            'metrics.*.translations' => ['array'],

            'solution_modules' => ['array'],
            'solution_modules.*.image' => ['nullable', 'string', 'max:2048'],
            'solution_modules.*.translations' => ['array'],
            'solution_modules.*.translations.*.title' => ['nullable', 'string', 'max:255'],
            'solution_modules.*.translations.*.description' => ['nullable', 'string'],
            'solution_modules.*.translations.*.technical_note' => ['nullable', 'string'],
            'solution_modules.*.translations.*.features' => ['array'],
            'solution_modules.*.translations.*.features.*' => ['nullable', 'string', 'max:255'],

            'gallery_images' => ['array'],
            'gallery_images.*.category_key' => ['nullable', 'string', 'max:100'],
            'gallery_images.*.image_url' => ['required_with:gallery_images', 'string', 'max:2048'],

            'related_project_ids' => ['array'],
            'related_project_ids.*' => ['integer', 'exists:projects,id'],
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

                    'hero_eyebrow' => $payload['hero_eyebrow'] ?? null,
                    'hero_cta_label' => $payload['hero_cta_label'] ?? null,
                    'hero_cta_url' => $payload['hero_cta_url'] ?? null,
                    'hero_badges' => $payload['hero_badges'] ?? [],
                    'hero_stats' => $payload['hero_stats'] ?? [],

                    'snapshot_items' => $payload['snapshot_items'] ?? [],

                    'scale_heading' => $payload['scale_heading'] ?? null,
                    'scale_description' => $payload['scale_description'] ?? null,
                    'scale_stats' => $payload['scale_stats'] ?? [],

                    'challenges_heading' => $payload['challenges_heading'] ?? null,
                    'challenges_description' => $payload['challenges_description'] ?? null,
                    'challenges' => $payload['challenges'] ?? [],

                    'feature_map_heading' => $payload['feature_map_heading'] ?? null,
                    'feature_groups' => $payload['feature_groups'] ?? [],

                    'journey_heading' => $payload['journey_heading'] ?? null,
                    'journey_steps' => $payload['journey_steps'] ?? [],

                    'gallery_heading' => $payload['gallery_heading'] ?? null,
                    'gallery_categories' => $payload['gallery_categories'] ?? [],

                    'architecture_heading' => $payload['architecture_heading'] ?? null,
                    'architecture_layers' => $payload['architecture_layers'] ?? [],

                    'tech_stack_groups' => $payload['tech_stack_groups'] ?? [],

                    'results_heading' => $payload['results_heading'] ?? null,
                    'results' => $payload['results'] ?? [],

                    'lessons_quote' => $payload['lessons_quote'] ?? null,
                    'lessons_citation' => $payload['lessons_citation'] ?? null,

                    'meta_title' => $payload['meta_title'] ?? null,
                    'meta_description' => $payload['meta_description'] ?? null,
                    'og_image' => $payload['og_image'] ?? null,
                    'canonical_url' => $payload['canonical_url'] ?? null,
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

    private function syncSolutionModules(Project $project, array $modules): void
    {
        $project->solutionModules()->delete();

        foreach ($modules as $index => $module) {
            $model = $project->solutionModules()->create([
                'image' => $module['image'] ?? null,
                'sort_order' => $index,
            ]);

            foreach ($module['translations'] ?? [] as $locale => $payload) {
                if (empty($payload['title'])) {
                    continue;
                }

                $model->translations()->create([
                    'locale' => $locale,
                    'title' => $payload['title'],
                    'description' => $payload['description'] ?? null,
                    'technical_note' => $payload['technical_note'] ?? null,
                    'features' => $payload['features'] ?? [],
                ]);
            }
        }
    }

    private function syncGalleryImages(Project $project, array $images): void
    {
        $project->galleryImages()->delete();

        foreach ($images as $index => $image) {
            if (empty($image['image_url'])) {
                continue;
            }

            $project->galleryImages()->create([
                'category_key' => $image['category_key'] ?? null,
                'image_url' => $image['image_url'],
                'sort_order' => $index,
            ]);
        }
    }

    private function syncRelatedProjects(Project $project, array $relatedProjectIds): void
    {
        $pivotData = [];

        foreach (array_values($relatedProjectIds) as $index => $relatedProjectId) {
            if ((int) $relatedProjectId === $project->id) {
                continue;
            }

            $pivotData[$relatedProjectId] = ['sort_order' => $index];
        }

        $project->relatedProjects()->sync($pivotData);
    }

    private function loadProject(Project $project): Project
    {
        return $project->load([
            'translations',
            'category.translations',
            'metrics.translations',
            'solutionModules.translations',
            'galleryImages',
            'relatedProjects.translations',
            'relatedProjects.category.translations',
        ]);
    }
}
