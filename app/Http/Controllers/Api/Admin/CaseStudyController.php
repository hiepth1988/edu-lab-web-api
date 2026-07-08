<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Concerns\EnforcesPublishPermission;
use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaseStudyController extends Controller
{
    use EnforcesPublishPermission;

    public function index(): JsonResponse
    {
        $caseStudies = CaseStudy::with(['translations', 'metrics.translations'])
            ->latest()
            ->get();

        return response()->json(['data' => $caseStudies]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $case = CaseStudy::create([
            'status' => $data['status'],
            'featured_image' => $data['featured_image'] ?? null,
            'published_at' => $data['published_at'] ?? ($data['status'] === 'published' ? now() : null),
        ]);

        $this->syncTranslations($case, $data['translations']);
        $this->syncMetrics($case, $data['metrics'] ?? []);

        return response()->json(['data' => $this->loadCase($case)], 201);
    }

    public function show(CaseStudy $caseStudy): JsonResponse
    {
        return response()->json(['data' => $this->loadCase($caseStudy)]);
    }

    public function update(Request $request, CaseStudy $caseStudy): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $caseStudy->update([
            'status' => $data['status'],
            'featured_image' => $data['featured_image'] ?? null,
            'published_at' => $data['published_at'] ?? $caseStudy->published_at,
        ]);

        $this->syncTranslations($caseStudy, $data['translations']);
        $this->syncMetrics($caseStudy, $data['metrics'] ?? []);

        return response()->json(['data' => $this->loadCase($caseStudy->fresh())]);
    }

    public function destroy(CaseStudy $caseStudy): JsonResponse
    {
        $caseStudy->delete();

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
        ]);
    }

    private function syncTranslations(CaseStudy $case, array $translations): void
    {
        foreach ($translations as $locale => $payload) {
            if (empty($payload['title'])) {
                continue;
            }

            $case->translations()->updateOrCreate(
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

    private function syncMetrics(CaseStudy $case, array $metrics): void
    {
        $case->metrics()->delete();

        foreach ($metrics as $index => $metric) {
            if (empty($metric['value'])) {
                continue;
            }

            $model = $case->metrics()->create(['value' => $metric['value'], 'sort_order' => $index]);

            foreach ($metric['translations'] ?? [] as $locale => $payload) {
                if (empty($payload['label'])) {
                    continue;
                }

                $model->translations()->create(['locale' => $locale, 'label' => $payload['label']]);
            }
        }
    }

    private function loadCase(CaseStudy $case): CaseStudy
    {
        return $case->load(['translations', 'metrics.translations']);
    }
}
