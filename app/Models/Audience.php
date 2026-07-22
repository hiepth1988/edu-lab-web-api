<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Audience extends Model
{
    protected $fillable = ['status', 'hero_image', 'sort_order'];

    public function translations(): HasMany
    {
        return $this->hasMany(AudienceTranslation::class);
    }

    public function solutions(): BelongsToMany
    {
        return $this->belongsToMany(Solution::class, 'audience_recommended_solutions')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order');
    }

    public function translation(string $locale): ?AudienceTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
