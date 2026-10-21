<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrackingEvent;
use App\Support\Tracking\ContentPath;
use Carbon\CarbonPeriod;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    private const PASSIVE_EVENTS = ['page_view', 'page_engagement', 'scroll_depth', 'section_view'];

    public function summary(Request $request): JsonResponse
    {
        $request->validate([
            'from' => ['nullable', 'date_format:Y-m-d'],
            'to' => ['nullable', 'date_format:Y-m-d'],
            'visitor_type' => ['nullable', 'in:vn,foreign,unknown'],
        ]);

        $today = now(config('tracking.timezone'))->toDateString();
        $to = $request->query('to', $today);
        $from = $request->query('from', Carbon::parse($to)->subDays(29)->toDateString());
        if ($from > $to) {
            [$from, $to] = [$to, $from];
        }
        $type = $request->query('visitor_type');

        // Overview always shows the full VN/foreign split; every other block honours the filter.
        $range = fn () => DB::table('tracking_events')->whereBetween('occurred_on', [$from, $to]);
        $filtered = fn () => $range()->when($type, fn ($q) => $q->where('visitor_type', $type));
        $split = "SUM(CASE WHEN visitor_type = 'vn' THEN 1 ELSE 0 END) as vn, SUM(CASE WHEN visitor_type = 'foreign' THEN 1 ELSE 0 END) as foreign_count";

        $overview = $range()
            ->selectRaw("visitor_type, COUNT(DISTINCT visitor_id) as visitors, COUNT(DISTINCT session_id) as sessions, SUM(CASE WHEN event = 'page_view' THEN 1 ELSE 0 END) as page_views")
            ->groupBy('visitor_type')
            ->get()
            ->keyBy('visitor_type');

        $conversions = $range()
            ->whereIn('event', ['lead_submit', 'newsletter_subscribe'])
            ->selectRaw('visitor_type, event, COUNT(*) as total')
            ->groupBy('visitor_type', 'event')
            ->get();

        $byType = collect(['vn', 'foreign', 'unknown'])->mapWithKeys(fn ($t) => [$t => [
            'visitors' => (int) ($overview[$t]->visitors ?? 0),
            'sessions' => (int) ($overview[$t]->sessions ?? 0),
            'page_views' => (int) ($overview[$t]->page_views ?? 0),
            'leads' => (int) $conversions->where('visitor_type', $t)->where('event', 'lead_submit')->sum('total'),
            'subscribers' => (int) $conversions->where('visitor_type', $t)->where('event', 'newsletter_subscribe')->sum('total'),
        ]]);

        $dailyRows = $filtered()
            ->selectRaw('occurred_on, visitor_type, COUNT(DISTINCT visitor_id) as visitors')
            ->groupBy('occurred_on', 'visitor_type')
            ->get();

        $daily = collect(CarbonPeriod::create($from, $to))->map(function ($date) use ($dailyRows) {
            $rows = $dailyRows->filter(fn ($r) => substr((string) $r->occurred_on, 0, 10) === $date->toDateString());

            return [
                'date' => $date->toDateString(),
                'vn' => (int) $rows->where('visitor_type', 'vn')->sum('visitors'),
                'foreign' => (int) $rows->where('visitor_type', 'foreign')->sum('visitors'),
                'unknown' => (int) $rows->where('visitor_type', 'unknown')->sum('visitors'),
            ];
        })->values();

        $topPages = $filtered()
            ->where('event', 'page_view')
            ->selectRaw("path, COUNT(*) as views, COUNT(DISTINCT visitor_id) as visitors, $split")
            ->groupBy('path')
            ->orderByDesc('views')
            ->limit(15)
            ->get();

        // Every opened page/article with views, VN/foreign split, time on page and how many
        // sessions read to ≥ 75% — one pass over the three page-level events.
        $content = $filtered()
            ->whereIn('event', ['page_view', 'page_engagement', 'scroll_depth'])
            ->whereNotNull('content_type')
            ->selectRaw("content_type, content_slug,
                SUM(CASE WHEN event = 'page_view' THEN 1 ELSE 0 END) as views,
                COUNT(DISTINCT CASE WHEN event = 'page_view' THEN visitor_id END) as visitors,
                COUNT(DISTINCT CASE WHEN event = 'page_view' THEN session_id END) as sessions,
                SUM(CASE WHEN event = 'page_view' AND visitor_type = 'vn' THEN 1 ELSE 0 END) as vn,
                SUM(CASE WHEN event = 'page_view' AND visitor_type = 'foreign' THEN 1 ELSE 0 END) as foreign_count,
                SUM(CASE WHEN event = 'page_engagement' THEN value ELSE 0 END) as engaged_seconds,
                COUNT(DISTINCT CASE WHEN event = 'scroll_depth' AND value >= 75 THEN session_id END) as read_sessions,
                MIN(CASE WHEN event = 'page_view' THEN path END) as sample_path")
            ->groupBy('content_type', 'content_slug')
            ->havingRaw("SUM(CASE WHEN event = 'page_view' THEN 1 ELSE 0 END) > 0")
            ->orderByDesc('views')
            ->limit(200)
            ->get()
            ->map(fn ($row) => (array) $row);

        // The vi and en URLs of one article resolve to the same record → one row per article.
        $resolved = ContentPath::resolve($content->groupBy('content_type')->map->pluck('content_slug')->map->all()->all());
        $recordKey = fn ($row) => $resolved["{$row['content_type']}:{$row['content_slug']}"] ?? null;

        $topContent = $content
            ->groupBy(fn ($row) => ($hit = $recordKey($row))
                ? "{$row['content_type']}#{$hit['id']}"
                : "{$row['content_type']}:{$row['content_slug']}")
            ->map(function ($group) use ($recordKey) {
                $main = $group->sortByDesc('views')->first();
                $sum = fn ($column) => (int) $group->sum($column);
                $views = $sum('views');
                $sessions = $sum('sessions');

                return [
                    'content_type' => $main['content_type'],
                    'content_slug' => $main['content_slug'],
                    'slugs' => $group->pluck('content_slug')->values(),
                    'title' => $recordKey($main)['title'] ?? null,
                    'path' => $main['sample_path'],
                    'views' => $views,
                    'visitors' => $sum('visitors'),
                    'vn' => $sum('vn'),
                    'foreign' => $sum('foreign_count'),
                    'avg_seconds' => $views ? (int) round($sum('engaged_seconds') / $views) : 0,
                    'read_rate' => $sessions ? round($sum('read_sessions') / $sessions, 3) : 0,
                ];
            })
            ->sortByDesc('views')
            ->take(100)
            ->values();

        $topActions = $filtered()
            ->whereNotIn('event', self::PASSIVE_EVENTS)
            ->selectRaw("event, label, COUNT(*) as total, COUNT(DISTINCT visitor_id) as visitors, $split")
            ->groupBy('event', 'label')
            ->orderByDesc('total')
            ->limit(30)
            ->get();

        $sections = $filtered()
            ->where('event', 'section_view')
            ->selectRaw("path, label, COUNT(DISTINCT session_id) as sessions, $split")
            ->groupBy('path', 'label')
            ->orderByDesc('sessions')
            ->limit(30)
            ->get();

        $scrollDepth = $filtered()
            ->where('event', 'scroll_depth')
            ->selectRaw('value, COUNT(DISTINCT session_id) as sessions')
            ->groupBy('value')
            ->orderBy('value')
            ->get();

        $countries = $filtered()
            ->whereNotNull('country')
            ->selectRaw('country, COUNT(DISTINCT visitor_id) as visitors')
            ->groupBy('country')
            ->orderByDesc('visitors')
            ->limit(20)
            ->get();

        $referrers = $filtered()
            ->whereNotNull('referrer_host')
            ->selectRaw("referrer_host, COUNT(DISTINCT session_id) as sessions, $split")
            ->groupBy('referrer_host')
            ->orderByDesc('sessions')
            ->limit(15)
            ->get();

        $devices = $filtered()
            ->selectRaw('device, COUNT(DISTINCT visitor_id) as visitors')
            ->groupBy('device')
            ->orderByDesc('visitors')
            ->get();

        $geoSources = $range()
            ->selectRaw('geo_source, COUNT(DISTINCT visitor_id) as visitors')
            ->groupBy('geo_source')
            ->get();

        return response()->json([
            'range' => ['from' => $from, 'to' => $to, 'visitor_type' => $type],
            'by_type' => $byType,
            'daily' => $daily,
            'top_pages' => $this->renameForeign($topPages),
            'top_content' => $topContent,
            'top_actions' => $this->renameForeign($topActions),
            'sections' => $this->renameForeign($sections),
            'scroll_depth' => $scrollDepth,
            'countries' => $countries,
            'referrers' => $this->renameForeign($referrers),
            'devices' => $devices,
            'geo_sources' => $geoSources,
        ]);
    }

    public function events(Request $request): JsonResponse
    {
        $events = TrackingEvent::query()
            ->when($request->query('event'), fn ($q, $event) => $q->where('event', $event))
            ->when($request->query('visitor_type'), fn ($q, $type) => $q->where('visitor_type', $type))
            ->when($request->query('content_type'), fn ($q, $type) => $q->where('content_type', $type))
            // Comma-separated, so the vi + en slugs of one article can be passed together.
            ->when($request->query('content_slug'), fn ($q, $slugs) => $q->whereIn('content_slug', explode(',', $slugs)))
            ->latest('id')
            ->paginate(50);

        return response()->json($events);
    }

    // "foreign" is a reserved word in MySQL, so the SQL alias is foreign_count.
    private function renameForeign(Collection $rows): Collection
    {
        return $rows->map(function ($row) {
            $row = (array) $row;
            $row['foreign'] = (int) $row['foreign_count'];
            $row['vn'] = (int) $row['vn'];
            unset($row['foreign_count']);

            return $row;
        });
    }
}
