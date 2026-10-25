<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use Database\Seeders\LocaleSeeder;
use Database\Seeders\PartnershipPageSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([LocaleSeeder::class, RolePermissionSeeder::class, PartnershipPageSeeder::class]);
    }

    private function actingAsRole(string $role): User
    {
        $user = User::factory()->create();
        $user->assignRole($role);
        Sanctum::actingAs($user);

        return $user;
    }

    /** Builds the update payload the admin form sends, from the page as the API returns it. */
    private function payloadFor(Page $page): array
    {
        $page->load(['translations', 'sections.translations']);

        return [
            'status' => $page->status,
            'translations' => $page->translations->mapWithKeys(fn ($t) => [$t->locale => $t->only(
                ['title', 'excerpt', 'content', 'meta_title', 'meta_description', 'og_image']
            )])->all(),
            'sections' => $page->sections->map(fn ($s) => [
                'id' => $s->id,
                'sort_order' => $s->sort_order,
                'is_active' => $s->is_active,
                'translations' => $s->translations->mapWithKeys(fn ($t) => [$t->locale => $t->only(
                    ['heading', 'subheading', 'body', 'cta_label', 'cta_url', 'extra']
                )])->all(),
            ])->all(),
        ];
    }

    public function test_guests_cannot_access(): void
    {
        $this->getJson('/api/admin/pages')->assertUnauthorized();
    }

    public function test_lists_pages(): void
    {
        $this->actingAsRole('editor');

        $this->getJson('/api/admin/pages')
            ->assertOk()
            ->assertJsonFragment(['key' => 'partnership']);
    }

    public function test_admin_updates_price_and_public_page_reflects_it(): void
    {
        $this->actingAsRole('admin');
        $page = Page::where('key', 'partnership')->firstOrFail();

        // Warm the public cache so we can check it gets flushed.
        $this->getJson('/api/pages/partnership?locale=en')->assertOk();

        $payload = $this->payloadFor($page);
        $index = collect($payload['sections'])->search(fn ($s) => $page->sections->firstWhere('id', $s['id'])->section_key === 'starter_package');
        $payload['sections'][$index]['translations']['en']['extra']['price'] = '$3,000';
        $payload['translations']['en']['meta_title'] = 'New meta title';

        $this->putJson("/api/admin/pages/{$page->id}", $payload)->assertOk();

        $response = $this->getJson('/api/pages/partnership?locale=en')->assertOk();
        $starter = collect($response->json('sections'))->firstWhere('section_key', 'starter_package');
        $this->assertSame('$3,000', $starter['translation']['extra']['price']);
        $this->assertSame('New meta title', $response->json('translation.meta_title'));
        // Vietnamese content is untouched.
        $vi = collect($this->getJson('/api/pages/partnership?locale=vi')->json('sections'))->firstWhere('section_key', 'starter_package');
        $this->assertSame('20 triệu', $vi['translation']['extra']['price']);
    }

    public function test_hidden_section_disappears_from_public_page(): void
    {
        $this->actingAsRole('admin');
        $page = Page::where('key', 'partnership')->firstOrFail();

        $payload = $this->payloadFor($page);
        $faq = $page->sections->firstWhere('section_key', 'faq');
        foreach ($payload['sections'] as &$section) {
            if ($section['id'] === $faq->id) {
                $section['is_active'] = false;
            }
        }

        $this->putJson("/api/admin/pages/{$page->id}", $payload)->assertOk();

        $keys = collect($this->getJson('/api/pages/partnership?locale=en')->json('sections'))->pluck('section_key');
        $this->assertNotContains('faq', $keys);
    }

    public function test_rejects_sections_from_another_page(): void
    {
        $this->actingAsRole('admin');
        $partnership = Page::where('key', 'partnership')->firstOrFail();
        $homeSection = Page::where('key', 'home')->firstOrFail()->sections()->first();

        $payload = $this->payloadFor($partnership);
        $payload['sections'][0]['id'] = $homeSection->id;

        $this->putJson("/api/admin/pages/{$partnership->id}", $payload)->assertUnprocessable();
    }

    public function test_editor_cannot_publish(): void
    {
        $this->actingAsRole('editor');
        $page = Page::where('key', 'partnership')->firstOrFail();

        $this->putJson("/api/admin/pages/{$page->id}", $this->payloadFor($page))->assertForbidden();
    }

    public function test_slug_cannot_be_changed(): void
    {
        $this->actingAsRole('admin');
        $page = Page::where('key', 'partnership')->firstOrFail();

        $payload = $this->payloadFor($page);
        $payload['translations']['en']['slug'] = 'hacked';

        $this->putJson("/api/admin/pages/{$page->id}", $payload)->assertOk();
        $this->assertSame('partnership', $page->translations()->where('locale', 'en')->value('slug'));
    }
}
