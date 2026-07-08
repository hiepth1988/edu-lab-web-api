<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $products = Cache::tags(['products'])->remember("products:index:{$locale}", 300, function () use ($locale) {
            return Product::query()
                ->where('status', 'published')
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale))
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderBy('sort_order')
                ->get()
                ->map(function (Product $product) use ($locale) {
                    $t = $product->translation($locale);

                    return [
                        'id' => $product->id,
                        'slug' => $t?->slug,
                        'name' => $t?->name,
                        'role_summary' => $t?->role_summary,
                        'stage' => $product->stage,
                    ];
                })
                ->all();
        });

        return response()->json(['data' => $products]);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $data = Cache::tags(['products'])->remember("products:show:{$locale}:{$slug}", 300, function () use ($locale, $slug) {
            $product = Product::query()
                ->where('status', 'published')
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', $slug))
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'features.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->firstOrFail();

            $t = $product->translation($locale);

            return [
                'id' => $product->id,
                'slug' => $t?->slug,
                'name' => $t?->name,
                'role_summary' => $t?->role_summary,
                'description' => $t?->description,
                'stage' => $product->stage,
                'meta_title' => $t?->meta_title,
                'meta_description' => $t?->meta_description,
                'features' => $product->features->map(function ($f) use ($locale) {
                    $ft = $f->translation($locale);

                    return ['title' => $ft?->title, 'description' => $ft?->description];
                })->values()->all(),
            ];
        });

        return response()->json(['data' => $data]);
    }
}
