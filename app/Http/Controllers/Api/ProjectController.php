<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $projects = Cache::tags(['projects'])->remember("projects:index:{$locale}", 300, function () use ($locale) {
            return Project::query()
                ->where('status', 'published')
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale))
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderByDesc('published_at')
                ->get()
                ->map(function (Project $project) use ($locale) {
                    $t = $project->translation($locale);

                    return [
                        'id' => $project->id,
                        'slug' => $t?->slug,
                        'title' => $t?->title,
                        'excerpt' => $t?->excerpt,
                        'featured_image' => $project->featured_image,
                    ];
                })
                ->all();
        });

        return response()->json(['data' => $projects]);
    }

    public function show(Request $request, string $slug): JsonResponse
    {
        $locale = $request->attributes->get('locale');

        $data = Cache::tags(['projects'])->remember("projects:show:{$locale}:{$slug}", 300, function () use ($locale, $slug) {
            $project = Project::query()
                ->where('status', 'published')
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', $slug))
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'metrics.translations' => fn ($q) => $q->where('locale', $locale),
                    'sectionImages',
                ])
                ->firstOrFail();

            $t = $project->translation($locale);

            return [
                'id' => $project->id,
                'slug' => $t?->slug,
                'title' => $t?->title,
                'excerpt' => $t?->excerpt,
                'problem' => $t?->problem,
                'solution_text' => $t?->solution_text,
                'result' => $t?->result,
                'featured_image' => $project->featured_image,
                'meta_title' => $t?->meta_title,
                'meta_description' => $t?->meta_description,
                'metrics' => $project->metrics->map(function ($m) use ($locale) {
                    $mt = $m->translation($locale);

                    return ['value' => $m->value, 'label' => $mt?->label];
                })->values()->all(),
                'section_images' => [
                    'problem' => $project->sectionImages->where('section', 'problem')->pluck('image_url')->values()->all(),
                    'solution' => $project->sectionImages->where('section', 'solution')->pluck('image_url')->values()->all(),
                    'result' => $project->sectionImages->where('section', 'result')->pluck('image_url')->values()->all(),
                ],
            ];
        });

        return response()->json(['data' => $data]);
    }
}
