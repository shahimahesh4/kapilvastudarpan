<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\PushSubscriber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NotificationSubscriptionController extends Controller
{
    /**
     * Store or update a push notification subscriber.
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => ['required', 'url', 'max:2048'],
        ]);

        $userAgent = (string) $request->userAgent();
        $isMobile = Str::contains(strtolower($userAgent), ['mobile', 'android', 'iphone', 'ipad']);
        $deviceType = $isMobile ? 'mobile' : 'desktop';
        $ip = $request->ip();

        $subscriber = PushSubscriber::updateOrCreate(
            [
                'ip_address' => $ip,
                'user_agent' => Str::limit($userAgent, 500, ''),
            ],
            [
                'user_id' => auth()->id(),
                'device_type' => $deviceType,
                'endpoint' => $validated['endpoint'],
                'is_active' => true,
                'subscribed_at' => now(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'message' => 'कपिलवस्तु दर्पणको ताजा समाचार नोटिफिकेसन सदस्यता सफलतापूर्वक सक्रिय भयो!',
            'subscriber_id' => $subscriber->id,
            'is_active' => true,
        ]);
    }

    /**
     * Unsubscribe or deactivate push notification.
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $ip = $request->ip();
        $userAgent = (string) $request->userAgent();

        PushSubscriber::where('ip_address', $ip)
            ->where('user_agent', Str::limit($userAgent, 500, ''))
            ->update(['is_active' => false]);

        return response()->json([
            'status' => 'success',
            'message' => 'नोटिफिकेसन सदस्यता सफलतापूर्वक बन्द गरियो।',
            'is_active' => false,
        ]);
    }

    /**
     * Get test notification payload (latest breaking news or welcome).
     */
    public function testPayload(): JsonResponse
    {
        $latest = Article::published()->latest('published_at')->first();

        return response()->json([
            'title' => $latest ? 'कपिलवस्तु दर्पण - ताजा समाचार' : 'कपिलवस्तु दर्पण डिजिटल न्यूज',
            'body' => $latest ? $latest->title : 'तपाईंले कपिलवस्तु दर्पणको ताजा तथा ब्रेकिङ समाचारहरूको सूचना सक्रिय गर्नुभएको छ।',
            'icon' => asset('images/kapilvastu-darpan-logo.jpg'),
            'url' => $latest ? route('articles.show', $latest->slug) : route('home'),
            'tag' => 'kapilvastu-news-' . ($latest ? $latest->id : 'welcome'),
        ]);
    }
}
