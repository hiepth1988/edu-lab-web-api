<?php

return [

    // Master switch for POST /api/track. When false the endpoint accepts and discards events.
    'enabled' => env('TRACKING_ENABLED', true),

    // Only trust the CF-IPCountry header when the API really sits behind Cloudflare,
    // otherwise any client could spoof its own country.
    'trust_cloudflare' => env('TRACKING_TRUST_CLOUDFLARE', false),

    // Events older than this are removed by `model:prune` (scheduled daily).
    'retention_days' => (int) env('TRACKING_RETENTION_DAYS', 395),

    // Day boundaries for reports ("occurred_on") use the audience's timezone, not UTC.
    'timezone' => env('TRACKING_TIMEZONE', 'Asia/Ho_Chi_Minh'),

    'home_country' => 'VN',

    // Browser timezones that mean "in Vietnam" when the IP lookup has no answer.
    'home_timezones' => ['Asia/Ho_Chi_Minh', 'Asia/Saigon'],

    // RIR delegation files used by `tracking:sync-ip-ranges` to build the IP → country table.
    // Public, no API key, refreshed daily by the registries.
    'rir_sources' => [
        'https://ftp.apnic.net/stats/apnic/delegated-apnic-latest',
        'https://ftp.ripe.net/pub/stats/ripencc/delegated-ripencc-latest',
        'https://ftp.arin.net/pub/stats/arin/delegated-arin-extended-latest',
        'https://ftp.lacnic.net/pub/stats/lacnic/delegated-lacnic-latest',
        'https://ftp.afrinic.net/pub/stats/afrinic/delegated-afrinic-latest',
    ],

    // Whitelist of event names the front end may send. Anything else is dropped.
    'events' => [
        'page_view',
        'page_engagement',
        'scroll_depth',
        'section_view',
        'cta_click',
        'nav_click',
        'outbound_click',
        'contact_click',
        'locale_switch',
        'search',
        'lead_submit',
        'lead_error',
        'newsletter_subscribe',
        'newsletter_error',
    ],

];
