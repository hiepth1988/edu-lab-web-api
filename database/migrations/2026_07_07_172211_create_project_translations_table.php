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
        Schema::create('project_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('slug');
            $table->string('title');
            $table->text('excerpt')->nullable();

            // Hero
            $table->string('hero_eyebrow')->nullable();
            $table->string('hero_cta_label')->nullable();
            $table->string('hero_cta_url')->nullable();
            $table->json('hero_badges')->nullable();
            $table->json('hero_stats')->nullable();

            // Project Snapshot
            $table->json('snapshot_items')->nullable();

            // Project Scale
            $table->string('scale_heading')->nullable();
            $table->text('scale_description')->nullable();
            $table->json('scale_stats')->nullable();

            // The Challenge
            $table->string('challenges_heading')->nullable();
            $table->text('challenges_description')->nullable();
            $table->json('challenges')->nullable();

            // Feature Map
            $table->string('feature_map_heading')->nullable();
            $table->json('feature_groups')->nullable();

            // Product Journey
            $table->string('journey_heading')->nullable();
            $table->json('journey_steps')->nullable();

            // Product Gallery
            $table->string('gallery_heading')->nullable();
            $table->json('gallery_categories')->nullable();

            // Technical Architecture
            $table->string('architecture_heading')->nullable();
            $table->json('architecture_layers')->nullable();

            // Technology Stack
            $table->json('tech_stack_groups')->nullable();

            // Results & Impact
            $table->string('results_heading')->nullable();
            $table->json('results')->nullable();

            // Lessons Learned
            $table->text('lessons_quote')->nullable();
            $table->string('lessons_citation')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image')->nullable();
            $table->timestamps();

            $table->unique(['project_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_translations');
    }
};
