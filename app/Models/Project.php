<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = ['category_id', 'status', 'featured_image', 'is_featured', 'published_at'];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProjectTranslation::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(ProjectMetric::class)->orderBy('sort_order');
    }

    public function sectionImages(): HasMany
    {
        return $this->hasMany(ProjectSectionImage::class)->orderBy('sort_order');
    }

    public function translation(string $locale): ?ProjectTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
