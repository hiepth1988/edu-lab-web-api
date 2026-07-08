<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolutionTranslation extends Model
{
    protected $fillable = [
        'solution_id',
        'locale',
        'slug',
        'title',
        'subheading',
        'problem',
        'solution_overview',
        'architecture_note',
        'use_cases',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }
}
