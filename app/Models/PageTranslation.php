<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PageTranslation extends Model
{
    protected $fillable = [
        'page_id',
        'locale',
        'slug',
        'title',
        'excerpt',
        'content',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }
}
