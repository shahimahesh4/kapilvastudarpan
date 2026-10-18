<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use Tests\TestCase;

class NewsPortalTest extends TestCase
{
    public function test_homepage_renders_successfully_with_nepali_branding(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('कपिलवस्तु दर्पण');
        $response->assertSee('मुख्य समाचार');
        $response->assertDontSee('कोशी संवाददाता');
    }

    public function test_single_article_page_renders_with_seo_and_increments_views(): void
    {
        $article = Article::published()->first();
        $this->assertNotNull($article, 'At least one published article must exist');

        $initialViews = $article->views;

        $response = $this->get(route('articles.show', $article->slug));

        $response->assertStatus(200);
        $response->assertSee($article->title);
        $response->assertSee($article->category->name);
        $response->assertSee('कपिलवस्तु दर्पण');
        $response->assertSee('meta property="og:title"', false);
        $response->assertSee('meta name="twitter:card"', false);
        $response->assertSee('NewsArticle');
        $response->assertSee('profile-avatar-dashed');
        $response->assertSee('संग्रह');
        $response->assertSee('Shares');
        $response->assertSee('facebook.com/sharer/sharer.php');

        $this->assertEquals($initialViews + 1, $article->fresh()->views);
    }

    public function test_category_archive_page_renders_articles(): void
    {
        $category = Category::where('slug', 'sports')->first() ?? Category::first();
        $this->assertNotNull($category);

        $response = $this->get(route('category.show', $category->slug));

        $response->assertStatus(200);
        $response->assertSee($category->name);
        $response->assertDontSee('कुनै समाचार फेला परेन');

        $firstArticle = Article::forCategory($category->slug)->published()->first();
        if ($firstArticle) {
            $response->assertSee($firstArticle->title);
        }
    }

    public function test_search_returns_filtered_articles(): void
    {
        $response = $this->get(route('search', ['q' => 'नेपाल']));

        $response->assertStatus(200);
        $response->assertSee('खोज नतिजा: नेपाल');
    }

    public function test_sitemap_xml_returns_valid_content(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('xml', (string) $response->headers->get('content-type'));
        $response->assertSee('urlset');
    }

    public function test_security_headers_are_present_in_responses(): void
    {
        $response = $this->get('/');

        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
    }

    public function test_admin_login_page_is_accessible(): void
    {
        $response = $this->get('/stnapanel/login');

        $response->assertStatus(200);
        $response->assertSee('कपिलवस्तु दर्पण');
    }

    public function test_dynamic_header_menu_renders_with_submenus(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        // Header menu items
        $response->assertSee('मुख्य समाचार');
        $response->assertSee('कपिलवस्तु विशेष');
        $response->assertSee('प्रदेश');
        $response->assertSee('लुम्बिनी प्रदेश');
        $response->assertDontSee('ई-पेपर');

        // Submenus matching user screenshot
        $response->assertSee('अदालत/प्रशासन');
        $response->assertSee('सुरक्षा/अपराध');
        $response->assertSee('हेडलाइन');

        // Mega Menu matching attached image
        $response->assertSee('कोशी प्रदेश');
        $response->assertSee('मधेश प्रदेश');
        $response->assertSee('बागमती प्रदेश');
        $response->assertSee('थप समाचार');
    }

    public function test_rashifal_detail_page_renders_successfully(): void
    {
        $response = $this->get('/rashifal/karkat');

        $response->assertStatus(200);
        $response->assertSee('कर्कट राशि');
        $response->assertSee('हि, हु, हे, हो, डा, डि, डु, डे, डो');
        $response->assertSee('दैनिक');
        $response->assertSee('वार्षिक');
        $response->assertSee('भर्खरै');
        $response->assertSee('ट्रेन्डिङ');
    }

    public function test_ashifal_redirects_to_rashifal(): void
    {
        $response = $this->get('/ashifal/karkat');

        $response->assertRedirect('/rashifal/karkat');
    }

    public function test_homepage_has_clickable_rashifal_cards(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('/rashifal/karkat');
        $response->assertSee('/rashifal/mesh');
    }

