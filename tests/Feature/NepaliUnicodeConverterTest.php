<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\NepaliUnicodeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NepaliUnicodeConverterTest extends TestCase
{
    protected NepaliUnicodeService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(NepaliUnicodeService::class);
    }

    public function test_unicode_converter_public_page_renders_successfully(): void
    {
        $response = $this->get('/unicode-converter');

        $response->assertStatus(200);
        $response->assertSee('नेपाली युनिकोड कन्भर्टर');
        $response->assertSee('रोमन ➔ युनिकोड');
        $response->assertSee('प्रीति ➔ युनिकोड');
        $response->assertSee('युनिकोड ➔ प्रीति');
        $response->assertSee('कन्भर्ट गर्नुहोस्');
    }

    public function test_legacy_unicode_route_redirects_to_converter(): void
    {
        $response = $this->get('/unicode');

        $response->assertRedirect('/unicode-converter');
    }

    public function test_converter_supports_query_string_pre_conversion(): void
    {
        $response = $this->get('/unicode-converter?mode=preeti&text=' . urlencode('g]kfn'));

        $response->assertStatus(200);
        $response->assertSee('नेपाल');
    }

    public function test_converter_service_preeti_to_unicode(): void
    {
        $this->assertEquals('नेपाल', $this->service->preetiToUnicode('g]kfn'));
        $this->assertEquals('कपिलवस्तु दर्पण', $this->service->preetiToUnicode("slknj:t' bk{0f"));
        $this->assertEquals('नमस्ते', $this->service->preetiToUnicode('gd:t]'));
        $this->assertEquals('विद्यार्थी', $this->service->preetiToUnicode('ljBfyL{'));
    }

    public function test_converter_service_unicode_to_preeti(): void
    {
        $this->assertEquals('g]kfn', $this->service->unicodeToPreeti('नेपाल'));
        $this->assertEquals('bk{0f', $this->service->unicodeToPreeti('दर्पण'));
        $this->assertEquals('gd:t]', $this->service->unicodeToPreeti('नमस्ते'));
        $this->assertEquals('ljBfyL{', $this->service->unicodeToPreeti('विद्यार्थी'));
    }

    public function test_converter_service_romanized_to_unicode(): void
    {
        $this->assertEquals('नमस्ते नेपाल', $this->service->romanizedToUnicode('namaste nepal'));
        $this->assertEquals('कपिलवस्तु दर्पण', $this->service->romanizedToUnicode('kapilvastu darpan'));
        $this->assertEquals('धन्यवाद', $this->service->romanizedToUnicode('dhanyabad'));
        $this->assertEquals('काम', $this->service->romanizedToUnicode('kaam'));
        $this->assertEquals('भात', $this->service->romanizedToUnicode('bhaat'));
        $this->assertEquals('तपाईं', $this->service->romanizedToUnicode('tapai'));
    }

    public function test_api_convert_endpoint_validates_and_converts(): void
    {
        // Romanized
        $response = $this->postJson('/api/unicode/convert', [
            'text' => 'namaste nepal',
            'mode' => 'romanized',
        ]);
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'result' => 'नमस्ते नेपाल',
                'mode' => 'romanized',
            ]);

        // Preeti
        $responsePreeti = $this->postJson('/api/unicode/convert', [
            'text' => 'g]kfn',
            'mode' => 'preeti',
        ]);
        $responsePreeti->assertStatus(200)
            ->assertJson([
                'success' => true,
                'result' => 'नेपाल',
                'mode' => 'preeti',
            ]);

        // Unicode to Preeti
        $responseUni = $this->postJson('/api/unicode/convert', [
            'text' => 'नेपाल',
            'mode' => 'unicode-to-preeti',
        ]);
        $responseUni->assertStatus(200)
            ->assertJson([
                'success' => true,
                'result' => 'g]kfn',
                'mode' => 'unicode-to-preeti',
            ]);
    }

    public function test_header_and_footer_contain_unicode_converter_links(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('/unicode-converter');
    }

    public function test_admin_can_access_filament_unicode_converter_page(): void
    {
        $admin = User::first() ?? User::factory()->create();

        $response = $this->actingAs($admin)->get('/stnapanel/nepali-unicode-converter');

        $response->assertStatus(200);
        $response->assertSee('नेपाली युनिकोड तथा प्रीति कन्भर्टर');
    }

    public function test_floating_unicode_widget_is_removed_from_frontend(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('Fixed Nepali Unicode Converter Widget', false);
        $response->assertDontSee('quick-unicode-output', false);
    }
}
