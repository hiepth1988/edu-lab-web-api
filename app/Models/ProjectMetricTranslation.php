<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectMetricTranslation extends Model
{
    protected $fillable = ['project_metric_id', 'locale', 'label'];

    public function metric(): BelongsTo
    {
        return $this->belongsTo(ProjectMetric::class, 'project_metric_id');
    }
}
