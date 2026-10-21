<?php

namespace App\Support\Tracking;

use Illuminate\Support\Facades\DB;

// Maps a front-end path to what was opened: an article/detail page (post, research, …) with
// its slug, or a static page ("page" + its first segment, "home" for /). Mirrors the routes in
// edu-lab-web-front/app/pages; /en is the only locale prefix (strategy prefix_except_default).
class ContentPath
{
    // URL segment => [content_type, translation table, parent key, title column]
    public const DETAIL_ROUTES = [
        'insights' => ['post', 'post_translations', 'post_id', 'title'],
        'research' => ['research', 'research_post_translations', 'research_post_id', 'title'],
        'solutions' => ['solution', 'solution_translations', 'solution_id', 'title'],
        'products' => ['product', 'product_translations', 'product_id', 'name'],
        'our-work' => ['project', 'project_translations', 'project_id', 'title'],
        'who-we-help' => ['audience', 'audience_translations', 'audience_id', 'title'],
    ];

    /**
     * @return array{content_type: string, content_slug: string}
     */
    public static function parse(string $path): array
    {
        $path = strtolower(trim(strtok($path, '?#') ?: '/', '/'));
        $segments = array_values(array_filter(explode('/', $path), 'strlen'));

        if (($segments[0] ?? null) === 'en') {
            array_shift($segments);
        }

        if (count($segments) >= 2 && isset(self::DETAIL_ROUTES[$segments[0]])) {
            return ['content_type' => self::DETAIL_ROUTES[$segments[0]][0], 'content_slug' => substr($segments[1], 0, 255)];
        }

        return ['content_type' => 'page', 'content_slug' => substr($segments[0] ?? 'home', 0, 255)];
    }

    /**
     * Resolves [type => [slugs]] to the record behind each slug, keyed "type:slug". The vi and
     * en URLs of one article have different slugs but the same id, so reports can merge them;
     * the title is the Vietnamese one when it exists.
     *
     * @param  array<string, array<int, string>>  $slugsByType
     * @return array<string, array{id: int, title: string}>
     */
    public static function resolve(array $slugsByType): array
    {
        $resolved = [];

        foreach (self::DETAIL_ROUTES as [$type, $table, $parentKey, $column]) {
            if (empty($slugsByType[$type])) {
                continue;
            }

            $matches = DB::table($table)->whereIn('slug', $slugsByType[$type])->pluck($parentKey, 'slug');

            $titles = DB::table($table)
                ->whereIn($parentKey, $matches->unique()->values())
                ->orderByRaw("CASE WHEN locale = 'vi' THEN 0 ELSE 1 END")
                ->get([$parentKey, $column])
                ->unique($parentKey)
                ->pluck($column, $parentKey);

            foreach ($matches as $slug => $id) {
                $resolved["{$type}:{$slug}"] = ['id' => (int) $id, 'title' => $titles[$id] ?? $slug];
            }
        }

        return $resolved;
    }
}
