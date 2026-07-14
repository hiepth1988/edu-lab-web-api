<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Concerns\EnforcesPublishPermission;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    use EnforcesPublishPermission;

    public function index(): JsonResponse
    {
        $posts = Post::with(['translations', 'category.translations', 'tags.translations'])
            ->latest()
            ->paginate(20);

        return response()->json($posts);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $post = Post::create($this->baseFields($data));
        $this->syncTranslations($post, $data['translations']);
        $post->tags()->sync($data['tag_ids'] ?? []);

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

        return response()->json(['data' => $this->loadPost($post->fresh())]);
    }

    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json(null, 204);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published'],
            'featured_image' => ['nullable', 'string', 'max:2048'],
            'is_featured' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'tag_ids' => ['array'],
            'tag_ids.*' => ['exists:tags,id'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
            'translations.*.excerpt' => ['nullable', 'string'],
            'translations.*.content' => ['nullable', 'string'],
            'translations.*.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.*.meta_description' => ['nullable', 'string'],
            'translations.*.og_image' => ['nullable', 'string', 'max:2048'],
            'translations.*.canonical_url' => ['nullable', 'string', 'max:2048'],
        ]);
    }

    private function baseFields(array $data): array
    {
        return [
            'category_id' => $data['category_id'] ?? null,
            'status' => $data['status'],
            'featured_image' => $data['featured_image'] ?? null,
            'is_featured' => $data['is_featured'] ?? false,
            'published_at' => $data['published_at'] ?? ($data['status'] === 'published' ? now() : null),
        ];
    }

    private function syncTranslations(Post $post, array $translations): void
    {
        foreach ($translations as $locale => $payload) {
            if (empty($payload['title'])) {
                continue;
            }

            $post->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => $payload['title'],
                    'slug' => $payload['slug'] ?? Str::slug($payload['title']),
                    'excerpt' => $payload['excerpt'] ?? null,
                    'content' => $payload['content'] ?? null,
                    'meta_title' => $payload['meta_title'] ?? null,
                    'meta_description' => $payload['meta_description'] ?? null,
                    'og_image' => $payload['og_image'] ?? null,
                    'canonical_url' => $payload['canonical_url'] ?? null,
                ]
            );
        }
    }

    private function loadPost(Post $post): Post
    {
        return $post->load(['translations', 'category.translations', 'tags.translations']);
    }
}
