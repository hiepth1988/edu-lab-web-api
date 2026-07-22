<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $fillable = ['status', 'featured_image', 'published_at'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ProjectTranslation::class);
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
