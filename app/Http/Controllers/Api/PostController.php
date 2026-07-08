<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = $request->attributes->get('locale');
        $category = (string) $request->query('category', '');
        $tag = (string) $request->query('tag', '');
        $page = $request->integer('page', 1);
        $perPage = $request->integer('per_page', 9);

        $cacheKey = "posts:index:{$locale}:{$category}:{$tag}:{$page}:{$perPage}";

        $payload = Cache::tags(['posts'])->remember($cacheKey, 300, function () use ($locale, $category, $tag, $perPage) {
            $query = Post::query()
                ->where('status', 'published')
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale))
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'category.translations' => fn ($q) => $q->where('locale', $locale),
                    'tags.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->orderByDesc('published_at');

            if ($category !== '') {
                $query->whereHas(
                    'category.translations',
                    fn ($q) => $q->where('locale', $locale)->where('slug', $category)
                );
            }

            if ($tag !== '') {
                $query->whereHas(
                    'tags.translations',
                    fn ($q) => $q->where('locale', $locale)->where('slug', $tag)
                );
            }

            $posts = $query->paginate($perPage);

            return [
                'data' => $posts->getCollection()->map(fn (Post $post) => $this->summary($post, $locale))->all(),
                'meta' => [
                    'current_page' => $posts->currentPage(),
                    'last_page' => $posts->lastPage(),
                    'per_page' => $posts->perPage(),
                    'total' => $posts->total(),
                ],
            ];
        });

        return response()->json($payload);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $post = Post::query()
            ->where('status', 'published')
            ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', $slug))
            ->with([
                'translations' => fn ($q) => $q->where('locale', $locale),
                'category.translations' => fn ($q) => $q->where('locale', $locale),
                'tags.translations' => fn ($q) => $q->where('locale', $locale),
            ])
            ->firstOrFail();

        $post->increment('view_count');

        $related = collect();

        if ($post->category_id) {
            $related = Post::query()
                ->where('status', 'published')
                ->where('id', '!=', $post->id)
                ->where('category_id', $post->category_id)
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale))
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderByDesc('published_at')
                ->take(3)
                ->get()
                ->map(function (Post $related) use ($locale) {
                    $t = $related->translation($locale);

                    return ['slug' => $t?->slug, 'title' => $t?->title, 'excerpt' => $t?->excerpt];
                });
        }

        return response()->json([
            'data' => [
                ...$this->summary($post, $locale),
                'content' => $post->translation($locale)?->content,
                'related_posts' => $related,
            ],
        ]);
    }

    private function summary(Post $post, string $locale): array
    {
        $translation = $post->translation($locale);
        $category = $post->category?->translation($locale);

        return [
            'id' => $post->id,
            'slug' => $translation?->slug,
            'title' => $translation?->title,
            'excerpt' => $translation?->excerpt,
            'meta_title' => $translation?->meta_title,
            'meta_description' => $translation?->meta_description,
            'og_image' => $translation?->og_image,
            'featured_image' => $post->featured_image,
            'is_featured' => $post->is_featured,
            'published_at' => $post->published_at?->toIso8601String(),
            'category' => $category ? ['slug' => $category->slug, 'name' => $category->name] : null,
            'tags' => $post->tags
                ->map(fn ($tag) => $tag->translation($locale))
                ->filter()
                ->map(fn ($t) => ['slug' => $t->slug, 'name' => $t->name])
                ->values(),
        ];
    }
}
