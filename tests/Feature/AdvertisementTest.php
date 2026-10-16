<?php

namespace Tests\Feature;

use App\Models\Advertisement;
use Tests\TestCase;

class AdvertisementTest extends TestCase
{
    public function test_active_advertisements_scope_filters_correctly(): void
    {
        $activeAd = Advertisement::create([
            'title' => 'सक्रिय ब्यानर टेस्ट',
            'placement' => 'header',
            'type' => 'image',
            'image_path' => 'images/ads/ad-header.png',
            'target_url' => 'https://example.com/test-active',
            'is_active' => true,
            'starts_at' => now()->subDay(),
            'ends_at' => now()->addDay(),
        ]);

        $inactiveAd = Advertisement::create([
            'title' => 'निष्क्रिय ब्यानर टेस्ट',
            'placement' => 'header',
            'type' => 'image',
            'image_path' => 'images/ads/ad-header.png',
            'target_url' => 'https://example.com/test-inactive',
            'is_active' => false,
        ]);

        $expiredAd = Advertisement::create([
            'title' => 'म्याद सकिएको ब्यानर टेस्ट',
            'placement' => 'header',
            'type' => 'image',
            'image_path' => 'images/ads/ad-header.png',
            'target_url' => 'https://example.com/test-expired',
            'is_active' => true,
            'ends_at' => now()->subHour(),
        ]);

        $activeIds = Advertisement::active()->pluck('id')->all();

        $this->assertContains($activeAd->id, $activeIds);
        $this->assertNotContains($inactiveAd->id, $activeIds);
        $this->assertNotContains($expiredAd->id, $activeIds);

        // Cleanup
        $activeAd->delete();
        $inactiveAd->delete();
        $expiredAd->delete();
    }

    public function test_advertisement_click_increments_and_redirects(): void
    {
        $ad = Advertisement::create([
            'title' => 'क्लिक परीक्षण ब्यानर',
            'placement' => 'sidebar',
            'type' => 'image',
            'image_path' => 'images/ads/ad-sidebar.png',
            'target_url' => 'https://example.com/destination',
            'is_active' => true,
            'clicks' => 5,
        ]);

        $response = $this->get(route('ads.click', $ad));

        $response->assertRedirect('https://example.com/destination');
        $this->assertEquals(6, $ad->fresh()->clicks);

        $ad->delete();
    }

    public function test_advertisement_impression_increments(): void
    {
        $ad = Advertisement::create([
            'title' => 'इम्प्रेसन परीक्षण',
            'placement' => 'home_middle',
            'type' => 'image',
            'image_path' => 'images/ads/ad-home-middle.png',
            'target_url' => 'https://example.com',
            'is_active' => true,
            'impressions' => 10,
        ]);

        $ad->recordImpression();

        $this->assertEquals(11, $ad->fresh()->impressions);

        $ad->delete();
    }

    public function test_homepage_renders_custom_ad_banners(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('विज्ञापन', false); // Nepali text: विज्ञापन
    }

    public function test_unauthenticated_user_redirected_from_admin_advertisements(): void
    {
        $response = $this->get('/admin/advertisements');

        // Unauthenticated access should redirect to login
        $response->assertRedirect('/admin/login');
    }
}
