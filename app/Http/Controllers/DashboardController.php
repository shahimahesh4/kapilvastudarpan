<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    /**
     * Display the frontend user dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Paginated user comments with eager loaded article
        $comments = $user->comments()
            ->with(['article', 'replies.user'])
            ->latest()
            ->paginate(10);

        // Dashboard Metrics
        $totalComments = $user->comments()->count();
        $approvedComments = $user->comments()->where('is_approved', true)->count();
        $pendingComments = $totalComments - $approvedComments;

        // Total replies received across this user's comments
        $userCommentIds = $user->comments()->pluck('id');
        $totalRepliesReceived = Comment::whereIn('parent_id', $userCommentIds)->count();

        // Recommended / Trending articles for reading
        $recommendedArticles = Article::published()
            ->with(['categories', 'category'])
            ->latest('published_at')
            ->take(4)
            ->get();

        return view('dashboard.index', compact(
            'user',
            'comments',
            'totalComments',
            'approvedComments',
            'pendingComments',
            'totalRepliesReceived',
            'recommendedArticles'
        ));
    }

    /**
     * Update user profile information (name, phone, bio, avatar).
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'bio' => ['nullable', 'string', 'max:600'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'कृपया आफ्नो पूरा नाम प्रविष्ट गर्नुहोस्।',
            'name.max' => 'नाम २५५ अक्षरभन्दा बढी हुनुहुँदैन।',
            'avatar.image' => 'कृपया मान्य फोटो फाइल छनोट गर्नुहोस्।',
            'avatar.max' => 'फोटोको साइज २ एमबीभन्दा कम हुनुपर्छ।',
            'bio.max' => 'परिचय ६०० अक्षरभन्दा बढी हुनुहुँदैन।',
        ]);

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;
        }

        $user->update($validated);

        return back()
            ->with('status_success', 'तपाईंको प्रोफाइल विवरण सफलतापूर्वक अद्यावधिक गरियो।')
            ->with('active_tab', 'profile');
    }

    /**
     * Update user password.
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'confirmed',
            ],
        ], [
            'current_password.required' => 'कृपया हालको पासवर्ड प्रविष्ट गर्नुहोस्।',
            'current_password.current_password' => 'तपाईंले राख्नुभएको हालको पासवर्ड गलत छ।',
            'password.required' => 'कृपया नयाँ पासवर्ड प्रविष्ट गर्नुहोस्।',
            'password.confirmed' => 'नयाँ पासवर्ड पुष्टिकरण मेल खाएन।',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()
            ->with('status_success', 'तपाईंको पासवर्ड सफलतापूर्वक परिवर्तन गरियो।')
            ->with('active_tab', 'security');
    }
}
