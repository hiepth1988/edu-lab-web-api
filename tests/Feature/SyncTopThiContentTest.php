<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Project;
use Database\Seeders\LocaleSeeder;
use Database\Seeders\ProductsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncTopThiContentTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_replaces_the_legacy_topthi_content(): void
    {
        $this->seed([LocaleSeeder::class, ProductsSeeder::class]);
        Product::query()->update(['stage' => '9-18-months']);

        // Mirrors what production holds today (seeded before the content revisions).
        $project = Project::create(['status' => 'published', 'is_featured' => true, 'published_at' => now()]);
        foreach (['vi', 'en'] as $locale) {
            $project->translations()->create([
                'locale' => $locale,
                'slug' => 'topthi',
                'title' => 'TopThi — Living Lab',
                'hero_badges' => [['icon' => 'shield', 'label' => 'Anti-cheat']],
                'hero_stats' => [['value' => '40%', 'label' => 'grading time']],
                'results' => [['value' => '99.9%', 'label' => 'uptime']],
                'journey_steps' => [['title' => 'Làm bài', 'description' => 'trắc nghiệm/tự luận']],
            ]);
        }
        $metric = $project->metrics()->create(['value' => '10,000+', 'sort_order' => 0]);
        $metric->translations()->create(['locale' => 'vi', 'label' => 'Lượt làm bài']);
        $module = $project->solutionModules()->create(['sort_order' => 0]);
        $module->translations()->create(['locale' => 'vi', 'title' => 'Ngân hàng câu hỏi', 'features' => []]);

        $this->artisan('app:sync-topthi-content')->assertSuccessful();

        $data = $this->getJson('/api/projects/topthi?locale=vi')->assertOk()->json('data');

        $this->assertStringContainsString('Vòng học tập thích ứng', $data['title']);
        $this->assertSame([], $data['hero_stats']);
        $this->assertSame([], $data['results']);
        $this->assertSame([], $data['metrics']);
        $this->assertCount(5, $data['journey_steps']);
        $this->assertNotEmpty($data['journey_note']);
        $this->assertSame('Khoa học phía sau', $data['science_heading']);
        $this->assertCount(6, $data['science_cards']);
        $this->assertNotEmpty($data['science_note']);
        $this->assertCount(3, $data['solution_modules']);
        $this->assertSame('Đề luyện cá nhân mỗi ngày', $data['solution_modules'][0]['title']);

        $payload = json_encode($data, JSON_UNESCAPED_UNICODE);
        foreach (['40%', '10,000+', '99.9%', 'Anti-cheat', 'tự luận', 'experimental', 'thử nghiệm)'] as $removed) {
            $this->assertStringNotContainsString($removed, $payload);
        }

        $en = $this->getJson('/api/projects/topthi?locale=en')->assertOk()->json('data');
        $this->assertSame([], $en['hero_stats']);
        $this->assertCount(6, $en['science_cards']);

        $stages = Product::with('translations')->get()
            ->mapWithKeys(fn ($p) => [$p->translations->firstWhere('locale', 'vi')->slug => $p->stage]);
        $this->assertSame('pilot-at-topthi', $stages['ai-learning-engine']);
        $this->assertSame('pilot-at-topthi', $stages['knowledge-graph-engine']);
        $this->assertSame('published', $project->fresh()->status);
    }

    public function test_draft_option_unpublishes_the_project(): void
    {
        $this->seed(LocaleSeeder::class);
        $project = Project::create(['status' => 'published', 'published_at' => now()]);
        $project->translations()->create(['locale' => 'vi', 'slug' => 'topthi', 'title' => 'TopThi']);

        $this->artisan('app:sync-topthi-content', ['--draft' => true])->assertSuccessful();

        $this->assertSame('draft', $project->fresh()->status);
        $this->getJson('/api/projects/topthi?locale=vi')->assertNotFound();
    }
}
