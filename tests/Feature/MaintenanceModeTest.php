<?php

namespace Tests\Feature;

use App\Filament\Widgets\PortalQuickOptionsWidget;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Livewire\Livewire;
use Tests\TestCase;

class MaintenanceModeTest extends TestCase
{
    protected function tearDown(): void
    {
        // Always ensure application is brought back up after tests
        try {
            Artisan::call('up');
            if (file_exists(storage_path('framework/down'))) {
                @unlink(storage_path('framework/down'));
            }
            \Illuminate\Support\Facades\DB::table('sessions')->where('id', 'like', 'test_maint_sess_%')->delete();
            \App\Models\User::where('email', 'subscriber_maint_test@example.com')->delete();
        } catch (\Throwable $e) {}

        parent::tearDown();
    }

    public function test_maintenance_page_renders_with_custom_branding_and_message(): void
    {
        try {
            Artisan::call('down', [
                '--render' => 'errors::503',
                '--secret' => 'darpan-live-bypass',
            ]);

            $response = $this->get('/');
            $response->assertStatus(503);
            $response->assertSee('हामी अहिले वेबसाइट अद्यावधिक गर्दैछौं');
            $response->assertSee('We are currently updating our website');
            $response->assertSee('प्रणाली मर्मत तथा अद्यावधिक कार्य जारी');
            $response->assertDontSee('/stnapanel');
            $response->assertDontSee('व्यवस्थापक लगइन');
        } finally {
            Artisan::call('up');
        }
    }

    public function test_admin_panel_bypasses_maintenance_mode(): void
    {
        try {
            Artisan::call('down', [
                '--render' => 'errors::503',
                '--secret' => 'darpan-live-bypass',
            ]);

            // Public home returns 503
            $homeResponse = $this->get('/');
            $homeResponse->assertStatus(503);

            // Admin panel login returns 200 OK (accessible!)
            $adminResponse = $this->get('/stnapanel/login');
            $adminResponse->assertStatus(200);
        } finally {
            Artisan::call('up');
        }
    }

    public function test_portal_quick_options_widget_can_toggle_maintenance_mode(): void
    {
        $superAdmin = User::where('role', User::ROLE_SUPER_ADMIN)->first()
            ?? User::where('role', User::ROLE_ADMIN)->first();
        $this->assertNotNull($superAdmin);

        $this->actingAs($superAdmin);

        // Initially website is live
        $this->assertFalse(app()->isDownForMaintenance());

        // 1. Toggle DOWN
        Livewire::test(PortalQuickOptionsWidget::class)
            ->call('toggleMaintenanceMode');

        $this->assertTrue(app()->isDownForMaintenance());

        // 2. Toggle UP
        Livewire::test(PortalQuickOptionsWidget::class)
            ->call('toggleMaintenanceMode');

        $this->assertFalse(app()->isDownForMaintenance());
    }

    public function test_website_settings_page_can_enable_and_disable_maintenance_mode(): void
    {
        $superAdmin = User::where('role', User::ROLE_SUPER_ADMIN)->first()
            ?? User::where('role', User::ROLE_ADMIN)->first();
        $this->assertNotNull($superAdmin);

        $this->actingAs($superAdmin);

        // Initially ensure website is live
        $this->assertFalse(app()->isDownForMaintenance());

        // 1. Enable maintenance mode via WebsiteSettings page
        Livewire::test(\App\Filament\Pages\WebsiteSettings::class)
            ->fillForm([
                'maintenance_mode_enabled' => true,
                'maintenance_secret' => 'darpan-live-bypass',
            ])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertTrue(app()->isDownForMaintenance());

        // 2. Disable maintenance mode via WebsiteSettings page
        Livewire::test(\App\Filament\Pages\WebsiteSettings::class)
            ->fillForm([
                'maintenance_mode_enabled' => false,
            ])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertFalse(app()->isDownForMaintenance());
    }

    public function test_logged_in_admin_can_view_frontend_during_maintenance_mode(): void
    {
        try {
            Artisan::call('down', [
                '--render' => 'errors::503',
                '--secret' => 'darpan-live-bypass',
            ]);

            // 1. Guest receives 503 maintenance page
            $guestResponse = $this->get('/');
            $guestResponse->assertStatus(503);

            // 2. Non-admin logged in user (subscriber) receives 503
            $subscriber = User::where('role', User::ROLE_SUBSCRIBER)->first();
            if (! $subscriber) {
                $subscriber = User::create([
                    'name' => 'Test Subscriber',
                    'email' => 'subscriber_maint_test@example.com',
                    'password' => bcrypt('password'),
                    'role' => User::ROLE_SUBSCRIBER,
                ]);
            }
            $this->actingAs($subscriber);
            $subscriberResponse = $this->get('/');
            $subscriberResponse->assertStatus(503);

            // 3. Admin user can view frontend (200 OK) with preview banner
            $admin = User::where('role', User::ROLE_ADMIN)->first()
                ?? User::where('role', User::ROLE_SUPER_ADMIN)->first();
            $this->actingAs($admin);
            $adminResponse = $this->get('/');
            $adminResponse->assertStatus(200);
            $adminResponse->assertSee('मेन्टेनेन्स मोड सक्रिय');

            // 4. Admin via encrypted browser session cookie directly can view frontend
            $sessionId = 'test_maint_sess_' . bin2hex(random_bytes(8));
            \Illuminate\Support\Facades\DB::table('sessions')->insert([
                'id' => $sessionId,
                'user_id' => $admin->id,
                'ip_address' => '127.0.0.1',
                'user_agent' => 'TestAgent',
                'payload' => base64_encode(serialize(['login_web_test' => $admin->id])),
                'last_activity' => time(),
            ]);

            $cookieName = config('session.cookie');
            $encryptedCookie = app('encrypter')->encrypt($sessionId, false);

            $this->flushSession();
            $cookieResponse = $this->withCookie($cookieName, $sessionId)->get('/');
            $this->assertEquals(200, $cookieResponse->getStatusCode());

            \Illuminate\Support\Facades\DB::table('sessions')->where('id', $sessionId)->delete();
        } finally {
            Artisan::call('up');
        }
    }
}
