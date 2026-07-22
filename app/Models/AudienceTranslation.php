<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AudienceTranslation extends Model
{
    protected $fillable = [
        'audience_id',
        'locale',
        'slug',
        'title',
        'subheading',
        'pain_points',
        'how_we_help',
        'meta_title',
        'meta_description',
    ];

    public function audience(): BelongsTo
    {
        return $this->belongsTo(Audience::class);
    }
}
