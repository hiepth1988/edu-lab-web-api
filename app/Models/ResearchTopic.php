<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResearchTopic extends Model
{
    public function translations(): HasMany
    {
        return $this->hasMany(ResearchTopicTranslation::class);
    }

    public function translation(string $locale): ?ResearchTopicTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
