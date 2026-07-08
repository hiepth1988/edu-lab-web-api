<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolutionFaqTranslation extends Model
{
    protected $fillable = ['solution_faq_id', 'locale', 'question', 'answer'];

    public function faq(): BelongsTo
    {
        return $this->belongsTo(SolutionFaq::class, 'solution_faq_id');
    }
}
