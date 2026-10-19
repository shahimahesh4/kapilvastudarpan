<x-layouts.app
    title="मेरो ड्यासबोर्ड | कपिलवस्तु दर्पण"
    description="कपिलवस्तु दर्पणको आधिकारिक अनलाइन पोर्टलमा प्रयोगकर्ता ड्यासबोर्ड, व्यक्तिगत विवरण तथा प्रतिक्रिया व्यवस्थापन।"
>
    <main class="min-h-screen py-8 sm:py-12 px-4 sm:px-6 lg:px-8 bg-stone-50" x-data="{
        activeTab: '{{ session('active_tab', request('tab', 'comments')) }}',
        newPassword: '',
        showCurrentPass: false,
        showNewPass: false,
        avatarPreview: '{{ $user->avatar_url }}',
        previewImage(e) {
            const file = e.target.files[0];
            if (file) {
                this.avatarPreview = URL.createObjectURL(file);
            }
        }
    }">
        <div class="mx-auto max-w-6xl space-y-6">

            <!-- Global Status Alerts -->
            @if (session('status_success'))
                <div class="rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-800 flex items-start gap-3 shadow-2xs">
                    <svg class="h-5 w-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div>
                        <p class="font-bold">{{ session('status_success') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-800 shadow-2xs">
                    <p class="font-black mb-1 flex items-center gap-1.5">
                        <svg class="h-4 w-4 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        <span>कृपया फारमका निम्न त्रुटिहरू सच्याउनुहोस्:</span>
                    </p>
                    <ul class="list-disc pl-5 space-y-0.5 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 1. Hero Profile Header Card (Red & Blue Gradient Theme) -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-[#0c295c] via-[#0955cf] to-[#c92137] p-6 sm:p-8 lg:p-10 text-white shadow-xl">
                <!-- Background ambient decorative blur -->
                <div class="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-white/10 blur-2xl pointer-events-none"></div>
                <div class="absolute -left-16 -bottom-16 h-64 w-64 rounded-full bg-black/20 blur-2xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row items-center md:items-start justify-between gap-6 text-center md:text-left">
                    
                    <!-- Avatar & User Info -->
                    <div class="flex flex-col sm:flex-row items-center gap-5 sm:gap-6">
                        <!-- Avatar Display -->
                        <div class="relative">
                            <template x-if="avatarPreview">
                                <img :src="avatarPreview" alt="{{ $user->name }}" class="h-24 w-24 sm:h-28 sm:w-28 rounded-full object-cover ring-4 ring-white/90 shadow-lg">
                            </template>
                            <template x-if="!avatarPreview">
                                <div class="grid h-24 w-24 sm:h-28 sm:w-28 place-items-center rounded-full bg-white text-[#0955cf] font-black text-3xl sm:text-4xl shadow-lg ring-4 ring-white/90 uppercase">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                            </template>
                            <span class="absolute bottom-1 right-1 grid h-7 w-7 place-items-center rounded-full bg-emerald-500 text-white ring-2 ring-white shadow-xs" title="अनलाइन">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </span>
                        </div>

                        <!-- Details -->
                        <div class="space-y-1.5">
                            <div class="flex flex-wrap items-center justify-center md:justify-start gap-2">
                                <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                                    {{ $user->name }}
                                </h1>
                                <span class="rounded-full bg-white/20 px-3 py-0.5 text-xs font-bold text-white backdrop-blur-xs border border-white/20">
                                    नागरिक पाठक
                                </span>
                                @if($user->hasVerifiedEmail() || !setting_bool('enable_email_verification', false))
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-500/90 px-2.5 py-0.5 text-[11px] font-black text-white shadow-2xs">
                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        <span>सत्यापित</span>
                                    </span>
                                @endif
                            </div>

                            <p class="text-white/85 text-sm font-medium flex items-center justify-center md:justify-start gap-2">
                                <span>{{ $user->email }}</span>
                                @if($user->phone)
                                    <span>•</span>
                                    <span>{{ $user->phone }}</span>
                                @endif
                            </p>

                            @if($user->bio)
                                <p class="text-white/90 text-xs sm:text-sm max-w-xl italic leading-relaxed pt-1">
                                    "{{ $user->bio }}"
                                </p>
                            @endif

                            <div class="pt-2 flex flex-wrap items-center justify-center md:justify-start gap-3 text-xs text-white/75 font-semibold">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="h-3.5 w-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span>सदस्यता: {{ $user->nepali_joined_date }}</span>
                                </span>
                                <span>•</span>
                                <span>{{ $user->created_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Action Buttons -->
                    <div class="flex items-center gap-2.5 shrink-0">
                        <button
                            type="button"
                            @click="activeTab = 'profile'"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-white text-[#0955cf] hover:bg-stone-100 px-4 py-2.5 text-xs sm:text-sm font-black shadow-md transition hover:scale-105 cursor-pointer"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            <span>प्रोफाइल सम्पादन</span>
                        </button>

                        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('के तपाईं साँच्चै लगआउट गर्न चाहनुहुन्छ?');">
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-white/15 hover:bg-red-600/90 text-white border border-white/25 px-4 py-2.5 text-xs sm:text-sm font-bold backdrop-blur-xs transition hover:scale-105 cursor-pointer"
                                title="लगआउट गर्नुहोस्"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                <span>लगआउट</span>
                            </button>
                        </form>
                    </div>

                </div>
            </div>

            <!-- 2. Interactive Analytics / Metrics Cards (Red & Blue Combinations) -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5">
                <!-- Total Comments -->
                <div class="rounded-2xl border border-stone-200/90 bg-white p-5 shadow-2xs hover:shadow-md transition group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm font-bold text-stone-500">कुल प्रतिक्रियाहरू</span>
                        <div class="grid h-10 w-10 place-items-center rounded-xl bg-blue-50 text-[#0955cf] group-hover:scale-110 transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl sm:text-3xl font-black text-stone-900 leading-none">
                            {{ nepali_number((string)$totalComments) }}
                        </span>
                        <span class="text-xs font-semibold text-stone-400 block mt-1">तपाईंले गरेका टिप्पणीहरू</span>
                    </div>
                </div>

                <!-- Approved Comments -->
                <div class="rounded-2xl border border-stone-200/90 bg-white p-5 shadow-2xs hover:shadow-md transition group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm font-bold text-stone-500">प्रकाशित टिप्पणीहरू</span>
                        <div class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-50 text-emerald-600 group-hover:scale-110 transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl sm:text-3xl font-black text-emerald-600 leading-none">
                            {{ nepali_number((string)$approvedComments) }}
                        </span>
                        <span class="text-xs font-semibold text-stone-400 block mt-1">स्वीकृत भई देखिँदै</span>
                    </div>
                </div>

                <!-- Replies Received -->
                <div class="rounded-2xl border border-stone-200/90 bg-white p-5 shadow-2xs hover:shadow-md transition group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm font-bold text-stone-500">प्राप्त जवाफहरू</span>
                        <div class="grid h-10 w-10 place-items-center rounded-xl bg-red-50 text-[#c92137] group-hover:scale-110 transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl sm:text-3xl font-black text-[#c92137] leading-none">
                            {{ nepali_number((string)$totalRepliesReceived) }}
                        </span>
                        <span class="text-xs font-semibold text-stone-400 block mt-1">अन्य पाठकका प्रत्युत्तर</span>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="rounded-2xl border border-stone-200/90 bg-white p-5 shadow-2xs hover:shadow-md transition group">
                    <div class="flex items-center justify-between">
                        <span class="text-xs sm:text-sm font-bold text-stone-500">खाता स्थिति</span>
                        <div class="grid h-10 w-10 place-items-center rounded-xl bg-amber-50 text-amber-600 group-hover:scale-110 transition">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="text-2xl sm:text-3xl font-black text-stone-900 leading-none">
                            सक्रिय
                        </span>
                        <span class="text-xs font-semibold text-emerald-600 block mt-1">सुरक्षित पाठक खाता</span>
                    </div>
                </div>
            </div>

            <!-- 3. Tabbed Navigation Pill Bar -->
            <div class="flex items-center gap-2 overflow-x-auto no-scrollbar border-b border-stone-200/90 pb-2">
                <button
                    type="button"
                    @click="activeTab = 'comments'"
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-xs sm:text-sm font-black transition cursor-pointer whitespace-nowrap shadow-2xs"
                    :class="activeTab === 'comments' ? 'bg-[#0955cf] text-white shadow-xs' : 'bg-white text-stone-700 hover:bg-stone-100 hover:text-[#0955cf] border border-stone-200'"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    <span>मेरा प्रतिक्रियाहरू ({{ nepali_number((string)$totalComments) }})</span>
                </button>

                <button
                    type="button"
                    @click="activeTab = 'profile'"
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-xs sm:text-sm font-black transition cursor-pointer whitespace-nowrap shadow-2xs"
                    :class="activeTab === 'profile' ? 'bg-[#c92137] text-white shadow-xs' : 'bg-white text-stone-700 hover:bg-stone-100 hover:text-[#c92137] border border-stone-200'"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    <span>व्यक्तिगत विवरण सम्पादन</span>
                </button>

                <button
                    type="button"
                    @click="activeTab = 'security'"
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-xs sm:text-sm font-black transition cursor-pointer whitespace-nowrap shadow-2xs"
                    :class="activeTab === 'security' ? 'bg-stone-900 text-white shadow-xs' : 'bg-white text-stone-700 hover:bg-stone-100 hover:text-stone-900 border border-stone-200'"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    <span>सुरक्षा तथा पासवर्ड</span>
                </button>

                <button
                    type="button"
                    @click="activeTab = 'recommended'"
                    class="inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-xs sm:text-sm font-black transition cursor-pointer whitespace-nowrap shadow-2xs"
                    :class="activeTab === 'recommended' ? 'bg-gradient-to-r from-[#0955cf] to-[#c92137] text-white shadow-xs' : 'bg-white text-stone-700 hover:bg-stone-100 hover:text-[#c92137] border border-stone-200'"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    <span>ताजा समाचारहरू</span>
                </button>
            </div>

            <!-- TAB 1: MY COMMENTS -->
            <div x-show="activeTab === 'comments'" x-cloak class="space-y-4">
                <div class="flex items-center justify-between pb-1">
                    <h3 class="text-lg font-black text-stone-900 flex items-center gap-2">
                        <span>💬 तपाईंले गर्नुभएका प्रतिक्रियाहरू</span>
                        <span class="rounded-full bg-blue-100 text-[#0955cf] px-2.5 py-0.5 text-xs font-black">
                            {{ nepali_number((string)$comments->total()) }}
                        </span>
                    </h3>
                    <a href="{{ route('home') }}" wire:navigate.hover class="text-xs font-bold text-[#0955cf] hover:text-[#c92137] hover:underline transition">
                        थप समाचार पढ्नुहोस् ↗
                    </a>
                </div>

                @if($comments->isEmpty())
                    <!-- Empty State -->
                    <div class="rounded-3xl border-2 border-dashed border-stone-200 bg-white p-10 text-center shadow-2xs">
                        <div class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-blue-50 text-[#0955cf] mb-3">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h4 class="text-lg font-black text-stone-800">अहिलेसम्म कुनै प्रतिक्रिया छैन</h4>
                        <p class="text-stone-500 text-xs sm:text-sm mt-1 max-w-md mx-auto">
                            तपाईंले समाचारहरूमा आफ्ना विचार, सल्लाह तथा नागरिक प्रतिक्रिया लेख्न सुरु गर्नुभएको छैन।
                        </p>
                        <div class="mt-5">
                            <a href="{{ route('home') }}" wire:navigate.hover class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#0955cf] to-[#c92137] px-5 py-2.5 text-xs sm:text-sm font-black text-white shadow-xs hover:opacity-95 transition">
                                <span>ताजा समाचार पढ्न जानुहोस्</span>
                                <span>→</span>
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Comments Stream List -->
                    <div class="space-y-3.5">
                        @foreach($comments as $comment)
                            <div class="rounded-2xl border border-stone-200/90 bg-white p-5 sm:p-6 shadow-2xs hover:border-blue-200 hover:shadow-xs transition">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-stone-100 pb-3">
                                    <div class="min-w-0 flex-1">
                                        <span class="text-[11px] font-bold text-stone-400 uppercase tracking-wider block">समाचार शीर्षक:</span>
                                        @if($comment->article)
                                            <a href="{{ route('articles.show', $comment->article->slug) }}" wire:navigate.hover class="text-sm sm:text-base font-black text-stone-900 hover:text-[#c92137] transition line-clamp-1">
                                                {{ $comment->article->title }} ↗
                                            </a>
                                        @else
                                            <span class="text-sm font-bold text-stone-500">[मेटाएको समाचार]</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        @if($comment->is_approved)
                                            <span class="rounded-full bg-emerald-50 border border-emerald-200 px-2.5 py-0.5 text-[11px] font-black text-emerald-800">
                                                ✓ प्रकाशित (Approved)
                                            </span>
                                        @else
                                            <span class="rounded-full bg-amber-50 border border-amber-200 px-2.5 py-0.5 text-[11px] font-black text-amber-800">
                                                ⏳ समीक्षामा (Pending)
                                            </span>
                                        @endif
                                        <time class="text-[11px] font-semibold text-stone-400">
                                            {{ $comment->nepali_time }}
                                        </time>
                                    </div>
                                </div>

                                <!-- Comment Body -->
                                <div class="mt-3.5 pl-3 border-l-2 border-blue-200 bg-stone-50/60 p-3 rounded-r-xl">
                                    <p class="text-xs sm:text-sm text-stone-800 leading-relaxed font-normal">
                                        {!! nl2br(e($comment->content)) !!}
                                    </p>
                                </div>

                                <!-- Footer actions -->
                                <div class="mt-3.5 flex items-center justify-between pt-2 text-xs">
                                    <div>
                                        @if($comment->replies && $comment->replies->count() > 0)
                                            <span class="inline-flex items-center gap-1 font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200/60 text-[11px]">
                                                <span>↩</span>
                                                <span>{{ nepali_number((string)$comment->replies->count()) }} जवाफ प्राप्त</span>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex items-center gap-3">
                                        @if($comment->article)
                                            <a href="{{ route('articles.show', $comment->article->slug) }}#comments" wire:navigate.hover class="font-bold text-[#0955cf] hover:underline">
                                                समाचारमा हेर्नुहोस् ↗
                                            </a>
                                        @endif

                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('के तपाईं साँच्चै यो प्रतिक्रिया मेटाउन चाहनुहुन्छ?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-bold text-stone-400 hover:text-red-600 transition cursor-pointer" title="प्रतिक्रिया मेटाउनुहोस्">
                                                मेटाउनुहोस्
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pt-4">
                        {{ $comments->links() }}
                    </div>
                @endif
            </div>

            <!-- TAB 2: EDIT PROFILE -->
            <div x-show="activeTab === 'profile'" x-cloak class="rounded-3xl border border-stone-200/90 bg-white p-6 sm:p-10 shadow-2xs">
                <div class="border-b border-stone-200 pb-4 mb-6">
                    <h3 class="text-xl font-black text-stone-900">
                        👤 व्यक्तिगत विवरण सम्पादन
                    </h3>
                    <p class="text-stone-500 text-xs sm:text-sm mt-1">
                        आफ्नो प्रोफाइल फोटो, नाम, फोन र परिचय विवरण अद्यावधिक गर्नुहोस्।
                    </p>
                </div>

                <form method="POST" action="{{ route('dashboard.profile.update') }}" enctype="multipart/form-data" class="space-y-5 max-w-2xl">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Upload -->
                    <div>
                        <label class="block text-xs sm:text-sm font-bold text-stone-700 mb-2">
                            प्रोफाइल फोटो (Profile Avatar)
                        </label>
                        <div class="flex items-center gap-4">
                            <template x-if="avatarPreview">
                                <img :src="avatarPreview" alt="Avatar" class="h-16 w-16 rounded-full object-cover ring-2 ring-[#0955cf]/40 shadow-xs">
                            </template>
                            <template x-if="!avatarPreview">
                                <div class="grid h-16 w-16 place-items-center rounded-full bg-stone-100 text-stone-500 font-black text-xl uppercase ring-1 ring-stone-300">
                                    {{ mb_substr($user->name, 0, 1) }}
                                </div>
                            </template>
                            <div>
                                <input
                                    type="file"
                                    name="avatar"
                                    accept="image/*"
                                    @change="previewImage($event)"
                                    class="text-xs text-stone-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-[#0955cf] hover:file:bg-blue-100 cursor-pointer"
                                >
                                <p class="text-[11px] text-stone-400 mt-1">अधिकतम २ एमबी (JPEG, PNG, WEBP)</p>
                            </div>
                        </div>
                    </div>

                    <!-- Full Name -->
                    <div>
                        <label for="prof_name" class="block text-xs sm:text-sm font-bold text-stone-700 mb-1.5">
                            पूरा नाम (Full Name) <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="prof_name"
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-900 placeholder-stone-400 focus:border-[#c92137] focus:ring-3 focus:ring-red-100 outline-none transition shadow-2xs"
                        >
                    </div>

                    <!-- Email (Read Only Notice) -->
                    <div>
                        <label for="prof_email" class="block text-xs sm:text-sm font-bold text-stone-700 mb-1.5">
                            इमेल ठेगाना (Email Address)
                        </label>
                        <input
                            id="prof_email"
                            type="email"
                            value="{{ $user->email }}"
                            disabled
                            class="w-full rounded-xl border border-stone-200 bg-stone-100 px-4 py-2.5 text-sm text-stone-500 cursor-not-allowed"
                        >
                        <p class="text-[11px] text-stone-400 mt-1">इमेल सुरक्षा प्रयोजनका लागि खाता पहिचानको रूपमा सुरक्षित राखिएको छ।</p>
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="prof_phone" class="block text-xs sm:text-sm font-bold text-stone-700 mb-1.5">
                            सम्पर्क नम्बर (Phone Number)
                        </label>
                        <input
                            id="prof_phone"
                            type="text"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            placeholder="९८xxxxxxxx / +९७७-xxxxxxxxx"
                            class="w-full rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-900 placeholder-stone-400 focus:border-[#0955cf] focus:ring-3 focus:ring-blue-100 outline-none transition shadow-2xs"
                        >
                    </div>

                    <!-- Bio -->
                    <div>
                        <label for="prof_bio" class="block text-xs sm:text-sm font-bold text-stone-700 mb-1.5">
                            संक्षिप्त परिचय (Bio)
                        </label>
                        <textarea
                            id="prof_bio"
                            name="bio"
                            rows="3"
                            maxlength="600"
                            placeholder="तपाईंको बारेमा केही शब्दहरू लेख्नुहोस्..."
                            class="w-full rounded-xl border border-stone-300 bg-white p-3.5 text-sm text-stone-900 placeholder-stone-400 focus:border-[#0955cf] focus:ring-3 focus:ring-blue-100 outline-none transition shadow-2xs resize-y"
                        >{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-[#c92137] to-[#0955cf] px-6 py-3 text-xs sm:text-sm font-black text-white shadow-xs hover:shadow-md transition cursor-pointer"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>विवरण सुरक्षित गर्नुहोस्</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 3: SECURITY & PASSWORD -->
            <div x-show="activeTab === 'security'" x-cloak class="rounded-3xl border border-stone-200/90 bg-white p-6 sm:p-10 shadow-2xs">
                <div class="border-b border-stone-200 pb-4 mb-6">
                    <h3 class="text-xl font-black text-stone-900">
                        🔒 सुरक्षा तथा पासवर्ड परिवर्तन
                    </h3>
                    <p class="text-stone-500 text-xs sm:text-sm mt-1">
                        आफ्नो खाताको सुरक्षाका लागि नियमित रूपमा बलियो पासवर्ड प्रयोग गर्नुहोस्।
                    </p>
                </div>

                <form method="POST" action="{{ route('dashboard.password.update') }}" class="space-y-5 max-w-xl">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="curr_password" class="block text-xs sm:text-sm font-bold text-stone-700">
                                हालको पासवर्ड (Current Password) <span class="text-red-500">*</span>
                            </label>
                            <button type="button" @click="showCurrentPass = !showCurrentPass" class="text-xs text-stone-500 hover:text-stone-800" x-text="showCurrentPass ? 'लुकाउनुहोस्' : 'देखाउनुहोस्'"></button>
                        </div>
                        <input
                            id="curr_password"
                            :type="showCurrentPass ? 'text' : 'password'"
                            name="current_password"
                            required
                            placeholder="••••••••"
                            class="w-full rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-900 placeholder-stone-400 focus:border-stone-800 focus:ring-3 focus:ring-stone-200 outline-none transition shadow-2xs"
                        >
                    </div>

                    <!-- New Password -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="new_password" class="block text-xs sm:text-sm font-bold text-stone-700">
                                नयाँ पासवर्ड (New Password) <span class="text-red-500">*</span>
                            </label>
                            <button type="button" @click="showNewPass = !showNewPass" class="text-xs text-stone-500 hover:text-stone-800" x-text="showNewPass ? 'लुकाउनुहोस्' : 'देखाउनुहोस्'"></button>
                        </div>
                        <input
                            id="new_password"
                            :type="showNewPass ? 'text' : 'password'"
                            name="password"
                            x-model="newPassword"
                            required
                            placeholder="••••••••"
                            class="w-full rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-900 placeholder-stone-400 focus:border-[#0955cf] focus:ring-3 focus:ring-blue-100 outline-none transition shadow-2xs"
                        >

                        <!-- Dynamic Password Checklist -->
                        <div class="mt-2.5 rounded-xl bg-stone-50 border border-stone-200 p-3 text-xs space-y-1">
                            <p class="font-bold text-stone-700 mb-1">आवश्यक मापदण्ड:</p>
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
                        <label for="new_password_confirmation" class="block text-xs sm:text-sm font-bold text-stone-700 mb-1.5">
                            नयाँ पासवर्ड पुष्टि गर्नुहोस् (Confirm Password) <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="new_password_confirmation"
                            :type="showNewPass ? 'text' : 'password'"
                            name="password_confirmation"
                            required
                            placeholder="••••••••"
                            class="w-full rounded-xl border border-stone-300 bg-white px-4 py-2.5 text-sm text-stone-900 placeholder-stone-400 focus:border-[#0955cf] focus:ring-3 focus:ring-blue-100 outline-none transition shadow-2xs"
                        >
                    </div>

                    <div class="pt-2">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-stone-900 hover:bg-black px-6 py-3 text-xs sm:text-sm font-black text-white shadow-xs transition cursor-pointer"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span>पासवर्ड अद्यावधिक गर्नुहोस्</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- TAB 4: RECOMMENDED READS -->
            <div x-show="activeTab === 'recommended'" x-cloak class="space-y-4">
                <div class="flex items-center justify-between pb-1">
                    <h3 class="text-lg font-black text-stone-900">
                        📰 तपाईंको लागि सिफारिस गरिएका ताजा समाचारहरू
                    </h3>
                    <a href="{{ route('home') }}" wire:navigate.hover class="text-xs font-bold text-[#c92137] hover:underline">
                        सबै समाचार हेर्नुहोस् ↗
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($recommendedArticles as $article)
                        <article class="group rounded-2xl border border-stone-200/90 bg-white overflow-hidden shadow-2xs hover:shadow-md transition flex flex-col">
                            <a href="{{ route('articles.show', $article->slug) }}" wire:navigate.hover class="block aspect-[16/10] overflow-hidden bg-stone-100">
                                <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                            </a>
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    @php $cat = $article->categories->first() ?? $article->category; @endphp
                                    @if($cat)
                                        <span class="text-[11px] font-black text-[#c92137] uppercase tracking-wider block mb-1">
                                            {{ $cat->name }}
                                        </span>
                                    @endif
                                    <h4 class="text-sm font-bold leading-snug text-stone-900 group-hover:text-[#0955cf] line-clamp-2 transition">
                                        <a href="{{ route('articles.show', $article->slug) }}" wire:navigate.hover>
                                            {{ $article->title }}
                                        </a>
                                    </h4>
                                </div>
                                <div class="mt-3 pt-3 border-t border-stone-100 flex items-center justify-between text-[11px] text-stone-400 font-semibold">
                                    <span>{{ $article->nepali_date }}</span>
                                    <span>{{ $article->nepali_views }} पटक पढिएको</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

        </div>
    </main>
</x-layouts.app>
