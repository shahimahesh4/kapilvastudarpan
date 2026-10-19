<?php

namespace Tests\Feature;

use App\Filament\Pages\WebsiteSettings;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class AuthVerificationTest extends TestCase
{
    public function test_user_account_modal_displays_login_and_register_forms(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('User Account');
        $response->assertSee('Log In');
        $response->assertSee('Register');
        $response->assertSee(route('login'));
        $response->assertSee(route('register'));
        $response->assertSee('Email Address');
        $response->assertSee('Password');
        $response->assertSee('Full Name');
        $response->assertSee('At least 8 characters');
        $response->assertSee('Upper and lowercase letters');
        $response->assertSee('At least one number');
        $response->assertSee('At least one symbol');
    }

    public function test_registration_validates_password_complexity_rules(): void
    {
        Setting::set('enable_email_verification', false);

        // 1. Too short (< 8 chars)
        $resShort = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'short_' . time() . '@test.com',
            'password' => 'Aa1!',
            'password_confirmation' => 'Aa1!',
        ]);
        $resShort->assertSessionHasErrors(['password']);

        // 2. Missing uppercase letter
        $resNoUpper = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'noupper_' . time() . '@test.com',
            'password' => 'password123!',
            'password_confirmation' => 'password123!',
        ]);
        $resNoUpper->assertSessionHasErrors(['password']);

        // 3. Missing lowercase letter
        $resNoLower = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'nolower_' . time() . '@test.com',
            'password' => 'PASSWORD123!',
            'password_confirmation' => 'PASSWORD123!',
        ]);
        $resNoLower->assertSessionHasErrors(['password']);

        // 4. Missing number
        $resNoNumber = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'nonumber_' . time() . '@test.com',
            'password' => 'PasswordSpecial!',
            'password_confirmation' => 'PasswordSpecial!',
        ]);
        $resNoNumber->assertSessionHasErrors(['password']);

        // 5. Missing special character / symbol
        $resNoSymbol = $this->post(route('register'), [
            'name' => 'Test User',
            'email' => 'nosymbol_' . time() . '@test.com',
            'password' => 'Password12345',
            'password_confirmation' => 'Password12345',
        ]);
        $resNoSymbol->assertSessionHasErrors(['password']);

        // 6. Valid password with length >= 8, upper, lower, number, and symbol
        $validEmail = 'valid_' . time() . '@test.com';
        $resValid = $this->post(route('register'), [
            'name' => 'Valid User',
            'email' => $validEmail,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);
        $resValid->assertSessionHasNoErrors();
        $this->assertTrue(Auth::check());

        // Clean up
        $createdUser = User::where('email', $validEmail)->first();
        $createdUser?->delete();
    }

    public function test_user_can_register_and_is_logged_in_immediately_when_verification_is_disabled_by_default(): void
    {
        Setting::set('enable_email_verification', false);

        $testEmail = 'newuser_' . time() . '@test.com';

        $response = $this->post(route('register'), [
            'name' => 'नयाँ प्रयोगकर्ता',
            'email' => $testEmail,
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertSessionHas('status_success');
        $this->assertTrue(Auth::check());

        $user = Auth::user();
        $this->assertEquals($testEmail, $user->email);
        $this->assertNotNull($user->email_verified_at);

        // Clean up
        $user->delete();
    }

    public function test_user_can_login_and_logout(): void
    {
        Setting::set('enable_email_verification', false);

        $password = 'SecretPass123!';
        $user = User::create([
            'name' => 'लगइन परीक्षण प्रयोगकर्ता',
            'email' => 'logintest_' . time() . '@test.com',
            'password' => Hash::make($password),
            'email_verified_at' => now(),
        ]);

        Auth::logout();
        $this->assertFalse(Auth::check());

        $loginResponse = $this->post(route('login'), [
            'email' => $user->email,
            'password' => $password,
        ]);

        $loginResponse->assertSessionHas('status_success');
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());

        // Logout
        $logoutResponse = $this->post(route('logout'));
        $logoutResponse->assertSessionHas('status_success');
        $this->assertFalse(Auth::check());

        // Clean up
        $user->delete();
    }

    public function test_registration_and_login_require_email_verification_when_enabled(): void
    {
        Setting::set('enable_email_verification', true);
        Notification::fake();

        $testEmail = 'verifyuser_' . time() . '@test.com';
        $password = 'SecurePass123!';

        // 1. Register with verification enabled
        $regResponse = $this->post(route('register'), [
            'name' => 'प्रमाणीकरण आवश्यक प्रयोगकर्ता',
            'email' => $testEmail,
            'password' => $password,
            'password_confirmation' => $password,
        ]);

        $regResponse->assertSessionHas('status_warning');
        $this->assertFalse(Auth::check()); // Not logged in yet because verification is required

        $user = User::where('email', $testEmail)->first();
        $this->assertNotNull($user);
        $this->assertNull($user->email_verified_at);
        $this->assertFalse($user->hasVerifiedEmail());

        // 2. Try to login before verifying -> blocked with warning
        $loginResponse = $this->post(route('login'), [
            'email' => $testEmail,
            'password' => $password,
        ]);

        $loginResponse->assertSessionHas('status_warning');
        $this->assertFalse(Auth::check());

        // 3. Verify email via signed URL
        $verifyUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->getEmailForVerification())]
        );

        $verifyResponse = $this->get($verifyUrl);
        $verifyResponse->assertRedirect(route('home'));
        $verifyResponse->assertSessionHas('status_success');

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->assertTrue(Auth::check());

        // Clean up
        $user->delete();
        Setting::set('enable_email_verification', false);
    }

    public function test_admin_can_toggle_email_verification_setting_in_filament(): void
    {
        $admin = User::where('role', User::ROLE_SUPER_ADMIN)->first() ?? User::where('role', User::ROLE_ADMIN)->first();
        $this->actingAs($admin);

        // Turn on
        Livewire::test(WebsiteSettings::class)
            ->fillForm([
                'enable_email_verification' => true,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertTrue(Setting::getBool('enable_email_verification'));

        // Turn off (default)
        Livewire::test(WebsiteSettings::class)
            ->fillForm([
                'enable_email_verification' => false,
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertFalse(Setting::getBool('enable_email_verification'));
    }
}
