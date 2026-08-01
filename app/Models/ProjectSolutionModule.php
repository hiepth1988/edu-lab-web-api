<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectSolutionModule extends Model
{
    protected $fillable = ['project_id', 'image', 'sort_order'];

    public function translations(): HasMany
    {
        return $this->hasMany(ProjectSolutionModuleTranslation::class, 'module_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function translation(string $locale): ?ProjectSolutionModuleTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
