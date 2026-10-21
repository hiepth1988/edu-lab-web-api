<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrackingEvent;
use App\Support\Tracking\ContentPath;
use App\Support\Tracking\VisitorClassifier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TrackingController extends Controller
{
    private const BOT_PATTERN = '/bot|crawl|spider|slurp|preview|facebookexternalhit|headless|lighthouse|pingdom|uptime/i';

    public function store(Request $request, VisitorClassifier $classifier): Response
    {
        $userAgent = (string) $request->userAgent();

        if (! config('tracking.enabled') || $userAgent === '' || preg_match(self::BOT_PATTERN, $userAgent)) {
            return response()->noContent();
        }

        // navigator.sendBeacon posts text/plain (avoids a CORS preflight), so parse the raw body.
        $payload = $request->isJson() ? $request->all() : (json_decode($request->getContent(), true) ?: []);

        $data = Validator::make($payload, [
            'visitor_id' => ['required', 'uuid'],
            'session_id' => ['required', 'uuid'],
            'timezone' => ['nullable', 'string', 'max:64'],
            'language' => ['nullable', 'string', 'max:20'],
            'referrer' => ['nullable', 'string', 'max:2048'],
            'utm_source' => ['nullable', 'string', 'max:100'],
            'utm_medium' => ['nullable', 'string', 'max:100'],
            'utm_campaign' => ['nullable', 'string', 'max:100'],
            'events' => ['required', 'array', 'min:1', 'max:25'],
            'events.*.event' => ['required', 'string', 'in:'.implode(',', config('tracking.events'))],
            'events.*.label' => ['nullable', 'string', 'max:255'],
            'events.*.path' => ['required', 'string', 'max:512'],
            'events.*.target' => ['nullable', 'string', 'max:1024'],
            // page_engagement sends seconds on screen; anything above an hour is a forgotten tab.
            'events.*.value' => ['nullable', 'integer', 'min:0', 'max:3600'],
            'events.*.meta' => ['nullable', 'array', 'max:10'],
        ])->validate();

        $timezone = $data['timezone'] ?? null;
        $geo = $classifier->classify($request, $timezone, $data['language'] ?? null);

        $shared = [
            'visitor_id' => $data['visitor_id'],
            'session_id' => $data['session_id'],
            'locale' => $request->attributes->get('locale'),
            ...$geo,
            'timezone' => $timezone,
            'browser_language' => $data['language'] ?? null,
            'referrer_host' => $this->referrerHost($data['referrer'] ?? null),
            'utm_source' => $data['utm_source'] ?? null,
            'utm_medium' => $data['utm_medium'] ?? null,
            'utm_campaign' => $data['utm_campaign'] ?? null,
            'device' => $this->device($userAgent),
            'ip_hash' => hash_hmac('sha256', (string) $request->ip(), (string) config('app.key')),
            'occurred_on' => now(config('tracking.timezone'))->toDateString(),
        ];

        foreach ($data['events'] as $event) {
            TrackingEvent::create([
                ...$shared,
                'event' => $event['event'],
                'label' => isset($event['label']) ? Str::limit($event['label'], 250) : null,
                'path' => $event['path'],
                ...ContentPath::parse($event['path']),
                'target' => $event['target'] ?? null,
                'value' => $event['value'] ?? null,
                'meta' => $event['meta'] ?? null,
            ]);
        }

        return response()->noContent();
    }

    private function referrerHost(?string $referrer): ?string
    {
        $host = $referrer ? parse_url($referrer, PHP_URL_HOST) : null;

        return $host ? Str::limit(strtolower(preg_replace('/^www\./i', '', $host)), 250, '') : null;
    }

    private function device(string $userAgent): string
    {
        return match (true) {
            (bool) preg_match('/ipad|tablet|kindle|silk|(android(?!.*mobile))/i', $userAgent) => 'tablet',
            (bool) preg_match('/mobi|iphone|ipod|android|blackberry|opera mini|iemobile/i', $userAgent) => 'mobile',
            default => 'desktop',
        };
    }
}
