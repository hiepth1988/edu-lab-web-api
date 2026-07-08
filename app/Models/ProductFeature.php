<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductFeature extends Model
{
    protected $fillable = ['product_id', 'sort_order'];

    public function translations(): HasMany
    {
        return $this->hasMany(ProductFeatureTranslation::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function translation(string $locale): ?ProductFeatureTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
