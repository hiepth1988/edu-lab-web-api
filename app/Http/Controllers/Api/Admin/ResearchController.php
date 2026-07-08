<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Concerns\EnforcesPublishPermission;
use App\Http\Controllers\Controller;
use App\Models\ResearchPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResearchController extends Controller
{
    use EnforcesPublishPermission;

    public function index(): JsonResponse
    {
        $posts = ResearchPost::with(['translations', 'topic.translations'])
            ->latest()
            ->paginate(20);

        return response()->json($posts);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $post = ResearchPost::create([
            'research_topic_id' => $data['research_topic_id'] ?? null,
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? now() : null,
        ]);

        $this->syncTranslations($post, $data['translations']);

        return response()->json(['data' => $this->loadPost($post)], 201);
    }

    public function show(ResearchPost $researchPost): JsonResponse
    {
        return response()->json(['data' => $this->loadPost($researchPost)]);
    }

    public function update(Request $request, ResearchPost $researchPost): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $researchPost->update([
            'research_topic_id' => $data['research_topic_id'] ?? null,
            'status' => $data['status'],
            'published_at' => $researchPost->published_at ?? ($data['status'] === 'published' ? now() : null),
        ]);

        $this->syncTranslations($researchPost, $data['translations']);

        return response()->json(['data' => $this->loadPost($researchPost->fresh())]);
    }

    public function destroy(ResearchPost $researchPost): JsonResponse
    {
        $researchPost->delete();

        return response()->json(null, 204);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'research_topic_id' => ['nullable', 'exists:research_topics,id'],
            'status' => ['required', 'in:draft,published'],
            'translations' => ['required', 'array'],
            'translations.*.title' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
            'translations.*.excerpt' => ['nullable', 'string'],
            'translations.*.content' => ['nullable', 'string'],
            'translations.*.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.*.meta_description' => ['nullable', 'string'],
        ]);
    }

    private function syncTranslations(ResearchPost $post, array $translations): void
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
                ]
            );
        }
    }

    private function loadPost(ResearchPost $post): ResearchPost
    {
        return $post->load(['translations', 'topic.translations']);
    }
}
