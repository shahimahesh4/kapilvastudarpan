<x-layouts.app
    title="नयाँ खाता दर्ता | कपिलवस्तु दर्पण"
    description="कपिलवस्तु दर्पणको आधिकारिक अनलाइन पोर्टलमा नयाँ प्रयोगकर्ता खाता खोल्नुहोस् र नागरिक बहसमा सहभागी हुनुहोस्।"
>
    <main class="min-h-[80vh] flex items-center justify-center py-10 sm:py-16 px-4 bg-gradient-to-b from-stone-50 via-red-50/20 to-sky-50/30 relative overflow-hidden">
        <!-- Decorative Ambient Glow Circles -->
        <div class="absolute -top-24 -right-24 h-96 w-96 rounded-full bg-red-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 h-96 w-96 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative w-full max-w-5xl rounded-3xl bg-white shadow-2xl border border-stone-200/80 overflow-hidden grid grid-cols-1 lg:grid-cols-12">
            
            <!-- Left Branding Showcase Pane (Red & Blue Gradient) -->
            <div class="lg:col-span-5 relative bg-gradient-to-br from-[#c92137] via-[#941122] to-[#0c295c] p-8 sm:p-10 text-white flex flex-col justify-between overflow-hidden">
                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,_var(--tw-gradient-stops))] from-white/10 via-transparent to-black/35 pointer-events-none"></div>

                <div class="relative z-10">
                    <a href="{{ route('home') }}" class="inline-block transition hover:opacity-90">
                        <img src="{{ asset('images/kapilvastu-darpan-logo.png') }}" alt="कपिलवस्तु दर्पण" class="h-14 sm:h-16 w-auto bg-white/95 rounded-xl p-2 shadow-md">
                    </a>

                    <div class="mt-8 space-y-2">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 text-xs font-black uppercase tracking-wider backdrop-blur-xs border border-white/20 text-white">
                            <span class="h-2 w-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            निःशुल्क सदस्यता
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-black tracking-tight leading-tight text-white">
                            नागरिक बहसमा सहभागी बन्नुहोस्
                        </h1>
                        <p class="text-white/85 text-sm sm:text-base leading-relaxed pt-1">
                            कपिलवस्तु दर्पण परिवारमा स्वागत छ। खाता खोलेर आफ्ना सान्दर्भिक विचार, टिप्पणी तथा स्वस्थ नागरिक बहस सुरु गर्नुहोस्।
                        </p>
                    </div>

                    <!-- Highlighted Benefits -->
                    <div class="mt-8 space-y-3.5">
                        <div class="flex items-center gap-3 text-sm">
                            <div class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-white/15 border border-white/25">
                                <svg class="h-4 w-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </div>
                            <span class="font-bold text-white/95">समाचारमा आफ्ना विचार तथा प्रतिक्रिया लेख्ने अधिकार</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <div class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-white/15 border border-white/25">
                                <svg class="h-4 w-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                            </div>
                            <span class="font-bold text-white/95">थ्रेडेड जवाफ प्रणाली र इमोजि मार्फत अन्तर्क्रिया</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <div class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-white/15 border border-white/25">
                                <svg class="h-4 w-4 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                            <span class="font-bold text-white/95">व्यक्तिगत पाठक ड्यासबोर्ड र सुरक्षित खाता</span>
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-10 pt-6 border-t border-white/20 text-xs text-white/75 flex items-center justify-between">
                    <span>© २०२६ कपिलवस्तु दर्पण</span>
                    <a href="{{ route('home') }}" class="font-bold text-white hover:underline inline-flex items-center gap-1">
                        <span>गृहपृष्ठमा फर्कनुहोस्</span>
                        <span>←</span>
                    </a>
                </div>
            </div>

            <!-- Right Registration Form Pane -->
            <div class="lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-center" x-data="{ newPassword: '', showPass: false }">
                
                <!-- Tab Pill Switcher (Red & Blue Combination) -->
                <div class="mb-8 flex items-center justify-between">
                    <div class="grid grid-cols-2 rounded-xl bg-stone-100 p-1 w-full sm:w-72 border border-stone-200/80 shadow-2xs">
                        <a href="{{ route('login') }}" class="rounded-lg py-2 text-center text-xs sm:text-sm font-bold text-stone-600 hover:text-[#0955cf] transition">
                            लगइन (Log In)
                        </a>
                        <a href="{{ route('register') }}" class="rounded-lg py-2 text-center text-xs sm:text-sm font-black bg-white text-[#c92137] shadow-xs transition">
                            दर्ता (Register)
                        </a>
                    </div>
                    <a href="{{ route('home') }}" class="hidden sm:inline-flex text-xs font-bold text-stone-500 hover:text-[#c92137] transition">
                        गृहपृष्ठ ↗
                    </a>
                </div>

                <div>
                    <h2 class="text-2xl sm:text-3xl font-black text-stone-900 tracking-tight">
                        नयाँ <span class="text-[#c92137]">खाता</span> खोल्नुहोस्
                    </h2>
                    <p class="text-stone-500 text-xs sm:text-sm mt-1.5">
                        कृपया आफ्नो विवरण भरेर कपिलवस्तु दर्पणमा निःशुल्क दर्ता हुनुहोस्।
                    </p>
                </div>

                <!-- Registration Form -->
                <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label for="reg_name" class="block text-xs sm:text-sm font-bold text-stone-700 mb-1.5">
                            पूरा नाम (Full Name) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            </span>
                            <input
                                id="reg_name"
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                placeholder="तपाईंको पूरा नाम"
                                class="w-full rounded-xl border border-stone-300 bg-white pl-10 pr-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-[#c92137] focus:ring-3 focus:ring-red-100 outline-none transition shadow-2xs @error('name') border-red-500 @enderror"
                            >
                        </div>
                        @error('name')
                            <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="reg_email" class="block text-xs sm:text-sm font-bold text-stone-700 mb-1.5">
                            इमेल ठेगाना (Email Address) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                            </span>
                            <input
                                id="reg_email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="name@example.com"
                                class="w-full rounded-xl border border-stone-300 bg-white pl-10 pr-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-[#c92137] focus:ring-3 focus:ring-red-100 outline-none transition shadow-2xs @error('email') border-red-500 @enderror"
                            >
                        </div>
                        @error('email')
                            <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password with dynamic checklist -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="reg_password" class="block text-xs sm:text-sm font-bold text-stone-700">
                                पासवर्ड (Password) <span class="text-red-500">*</span>
                            </label>
                            <button
                                type="button"
                                @click="showPass = !showPass"
                                class="text-xs font-semibold text-stone-500 hover:text-stone-800 cursor-pointer"
                                x-text="showPass ? 'पासवर्ड लुकाउनुहोस्' : 'पासवर्ड देखाउनुहोस्'"
                            ></button>
                        </div>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </span>
                            <input
                                id="reg_password"
                                :type="showPass ? 'text' : 'password'"
                                name="password"
                                x-model="newPassword"
                                required
                                placeholder="••••••••"
                                class="w-full rounded-xl border border-stone-300 bg-white pl-10 pr-10 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-[#c92137] focus:ring-3 focus:ring-red-100 outline-none transition shadow-2xs @error('password') border-red-500 @enderror"
                            >
                        </div>
                        @error('password')
                            <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                        @enderror

                        <!-- Dynamic Password Complexity Rules -->
                        <div class="mt-2.5 rounded-xl bg-stone-50 border border-stone-200 p-3 text-xs space-y-1.5">
                            <p class="font-bold text-stone-700 mb-1">सुरक्षित पासवर्डका लागि आवश्यक मापदण्ड:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-3 gap-y-1">
                                <div class="flex items-center gap-1.5 transition-colors" :class="newPassword.length >= 8 ? 'text-emerald-700 font-bold' : 'text-stone-500'">
                                    <span x-text="newPassword.length >= 8 ? '✓' : '•'">•</span>
                                    <span>कम्तीमा ८ अक्षर (At least 8 chars)</span>
                                </div>
                                <div class="flex items-center gap-1.5 transition-colors" :class="(/[a-z]/.test(newPassword) && /[A-Z]/.test(newPassword)) ? 'text-emerald-700 font-bold' : 'text-stone-500'">
                                    <span x-text="(/[a-z]/.test(newPassword) && /[A-Z]/.test(newPassword)) ? '✓' : '•'">•</span>
                                    <span>ठूला र साना दुवै अक्षर (Upper & lowercase)</span>
                                </div>
                                <div class="flex items-center gap-1.5 transition-colors" :class="/[0-9]/.test(newPassword) ? 'text-emerald-700 font-bold' : 'text-stone-500'">
                                    <span x-text="/[0-9]/.test(newPassword) ? '✓' : '•'">•</span>
                                    <span>कम्तीमा १ अङ्क (At least 1 number)</span>
                                </div>
                                <div class="flex items-center gap-1.5 transition-colors" :class="/[^A-Za-z0-9]/.test(newPassword) ? 'text-emerald-700 font-bold' : 'text-stone-500'">
                                    <span x-text="/[^A-Za-z0-9]/.test(newPassword) ? '✓' : '•'">•</span>
                                    <span>कम्तीमा १ सङ्केत चिन्ह (@$!%*#?&)</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="reg_password_confirmation" class="block text-xs sm:text-sm font-bold text-stone-700 mb-1.5">
                            पासवर्ड पुष्टि गर्नुहोस् (Confirm Password) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </span>
                            <input
                                id="reg_password_confirmation"
                                :type="showPass ? 'text' : 'password'"
                                name="password_confirmation"
                                required
                                placeholder="••••••••"
                                class="w-full rounded-xl border border-stone-300 bg-white pl-10 pr-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-[#c92137] focus:ring-3 focus:ring-red-100 outline-none transition shadow-2xs"
                            >
                        </div>
                    </div>

                    @if(setting_bool('enable_email_verification', false))
                        <div class="rounded-xl bg-amber-50 border border-amber-200 p-3 text-xs text-amber-900 flex items-start gap-2">
                            <span class="font-bold shrink-0">ℹ जानकारी:</span>
                            <span>खाता दर्तापछि तपाईंको इमेलमा प्रमाणीकरण लिङ्क पठाइनेछ।</span>
                        </div>
                    @else
                        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3 text-xs text-emerald-900 flex items-start gap-2">
                            <span class="font-bold shrink-0">✓ द्रुत दर्ता:</span>
                            <span>दर्ता हुनासाथ तपाईंको खाता तत्काल सक्रिय भई सिधै लगइन हुनेछ।</span>
                        </div>
                    @endif

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="mt-2 flex items-center justify-center gap-2.5 w-full rounded-xl bg-gradient-to-r from-[#c92137] via-[#a50404] to-[#0955cf] hover:from-[#941122] hover:to-[#073c61] px-6 py-3.5 text-sm sm:text-base font-black text-white shadow-md hover:shadow-lg hover:scale-[1.01] transition-all duration-200 cursor-pointer"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        <span>खाता सिर्जना गर्नुहोस् (Register)</span>
                    </button>
                </form>

                <!-- Footer Navigation -->
                <div class="mt-8 pt-6 border-t border-stone-200/80 text-center">
                    <p class="text-xs sm:text-sm text-stone-600">
                        पहिले नै खाता छ?
                        <a href="{{ route('login') }}" class="font-black text-[#0955cf] hover:underline ml-1">
                            यहाँ लगइन गर्नुहोस् (Log In) ↗
                        </a>
                    </p>
                </div>
            </div>

        </div>
    </main>
</x-layouts.app>
