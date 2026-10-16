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

        $this->assertEquals($initialViews + 1, $article->fresh()->views);
    }

    public function test_category_archive_page_renders_articles(): void
    {
        $category = Category::where('slug', 'main-news')->first() ?? Category::first();
        $this->assertNotNull($category);

        $response = $this->get(route('category.show', $category->slug));

        $response->assertStatus(200);
        $response->assertSee($category->name);
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
        $response = $this->get('/admin/login');

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
        $response->assertSee('ई-पेपर');

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
}

