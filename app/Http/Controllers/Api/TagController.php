<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $tags = Tag::with('translations')
            ->get()
            ->map(fn (Tag $tag) => $tag->translation($locale))
            ->filter()
            ->values();

        return response()->json(['data' => $tags]);
    }
}
