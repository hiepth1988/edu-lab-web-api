<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = ['status', 'stage', 'sort_order'];

    public function translations(): HasMany
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function features(): HasMany
    {
        return $this->hasMany(ProductFeature::class)->orderBy('sort_order');
    }

    public function translation(string $locale): ?ProductTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
