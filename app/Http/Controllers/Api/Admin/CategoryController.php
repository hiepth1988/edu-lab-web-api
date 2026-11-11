<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $type = (string) $request->query('type', '');

        $categories = Category::with('translations')
            ->when(in_array($type, Post::TYPES, true), fn ($q) => $q->where('type', $type))
            ->latest()
            ->get();

        return response()->json(['data' => $categories]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type' => ['sometimes', Rule::in(Post::TYPES)],
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
        ]);

        $category = Category::create(['type' => $data['type'] ?? Post::TYPE_POST]);
        $this->syncTranslations($category, $data['translations']);

        return response()->json(['data' => $category->load('translations')], 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(['data' => $category->load('translations')]);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $data = $request->validate([
            'type' => ['sometimes', Rule::in(Post::TYPES)],
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
        ]);

        if (isset($data['type']) && $data['type'] !== $category->type) {
            $category->update(['type' => $data['type']]);
            // Posts of the other type can no longer use this category.
            $category->posts()->where('type', '!=', $data['type'])->update(['category_id' => null]);
        }

        $this->syncTranslations($category, $data['translations']);

        return response()->json(['data' => $category->fresh('translations')]);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(null, 204);
    }

    private function syncTranslations(Category $category, array $translations): void
    {
        foreach ($translations as $locale => $payload) {
            $category->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $payload['name'],
                    'slug' => $payload['slug'] ?? Str::slug($payload['name']),
                    'description' => $payload['description'] ?? null,
                ]
            );
        }
    }
}
