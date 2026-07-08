<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CaseStudyTranslation;
use App\Models\PostTranslation;
use App\Models\ResearchPostTranslation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private const SOURCES = [
        ['model' => PostTranslation::class, 'type' => 'post', 'relation' => 'post'],
        ['model' => ResearchPostTranslation::class, 'type' => 'research', 'relation' => 'researchPost'],
        ['model' => CaseStudyTranslation::class, 'type' => 'case-study', 'relation' => 'caseStudy'],
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
                ->query(fn ($q) => $q->whereHas($relation, fn ($q2) => $q2->where('status', 'published')))
                ->take(10)
                ->get();

            foreach ($matches as $match) {
                $results->push([
                    'type' => $source['type'],
                    'slug' => $match->slug,
                    'title' => $match->title,
                    'excerpt' => $match->excerpt,
                ]);
            }
        }

        return response()->json(['data' => $results->values()]);
    }
}
