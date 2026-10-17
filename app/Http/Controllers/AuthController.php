<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Display standalone login page.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Display standalone register page.
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Display forgot password request page.
     */
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send password reset link email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
        ], [
            'email.required' => 'कृपया आफ्नो इमेल ठेगाना प्रविष्ट गर्नुहोस्।',
            'email.email' => 'कृपया मान्य इमेल ठेगाना प्रविष्ट गर्नुहोस्।',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $status = PasswordBroker::sendResetLink($request->only('email'));

        if ($status === PasswordBroker::RESET_LINK_SENT) {
            return back()->with('status_success', 'हामीले तपाईंको इमेलमा पासवर्ड रिसेट लिङ्क पठाएका छौँ। कृपया आफ्नो इनबक्स जाँच गर्नुहोस्।');
        }

        return back()->withErrors(['email' => 'यो इमेल ठेगाना हाम्रो प्रणालीमा फेला परेन। कृपया पुनः जाँच गर्नुहोस्।'])->withInput();
    }

    /**
     * Display password reset form.
     */
    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Reset the user password.
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required'],
            'email' => ['required', 'email'],
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
            'email.required' => 'कृपया आफ्नो इमेल ठेगाना प्रविष्ट गर्नुहोस्।',
            'email.email' => 'कृपया मान्य इमेल ठेगाना प्रविष्ट गर्नुहोस्।',
            'password.required' => 'कृपया नयाँ पासवर्ड प्रविष्ट गर्नुहोस्।',
            'password.confirmed' => 'पासवर्ड पुष्टिकरण मेल खाएन।',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('email'));
        }

        $status = PasswordBroker::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === PasswordBroker::PASSWORD_RESET) {
            return redirect()->route('login')->with('status_success', 'तपाईंको पासवर्ड सफलतापूर्वक रिसेट गरिएको छ। अब नयाँ पासवर्डबाट लगइन गर्नुहोस्।');
        }

        return back()->withErrors(['email' => 'पासवर्ड रिसेट गर्न सकिएन वा लिङ्कको म्याद समाप्त भइसकेको छ। कृपया नयाँ अनुरोध गर्नुहोस्।'])->withInput();
    }

    /**
     * Handle frontend user login.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Please enter your password.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->only('email'))
                ->with('auth_tab', 'login');
        }

        $credentials = $validator->validated();
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withErrors(['email' => 'Invalid email or password. Please try again.'])
                ->with('auth_tab', 'login')
                ->withInput($request->only('email'));
        }

        // Check if email verification is enabled and required
        $verificationRequired = setting_bool('enable_email_verification', false);

        if ($verificationRequired && ! $user->hasVerifiedEmail()) {
            return back()
                ->with('status_warning', 'Email verification is required to activate your account. Click the button below to resend the verification link.')
                ->with('unverified_email', $user->email)
                ->with('auth_tab', 'login')
                ->withInput($request->only('email'));
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        $referer = $request->header('referer');
        if ($referer && (str_contains($referer, '/login') || str_contains($referer, '/register') || str_contains($referer, '/forgot-password'))) {
            return redirect()->intended(route('dashboard'))->with('status_success', 'Successfully logged in. Welcome, ' . $user->name . '!');
        }

        return back()->with('status_success', 'Successfully logged in. Welcome, ' . $user->name . '!');
    }

    /**
     * Handle frontend user registration.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
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
            'name.required' => 'Please enter your full name.',
            'name.max' => 'Full name cannot exceed 255 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'password.required' => 'Please enter a password.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except('password', 'password_confirmation'))
                ->with('auth_tab', 'register');
        }

        $validated = $validator->validated();
        $verificationRequired = setting_bool('enable_email_verification', false);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => $verificationRequired ? null : now(),
        ]);

        event(new Registered($user));

        if ($verificationRequired) {
            $user->sendEmailVerificationNotification();

            return back()
                ->with('status_warning', 'Your account has been created. A verification link has been sent to your email (' . $user->email . '). Please check your inbox.')
                ->with('unverified_email', $user->email)
                ->with('auth_tab', 'login');
        }

        // When verification is disabled (default): log user in immediately
        Auth::login($user);
        $request->session()->regenerate();

        $referer = $request->header('referer');
        if ($referer && (str_contains($referer, '/register') || str_contains($referer, '/login'))) {
            return redirect()->intended(route('dashboard'))->with('status_success', 'Your account has been registered and logged in successfully. Welcome, ' . $user->name . '!');
        }

        return back()->with('status_success', 'Your account has been registered and logged in successfully. Welcome, ' . $user->name . '!');
    }

    /**
     * Handle user logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->is('dashboard*') || str_contains($request->header('referer') ?? '', '/dashboard')) {
            return redirect()->route('home')->with('status_success', 'You have been logged out successfully.');
        }

        return back()->with('status_success', 'You have been logged out successfully.');
    }

    /**
     * Verify email via signed URL.
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Invalid or expired email verification link.');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        Auth::login($user);

        return redirect()->route('home')->with('status_success', 'Your email has been verified successfully! Your account is now fully active.');
    }

    /**
     * Resend the email verification notification.
     */
    public function resendVerification(Request $request)
    {
        $email = $request->input('email') ?? (Auth::check() ? Auth::user()->email : null);

        if (! $email) {
            return back()->with('status_warning', 'Please enter your email address.')->with('auth_tab', 'login');
        }

        $user = User::where('email', $email)->first();

        if ($user && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
            return back()
                ->with('status_success', 'A new verification link has been sent to your email. Please check your inbox.')
                ->with('unverified_email', $user->email)
                ->with('auth_tab', 'login');
        }

        return back()
            ->with('status_warning', 'This email was not found or is already verified.')
            ->with('auth_tab', 'login');
    }
}
