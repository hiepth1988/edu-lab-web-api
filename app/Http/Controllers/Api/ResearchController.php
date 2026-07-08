<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ResearchPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ResearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $posts = Cache::tags(['research'])->remember("research:index:{$locale}", 300, function () use ($locale) {
            return ResearchPost::query()
                ->where('status', 'published')
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale))
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'topic.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->orderByDesc('published_at')
                ->get()
                ->map(function (ResearchPost $post) use ($locale) {
                    $t = $post->translation($locale);
                    $topic = $post->topic?->translation($locale);

                    return [
                        'id' => $post->id,
                        'slug' => $t?->slug,
                        'title' => $t?->title,
                        'excerpt' => $t?->excerpt,
                        'topic' => $topic ? ['slug' => $topic->slug, 'name' => $topic->name] : null,
                    ];
                })
                ->all();
        });

        return response()->json(['data' => $posts]);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $data = Cache::tags(['research'])->remember("research:show:{$locale}:{$slug}", 300, function () use ($locale, $slug) {
            $post = ResearchPost::query()
                ->where('status', 'published')
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', $slug))
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'topic.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->firstOrFail();

            $t = $post->translation($locale);
            $topic = $post->topic?->translation($locale);

            return [
                'id' => $post->id,
                'slug' => $t?->slug,
                'title' => $t?->title,
                'excerpt' => $t?->excerpt,
                'content' => $t?->content,
                'meta_title' => $t?->meta_title,
                'meta_description' => $t?->meta_description,
                'topic' => $topic ? ['slug' => $topic->slug, 'name' => $topic->name] : null,
            ];
        });

        return response()->json(['data' => $data]);
    }
}
