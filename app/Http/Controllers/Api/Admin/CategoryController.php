<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::with('translations')->latest()->get();

        return response()->json(['data' => $categories]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
        ]);

        $category = Category::create();
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
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
        ]);

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
