<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Solution;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SolutionController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $solutions = Cache::tags(['solutions'])->remember("solutions:index:{$locale}", 300, function () use ($locale) {
            return Solution::query()
                ->where('status', 'published')
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale))
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderBy('sort_order')
                ->get()
                ->map(function (Solution $solution) use ($locale) {
                    $t = $solution->translation($locale);

                    return [
                        'id' => $solution->id,
                        'slug' => $t?->slug,
                        'title' => $t?->title,
                        'subheading' => $t?->subheading,
                    ];
                })
                ->all();
        });

        return response()->json(['data' => $solutions]);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $data = Cache::tags(['solutions'])->remember("solutions:show:{$locale}:{$slug}", 300, function () use ($locale, $slug) {
            $solution = Solution::query()
                ->where('status', 'published')
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', $slug))
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'features.translations' => fn ($q) => $q->where('locale', $locale),
                    'faqs.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->firstOrFail();

            $t = $solution->translation($locale);

            return [
                'id' => $solution->id,
                'slug' => $t?->slug,
                'title' => $t?->title,
                'subheading' => $t?->subheading,
                'problem' => $t?->problem,
                'solution_overview' => $t?->solution_overview,
                'architecture_note' => $t?->architecture_note,
                'use_cases' => $t?->use_cases,
                'meta_title' => $t?->meta_title,
                'meta_description' => $t?->meta_description,
                'features' => $solution->features->map(function ($f) use ($locale) {
                    $ft = $f->translation($locale);

                    return ['title' => $ft?->title, 'description' => $ft?->description];
                })->values()->all(),
                'faqs' => $solution->faqs->map(function ($f) use ($locale) {
                    $ft = $f->translation($locale);

                    return ['question' => $ft?->question, 'answer' => $ft?->answer];
                })->values()->all(),
            ];
        });

        return response()->json(['data' => $data]);
    }
}
