<x-layouts.app
    title="लगइन गर्नुहोस् | कपिलवस्तु दर्पण"
    description="कपिलवस्तु दर्पणको आधिकारिक अनलाइन पोर्टलमा लगइन गरी ताजा समाचार पढ्नुहोस् र आफ्ना विचार तथा प्रतिक्रियाहरू साझा गर्नुहोस्।"
>
    <main class="min-h-[80vh] flex items-center justify-center py-10 sm:py-16 px-4 bg-gradient-to-b from-stone-50 via-sky-50/30 to-red-50/20 relative overflow-hidden">
        <!-- Decorative Ambient Glow Circles -->
        <div class="absolute -top-24 -left-24 h-96 w-96 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 h-96 w-96 rounded-full bg-red-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative w-full max-w-5xl rounded-3xl bg-white shadow-2xl border border-stone-200/80 overflow-hidden grid grid-cols-1 lg:grid-cols-12">
            
            <!-- Left Branding Showcase Pane (Red & Blue Gradient) -->
            <div class="lg:col-span-5 relative bg-gradient-to-br from-[#0c295c] via-[#0955cf] to-[#c92137] p-8 sm:p-10 text-white flex flex-col justify-between overflow-hidden">
                <!-- Subtle Background Grid/Accent -->
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-white/10 via-transparent to-black/30 pointer-events-none"></div>

                <div class="relative z-10">
                    <a href="{{ route('home') }}" class="inline-block transition hover:opacity-90">
                        <img src="{{ asset('images/kapilvastu-darpan-logo.png') }}" alt="कपिलवस्तु दर्पण" class="h-14 sm:h-16 w-auto bg-white/95 rounded-xl p-2 shadow-md">
                    </a>

                    <div class="mt-8 space-y-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-black uppercase tracking-wider backdrop-blur-xs border border-white/20 text-white">
                            <span class="h-2 w-2 rounded-full bg-red-400 animate-pulse"></span>
                            डिजिटल पाठक क्लब
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight leading-tight text-white">
                            सत्य, तथ्य र निष्पक्ष पत्रकारिताको साथमा
                        </h1>
                        <p class="text-white/85 text-sm sm:text-base leading-relaxed pt-1">
                            कपिलवस्तु दर्पणमा स्वागत छ। लगइन गर्नुहोस् र समाचारमा आफ्नो विचार, टिप्पणी तथा स्वस्थ नागरिक बहस सुरु गर्नुहोस्।
                        </p>
                    </div>

                    <!-- Highlighted Features -->
                    <div class="mt-8 space-y-3.5">
                        <div class="flex items-center gap-3 text-sm">
                            <div class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-white/15 border border-white/25">
                                <svg class="h-4 w-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                            <span class="font-bold text-white/95">२४ घण्टा ताजा समाचार तथा विश्लेषण</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <div class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-white/15 border border-white/25">
                                <svg class="h-4 w-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                            </div>
                            <span class="font-bold text-white/95">मर्यादित नागरिक बहस र थ्रेडेड प्रतिक्रिया</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <div class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-white/15 border border-white/25">
                                <svg class="h-4 w-4 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </div>
                            <span class="font-bold text-white/95">व्यक्तिगत पाठक ड्यासबोर्ड तथा प्रोफाइल</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Quote -->
                <div class="relative z-10 mt-10 pt-6 border-t border-white/20 text-xs text-white/75 flex items-center justify-between">
                    <span>© २०२६ कपिलवस्तु दर्पण</span>
                    <a href="{{ route('home') }}" class="font-bold text-white hover:underline inline-flex items-center gap-1">
                        <span>गृहपृष्ठमा फर्कनुहोस्</span>
                        <span>←</span>
                    </a>
                </div>
            </div>

            <!-- Right Interactive Form Pane -->
            <div class="lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center" x-data="{ showPass: false }">
                
                <!-- Tab Pill Switcher (Red & Blue Combination) -->
                <div class="mb-8 flex items-center justify-between">
                    <div class="grid grid-cols-2 rounded-xl bg-stone-100 p-1 w-full sm:w-72 border border-stone-200/80 shadow-2xs">
                        <a href="{{ route('login') }}" class="rounded-lg py-2 text-center text-xs sm:text-sm font-black bg-white text-[#0955cf] shadow-xs transition">
                            लगइन (Log In)
                        </a>
                        <a href="{{ route('register') }}" class="rounded-lg py-2 text-center text-xs sm:text-sm font-bold text-stone-600 hover:text-[#c92137] transition">
                            दर्ता (Register)
                        </a>
                    </div>
                    <a href="{{ route('home') }}" class="hidden sm:inline-flex text-xs font-bold text-stone-500 hover:text-[#c92137] transition">
                        गृहपृष्ठ ↗
                    </a>
                </div>

                <div>
                    <h2 class="text-2xl sm:text-3xl font-black text-stone-900 tracking-tight">
                        खातामा <span class="text-[#0955cf]">लगइन</span> गर्नुहोस्
                    </h2>
                    <p class="text-stone-500 text-xs sm:text-sm mt-1.5">
                        कृपया आफ्नो दर्ता गरिएको इमेल ठेगाना र पासवर्ड प्रविष्ट गर्नुहोस्।
                    </p>
                </div>

                <!-- Status Notifications -->
                @if (session('status_success'))
                    <div class="mt-5 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm text-emerald-800 flex items-start gap-3 shadow-2xs">
                        <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div>
                            <p class="font-bold">{{ session('status_success') }}</p>
                        </div>
                    </div>
                @endif

                @if (session('status_warning'))
                    <div class="mt-5 rounded-2xl bg-amber-50 border border-amber-200 p-4 text-xs sm:text-sm text-amber-900 shadow-2xs">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <div class="flex-1">
                                <p class="font-bold">{{ session('status_warning') }}</p>
                                @if (session('unverified_email'))
                                    <form method="POST" action="{{ route('verification.send') }}" class="mt-2.5">
                                        @csrf
                                        <input type="hidden" name="email" value="{{ session('unverified_email') }}">
                                        <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg bg-[#0955cf] px-3.5 py-1.5 text-xs font-bold text-white hover:bg-blue-800 transition cursor-pointer">
                                            <span>पुनः प्रमाणीकरण लिङ्क पठाउनुहोस्</span>
                                            <span>↗</span>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="login_email" class="block text-xs sm:text-sm font-bold text-stone-700 mb-1.5">
                            इमेल ठेगाना (Email Address) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                            </span>
                            <input
                                id="login_email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                placeholder="name@example.com"
                                class="w-full rounded-xl border border-stone-300 bg-white pl-10 pr-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-[#0955cf] focus:ring-3 focus:ring-blue-100 outline-none transition shadow-2xs @error('email') border-red-500 @enderror"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="login_password" class="block text-xs sm:text-sm font-bold text-stone-700">
                                पासवर्ड (Password) <span class="text-red-500">*</span>
                            </label>
                            <a href="{{ route('password.request') }}" class="text-xs font-bold text-[#0955cf] hover:text-[#c92137] hover:underline transition">
                                पासवर्ड बिर्सनुभयो? (Forgot Password?)
                            </a>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input
                                id="login_password"
                                :type="showPass ? 'text' : 'password'"
                                name="password"
                                required
                                placeholder="••••••••"
                                class="w-full rounded-xl border border-stone-300 bg-white pl-10 pr-12 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-[#c92137] focus:ring-3 focus:ring-red-100 outline-none transition shadow-2xs @error('password') border-red-500 @enderror"
                            >
                            <button
                                type="button"
                                @click="showPass = !showPass"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-stone-400 hover:text-stone-700 cursor-pointer"
                                aria-label="पासवर्ड देखाउनुहोस्/लुकाउनुहोस्"
                            >
                                <svg x-show="!showPass" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <svg x-show="showPass" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2.5 cursor-pointer text-stone-600 text-xs sm:text-sm select-none">
                            <input type="checkbox" name="remember" class="rounded border-stone-300 text-[#0955cf] focus:ring-[#0955cf] h-4 w-4">
                            <span class="font-medium">मलाई सम्झनुहोस् (Remember Me)</span>
                        </label>
                    </div>

                    <!-- Submit Button (Red & Blue Gradient Button) -->
                    <button
                        type="submit"
                        class="mt-2 flex items-center justify-center gap-2.5 w-full rounded-xl bg-gradient-to-r from-[#0955cf] via-[#143d87] to-[#c92137] hover:from-[#073c61] hover:to-[#a50404] px-6 py-3.5 text-sm sm:text-base font-black text-white shadow-md hover:shadow-lg hover:scale-[1.01] transition-all duration-200 cursor-pointer"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        <span>लगइन गर्नुहोस् (Log In)</span>
                    </button>
                </form>

                <!-- Footer Navigation -->
                <div class="mt-8 pt-6 border-t border-stone-200/80 text-center space-y-3">
                    <p class="text-xs sm:text-sm text-stone-600">
                        खाता छैन?
                        <a href="{{ route('register') }}" class="font-black text-[#c92137] hover:underline ml-1">
                            यहाँ नयाँ खाता खोल्नुहोस् (Register) ↗
                        </a>
                    </p>

                    <div class="flex items-center justify-center gap-1.5 text-xs text-stone-400 pt-1">
                        <svg class="h-3.5 w-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span>सुरक्षित तथा इन्क्रिप्टेड प्रणाली (SSL Secured)</span>
                    </div>
                </div>
            </div>

        </div>
    </main>
</x-layouts.app>
