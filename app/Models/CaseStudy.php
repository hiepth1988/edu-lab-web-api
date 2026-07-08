<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseStudy extends Model
{
    protected $fillable = ['status', 'featured_image', 'published_at'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CaseStudyTranslation::class);
    }

    public function metrics(): HasMany
    {
        return $this->hasMany(CaseMetric::class)->orderBy('sort_order');
    }

    public function translation(string $locale): ?CaseStudyTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
