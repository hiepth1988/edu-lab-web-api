<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PageController extends Controller
{
    public function home(Request $request): JsonResponse
    {
        return $this->showByKey($request, 'home');
    }

    public function show(Request $request, string $key): JsonResponse
    {
        return $this->showByKey($request, $key);
    }

    private function showByKey(Request $request, string $key): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $payload = Cache::tags(['pages'])->remember("pages:show:{$locale}:{$key}", 300, function () use ($locale, $key) {
            $page = Page::with(['translations', 'sections.translations'])
                ->where('key', $key)
                ->where('status', 'published')
                ->firstOrFail();

            $translation = $page->translation($locale);

            if (! $translation) {
                return null;
            }

            return [
                'key' => $page->key,
                'translation' => $translation,
                'sections' => $page->sections
                    ->where('is_active', true)
                    ->map(fn ($section) => [
                        'section_key' => $section->section_key,
                        'sort_order' => $section->sort_order,
                        'translation' => $section->translation($locale),
                    ])
                    ->values()
                    ->all(),
            ];
        });

        if ($payload === null) {
            return response()->json([
                'message' => 'Translation not available for this locale.',
            ], 404);
        }

        return response()->json($payload);
    }
}
