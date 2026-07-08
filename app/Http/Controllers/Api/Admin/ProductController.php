<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\Admin\Concerns\EnforcesPublishPermission;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    use EnforcesPublishPermission;

    public function index(): JsonResponse
    {
        $products = Product::with(['translations', 'features.translations'])
            ->orderBy('sort_order')
            ->get();

        return response()->json(['data' => $products]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $product = Product::create([
            'status' => $data['status'],
            'stage' => $data['stage'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $this->syncTranslations($product, $data['translations']);
        $this->syncFeatures($product, $data['features'] ?? []);

        return response()->json(['data' => $this->loadProduct($product)], 201);
    }

    public function show(Product $product): JsonResponse
    {
        return response()->json(['data' => $this->loadProduct($product)]);
    }

    public function update(Request $request, Product $product): JsonResponse
    {
        $data = $this->validated($request);
        $this->ensureCanSetStatus($data['status']);

        $product->update([
            'status' => $data['status'],
            'stage' => $data['stage'] ?? null,
            'sort_order' => $data['sort_order'] ?? 0,
        ]);

        $this->syncTranslations($product, $data['translations']);
        $this->syncFeatures($product, $data['features'] ?? []);

        return response()->json(['data' => $this->loadProduct($product->fresh())]);
    }

    public function destroy(Product $product): JsonResponse
    {
        $product->delete();

        return response()->json(null, 204);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'status' => ['required', 'in:draft,published'],
            'stage' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
            'translations.*.role_summary' => ['nullable', 'string'],
            'translations.*.description' => ['nullable', 'string'],
            'translations.*.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.*.meta_description' => ['nullable', 'string'],
            'features' => ['array'],
            'features.*.translations' => ['array'],
        ]);
    }

    private function syncTranslations(Product $product, array $translations): void
    {
        foreach ($translations as $locale => $payload) {
            if (empty($payload['name'])) {
                continue;
            }

            $product->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'name' => $payload['name'],
                    'slug' => $payload['slug'] ?? Str::slug($payload['name']),
                    'role_summary' => $payload['role_summary'] ?? null,
                    'description' => $payload['description'] ?? null,
                    'meta_title' => $payload['meta_title'] ?? null,
                    'meta_description' => $payload['meta_description'] ?? null,
                ]
            );
        }
    }

    private function syncFeatures(Product $product, array $features): void
    {
        $product->features()->delete();

        foreach ($features as $index => $feature) {
            $model = $product->features()->create(['sort_order' => $index]);

            foreach ($feature['translations'] ?? [] as $locale => $payload) {
                if (empty($payload['title'])) {
                    continue;
                }

                $model->translations()->create([
                    'locale' => $locale,
                    'title' => $payload['title'],
                    'description' => $payload['description'] ?? null,
                ]);
            }
        }
    }

    private function loadProduct(Product $product): Product
    {
        return $product->load(['translations', 'features.translations']);
    }
}