    public function test_profile_name_displays_kapilvastu_darpan_consistently(): void
    {
        // 1. Helper formatting logic
        $this->assertEquals('कपिलवस्तु दर्पण', Article::formatAuthorName(null));
        $this->assertEquals('कपिलवस्तु दर्पण', Article::formatAuthorName(''));
        $this->assertEquals('कपिलवस्तु दर्पण', Article::formatAuthorName('Kapilvastu Darpan'));
        $this->assertEquals('कपिलवस्तु दर्पण', Article::formatAuthorName('Test User'));
        $this->assertEquals('कपिलवस्तु दर्पण', Article::formatAuthorName('गणेश पाण्डे'));
        $this->assertEquals('कपिलवस्तु दर्पण', Article::formatAuthorName('प्रमोद पौडेल / कपिलवस्तु'));
        $this->assertEquals('कपिलवस्तु दर्पण', Article::formatAuthorName('प्रमोद पौडेल / कपिलवस्तु - कपिलवस्तु दर्पण'));

        // 2. Single article detail page profile name
        $article = Article::published()->first();
        $this->assertNotNull($article);

        $response = $this->get(route('articles.show', $article->slug));
        $response->assertStatus(200);
        $response->assertSee('profile-avatar');
        $response->assertSee($article->display_author);
        $this->assertStringContainsString('कपिलवस्तु दर्पण', $article->display_author);

        // 3. Homepage profile name rendering
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $homeResponse->assertSee('कपिलवस्तु दर्पण');
    }

    public function test_numbers_are_displayed_in_nepali_across_the_website(): void
    {
        // 1. nepali_number helper tests
        $this->assertEquals('१९', nepali_number(19));
        $this->assertEquals('१९ घण्टा अगाडि', nepali_number('19 घण्टा अगाडि'));
        $this->assertEquals('२,४५०', nepali_number('2,450'));
        $this->assertEquals('२०२६', nepali_number(2026));
        $this->assertEquals('०', nepali_number(0));

        // 2. Relative time diffs in Carbon use Nepali digits
        $this->assertStringContainsString('१९ घण्टा अगाडि', now()->subHours(19)->diffForHumans());
        $this->assertStringContainsString('२ घण्टा अगाडि', now()->subHours(2)->diffForHumans());
        $this->assertStringContainsString('५ मिनेट अगाडि', now()->subMinutes(5)->diffForHumans());

        // 3. Homepage check: Ensure no English digits appear before relative time units
        $homeResponse = $this->get('/');
        $homeResponse->assertStatus(200);
        $content = $homeResponse->getContent();
        $this->assertDoesNotMatchRegularExpression('/[0-9]+\s*(घण्टा|मिनेट|दिन|सेकेण्ड)\s*अगाडि/u', $content);

        // 4. Single article page check: Reading time & views in Nepali
        $article = Article::published()->first();
        $this->assertNotNull($article);
        $articleResponse = $this->get(route('articles.show', $article->slug));
        $articleResponse->assertStatus(200);
        $articleContent = $articleResponse->getContent();
        $this->assertDoesNotMatchRegularExpression('/[0-9]+\s*(घण्टा|मिनेट|दिन)\s*अगाडि/u', $articleContent);
        $this->assertMatchesRegularExpression('/[०-९]+\s*मिनेट पढ्न सकिने/u', $articleContent);
        $this->assertMatchesRegularExpression('/[०-९,]+\s*पटक पढिएको/u', $articleContent);
    }

    public function test_seo_meta_title_renders_properly(): void
    {
        $category = Category::first() ?? Category::create(['name' => 'अर्थतन्त्र', 'slug' => 'economy']);
        $article = Article::create([
            'category_id' => $category->id,
            'title' => 'परीक्षण समाचार शीर्षक',
            'slug' => 'test-seo-meta-title-article-' . uniqid(),
            'body' => '<p>परीक्षण सामग्री</p>',
            'meta_title' => 'कस्टम गुगल एसईओ मेटा शीर्षक | विशेष',
            'is_published' => true,
            'published_at' => now(),
        ]);

        try {
            $response = $this->get(route('articles.show', $article->slug));
            $response->assertStatus(200);
            $response->assertSee('<title>कस्टम गुगल एसईओ मेटा शीर्षक | विशेष</title>', false);
            $response->assertSee('<meta property="og:title" content="कस्टम गुगल एसईओ मेटा शीर्षक | विशेष">', false);
        } finally {
            $article->categories()->detach();
            $article->forceDelete();
        }
    }

