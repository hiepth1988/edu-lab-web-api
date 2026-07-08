<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tag extends Model
{
    public function translations(): HasMany
    {
        return $this->hasMany(TagTranslation::class);
    }

    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }

    public function translation(string $locale): ?TagTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
