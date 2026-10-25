<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectSolutionModule extends Model
{
    use HasLocalizedImages;

    protected $fillable = ['project_id', 'image', 'image_en', 'sort_order'];

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
