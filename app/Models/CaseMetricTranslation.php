<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseMetricTranslation extends Model
{
    protected $fillable = ['case_metric_id', 'locale', 'label'];

    public function metric(): BelongsTo
    {
        return $this->belongsTo(CaseMetric::class, 'case_metric_id');
    }
}
