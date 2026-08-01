<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Repair migration: 2026_07_07_172211_create_project_translations_table.php was edited to add
 * hero/scale/challenges/feature_map/journey/gallery/architecture/tech_stack/results/lessons/meta
 * columns AFTER it had already run on production, so `php artisan migrate` never applied them
 * there (the migration is already marked as run). This adds any of those columns that are still
 * missing, guarded by hasColumn() so it's a no-op on environments (like local) that already have
 * them. Also guards projects.category_id/is_featured for the same reason, just in case.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('project_translations', function (Blueprint $table) {
            $columns = [
                'hero_eyebrow' => fn () => $table->string('hero_eyebrow')->nullable(),
                'hero_cta_label' => fn () => $table->string('hero_cta_label')->nullable(),
                'hero_cta_url' => fn () => $table->string('hero_cta_url')->nullable(),
                'hero_badges' => fn () => $table->json('hero_badges')->nullable(),
                'hero_stats' => fn () => $table->json('hero_stats')->nullable(),
                'snapshot_items' => fn () => $table->json('snapshot_items')->nullable(),
                'scale_heading' => fn () => $table->string('scale_heading')->nullable(),
                'scale_description' => fn () => $table->text('scale_description')->nullable(),
                'scale_stats' => fn () => $table->json('scale_stats')->nullable(),
                'challenges_heading' => fn () => $table->string('challenges_heading')->nullable(),
                'challenges_description' => fn () => $table->text('challenges_description')->nullable(),
                'challenges' => fn () => $table->json('challenges')->nullable(),
                'feature_map_heading' => fn () => $table->string('feature_map_heading')->nullable(),
                'feature_groups' => fn () => $table->json('feature_groups')->nullable(),
                'journey_heading' => fn () => $table->string('journey_heading')->nullable(),
                'journey_steps' => fn () => $table->json('journey_steps')->nullable(),
                'gallery_heading' => fn () => $table->string('gallery_heading')->nullable(),
                'gallery_categories' => fn () => $table->json('gallery_categories')->nullable(),
                'architecture_heading' => fn () => $table->string('architecture_heading')->nullable(),
                'architecture_layers' => fn () => $table->json('architecture_layers')->nullable(),
                'tech_stack_groups' => fn () => $table->json('tech_stack_groups')->nullable(),
                'results_heading' => fn () => $table->string('results_heading')->nullable(),
                'results' => fn () => $table->json('results')->nullable(),
                'lessons_quote' => fn () => $table->text('lessons_quote')->nullable(),
                'lessons_citation' => fn () => $table->string('lessons_citation')->nullable(),
                'meta_title' => fn () => $table->string('meta_title')->nullable(),
                'meta_description' => fn () => $table->text('meta_description')->nullable(),
                'og_image' => fn () => $table->string('og_image')->nullable(),
                'canonical_url' => fn () => $table->string('canonical_url')->nullable(),
            ];

            foreach ($columns as $name => $add) {
                if (! Schema::hasColumn('project_translations', $name)) {
                    $add();
                }
            }
        });

        Schema::table('projects', function (Blueprint $table) {
            if (! Schema::hasColumn('projects', 'category_id')) {
                $table->foreignId('category_id')->nullable()->after('id')->constrained()->nullOnDelete();
            }

            if (! Schema::hasColumn('projects', 'is_featured')) {
                $table->boolean('is_featured')->default(false)->after('status');
            }
        });
    }

    public function down(): void
    {
        // Intentionally no-op: this migration only backfills columns that the original
        // create_project_translations_table migration was always supposed to have. Rolling it
        // back would drop columns that other, already-applied migrations also declare.
    }
};
