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
        'architecture_approach',
        'use_cases',
        'trust_safety',
        'meta_title',
        'meta_description',
        'og_image',
        'canonical_url',
    ];

    protected function casts(): array
    {
        return [
            'trust_safety' => 'array',
        ];
    }

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }
}
