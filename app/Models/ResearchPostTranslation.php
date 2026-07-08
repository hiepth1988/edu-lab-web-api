<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class ResearchPostTranslation extends Model
{
    use Searchable;

    protected $fillable = [
        'research_post_id',
        'locale',
        'slug',
        'title',
        'excerpt',
        'content',
        'meta_title',
        'meta_description',
    ];

    public function researchPost(): BelongsTo
    {
        return $this->belongsTo(ResearchPost::class, 'research_post_id');
    }

    public function shouldBeSearchable(): bool
    {
        return $this->researchPost?->status === 'published';
    }

    public function searchableAs(): string
    {
        return 'research_post_translations';
    }

    public function toSearchableArray(): array
    {
        return [
            'locale' => $this->locale,
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
        ];
    }
}
