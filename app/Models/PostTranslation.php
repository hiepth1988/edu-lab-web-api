<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Laravel\Scout\Searchable;

class PostTranslation extends Model
{
    use Searchable;

    protected $fillable = [
        'post_id',
        'locale',
        'is_hidden',
        'slug',
        'title',
        'excerpt',
        'content',
        'cta_text',
        'cta_label',
        'cta_url',
        'meta_title',
        'meta_description',
        'og_image',
        'canonical_url',
    ];

    protected function casts(): array
    {
        return [
            'is_hidden' => 'boolean',
        ];
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function shouldBeSearchable(): bool
    {
        return ! $this->is_hidden && $this->post?->status === 'published';
    }

    public function searchableAs(): string
    {
        return 'post_translations';
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
