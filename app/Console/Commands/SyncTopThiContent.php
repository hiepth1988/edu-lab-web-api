<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\Project;
use App\Support\Content\TopThiContent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

// One-off command to push docs/edulab_topthi_page_update.md into an already-seeded
// production database, since ProjectsSeeder/ProductsSeeder skip rows that exist.
// Removes the unsourced metrics (40% / 10,000+ / 99.9%), replaces the case-study
// content and solution modules, and moves AI Learning Engine / Knowledge Graph
// Engine out of the "in development" stage. Safe to re-run.
class SyncTopThiContent extends Command
{
    protected $signature = 'app:sync-topthi-content
                            {--draft : Also set the TopThi project to draft (hides /our-work/topthi until re-published)}';

    protected $description = 'Update the TopThi case study and related products per docs/edulab_topthi_page_update.md';

    public function handle(): int
    {
        $project = Project::whereHas('translations', fn ($q) => $q->where('slug', 'topthi'))->first();

        if (! $project) {
            $this->error('topthi project not found.');

            return self::FAILURE;
        }

        DB::transaction(function () use ($project) {
            foreach (TopThiContent::translations() as $locale => $fields) {
                $project->translations()->updateOrCreate(
                    ['locale' => $locale],
                    ['slug' => 'topthi'] + $fields,
                );
            }

            $project->metrics()->delete();

            $project->solutionModules()->delete();

            foreach (TopThiContent::solutionModules() as $index => $module) {
                $model = $project->solutionModules()->create(['image' => $module['image'], 'sort_order' => $index]);

                foreach (['vi', 'en'] as $locale) {
                    $model->translations()->create(['locale' => $locale] + $module[$locale]);
                }
            }

            if ($this->option('draft')) {
                $project->update(['status' => 'draft']);
            }

            $this->syncProducts();
        });

        Cache::tags(['projects'])->flush();
        Cache::tags(['products'])->flush();

        $this->info('topthi content synced successfully'.($this->option('draft') ? ' (project set to draft).' : '.'));

        return self::SUCCESS;
    }

    private function syncProducts(): void
    {
        foreach (TopThiContent::pilotProductStages() as $slug => $stage) {
            $product = Product::whereHas('translations', fn ($q) => $q->where('slug', $slug))->first();

            if (! $product) {
                $this->warn("Product {$slug} not found, skipping.");

                continue;
            }

            $product->update(['stage' => $stage]);
        }

        $topthi = Product::whereHas('translations', fn ($q) => $q->where('slug', 'topthi'))->first();

        if (! $topthi) {
            $this->warn('Product topthi not found, skipping.');

            return;
        }

        foreach (TopThiContent::productCopy() as $locale => $copy) {
            $topthi->translations()->where('locale', $locale)->update([
                'role_summary' => $copy['role_summary'],
                'description' => $copy['description'],
                'meta_description' => $copy['role_summary'],
            ]);
        }
    }
}
