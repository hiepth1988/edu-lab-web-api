<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    public function translations(): HasMany
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function translation(string $locale): ?CategoryTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
