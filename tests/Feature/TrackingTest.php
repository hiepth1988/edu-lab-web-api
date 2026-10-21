<?php

namespace Tests\Feature;

use App\Models\IpCountryRange;
use App\Models\Post;
use App\Models\TrackingEvent;
use App\Models\User;
use App\Support\Tracking\ContentPath;
use App\Support\Tracking\GeoIp;
use Database\Seeders\LocaleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TrackingTest extends TestCase
{
    use RefreshDatabase;

    private const BROWSER_UA = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/130.0 Safari/537.36';

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(LocaleSeeder::class);

        // 14.160.0.0/11 is a VNPT block; 8.8.8.0/24 is US.
        IpCountryRange::insert([
            ['ip_version' => 4, 'range_start' => GeoIp::toHex('14.160.0.0')[1], 'range_end' => GeoIp::toHex('14.191.255.255')[1], 'country' => 'VN'],
            ['ip_version' => 4, 'range_start' => GeoIp::toHex('8.8.8.0')[1], 'range_end' => GeoIp::toHex('8.8.8.255')[1], 'country' => 'US'],
            ['ip_version' => 6, 'range_start' => GeoIp::toHex('2405:4800::')[1], 'range_end' => GeoIp::ipv6RangeEnd(GeoIp::toHex('2405:4800::')[1], 32), 'country' => 'VN'],
        ]);
    }

    private function track(string $ip, array $overrides = [], array $headers = [])
    {
        return $this->withServerVariables(['REMOTE_ADDR' => $ip])
            ->withHeaders(['User-Agent' => self::BROWSER_UA, ...$headers])
            ->postJson('/api/track?locale=en', [
                'visitor_id' => (string) Str::uuid(),
                'session_id' => (string) Str::uuid(),
                'timezone' => 'Europe/Berlin',
                'language' => 'en-US',
                'referrer' => 'https://www.google.com/search?q=xo',
                'events' => [
                    ['event' => 'page_view', 'path' => '/en'],
                    ['event' => 'cta_click', 'label' => 'Build with XO', 'path' => '/en', 'target' => '/en/contact'],
                ],
                ...$overrides,
            ]);
    }

    public function test_vietnamese_ip_is_classified_as_vn_even_with_foreign_browser_settings(): void
    {
        $this->track('14.170.1.2')->assertNoContent();

        $this->assertSame(2, TrackingEvent::count());
        $event = TrackingEvent::first();
        $this->assertSame('vn', $event->visitor_type);
        $this->assertSame('VN', $event->country);
        $this->assertSame('ip', $event->geo_source);
        $this->assertSame('google.com', $event->referrer_host);
        $this->assertSame('en', $event->locale);
        $this->assertNotSame('14.170.1.2', $event->ip_hash);
    }

    public function test_foreign_ip_and_ipv6_lookup(): void
    {
        $this->track('8.8.8.8')->assertNoContent();
        $this->assertSame(['foreign', 'US'], [TrackingEvent::first()->visitor_type, TrackingEvent::first()->country]);

        TrackingEvent::query()->delete();
        $this->track('2405:4800:1234::1')->assertNoContent();
        $this->assertSame('vn', TrackingEvent::first()->visitor_type);
    }

    public function test_falls_back_to_browser_timezone_when_ip_is_unknown(): void
    {
        $this->track('127.0.0.1', ['timezone' => 'Asia/Ho_Chi_Minh'])->assertNoContent();
        $this->assertSame(['vn', 'timezone'], [TrackingEvent::first()->visitor_type, TrackingEvent::first()->geo_source]);

        TrackingEvent::query()->delete();
        $this->track('127.0.0.1', ['timezone' => 'America/New_York'])->assertNoContent();
        $this->assertSame('foreign', TrackingEvent::first()->visitor_type);
    }

    public function test_cloudflare_header_is_ignored_unless_trusted(): void
    {
        $this->track('8.8.8.8', [], ['CF-IPCountry' => 'VN']);
        $this->assertSame('foreign', TrackingEvent::first()->visitor_type);

        config(['tracking.trust_cloudflare' => true]);
        TrackingEvent::query()->delete();
        $this->track('8.8.8.8', [], ['CF-IPCountry' => 'VN']);
        $this->assertSame(['vn', 'cloudflare'], [TrackingEvent::first()->visitor_type, TrackingEvent::first()->geo_source]);
    }

    public function test_bots_and_unknown_events_are_not_stored(): void
    {
        $this->track('8.8.8.8', [], ['User-Agent' => 'Googlebot/2.1'])->assertNoContent();
        $this->assertSame(0, TrackingEvent::count());

        $this->track('8.8.8.8', ['events' => [['event' => 'drop_table', 'path' => '/']]])->assertUnprocessable();
        $this->assertSame(0, TrackingEvent::count());
    }

    public function test_accepts_text_plain_beacon_body(): void
    {
        $body = json_encode([
            'visitor_id' => (string) Str::uuid(),
            'session_id' => (string) Str::uuid(),
            'events' => [['event' => 'scroll_depth', 'path' => '/', 'value' => 75]],
        ]);

        $this->withServerVariables(['REMOTE_ADDR' => '14.170.1.2'])
            ->call('POST', '/api/track', [], [], [], ['CONTENT_TYPE' => 'text/plain', 'HTTP_USER_AGENT' => self::BROWSER_UA], $body)
            ->assertNoContent();

        $this->assertSame(75, TrackingEvent::first()->value);
    }

    public function test_admin_summary_splits_vn_and_foreign(): void
    {
        $this->track('14.170.1.2');
        $this->track('8.8.8.8', ['events' => [['event' => 'page_view', 'path' => '/en/hop-tac'], ['event' => 'lead_submit', 'path' => '/en/contact']]]);

        $this->getJson('/api/admin/tracking/summary')->assertUnauthorized();

        Sanctum::actingAs(User::factory()->create());

        $response = $this->getJson('/api/admin/tracking/summary')->assertOk();

        $response->assertJsonPath('by_type.vn.visitors', 1)
            ->assertJsonPath('by_type.vn.page_views', 1)
            ->assertJsonPath('by_type.foreign.visitors', 1)
            ->assertJsonPath('by_type.foreign.leads', 1)
            ->assertJsonCount(30, 'daily');

        $actions = collect($response->json('top_actions'))->keyBy('event');
        $this->assertSame([1, 0], [$actions['cta_click']['vn'], $actions['cta_click']['foreign']]);
        $this->assertSame([0, 1], [$actions['lead_submit']['vn'], $actions['lead_submit']['foreign']]);

        $this->getJson('/api/admin/tracking/summary?visitor_type=foreign')
            ->assertOk()
            ->assertJsonCount(1, 'top_pages')
            ->assertJsonPath('top_pages.0.path', '/en/hop-tac');

        $this->getJson('/api/admin/tracking/events?visitor_type=vn')->assertOk()->assertJsonCount(2, 'data');
    }

    public function test_paths_are_mapped_to_content(): void
    {
        $this->assertSame(['content_type' => 'post', 'content_slug' => 'lms-guide'], ContentPath::parse('/insights/lms-guide'));
        $this->assertSame(['content_type' => 'post', 'content_slug' => 'lms-guide'], ContentPath::parse('/en/insights/lms-guide/'));
        $this->assertSame(['content_type' => 'project', 'content_slug' => 'topthi'], ContentPath::parse('/our-work/topthi'));
        $this->assertSame(['content_type' => 'audience', 'content_slug' => 'edtech-startups'], ContentPath::parse('/en/who-we-help/edtech-startups'));
        $this->assertSame(['content_type' => 'page', 'content_slug' => 'insights'], ContentPath::parse('/insights'));
        $this->assertSame(['content_type' => 'page', 'content_slug' => 'home'], ContentPath::parse('/en'));
        $this->assertSame(['content_type' => 'page', 'content_slug' => 'home'], ContentPath::parse('/'));
    }

    public function test_summary_reports_opened_articles_with_title_time_and_read_rate(): void
    {
        $post = Post::create(['status' => 'published', 'published_at' => now()]);
        $post->translations()->create(['locale' => 'vi', 'slug' => 'bai-lms', 'title' => 'Hướng dẫn LMS']);
        $post->translations()->create(['locale' => 'en', 'slug' => 'lms-guide', 'title' => 'LMS guide']);

        // VN reader: opens the article, reads 90s, scrolls to the end.
        $this->track('14.170.1.2', ['events' => [
            ['event' => 'page_view', 'path' => '/insights/bai-lms'],
            ['event' => 'scroll_depth', 'path' => '/insights/bai-lms', 'value' => 75],
            ['event' => 'page_engagement', 'path' => '/insights/bai-lms', 'value' => 90],
        ]])->assertNoContent();
        // Foreign reader on the English URL bounces after 10s.
        $this->track('8.8.8.8', ['events' => [
            ['event' => 'page_view', 'path' => '/en/insights/lms-guide'],
            ['event' => 'page_engagement', 'path' => '/en/insights/lms-guide', 'value' => 10],
        ]])->assertNoContent();

        Sanctum::actingAs(User::factory()->create());

        $content = collect($this->getJson('/api/admin/tracking/summary')->assertOk()->json('top_content'))->keyBy('content_slug');

        // vi + en URLs merged into one row for the article, titled in Vietnamese.
        $this->assertCount(1, $content);
        $article = $content->first();
        $this->assertSame('Hướng dẫn LMS', $article['title']);
        $this->assertEqualsCanonicalizing(['bai-lms', 'lms-guide'], $article['slugs']);
        $this->assertSame([2, 1, 1, 50], [$article['views'], $article['vn'], $article['foreign'], $article['avg_seconds']]);
        $this->assertEquals(0.5, $article['read_rate']);

        $this->getJson('/api/admin/tracking/events?content_type=post&content_slug=bai-lms,lms-guide')->assertOk()->assertJsonCount(5, 'data');
    }

    public function test_ipv6_range_end(): void
    {
        $this->assertSame(
            GeoIp::toHex('2405:4800:ffff:ffff:ffff:ffff:ffff:ffff')[1],
            GeoIp::ipv6RangeEnd(GeoIp::toHex('2405:4800::')[1], 32),
        );
        $this->assertSame(
            GeoIp::toHex('2001:db8:7f:ffff:ffff:ffff:ffff:ffff')[1],
            GeoIp::ipv6RangeEnd(GeoIp::toHex('2001:db8::')[1], 41),
        );
    }
}
