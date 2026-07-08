<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolutionFeatureTranslation extends Model
{
    protected $fillable = ['solution_feature_id', 'locale', 'title', 'description'];

    public function feature(): BelongsTo
    {
        return $this->belongsTo(SolutionFeature::class, 'solution_feature_id');
    }
}
