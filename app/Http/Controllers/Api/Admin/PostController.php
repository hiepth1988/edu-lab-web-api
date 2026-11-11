<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Concerns\EnforcesPublishPermission;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PostController extends Controller
{
    use EnforcesPublishPermission;

    public function index(Request $request): JsonResponse
    {
        $perPage = min(max((int) $request->query('per_page', 20), 1), 200);
        $type = (string) $request->query('type', '');

        $posts = Post::with(['translations', 'category.translations', 'tags.translations'])
            ->when(in_array($type, Post::TYPES, true), fn ($q) => $q->where('type', $type))
            ->latest()
            ->paginate($perPage);

        return response()->json($posts);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $post = Post::create($this->baseFields($data));
        $this->syncTranslations($post, $data['translations']);
        $post->tags()->sync($data['tag_ids'] ?? []);
        $this->syncRelatedPosts($post, $data['related_post_ids'] ?? []);

        return response()->json(['data' => $this->loadPost($post)], 201);
    }

    public function show(Post $post): JsonResponse
    {
        return response()->json(['data' => $this->loadPost($post)]);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $post->update($this->baseFields($data));
        $this->syncTranslations($post, $data['translations']);
        $post->tags()->sync($data['tag_ids'] ?? []);
        $this->syncRelatedPosts($post, $data['related_post_ids'] ?? []);

        return response()->json(['data' => $this->loadPost($post->fresh())]);
    }

    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json(null, 204);
    }

    private function validated(Request $request): array
    {
        $type = in_array($request->input('type'), Post::TYPES, true) ? $request->input('type') : Post::TYPE_POST;

        return $request->validate([
            'type' => ['sometimes', Rule::in(Post::TYPES)],
            // The category must belong to the same type (article categories vs research topics).
            'category_id' => ['nullable', Rule::exists('categories', 'id')->where('type', $type)],
            'status' => ['required', 'in:draft,published'],
            'featured_image' => ['nullable', 'string', 'max:2048'],
            'featured_image_en' => ['nullable', 'string', 'max:2048'],
            'is_featured' => ['boolean'],
            'show_cta' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'tag_ids' => ['array'],
            'tag_ids.*' => ['exists:tags,id'],
            'related_post_ids' => ['array'],
            'related_post_ids.*' => ['integer', 'exists:posts,id'],
            'translations' => ['required', 'array'],
            'translations.*.is_hidden' => ['boolean'],
            // A hidden language version may be left untranslated.
            'translations.*.title' => ['required_unless:translations.*.is_hidden,true', 'nullable', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
            'translations.*.excerpt' => ['nullable', 'string'],
            'translations.*.content' => ['nullable', 'string'],
            'translations.*.cta_text' => ['nullable', 'string', 'max:1000'],
            'translations.*.cta_label' => ['nullable', 'string', 'max:255'],
            'translations.*.cta_url' => ['nullable', 'string', 'max:2048'],
            'translations.*.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.*.meta_description' => ['nullable', 'string'],
            'translations.*.og_image' => ['nullable', 'string', 'max:2048'],
            'translations.*.canonical_url' => ['nullable', 'string', 'max:2048'],
        ]);
    }

    private function baseFields(array $data): array
    {
        return [
            'type' => $data['type'] ?? Post::TYPE_POST,
            'category_id' => $data['category_id'] ?? null,
            'status' => $data['status'],
            'featured_image' => $data['featured_image'] ?? null,
            'featured_image_en' => $data['featured_image_en'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
            'show_cta' => $data['show_cta'] ?? true,
            'published_at' => $data['published_at'] ?? ($data['status'] === 'published' ? now() : null),
        ];
    }

    private function syncTranslations(Post $post, array $translations): void
    {
        foreach ($translations as $locale => $payload) {
            $isHidden = (bool) ($payload['is_hidden'] ?? false);

            if (empty($payload['title'])) {
                // No content to save, but keep the visibility flag of an existing translation in sync.
                $post->translations()->where('locale', $locale)->get()
                    ->each(fn ($t) => $t->update(['is_hidden' => $isHidden]));

                continue;
            }

            $post->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'is_hidden' => $isHidden,
                    'title' => $payload['title'],
                    'slug' => $payload['slug'] ?? Str::slug($payload['title']),
                    'excerpt' => $payload['excerpt'] ?? null,
                    'content' => $payload['content'] ?? null,
                    'cta_text' => $payload['cta_text'] ?? null,
                    'cta_label' => $payload['cta_label'] ?? null,
                    'cta_url' => $payload['cta_url'] ?? null,
                    'meta_title' => $payload['meta_title'] ?? null,
                    'meta_description' => $payload['meta_description'] ?? null,
                    'og_image' => $payload['og_image'] ?? null,
                    'canonical_url' => $payload['canonical_url'] ?? null,
                ]
            );
        }
    }

    private function syncRelatedPosts(Post $post, array $relatedPostIds): void
    {
        $pivotData = [];

        foreach (array_values($relatedPostIds) as $index => $relatedPostId) {
            if ((int) $relatedPostId === $post->id) {
                continue;
            }

            $pivotData[$relatedPostId] = ['sort_order' => $index];
        }

        $post->relatedPosts()->sync($pivotData);
    }

    private function loadPost(Post $post): Post
    {
        return $post->load(['translations', 'category.translations', 'tags.translations', 'relatedPosts.translations']);
    }
}
