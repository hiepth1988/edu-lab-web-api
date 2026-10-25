<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Optional English versions of shared images. Empty = the English site uses the default image.
return new class extends Migration
{
    private array $columns = [
        'posts' => 'featured_image',
        'projects' => 'featured_image',
        'audiences' => 'hero_image',
        'project_solution_modules' => 'image',
        'project_gallery_images' => 'image_url',
    ];

    public function up(): void
    {
        foreach ($this->columns as $table => $column) {
            Schema::table($table, function (Blueprint $t) use ($column) {
                $t->string("{$column}_en", 2048)->nullable()->after($column);
            });
        }
    }

    public function down(): void
    {
        foreach ($this->columns as $table => $column) {
            Schema::table($table, function (Blueprint $t) use ($column) {
                $t->dropColumn("{$column}_en");
            });
        }
    }
};
