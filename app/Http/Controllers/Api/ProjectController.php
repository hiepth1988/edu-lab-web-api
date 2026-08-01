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
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'category.translations' => fn ($q) => $q->where('locale', $locale),
                    'metrics.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->orderByDesc('published_at')
                ->get()
                ->map(function (Project $project) use ($locale) {
                    $t = $project->translation($locale);
                    $category = $project->category?->translation($locale);

                    return [
                        'id' => $project->id,
                        'slug' => $t?->slug,
                        'title' => $t?->title,
                        'excerpt' => $t?->excerpt,
                        'featured_image' => $project->featured_image,
                        'is_featured' => $project->is_featured,
                        'category' => $category ? ['slug' => $category->slug, 'name' => $category->name] : null,
                        'metrics' => $project->metrics->take(1)->map(function ($m) use ($locale) {
                            $mt = $m->translation($locale);

                            return ['value' => $m->value, 'label' => $mt?->label];
                        })->values()->all(),
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
                    'category.translations' => fn ($q) => $q->where('locale', $locale),
                    'metrics.translations' => fn ($q) => $q->where('locale', $locale),
                    'solutionModules.translations' => fn ($q) => $q->where('locale', $locale),
                    'galleryImages',
                    'relatedProjects.translations' => fn ($q) => $q->where('locale', $locale),
                    'relatedProjects.category.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->firstOrFail();

            $t = $project->translation($locale);
            $category = $project->category?->translation($locale);

            $relatedProjects = $project->relatedProjects->isNotEmpty()
                ? $project->relatedProjects
                : Project::query()
                    ->where('status', 'published')
                    ->where('id', '!=', $project->id)
                    ->when($project->category_id, fn ($q) => $q->where('category_id', $project->category_id))
                    ->whereHas('translations', fn ($q) => $q->where('locale', $locale))
                    ->with([
                        'translations' => fn ($q) => $q->where('locale', $locale),
                        'category.translations' => fn ($q) => $q->where('locale', $locale),
                    ])
                    ->orderByDesc('published_at')
                    ->take(3)
                    ->get();

            return [
                'id' => $project->id,
                'slug' => $t?->slug,
                'title' => $t?->title,
                'excerpt' => $t?->excerpt,
                'featured_image' => $project->featured_image,
                'is_featured' => $project->is_featured,
                'category' => $category ? ['slug' => $category->slug, 'name' => $category->name] : null,

                'hero_eyebrow' => $t?->hero_eyebrow,
                'hero_cta_label' => $t?->hero_cta_label,
                'hero_cta_url' => $t?->hero_cta_url,
                'hero_badges' => $t?->hero_badges ?? [],
                'hero_stats' => $t?->hero_stats ?? [],

                'snapshot_items' => $t?->snapshot_items ?? [],

                'scale_heading' => $t?->scale_heading,
                'scale_description' => $t?->scale_description,
                'scale_stats' => $t?->scale_stats ?? [],

                'challenges_heading' => $t?->challenges_heading,
                'challenges_description' => $t?->challenges_description,
                'challenges' => $t?->challenges ?? [],

                'feature_map_heading' => $t?->feature_map_heading,
                'feature_groups' => $t?->feature_groups ?? [],

                'journey_heading' => $t?->journey_heading,
                'journey_steps' => $t?->journey_steps ?? [],

                'solution_modules' => $project->solutionModules->map(function ($module) use ($locale) {
                    $mt = $module->translation($locale);

                    return [
                        'image' => $module->image,
                        'title' => $mt?->title,
                        'description' => $mt?->description,
                        'technical_note' => $mt?->technical_note,
                        'features' => $mt?->features ?? [],
                    ];
                })->values()->all(),

                'gallery_heading' => $t?->gallery_heading,
                'gallery_categories' => $t?->gallery_categories ?? [],
                'gallery_images' => $project->galleryImages->map(fn ($image) => [
                    'category_key' => $image->category_key,
                    'image_url' => $image->image_url,
                ])->values()->all(),

                'architecture_heading' => $t?->architecture_heading,
                'architecture_layers' => $t?->architecture_layers ?? [],

                'tech_stack_groups' => $t?->tech_stack_groups ?? [],

                'results_heading' => $t?->results_heading,
                'results' => $t?->results ?? [],

                'lessons_quote' => $t?->lessons_quote,
                'lessons_citation' => $t?->lessons_citation,

                'related_projects' => $relatedProjects->map(function (Project $related) use ($locale) {
                    $rt = $related->translation($locale);
                    $rc = $related->category?->translation($locale);

                    return [
                        'slug' => $rt?->slug,
                        'title' => $rt?->title,
                        'featured_image' => $related->featured_image,
                        'category' => $rc ? ['slug' => $rc->slug, 'name' => $rc->name] : null,
                    ];
                })->values()->all(),

                'meta_title' => $t?->meta_title,
                'meta_description' => $t?->meta_description,
                'og_image' => $t?->og_image,
                'canonical_url' => $t?->canonical_url,
                'metrics' => $project->metrics->map(function ($m) use ($locale) {
                    $mt = $m->translation($locale);

                    return ['value' => $m->value, 'label' => $mt?->label];
                })->values()->all(),
            ];
        });

        return response()->json(['data' => $data]);
    }
}
