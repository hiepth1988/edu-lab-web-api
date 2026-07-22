<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Concerns\EnforcesPublishPermission;
use App\Http\Controllers\Controller;
use App\Models\Audience;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AudienceController extends Controller
{
    use EnforcesPublishPermission;

    public function index(): JsonResponse
    {
        $audiences = Audience::with(['translations', 'solutions'])
            ->orderBy('sort_order')
            ->get();

        return response()->json(['data' => $audiences]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $audience = Audience::create([
            'status' => $data['status'],
            'hero_image' => $data['hero_image'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $this->syncTranslations($audience, $data['translations']);
        $audience->solutions()->sync($this->solutionSyncPayload($data['solution_ids'] ?? []));

        return response()->json(['data' => $this->loadAudience($audience)], 201);
    }

    public function show(Audience $audience): JsonResponse
    {
        return response()->json(['data' => $this->loadAudience($audience)]);
    }

    public function update(Request $request, Audience $audience): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $audience->update([
            'status' => $data['status'],
            'hero_image' => $data['hero_image'] ?? null,
            'sort_order' => $data['sort_order'] ?? $audience->sort_order,
        ]);

        $this->syncTranslations($audience, $data['translations']);
        $audience->solutions()->sync($this->solutionSyncPayload($data['solution_ids'] ?? []));

        return response()->json(['data' => $this->loadAudience($audience->fresh())]);
    }

    public function destroy(Audience $audience): JsonResponse
    {
        $audience->delete();

        return response()->json(null, 204);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'status' => ['required', 'in:draft,published'],
            'hero_image' => ['nullable', 'string', 'max:2048'],
            'sort_order' => ['nullable', 'integer'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
            'translations.*.subheading' => ['nullable', 'string', 'max:255'],
            'translations.*.pain_points' => ['nullable', 'string'],
            'translations.*.how_we_help' => ['nullable', 'string'],
            'translations.*.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.*.meta_description' => ['nullable', 'string'],
            'solution_ids' => ['array'],
            'solution_ids.*' => ['integer', 'exists:solutions,id'],
        ]);
    }

    private function syncTranslations(Audience $audience, array $translations): void
    {
        foreach ($translations as $locale => $payload) {
            if (empty($payload['title'])) {
                continue;
            }

            $audience->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $payload['title'],
                    'slug' => $payload['slug'] ?? Str::slug($payload['title']),
                    'subheading' => $payload['subheading'] ?? null,
                    'pain_points' => $payload['pain_points'] ?? null,
                    'how_we_help' => $payload['how_we_help'] ?? null,
                    'meta_title' => $payload['meta_title'] ?? null,
                    'meta_description' => $payload['meta_description'] ?? null,
                ]
            );
        }
    }

    private function solutionSyncPayload(array $solutionIds): array
    {
        $payload = [];

        foreach (array_values($solutionIds) as $index => $solutionId) {
            $payload[$solutionId] = ['sort_order' => $index];
        }

        return $payload;
    }

    private function loadAudience(Audience $audience): Audience
    {
        return $audience->load(['translations', 'solutions']);
    }
}
