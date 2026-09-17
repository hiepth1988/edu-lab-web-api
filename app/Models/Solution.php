<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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

    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'solution_related_products', 'solution_id', 'product_id')
            ->withTimestamps()
            ->orderBy('solution_related_products.sort_order');
    }

    public function relatedInsights(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'solution_related_posts', 'solution_id', 'post_id')
            ->withTimestamps()
            ->orderBy('solution_related_posts.sort_order');
    }

    public function translation(string $locale): ?SolutionTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
