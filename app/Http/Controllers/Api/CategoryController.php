<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $categories = Category::with('translations')
            ->get()
            ->map(fn (Category $category) => $category->translation($locale))
            ->filter()
            ->values();

        return response()->json(['data' => $categories]);
    }
}
