<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PagesTest extends TestCase
{
    public function test_static_page_renders_successfully(): void
    {
        $page = Page::withTrashed()->updateOrCreate(
            ['slug' => 'test-about-us'],
            [
                'title' => 'हाम्रो बारेमा टेस्ट पृष्ठ',
                'body' => '<p>यो कपिलवस्तु दर्पणको परीक्षण पृष्ठ हो।</p>',
                'excerpt' => 'परीक्षण संक्षेप',
                'is_published' => true,
                'published_at' => now()->subDay(),
            ]
        );
        $page->restore();

        $initialViews = $page->views;

        $response = $this->get(route('pages.show', $page->slug));

        $response->assertStatus(200);
        $response->assertSee('हाम्रो बारेमा टेस्ट पृष्ठ');
        $response->assertSee('यो कपिलवस्तु दर्पणको परीक्षण पृष्ठ हो।');
        $response->assertSee('संस्थागत पृष्ठहरू');

        $this->assertEquals($initialViews + 1, $page->fresh()->views);
        $page->forceDelete();
    }

    public function test_unpublished_page_returns_404(): void
    {
        $draftPage = Page::create([
            'title' => 'ड्राफ्ट पृष्ठ',
            'slug' => 'draft-page-' . uniqid(),
            'body' => '<p>गोप्य सामग्री</p>',
            'is_published' => false,
            'published_at' => now(),
        ]);

        try {
            $response = $this->get(route('pages.show', $draftPage->slug));
            $response->assertStatus(404);
        } finally {
            $draftPage->forceDelete();
        }
    }

    public function test_future_scheduled_page_returns_404(): void
    {
        $scheduledPage = Page::create([
            'title' => 'भविष्यको पृष्ठ',
            'slug' => 'future-page-' . uniqid(),
            'body' => '<p>भविष्यमा आउने सामग्री</p>',
            'is_published' => true,
            'published_at' => now()->addDays(5),
        ]);

        try {
            $response = $this->get(route('pages.show', $scheduledPage->slug));
            $response->assertStatus(404);
        } finally {
            $scheduledPage->forceDelete();
        }
    }

    public function test_contact_page_form_submission(): void
    {
        $response = $this->post(route('contact.submit'), [
            'name' => 'हरि पौडेल',
            'email' => 'hari@example.com',
            'phone' => '९८१२३४५६७८',
            'subject' => 'विज्ञापन सोधपुछ',
            'message' => 'नमस्ते, विज्ञापन दररेट सम्बन्धी थप जानकारी चाहियो।',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_admin_can_access_pages_resource(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@kapilvastudarpan.com'],
            ['name' => 'एडमिन', 'password' => bcrypt('password')]
        );

        $response = $this->actingAs($admin)->get('/admin/pages');
        $response->assertStatus(200);
    }

    public function test_footer_contains_pages_links(): void
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertSee(route('pages.show', 'about-us'));
        $response->assertSee(route('pages.show', 'our-team'));
        $response->assertSee(route('pages.show', 'advertise'));
        $response->assertSee(route('pages.show', 'privacy-policy'));
        $response->assertSee(route('pages.show', 'code-of-conduct'));
        $response->assertSee(route('pages.show', 'contact'));
    }

    public function test_page_publish_and_unpublish_functionality(): void
    {
        $page = Page::create([
            'title' => 'ड्राफ्ट परीक्षण पृष्ठ',
            'slug' => 'draft-test-' . uniqid(),
            'body' => '<p>सामग्री</p>',
            'is_published' => false,
        ]);

        try {
            $this->assertFalse($page->is_published);

            $page->publish();
            $this->assertTrue($page->fresh()->is_published);
            $this->assertNotNull($page->fresh()->published_at);

            $page->unpublish();
            $this->assertFalse($page->fresh()->is_published);
        } finally {
            $page->forceDelete();
        }
    }

    public function test_clean_root_page_url_and_301_redirect(): void
    {
        Page::withTrashed()->updateOrCreate(
            ['slug' => 'our-team'],
            [
                'title' => 'हाम्रो टिम',
                'body' => '<p>हाम्रो टिम विवरण</p>',
                'is_published' => true,
                'published_at' => now()->subDay(),
                'deleted_at' => null,
            ]
        );

        $response = $this->get('/our-team');
        $response->assertStatus(200);
        $response->assertSee('हाम्रो टिम');

        $redirect = $this->get('/page/our-team');
        $redirect->assertRedirect('/our-team');
        $redirect->assertStatus(301);
    }

    public function test_page_meta_title_renders_properly(): void
    {
        $page = Page::create([
            'title' => 'नियम तथा सर्तहरू',
            'slug' => 'terms-conditions-' . uniqid(),
            'body' => '<p>नियम तथा सर्त विवरण</p>',
            'meta_title' => 'नियम तथा सर्तहरू | आधिकारिक निर्देशिका',
            'is_published' => true,
            'published_at' => now(),
        ]);

        try {
            $response = $this->get('/' . $page->slug);
            $response->assertStatus(200);
            $response->assertSee('<title>नियम तथा सर्तहरू | आधिकारिक निर्देशिका</title>', false);
            $response->assertSee('<meta property="og:title" content="नियम तथा सर्तहरू | आधिकारिक निर्देशिका">', false);
        } finally {
            $page->forceDelete();
        }
    }
}

