<x-layouts.app
    title="पासवर्ड बिर्सनुभयो? | कपिलवस्तु दर्पण"
    description="कपिलवस्तु दर्पण प्रयोगकर्ता खाताको पासवर्ड रिसेट अनुरोध फारम।"
>
    <main class="min-h-[75vh] flex items-center justify-center py-12 px-4 bg-gradient-to-b from-stone-50 via-sky-50/20 to-red-50/20 relative overflow-hidden">
        <div class="absolute -top-24 -left-24 h-96 w-96 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 h-96 w-96 rounded-full bg-red-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative w-full max-w-lg rounded-3xl bg-white shadow-2xl border border-stone-200/80 p-6 sm:p-10 text-stone-900 z-10">
            <!-- Icon Header -->
            <div class="text-center">
                <div class="mx-auto grid h-16 w-16 place-items-center rounded-2xl bg-gradient-to-br from-[#0955cf] to-[#c92137] text-white shadow-lg mb-4">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black text-stone-900 tracking-tight">
                    पासवर्ड <span class="text-[#0955cf]">बिर्सनुभयो?</span>
                </h1>
                <p class="text-stone-600 text-xs sm:text-sm mt-2 leading-relaxed">
                    चिन्ता नलिनुहोस्! आफ्नो दर्ता गरिएको इमेल ठेगाना प्रविष्ट गर्नुहोस्, हामी तपाईंको पासवर्ड रिसेट गर्न सुरक्षित लिङ्क पठाउनेछौँ।
                </p>
            </div>

            <!-- Status Alerts -->
            @if (session('status_success'))
                <div class="mt-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-xs sm:text-sm text-emerald-800 flex items-start gap-3 shadow-2xs">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-bold">{{ session('status_success') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="reset_email" class="block text-xs sm:text-sm font-bold text-stone-700 mb-1.5">
                        दर्ता गरिएको इमेल ठेगाना (Registered Email) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-stone-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                        </span>
                        <input
                            id="reset_email"
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

                <button
                    type="submit"
                    class="mt-2 flex items-center justify-center gap-2.5 w-full rounded-xl bg-gradient-to-r from-[#0955cf] to-[#c92137] hover:from-[#073c61] hover:to-[#a50404] px-6 py-3.5 text-sm sm:text-base font-black text-white shadow-md hover:shadow-lg transition-all duration-200 cursor-pointer"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    <span>पासवर्ड रिसेट लिङ्क पठाउनुहोस्</span>
                </button>
            </form>

            <div class="mt-8 pt-6 border-t border-stone-200 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-stone-600 hover:text-[#0955cf] transition">
                    <span>←</span>
                    <span>लगइन पृष्ठमा फर्कनुहोस् (Back to Login)</span>
                </a>
            </div>
        </div>
    </main>
</x-layouts.app>
