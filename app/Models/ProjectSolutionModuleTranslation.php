<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectSolutionModuleTranslation extends Model
{
    protected $fillable = ['module_id', 'locale', 'title', 'description', 'technical_note', 'features'];

    protected function casts(): array
    {
        return [
            'features' => 'array',
        ];
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(ProjectSolutionModule::class, 'module_id');
    }
}
