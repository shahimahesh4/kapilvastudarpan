<?php

namespace Tests\Feature;

use App\Models\PushSubscriber;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class NotificationSubscriberTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Setting::set('push_notifications_enabled', true);
        Cache::flush();
    }

    protected function tearDown(): void
    {
        PushSubscriber::query()->delete();
        Setting::set('push_notifications_enabled', true);
        Cache::flush();
        parent::tearDown();
    }

    public function test_left_bottom_notification_widget_is_present_on_homepage(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('kd-notification-widget');
        $response->assertSee('ताजा समाचारको सूचना पाउनुहोस्');
        $response->assertSee('अहिले अनुमति दिनुहोस्');
    }

    public function test_notification_widget_can_be_disabled_via_settings(): void
    {
        Setting::set('push_notifications_enabled', false);

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertDontSee('kd-notification-widget');
    }

    public function test_user_can_subscribe_to_notifications_via_api(): void
    {
        $response = $this->postJson(route('api.notifications.subscribe'), [
            'endpoint' => 'https://fcm.googleapis.com/fcm/send/fake-test-endpoint',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('push_subscribers', [
            'is_active' => true,
        ]);
    }

    public function test_subscriber_records_authenticated_user_id(): void
    {
        $user = User::first() ?? User::factory()->create();

        $response = $this->actingAs($user)->postJson(route('api.notifications.subscribe'), [
            'endpoint' => null,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('push_subscribers', [
            'user_id' => $user->id,
            'is_active' => true,
        ]);
    }

    public function test_user_can_unsubscribe_from_notifications_via_api(): void
    {
        // First subscribe
        $this->postJson(route('api.notifications.subscribe'), []);

        // Then unsubscribe
        $response = $this->postJson(route('api.notifications.unsubscribe'), []);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'is_active' => false,
        ]);
    }

    public function test_notification_test_payload_endpoint_returns_valid_structure(): void
    {
        $response = $this->getJson(route('api.notifications.test-payload'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'title',
            'body',
            'icon',
            'url',
            'tag',
        ]);
    }
}
