<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Concerns\EnforcesPublishPermission;
use App\Http\Controllers\Controller;
use App\Models\Solution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SolutionController extends Controller
{
    use EnforcesPublishPermission;

    public function index(): JsonResponse
    {
        $solutions = Solution::with(['translations', 'features.translations', 'faqs.translations'])
            ->orderBy('sort_order')
            ->get();

        return response()->json(['data' => $solutions]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $solution = Solution::create([
            'status' => $data['status'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $this->syncTranslations($solution, $data['translations']);
        $this->syncFeatures($solution, $data['features'] ?? []);
        $this->syncFaqs($solution, $data['faqs'] ?? []);

        return response()->json(['data' => $this->loadSolution($solution)], 201);
    }

    public function show(Solution $solution): JsonResponse
    {
        return response()->json(['data' => $this->loadSolution($solution)]);
    }

    public function update(Request $request, Solution $solution): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $solution->update([
            'status' => $data['status'],
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $this->syncTranslations($solution, $data['translations']);
        $this->syncFeatures($solution, $data['features'] ?? []);
        $this->syncFaqs($solution, $data['faqs'] ?? []);

        return response()->json(['data' => $this->loadSolution($solution->fresh())]);
    }

    public function destroy(Solution $solution): JsonResponse
    {
        $solution->delete();

        return response()->json(null, 204);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'status' => ['required', 'in:draft,published'],
            'sort_order' => ['nullable', 'integer'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
            'translations.*.subheading' => ['nullable', 'string'],
            'translations.*.problem' => ['nullable', 'string'],
            'translations.*.solution_overview' => ['nullable', 'string'],
            'translations.*.architecture_note' => ['nullable', 'string'],
            'translations.*.use_cases' => ['nullable', 'string'],
            'translations.*.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.*.meta_description' => ['nullable', 'string'],
            'features' => ['array'],
            'features.*.translations' => ['array'],
            'faqs' => ['array'],
            'faqs.*.translations' => ['array'],
        ]);
    }

    private function syncTranslations(Solution $solution, array $translations): void
    {
        foreach ($translations as $locale => $payload) {
            if (empty($payload['title'])) {
                continue;
            }

            $solution->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $payload['title'],
                    'slug' => $payload['slug'] ?? Str::slug($payload['title']),
                    'subheading' => $payload['subheading'] ?? null,
                    'problem' => $payload['problem'] ?? null,
                    'solution_overview' => $payload['solution_overview'] ?? null,
                    'architecture_note' => $payload['architecture_note'] ?? null,
                    'use_cases' => $payload['use_cases'] ?? null,
                    'meta_title' => $payload['meta_title'] ?? null,
                    'meta_description' => $payload['meta_description'] ?? null,
                ]
            );
        }
    }

    private function syncFeatures(Solution $solution, array $features): void
    {
        $solution->features()->delete();

        foreach ($features as $index => $feature) {
            $model = $solution->features()->create(['sort_order' => $index]);

            foreach ($feature['translations'] ?? [] as $locale => $payload) {
                if (empty($payload['title'])) {
                    continue;
                }

                $model->translations()->create([
                    'locale' => $locale,
                    'title' => $payload['title'],
                    'description' => $payload['description'] ?? null,
                ]);
            }
        }
    }

    private function syncFaqs(Solution $solution, array $faqs): void
    {
        $solution->faqs()->delete();

        foreach ($faqs as $index => $faq) {
            $model = $solution->faqs()->create(['sort_order' => $index]);

            foreach ($faq['translations'] ?? [] as $locale => $payload) {
                if (empty($payload['question'])) {
                    continue;
                }

                $model->translations()->create([
                    'locale' => $locale,
                    'question' => $payload['question'],
                    'answer' => $payload['answer'] ?? null,
                ]);
            }
        }
    }

    private function loadSolution(Solution $solution): Solution
    {
        return $solution->load(['translations', 'features.translations', 'faqs.translations']);
    }
}
