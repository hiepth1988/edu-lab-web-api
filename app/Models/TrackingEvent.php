<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

class TrackingEvent extends Model
{
    use MassPrunable;

    public const UPDATED_AT = null;

    protected $fillable = [
        'event', 'label', 'path', 'content_type', 'content_slug', 'target', 'value', 'meta',
        'visitor_id', 'session_id', 'locale',
        'visitor_type', 'country', 'geo_source', 'timezone', 'browser_language',
        'referrer_host', 'utm_source', 'utm_medium', 'utm_campaign', 'device', 'ip_hash',
        'occurred_on',
    ];

    protected $hidden = ['ip_hash'];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'value' => 'integer',
            'occurred_on' => 'date:Y-m-d',
        ];
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<', now()->subDays(config('tracking.retention_days')));
    }
}
