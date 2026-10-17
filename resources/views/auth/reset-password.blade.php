<x-layouts.app
    title="नयाँ पासवर्ड सेट गर्नुहोस् | कपिलवस्तु दर्पण"
    description="कपिलवस्तु दर्पण प्रयोगकर्ता खाताको पासवर्ड रिसेट फारम।"
>
    <main class="min-h-[75vh] flex items-center justify-center py-12 px-4 bg-gradient-to-b from-stone-50 via-sky-50/20 to-red-50/20 relative overflow-hidden" x-data="{ newPassword: '', showPass: false }">
        <div class="absolute -top-24 -left-24 h-96 w-96 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 h-96 w-96 rounded-full bg-red-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative w-full max-w-lg rounded-3xl bg-white shadow-2xl border border-stone-200/80 p-6 sm:p-10 text-stone-900 z-10">
            <div class="text-center">
                <div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br from-[#0955cf] to-[#c92137] text-white shadow-lg mb-4">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-stone-900 tracking-tight">
                    नयाँ <span class="text-[#c92137]">पासवर्ड</span> सेट गर्नुहोस्
                </h1>
                <p class="text-stone-600 text-xs sm:text-sm mt-2 leading-relaxed">
                    कृपया आफ्नो खाताको लागि नयाँ र सुरक्षित पासवर्ड प्रविष्ट गर्नुहोस्।
                </p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email Address -->
                <div>
                    <label for="reset_email" class="block text-xs sm:text-sm font-bold text-stone-700 mb-1.5">
                        इमेल ठेगाना (Email Address) <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="reset_email"
                        type="email"
                        name="email"
                        value="{{ old('email', $email) }}"
                        required
                        class="w-full rounded-xl border border-stone-300 bg-stone-50 pl-4 pr-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-[#0955cf] focus:ring-3 focus:ring-blue-100 outline-none transition shadow-2xs @error('email') border-red-500 @enderror"
                    >
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror
                </div>

                <!-- New Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="reset_password" class="block text-xs sm:text-sm font-bold text-stone-700">
                            नयाँ पासवर्ड (New Password) <span class="text-red-500">*</span>
                        </label>
                        <button
                            type="button"
                            @click="showPass = !showPass"
                            class="text-xs font-semibold text-stone-500 hover:text-stone-800 cursor-pointer"
                            x-text="showPass ? 'लुकाउनुहोस्' : 'देखाउनुहोस्'"
                        ></button>
                    </div>
                    <input
                        id="reset_password"
                        :type="showPass ? 'text' : 'password'"
                        name="password"
                        x-model="newPassword"
                        required
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-[#c92137] focus:ring-3 focus:ring-red-100 outline-none transition shadow-2xs @error('password') border-red-500 @enderror"
                    >
                    @error('password')
                        <p class="mt-1 text-xs text-red-600 font-semibold">{{ $message }}</p>
                    @enderror

                    <!-- Checklist -->
                    <div class="mt-2.5 rounded-xl bg-stone-50 border border-stone-200 p-3 text-xs space-y-1">
                        <p class="font-bold text-stone-700 mb-1">पासवर्ड मापदण्ड:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1 text-[11px]">
                            <div :class="newPassword.length >= 8 ? 'text-emerald-700 font-bold' : 'text-stone-500'">
                                <span x-text="newPassword.length >= 8 ? '✓' : '•'">•</span> कम्तीमा ८ अक्षर
                            </div>
                            <div :class="(/[a-z]/.test(newPassword) && /[A-Z]/.test(newPassword)) ? 'text-emerald-700 font-bold' : 'text-stone-500'">
                                <span x-text="(/[a-z]/.test(newPassword) && /[A-Z]/.test(newPassword)) ? '✓' : '•'">•</span> ठूला र साना अक्षर
                            </div>
                            <div :class="/[0-9]/.test(newPassword) ? 'text-emerald-700 font-bold' : 'text-stone-500'">
                                <span x-text="/[0-9]/.test(newPassword) ? '✓' : '•'">•</span> कम्तीमा १ अङ्क
                            </div>
                            <div :class="/[^A-Za-z0-9]/.test(newPassword) ? 'text-emerald-700 font-bold' : 'text-stone-500'">
                                <span x-text="/[^A-Za-z0-9]/.test(newPassword) ? '✓' : '•'">•</span> कम्तीमा १ सङ्केत (@$!%*#?&)
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="reset_password_confirmation" class="block text-xs sm:text-sm font-bold text-stone-700 mb-1.5">
                        नयाँ पासवर्ड पुष्टि गर्नुहोस् (Confirm Password) <span class="text-red-500">*</span>
                    </label>
                    <input
                        id="reset_password_confirmation"
                        :type="showPass ? 'text' : 'password'"
                        name="password_confirmation"
                        required
                        placeholder="••••••••"
                        class="w-full rounded-xl border border-stone-300 bg-white px-4 py-3 text-sm text-stone-900 placeholder-stone-400 focus:border-[#c92137] focus:ring-3 focus:ring-red-100 outline-none transition shadow-2xs"
                    >
                </div>

                <button
                    type="submit"
                    class="mt-2 flex items-center justify-center gap-2.5 w-full rounded-xl bg-gradient-to-r from-[#0955cf] to-[#c92137] hover:from-[#073c61] hover:to-[#a50404] px-6 py-3.5 text-sm sm:text-base font-black text-white shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>पासवर्ड सुरक्षित गर्नुहोस् (Reset Password)</span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-stone-200 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-stone-600 hover:text-[#0955cf] transition">
                    <span>←</span>
                    <span>लगइन पृष्ठमा फर्कनुहोस्</span>
                </a>
            </div>
        </div>
    </main>
</x-layouts.app>
