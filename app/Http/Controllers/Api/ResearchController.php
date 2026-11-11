<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

// Research items are posts with type = "research"; their category is shown as the "topic".
class ResearchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $posts = Cache::tags(['research'])->remember("research:index:{$locale}", 300, function () use ($locale) {
            return $this->query($locale)
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('is_hidden', false))
                ->orderByDesc('published_at')
                ->get()
                ->map(fn (Post $post) => $this->summary($post, $locale))
                ->all();
        });

        return response()->json(['data' => $posts]);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $data = Cache::tags(['research'])->remember("research:show:{$locale}:{$slug}", 300, function () use ($locale, $slug) {
            $post = $this->query($locale)
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', $slug)->where('is_hidden', false))
                ->firstOrFail();

            $t = $post->translation($locale);

            return [
                ...$this->summary($post, $locale),
                'alternate_slugs' => $post->translations()->where('is_hidden', false)->pluck('slug', 'locale'),
                'content' => $t?->content,
                'meta_title' => $t?->meta_title,
                'meta_description' => $t?->meta_description,
                'og_image' => $t?->og_image,
                'canonical_url' => $t?->canonical_url,
            ];
        });

        return response()->json(['data' => $data]);
    }

    private function query(string $locale): Builder
    {
        return Post::query()
            ->where('type', Post::TYPE_RESEARCH)
            ->where('status', 'published')
            ->with([
                'translations' => fn ($q) => $q->where('locale', $locale),
                'category.translations' => fn ($q) => $q->where('locale', $locale),
            ]);
    }

    private function summary(Post $post, string $locale): array
    {
        $t = $post->translation($locale);
        $topic = $post->category?->translation($locale);

        return [
            'id' => $post->id,
            'slug' => $t?->slug,
            'title' => $t?->title,
            'excerpt' => $t?->excerpt,
            'published_at' => $post->published_at?->toIso8601String(),
            'topic' => $topic ? ['slug' => $topic->slug, 'name' => $topic->name] : null,
        ];
    }
}
