<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResearchPost extends Model
{
    protected $fillable = ['research_topic_id', 'status', 'published_at'];

    protected function casts(): array
    {
        return ['published_at' => 'datetime'];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(ResearchPostTranslation::class);
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(ResearchTopic::class, 'research_topic_id');
    }

    public function translation(string $locale): ?ResearchPostTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
