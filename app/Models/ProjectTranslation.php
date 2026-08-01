<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class ProjectTranslation extends Model
{
    use Searchable;

    protected $fillable = [
        'project_id',
        'locale',
        'slug',
        'title',
        'excerpt',
        'hero_eyebrow',
        'hero_cta_label',
        'hero_cta_url',
        'hero_badges',
        'hero_stats',
        'snapshot_items',
        'scale_heading',
        'scale_description',
        'scale_stats',
        'challenges_heading',
        'challenges_description',
        'challenges',
        'feature_map_heading',
        'feature_groups',
        'journey_heading',
        'journey_steps',
        'gallery_heading',
        'gallery_categories',
        'architecture_heading',
        'architecture_layers',
        'tech_stack_groups',
        'results_heading',
        'results',
        'lessons_quote',
        'lessons_citation',
        'meta_title',
        'meta_description',
        'og_image',
        'canonical_url',
    ];

    protected function casts(): array
    {
        return [
            'hero_badges' => 'array',
            'hero_stats' => 'array',
            'snapshot_items' => 'array',
            'scale_stats' => 'array',
            'challenges' => 'array',
            'feature_groups' => 'array',
            'journey_steps' => 'array',
            'gallery_categories' => 'array',
            'architecture_layers' => 'array',
            'tech_stack_groups' => 'array',
            'results' => 'array',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function shouldBeSearchable(): bool
    {
        return $this->project?->status === 'published';
    }

    public function searchableAs(): string
    {
        return 'project_translations';
    }

    public function toSearchableArray(): array
    {
        return [
            'locale' => $this->locale,
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
        ];
    }
}
