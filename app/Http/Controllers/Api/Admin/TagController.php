<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => Tag::with('translations')->latest()->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
        ]);

        $tag = Tag::create();
        $this->syncTranslations($tag, $data['translations']);

        return response()->json(['data' => $tag->load('translations')], 201);
    }

    public function show(Tag $tag): JsonResponse
    {
        return response()->json(['data' => $tag->load('translations')]);
    }

    public function update(Request $request, Tag $tag): JsonResponse
    {
        $data = $request->validate([
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
        ]);

        $this->syncTranslations($tag, $data['translations']);

        return response()->json(['data' => $tag->fresh('translations')]);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        return response()->json(null, 204);
    }

    private function syncTranslations(Tag $tag, array $translations): void
    {
        foreach ($translations as $locale => $payload) {
            $tag->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $payload['name'],
                    'slug' => $payload['slug'] ?? Str::slug($payload['name']),
                ]
            );
        }
    }
}
