<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CaseStudy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CaseStudyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $caseStudies = Cache::tags(['case-studies'])->remember("case-studies:index:{$locale}", 300, function () use ($locale) {
            return CaseStudy::query()
                ->where('status', 'published')
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale))
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderByDesc('published_at')
                ->get()
                ->map(function (CaseStudy $case) use ($locale) {
                    $t = $case->translation($locale);

                    return [
                        'id' => $case->id,
                        'slug' => $t?->slug,
                        'title' => $t?->title,
                        'excerpt' => $t?->excerpt,
                        'featured_image' => $case->featured_image,
                    ];
                })
                ->all();
        });

        return response()->json(['data' => $caseStudies]);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $data = Cache::tags(['case-studies'])->remember("case-studies:show:{$locale}:{$slug}", 300, function () use ($locale, $slug) {
            $case = CaseStudy::query()
                ->where('status', 'published')
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', $slug))
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'metrics.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->firstOrFail();

            $t = $case->translation($locale);

            return [
                'id' => $case->id,
                'slug' => $t?->slug,
                'title' => $t?->title,
                'excerpt' => $t?->excerpt,
                'problem' => $t?->problem,
                'solution_text' => $t?->solution_text,
                'result' => $t?->result,
                'featured_image' => $case->featured_image,
                'meta_title' => $t?->meta_title,
                'meta_description' => $t?->meta_description,
                'metrics' => $case->metrics->map(function ($m) use ($locale) {
                    $mt = $m->translation($locale);

                    return ['value' => $m->value, 'label' => $mt?->label];
                })->values()->all(),
            ];
        });

        return response()->json(['data' => $data]);
    }
}
