<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class CaseStudyTranslation extends Model
{
    use Searchable;

    protected $fillable = [
        'case_study_id',
        'locale',
        'slug',
        'title',
        'excerpt',
        'problem',
        'solution_text',
        'result',
        'meta_title',
        'meta_description',
        'og_image',
    ];

    public function caseStudy(): BelongsTo
    {
        return $this->belongsTo(CaseStudy::class);
    }

    public function shouldBeSearchable(): bool
    {
        return $this->caseStudy?->status === 'published';
    }

    public function searchableAs(): string
    {
        return 'case_study_translations';
    }

    public function toSearchableArray(): array
    {
        return [
            'locale' => $this->locale,
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'problem' => $this->problem,
            'solution_text' => $this->solution_text,
            'result' => $this->result,
        ];
    }
}
