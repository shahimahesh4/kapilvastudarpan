<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FrontendAuthAndDashboardTest extends TestCase
{
    public function test_guest_can_view_attractive_standalone_login_page_with_forgot_password_option(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertSee('लगइन गर्नुहोस्');
        $response->assertSee('पासवर्ड बिर्सनुभयो?');
        $response->assertSee(route('password.request'));
        $response->assertSee(route('register'));
        $response->assertSee('Email Address');
        $response->assertSee('Password');
    }

    public function test_guest_can_view_attractive_standalone_register_page(): void
    {
        $response = $this->get(route('register'));

        $response->assertStatus(200);
        $response->assertSee('नयाँ खाता');
        $response->assertSee('खाता सिर्जना गर्नुहोस्');
        $response->assertSee(route('login'));
        $response->assertSee('पूरा नाम');
        $response->assertSee('सुरक्षित पासवर्डका लागि आवश्यक मापदण्ड');
    }

    public function test_guest_can_view_forgot_password_page_and_request_reset_link(): void
    {
        $user = User::factory()->create([
            'email' => 'forgot_test_' . time() . '@test.com',
            'password' => Hash::make('SecretPass123!'),
        ]);

        $pageResponse = $this->get(route('password.request'));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('पासवर्ड बिर्सनुभयो?');
        $pageResponse->assertSee(route('password.email'));

        $postResponse = $this->post(route('password.email'), [
            'email' => $user->email,
        ]);

        $postResponse->assertSessionHas('status_success');

        $user->delete();
    }

    public function test_unauthenticated_user_cannot_access_user_dashboard(): void
    {
        Auth::logout();
        $response = $this->get(route('dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_dashboard_with_stats_and_comments(): void
    {
        $user = User::factory()->create([
            'name' => 'ड्यासबोर्ड प्रयोगकर्ता',
            'email' => 'dashuser_' . time() . '@test.com',
        ]);

        $category = Category::first() ?? Category::create([
            'name' => 'समाचार',
            'slug' => 'news-test-' . time(),
        ]);

        $article = Article::create([
            'title' => 'ड्यासबोर्ड परीक्षण समाचार ' . time(),
            'slug' => 'dashboard-article-' . time(),
            'body' => 'समाचार विवरण परीक्षण',
            'published_at' => now(),
            'status' => 'published',
            'category_id' => $category->id,
        ]);

        $comment = Comment::create([
            'article_id' => $article->id,
            'user_id' => $user->id,
            'content' => 'यो मेरो ड्यासबोर्डमा देखिने टिप्पणी हो।',
            'is_approved' => true,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('ड्यासबोर्ड प्रयोगकर्ता');
        $response->assertSee('नागरिक पाठक');
        $response->assertSee('कुल प्रतिक्रियाहरू');
        $response->assertSee('प्रकाशित टिप्पणीहरू');
        $response->assertSee('व्यक्तिगत विवरण सम्पादन');
        $response->assertSee('सुरक्षा तथा पासवर्ड');
        $response->assertSee('यो मेरो ड्यासबोर्डमा देखिने टिप्पणी हो।');
        $response->assertSee($article->title);

        // Clean up
        $comment->delete();
        $article->delete();
        $user->delete();
    }

    public function test_authenticated_user_can_update_profile_and_avatar(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'पुरानो नाम',
            'email' => 'profile_test_' . time() . '@test.com',
            'phone' => null,
            'bio' => null,
        ]);

        $this->actingAs($user);

        $avatarFile = UploadedFile::fake()->image('my_avatar.jpg', 200, 200);

        $response = $this->put(route('dashboard.profile.update'), [
            'name' => 'नयाँ परिमार्जित नाम',
            'phone' => '९८००००००००',
            'bio' => 'म एक सक्रिय नागरिक तथा पाठक हुँ।',
            'avatar' => $avatarFile,
        ]);

        $response->assertSessionHas('status_success');

        $user->refresh();
        $this->assertEquals('नयाँ परिमार्जित नाम', $user->name);
        $this->assertEquals('९८००००००००', $user->phone);
        $this->assertEquals('म एक सक्रिय नागरिक तथा पाठक हुँ।', $user->bio);
        $this->assertNotNull($user->avatar);
        $this->assertNotNull($user->avatar_url);

        $user->delete();
    }

    public function test_authenticated_user_can_update_password_with_validations(): void
    {
        $oldPassword = 'OldPassword123!';
        $newPassword = 'NewSecretPassword123!';

        $user = User::factory()->create([
            'email' => 'password_test_' . time() . '@test.com',
            'password' => Hash::make($oldPassword),
        ]);

        $this->actingAs($user);

        // Wrong current password
        $failResponse = $this->put(route('dashboard.password.update'), [
            'current_password' => 'WrongPass123!',
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);
        $failResponse->assertSessionHasErrors(['current_password']);

        // Valid current password and new password
        $successResponse = $this->put(route('dashboard.password.update'), [
            'current_password' => $oldPassword,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
        ]);
        $successResponse->assertSessionHas('status_success');

        $user->refresh();
        $this->assertTrue(Hash::check($newPassword, $user->password));

        $user->delete();
    }
}
