<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SolutionFeature extends Model
{
    protected $fillable = ['solution_id', 'sort_order'];

    public function translations(): HasMany
    {
        return $this->hasMany(SolutionFeatureTranslation::class);
    }

    public function solution(): BelongsTo
    {
        return $this->belongsTo(Solution::class);
    }

    public function translation(string $locale): ?SolutionFeatureTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
