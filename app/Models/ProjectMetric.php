<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectMetric extends Model
{
    protected $fillable = ['project_id', 'value', 'sort_order'];

    public function translations(): HasMany
    {
        return $this->hasMany(ProjectMetricTranslation::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function translation(string $locale): ?ProjectMetricTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
