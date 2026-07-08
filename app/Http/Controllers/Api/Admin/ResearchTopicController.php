<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ResearchTopic;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResearchTopicController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(['data' => ResearchTopic::with('translations')->latest()->get()]);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'translations' => ['required', 'array'],
            'translations.*.name' => ['required', 'string', 'max:255'],
            'translations.*.slug' => ['nullable', 'string', 'max:255'],
        ]);

        $topic = ResearchTopic::create();

        foreach ($data['translations'] as $locale => $payload) {
            $topic->translations()->create([
                'locale' => $locale,
                'name' => $payload['name'],
                'slug' => $payload['slug'] ?? Str::slug($payload['name']),
            ]);
        }

        return response()->json(['data' => $topic->load('translations')], 201);
    }

    public function destroy(ResearchTopic $researchTopic): JsonResponse
    {
        $researchTopic->delete();

        return response()->json(null, 204);
    }
}
