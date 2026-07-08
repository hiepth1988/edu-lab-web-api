<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'company',
        'need',
        'message',
        'locale',
        'source_url',
        'status',
    ];

    public function notes(): HasMany
    {
        return $this->hasMany(LeadNote::class)->latest();
    }
}