    public function test_video_section_displays_by_default_with_fallback_articles(): void
    {
        \App\Models\Setting::set('show_video_section', true);
        \App\Models\Setting::set('youtube_api_enabled', false);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('भिडियो विशेष');
        $response->assertSee('सबै भिडियो');
        $response->assertSee('विशेष भिडियो');
    }

    public function test_video_section_can_be_hidden_via_setting(): void
    {
        \App\Models\Setting::set('show_video_section', false);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('id="video-heading"', false);

        // Reset back to true
        \App\Models\Setting::set('show_video_section', true);
    }

    public function test_youtube_api_renders_youtube_video_when_configured(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'https://www.googleapis.com/youtube/v3/videos*' => \Illuminate\Support\Facades\Http::response([
                'items' => [
                    [
                        'snippet' => [
                            'title' => 'नमुना युट्युब भिडियो शीर्षक',
                            'description' => 'यो नमुना युट्युब भिडियोको विवरण हो।',
                            'thumbnails' => [
                                'high' => ['url' => 'https://i.ytimg.com/vi/dQw4w9WgXcQ/hqdefault.jpg'],
                            ],
                            'publishedAt' => '2026-10-17T12:00:00Z',
                        ],
                    ],
                ],
            ], 200),
        ]);

        \Illuminate\Support\Facades\Cache::flush();

        \App\Models\Setting::set('show_video_section', true);
        \App\Models\Setting::set('youtube_api_enabled', true);
        \App\Models\Setting::set('youtube_api_key', 'mock_api_key');
        \App\Models\Setting::set('youtube_featured_video_url', 'https://www.youtube.com/watch?v=dQw4w9WgXcQ');

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('नमुना युट्युब भिडियो शीर्षक');
        $response->assertSee('यूट्युब');

        // Reset settings
        \App\Models\Setting::set('youtube_api_enabled', false);
        \App\Models\Setting::set('youtube_api_key', '');
        \App\Models\Setting::set('youtube_featured_video_url', '');
        \Illuminate\Support\Facades\Cache::flush();
    }

    public function test_youtube_api_gracefully_falls_back_when_api_fails(): void
    {
        \Illuminate\Support\Facades\Http::fake([
            'https://www.googleapis.com/youtube/v3/*' => \Illuminate\Support\Facades\Http::response([
                'error' => ['message' => 'Quota Exceeded'],
            ], 403),
        ]);

        \Illuminate\Support\Facades\Cache::flush();

        \App\Models\Setting::set('show_video_section', true);
        \App\Models\Setting::set('youtube_api_enabled', true);
        \App\Models\Setting::set('youtube_api_key', 'mock_failing_key');

        $response = $this->get('/');
        $response->assertStatus(200);
        // Section still renders with default database video articles
        $response->assertSee('भिडियो विशेष');

        // Reset settings
        \App\Models\Setting::set('youtube_api_enabled', false);
        \App\Models\Setting::set('youtube_api_key', '');
        \Illuminate\Support\Facades\Cache::flush();
    }

    public function test_rashifal_detail_page_loads_predictions_from_database_model(): void
    {
        $mesh = \App\Models\Rashifal::where('slug', 'mesh')->first();
        $this->assertNotNull($mesh);

        $customPrediction = 'आज मेष राशिका जातकहरूका लागि विशेष परीक्षण फलादेश सन्देश ' . uniqid();
        $mesh->update(['dainik' => $customPrediction]);

        $response = $this->get('/rashifal/mesh?tab=dainik');
        $response->assertStatus(200);
        $response->assertSee($customPrediction);
        $response->assertSee('मेष');
        $response->assertSee('अग्नि (Fire)');
    }

    public function test_rashifal_section_can_be_hidden_via_setting(): void
    {
        \App\Models\Setting::set('show_rashifal_section', false);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertDontSee('id="rashifal-heading"', false);

        // Reset back to true
        \App\Models\Setting::set('show_rashifal_section', true);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('id="rashifal-heading"', false);
    }
}


