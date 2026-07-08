<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductFeatureTranslation extends Model
{
    protected $fillable = ['product_feature_id', 'locale', 'title', 'description'];

    public function feature(): BelongsTo
    {
        return $this->belongsTo(ProductFeature::class, 'product_feature_id');
    }
}
