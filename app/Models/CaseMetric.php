<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseMetric extends Model
{
    protected $fillable = ['case_study_id', 'value', 'sort_order'];

    public function translations(): HasMany
    {
        return $this->hasMany(CaseMetricTranslation::class);
    }

    public function caseStudy(): BelongsTo
    {
        return $this->belongsTo(CaseStudy::class);
    }

    public function translation(string $locale): ?CaseMetricTranslation
    {
        return $this->translations->firstWhere('locale', $locale);
    }
}
