<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('project_metric_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_metric_id')->constrained('project_metrics')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('label');
            $table->timestamps();

            $table->unique(['project_metric_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_metric_translations');
    }
};
