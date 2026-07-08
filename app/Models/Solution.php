<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Solution extends Model
{
    protected $fillable = ['status', 'sort_order'];

    public function translations(): HasMany
    {
        return $this->hasMany(SolutionTranslation::class);
    }

    public function features(): HasMany
    {
        return $this->hasMany(SolutionFeature::class)->orderBy('sort_order');
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(SolutionFaq::class)->orderBy('sort_order');
    }

    public function translation(string $locale): ?SolutionTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
