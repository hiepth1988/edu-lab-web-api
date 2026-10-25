<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectGalleryImage extends Model
{
    use HasLocalizedImages;

    protected $fillable = ['project_id', 'category_key', 'image_url', 'image_url_en', 'sort_order'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
