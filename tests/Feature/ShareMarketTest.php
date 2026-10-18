<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Setting;
use App\Models\User;
use App\Services\NepseService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShareMarketTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    protected function tearDown(): void
    {
        Setting::set('sharemarket_ticker_enabled', true);
        Setting::set('sharemarket_enabled', true);
        Setting::set('sharehub_api_email', null);
        Setting::set('sharehub_api_password', null);
        Cache::flush();
        parent::tearDown();
    }

    public function test_sharemarket_public_page_renders_successfully(): void
    {
        $response = $this->get(route('sharemarket.index'));

        $response->assertStatus(200);
        $response->assertSee('नेपाल शेयर बजार');
        $response->assertSee('NEPSE');
        $response->assertSee('उप-परिसूचकहरू');
    }

    public function test_nepse_legacy_route_redirects_to_sharemarket(): void
    {
        $response = $this->get('/nepse');

        $response->assertRedirect(route('sharemarket.index'));
    }

    public function test_sharemarket_api_live_returns_json_market_data(): void
    {
        $response = $this->getJson(route('api.nepse.live'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                'market_status',
                'market_status_text',
                'total_turnover',
                'indices',
                'sub_indices',
                'top_gainers',
                'top_losers',
                'top_turnover',
                'breadth',
            ],
            'ticker',
            'timestamp',
        ]);
    }

    public function test_sharemarket_api_ticker_returns_json_ticker_items(): void
    {
        $response = $this->getJson(route('api.nepse.ticker'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'ticker' => [
                '*' => [
                    'symbol',
                    'name',
                    'ltp',
                    'change',
                    'per_change',
                    'is_positive',
                ],
            ],
        ]);
    }

    public function test_nepse_service_fallback_provides_complete_data(): void
    {
        $service = app(NepseService::class);
        $data = $service->getMarketData();

        $this->assertNotEmpty($data['indices']);
        $this->assertNotEmpty($data['sub_indices']);
        $this->assertNotEmpty($data['top_gainers']);
        $this->assertNotEmpty($data['top_losers']);
        $this->assertNotEmpty($data['top_turnover']);
        $this->assertArrayHasKey('advances', $data['breadth']);
        $this->assertArrayHasKey('declines', $data['breadth']);
    }

    public function test_nepse_service_fetches_and_normalizes_sharehub_live_data(): void
    {
        Setting::set('sharehub_api_email', 'trader@example.com');
        Setting::set('sharehub_api_password', 'secret123');

        Http::fake([
            NepseService::SHAREHUB_AUTH_URL => Http::response([
                'status' => 200,
                'data' => [
                    'accessToken' => 'fake-bearer-token-12345',
                ],
            ], 200),
            NepseService::SHAREHUB_MARKET_URL => Http::response([
                'status' => 200,
                'data' => [
                    'marketStatus' => 'OPEN',
                    'totalTurnover' => 6500000000,
                    'totalShares' => 15000000,
                    'totalTransactions' => 85000,
                    'totalScrips' => 320,
                    'indices' => [
                        [
                            'name' => 'NEPSE Index',
                            'symbol' => 'NEPSE',
                            'current' => 2800.50,
                            'change' => 35.20,
                            'perChange' => 1.27,
                        ],
                    ],
                    'subIndices' => [
                        [
                            'name' => 'Banking',
                            'symbol' => 'BANK',
                            'current' => 1520.00,
                            'change' => 15.00,
                            'perChange' => 1.00,
                            'turnover' => 900000000,
                        ],
                    ],
                    'topGainers' => [
                        [
                            'symbol' => 'NABIL',
                            'name' => 'Nabil Bank',
                            'ltp' => 650.0,
                            'change' => 45.0,
                            'perChange' => 7.43,
                        ],
                    ],
                    'breadth' => [
                        'advances' => 180,
                        'declines' => 50,
                        'unchanged' => 10,
                    ],
                ],
            ], 200),
        ]);

        $service = app(NepseService::class);
        $service->clearCache();
        $data = $service->getMarketData();

        $this->assertTrue($data['is_live']);
        $this->assertEquals('OPEN', $data['market_status']);
        $this->assertEquals('कारोबार खुला', $data['market_status_text']);
        $this->assertEquals(2800.50, $data['indices'][0]['current']);
    }

    public function test_header_and_footer_contain_sharemarket_links(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSee(route('sharemarket.index'));
        $response->assertSee('शेयर बजार');
    }

    public function test_ticker_can_be_disabled_via_settings(): void
    {
        Setting::set('sharemarket_ticker_enabled', false);

        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertDontSee('ticker-track');
    }

    public function test_sharemarket_feature_can_be_disabled_globally(): void
    {
        Setting::set('sharemarket_enabled', false);

        // Web pages return 404
        $this->get(route('sharemarket.index'))->assertStatus(404);
        $this->get(route('sharemarket.ticker', 'RADHI'))->assertStatus(404);
        $this->get('/markets/ticker/RADHI')->assertStatus(404);
        $this->get('/nepse')->assertStatus(404);

        // APIs return 404 with disabled status
        $apiLive = $this->getJson(route('api.nepse.live'));
        $apiLive->assertStatus(404);
        $apiLive->assertJson(['status' => 'disabled']);

        $apiTicker = $this->getJson(route('api.nepse.ticker'));
        $apiTicker->assertStatus(404);
        $apiTicker->assertJson(['status' => 'disabled']);

        $apiDetail = $this->getJson(route('api.nepse.ticker.show', 'RADHI'));
        $apiDetail->assertStatus(404);
        $apiDetail->assertJson(['status' => 'disabled']);

        // Home page should hide header ticker and nav links
        $home = $this->get(route('home'));
        $home->assertStatus(200);
        $home->assertDontSee('ticker-track');
        $home->assertDontSee(route('sharemarket.index'));
    }

    public function test_sharemarket_pages_work_when_only_ticker_is_disabled(): void
    {
        Setting::set('sharemarket_enabled', true);
        Setting::set('sharemarket_ticker_enabled', false);

        // Pages still accessible
        $this->get(route('sharemarket.index'))->assertStatus(200);
        $this->get(route('sharemarket.ticker', 'RADHI'))->assertStatus(200);

        // Header link still present, but ticker strip hidden
        $home = $this->get(route('home'));
        $home->assertStatus(200);
        $home->assertSee(route('sharemarket.index'));
        $home->assertDontSee('ticker-track');
    }

    public function test_portal_quick_options_widget_can_toggle_sharemarket(): void
    {
        Setting::set('sharemarket_enabled', true);
        Setting::set('sharemarket_ticker_enabled', true);

        $widget = new \App\Filament\Widgets\PortalQuickOptionsWidget();

        // Toggle Share Market Off
        $widget->toggleShareMarket();
        $this->assertFalse(setting_bool('sharemarket_enabled', true));

        // Toggle Share Market On
        $widget->toggleShareMarket();
        $this->assertTrue(setting_bool('sharemarket_enabled', false));

        // Toggle Ticker Off
        $widget->toggleShareMarketTicker();
        $this->assertFalse(setting_bool('sharemarket_ticker_enabled', true));

        // Toggle Ticker On
        $widget->toggleShareMarketTicker();
        $this->assertTrue(setting_bool('sharemarket_ticker_enabled', false));
    }

    public function test_ticker_detail_page_renders_successfully_for_radhi(): void
    {
        $response = $this->get(route('sharemarket.ticker', 'RADHI'));

        $response->assertStatus(200);
        $response->assertSee('RADHI');
        $response->assertSee('Radhi Bidyut Company');
        $response->assertSee('711.00');
        $response->assertSee('Hydro Power');
        $response->assertSee('Quick View');
        $response->assertSee('Technical Indicators');
        $response->assertSee('Active Brokers');
        $response->assertSee('Historical Data');
        $response->assertSee('Financials');
        $response->assertSee('Price Movement');
        $response->assertSee('Dividend History');
        $response->assertSee('KeyStats');
        $response->assertSee('MF Ownership');
        $response->assertSee('Announcement');
        $response->assertSee('FAQs');
        $response->assertSee('About');
    }

    public function test_onlinekhabar_style_markets_ticker_alias_renders_successfully(): void
    {
        $response = $this->get('/markets/ticker/RADHI');

        $response->assertStatus(200);
        $response->assertSee('RADHI');
        $response->assertSee('Radhi Bidyut Company');
    }

    public function test_ticker_detail_handles_arbitrary_nepse_symbol(): void
    {
        $response = $this->get(route('sharemarket.ticker', 'NABIL'));

        $response->assertStatus(200);
        $response->assertSee('NABIL');
        $response->assertSee('Nabil Bank Limited');

        $responseMkjc = $this->get(route('sharemarket.ticker', 'MKJC'));
        $responseMkjc->assertStatus(200);
        $responseMkjc->assertSee('MKJC');
        $responseMkjc->assertSee('305.60');
        $responseMkjc->assertSee('-1.23%');
        $responseMkjc->assertSee('26-10-16 15-00-00');

        $responseCustom = $this->get(route('sharemarket.ticker', 'CUSTOMXYZ'));
        $responseCustom->assertStatus(200);
        $responseCustom->assertSee('CUSTOMXYZ');
    }

    public function test_ticker_detail_api_endpoint_returns_json_structure(): void
    {
        $response = $this->getJson(route('api.nepse.ticker.show', 'RADHI'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'symbol',
            'data' => [
                'symbol',
                'name',
                'sector',
                'ltp',
                'change',
                'per_change',
                'quick_view',
                'technical_indicators',
                'active_brokers',
                'historical_trades',
                'financials',
                'announcements',
                'chart_series',
            ],
            'timestamp',
        ]);
        $response->assertJson([
            'status' => 'success',
            'symbol' => 'RADHI',
        ]);
    }
}
