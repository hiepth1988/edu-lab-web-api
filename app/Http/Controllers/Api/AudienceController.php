<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Audience;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AudienceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $audiences = Cache::tags(['audiences'])->remember("audiences:index:{$locale}", 300, function () use ($locale) {
            return Audience::query()
                ->where('status', 'published')
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale))
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderBy('sort_order')
                ->get()
                ->map(function (Audience $audience) use ($locale) {
                    $t = $audience->translation($locale);

                    return [
                        'id' => $audience->id,
                        'slug' => $t?->slug,
                        'title' => $t?->title,
                        'subheading' => $t?->subheading,
                        'hero_image' => $audience->hero_image,
                    ];
                })
                ->all();
        });

        return response()->json(['data' => $audiences]);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $data = Cache::tags(['audiences'])->remember("audiences:show:{$locale}:{$slug}", 300, function () use ($locale, $slug) {
            $audience = Audience::query()
                ->where('status', 'published')
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', $slug))
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'solutions.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->firstOrFail();

            $t = $audience->translation($locale);

            return [
                'id' => $audience->id,
                'slug' => $t?->slug,
                'title' => $t?->title,
                'subheading' => $t?->subheading,
                'pain_points' => $t?->pain_points,
                'how_we_help' => $t?->how_we_help,
                'hero_image' => $audience->hero_image,
                'meta_title' => $t?->meta_title,
                'meta_description' => $t?->meta_description,
                'solutions' => $audience->solutions->map(function ($solution) use ($locale) {
                    $st = $solution->translation($locale);

                    return ['slug' => $st?->slug, 'title' => $st?->title, 'subheading' => $st?->subheading];
                })->values()->all(),
            ];
        });

        return response()->json(['data' => $data]);
    }
}
