<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResearchTopicTranslation extends Model
{
    protected $fillable = ['research_topic_id', 'locale', 'slug', 'name'];

    public function topic(): BelongsTo
    {
        return $this->belongsTo(ResearchTopic::class, 'research_topic_id');
    }
}
