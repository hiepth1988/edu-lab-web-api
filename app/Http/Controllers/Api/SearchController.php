<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PostTranslation;
use App\Models\ProjectTranslation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private const SOURCES = [
        // Covers articles and research; the result type comes from the post's own type.
        ['model' => PostTranslation::class, 'type' => 'post', 'relation' => 'post', 'hideable' => true],
        ['model' => ProjectTranslation::class, 'type' => 'project', 'relation' => 'project', 'hideable' => false],
    ];

    public function index(Request $request): JsonResponse
    {
        $locale = $request->attributes->get('locale');
        $query = trim((string) $request->query('q', ''));

        if ($query === '') {
            return response()->json(['data' => []]);
        }

        $results = collect();

        foreach (self::SOURCES as $source) {
            $model = $source['model'];
            $relation = $source['relation'];

            $matches = $model::search($query)
                ->where('locale', $locale)
                ->query(fn ($q) => $q
                    ->whereHas($relation, fn ($q2) => $q2->where('status', 'published'))
                    ->when($source['hideable'], fn ($q2) => $q2->where('is_hidden', false)))
                ->take(10)
                ->get();

            foreach ($matches as $match) {
                $results->push([
                    'type' => $source['relation'] === 'post' ? ($match->post?->type ?? 'post') : $source['type'],
                    'slug' => $match->slug,
                    'title' => $match->title,
                    'excerpt' => $match->excerpt,
                ]);
            }
        }

        return response()->json(['data' => $results->values()]);
    }
}
