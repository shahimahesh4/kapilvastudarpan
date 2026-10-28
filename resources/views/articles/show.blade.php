<x-layouts.app
    :title="!empty($article->meta_title) ? $article->meta_title : ($article->title . ' | ' . setting('site_name', 'कपिलवस्तु दर्पण'))"
    :description="$article->meta_description ?? $article->excerpt ?? Str::limit(strip_tags($article->body), 160)"
    :keywords="$article->meta_keywords ?? 'कपिलवस्तु दर्पण, ' . ($article->categories->isNotEmpty() ? $article->categories->pluck('name')->join(', ') : ($article->category?->name ?? 'समाचार')) . ', ' . $article->title"
    :ogType="'article'"
    :ogImage="$article->image_url"
    :article="$article"
>
    @php
        $showArticleViewCount = setting_bool('show_article_view_count', true);
        $primaryCategory = $article->categories->first() ?? $article->category;
    @endphp

    <!-- Top Reading Progress Indicator -->
    <div id="article-progress" class="fixed top-0 left-0 h-1 bg-gradient-to-r from-[#c92137] via-red-500 to-amber-500 z-[100] transition-all duration-75" style="width: 0%"></div>

    <!-- Main Article Page Container (Alpine managed font size scaling & reader state) -->
    <main class="article-reader-page mx-auto max-w-[1280px] px-4 py-6 sm:py-8 sm:px-6 lg:px-8" x-data="{
        fontSize: 'md',
        copied: false,
        bookmarked: false,
        shareUrl: '{{ url()->current() }}',
        init() {
            try {
                this.bookmarked = localStorage.getItem('kd_bookmark_{{ $article->id }}') === 'true';
            } catch (e) {}
        },
        toggleBookmark() {
            this.bookmarked = !this.bookmarked;
            try {
                localStorage.setItem('kd_bookmark_{{ $article->id }}', this.bookmarked);
            } catch (e) {}
            if (window.Swal) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: this.bookmarked ? 'success' : 'info',
                    title: this.bookmarked ? 'समाचार संग्रहमा थपियो! ✓' : 'समाचार संग्रहबाट हटाइयो',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        },
        scrollToComments() {
            const target = document.getElementById('comments') || document.getElementById('feedback-section') || document.querySelector('footer');
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        },
        copyLink() {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(this.shareUrl).then(() => {
                    this.copied = true;
                    setTimeout(() => this.copied = false, 3000);
                });
            } else {
                prompt('कृपया यो लिङ्क कपी गर्नुहोस्:', this.shareUrl);
            }
        },
        triggerShare() {
            if (navigator.share) {
                navigator.share({
                    title: '{{ addslashes($article->title) }}',
                    url: this.shareUrl
                }).catch(() => {});
            } else {
                this.copyLink();
            }
        }
    }">

        <!-- Breadcrumb & Topic Category Bar -->
        <nav class="mb-5 flex flex-wrap items-center justify-between gap-3 text-xs font-bold text-stone-500" aria-label="Breadcrumb">
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('home') }}" wire:navigate.hover class="inline-flex items-center gap-1.5 rounded-full bg-stone-100 hover:bg-[#c92137] hover:text-white px-3 py-1 transition-all duration-200">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    <span>गृहपृष्ठ</span>
                </a>
                <span class="text-stone-300">/</span>
                @if($primaryCategory)
                    <a href="{{ route('category.show', $primaryCategory->slug) }}" wire:navigate.hover class="inline-flex items-center gap-1 rounded-full bg-red-50 text-[#c92137] border border-red-200/80 px-3.5 py-1 font-black hover:bg-[#c92137] hover:text-white transition-all duration-200 shadow-2xs">
                        <span>{{ $primaryCategory->name }}</span>
                    </a>
                @endif
                @if($article->province)
                    <span class="text-stone-300">/</span>
                    <span class="inline-flex items-center gap-1 rounded-full bg-stone-100 text-stone-700 px-3 py-1 font-semibold">
                        <svg class="h-3 w-3 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        <span>{{ $article->province }}</span>
                    </span>
                @endif
            </div>

            <!-- Reading Duration Pill -->
            <div class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-stone-100/90 text-stone-600 px-3 py-1 font-semibold">
                <svg class="h-3.5 w-3.5 text-[#c92137]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <span>{{ $article->nepali_reading_time }} मिनेट पढ्न सकिने</span>
            </div>
        </nav>

        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start">
            <!-- Article Main Content Stream -->
            <article class="article-main-surface flex-1 min-w-0 bg-white">
                <!-- Additional Category Tags if multiple -->
                @if($article->categories->count() > 1)
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        @foreach($article->categories as $cat)
                            <a href="{{ route('category.show', $cat->slug) }}" wire:navigate.hover class="inline-block rounded-full bg-red-50 px-3 py-0.5 text-xs font-extrabold text-[#c92137] border border-red-200/80 hover:bg-[#c92137] hover:text-white transition">
                                {{ $cat->name }}
                            </a>
                        @endforeach
                    </div>
                @endif

                <!-- Article Headline -->
                <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-[42px] xl:text-[45px] font-black leading-[1.28] text-stone-900 tracking-tight">
                    {{ $article->title }}
                </h1>

                <!-- Content Layout with Sticky Left Side Scroller (Matches Ratopati & Screenshot) -->
                <div class="mt-6 flex flex-col lg:flex-row gap-6 xl:gap-8 items-start relative">
                    <!-- Left Sticky Side Scroller on Scroll -->
                    <aside class="hidden lg:flex flex-col items-center w-20 shrink-0 sticky top-24 self-start text-center select-none z-20 pt-1 pb-4" aria-label="फ्लोटिङ लेखक तथा सेयर मेनु">
                        <!-- Author Avatar with Red Dashed Border -->
                        <div class="relative">
                            <span class="profile-avatar-dashed rounded-full w-12 h-12 shrink-0 shadow-xs overflow-hidden p-0.5 bg-white">
                                <img src="{{ setting_image_url(setting('news_profile_photo'), 'images/profile-avatar.png') }}" alt="{{ $article->display_author }}" class="h-full w-full rounded-full object-contain">
                            </span>
                        </div>

                        <!-- Author Name -->
                        <h4 class="mt-1.5 font-black text-stone-900 text-xs leading-tight text-center max-w-[80px]">
                            {{ $article->display_author }}
                        </h4>

                        <!-- Nepali Date with Clock Icon -->
                        <div class="mt-1 flex flex-col items-center text-[10px] sm:text-[11px] font-semibold text-stone-500 leading-tight max-w-[85px]">
                            <div class="flex items-center gap-1">
                                <svg class="h-3 w-3 text-[#c92137] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="9.5"></circle>
                                    <polyline points="12 6.5 12 12 15.5 14"></polyline>
                                </svg>
                                <span>{{ $article->nepali_date }}</span>
                            </div>
                        </div>

                        <!-- Bookmark / संग्रह Button -->
                        <button
                            type="button"
                            @click="toggleBookmark()"
                            class="mt-2.5 flex flex-col items-center gap-0.5 text-stone-600 hover:text-amber-600 transition group cursor-pointer"
                            :title="bookmarked ? 'संग्रहबाट हटाउनुहोस्' : 'समाचार संग्रह गर्नुहोस्'"
                        >
                            <svg class="h-5 w-5 transition-colors" :class="bookmarked ? 'fill-amber-500 text-amber-500' : 'fill-stone-500 group-hover:fill-amber-600'" viewBox="0 0 24 24">
                                <path d="M5 4a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v18l-7-4-7 4V4z"/>
                            </svg>
                            <span class="text-[11px] font-black tracking-tight" :class="bookmarked ? 'text-amber-600' : 'text-stone-700 group-hover:text-amber-600'" x-text="bookmarked ? 'संग्रहित' : 'संग्रह'"></span>
                        </button>

                        <!-- Red Speech Bubble (प्रतिक्रिया) -->
                        <button
                            type="button"
                            @click="scrollToComments()"
                            class="mt-2 flex flex-col items-center text-[#c92137] hover:scale-110 transition cursor-pointer"
                            title="प्रतिक्रिया / टिप्पणी सेक्सनमा जानुहोस्"
                        >
                            <svg class="h-6 w-6 fill-current" viewBox="0 0 24 24">
                                <path d="M21 6h-2v9H6v2c0 .55.45 1 1 1h11l4 4V7c0-.55-.45-1-1-1zm-4 7V3c0-.55-.45-1-1-1H3c-.55 0-1 .45-1 1v14l4-4h10c.55 0 1-.45 1-1z"/>
                            </svg>
                        </button>

                        @if($showArticleViewCount)
                            <!-- View Count -->
                            <div class="mt-2 text-center">
                                <span class="block text-base sm:text-lg font-black text-stone-800 tracking-tight leading-none">{{ $article->nepali_views }}</span>
                                <span class="block text-[10px] font-bold text-stone-600 uppercase tracking-wider mt-0.5">पटक पढिएको</span>
                            </div>
                        @endif

                        <!-- Social Share Circular Buttons Stack -->
                        <div class="mt-2 flex flex-col items-center gap-2">
                            <!-- Facebook -->
                            <a
                                href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="grid h-10 w-10 place-items-center rounded-full bg-[#1877f2] hover:bg-[#0c63d4] text-white shadow-xs hover:scale-110 transition-all duration-200"
                                title="Facebook मा सेयर गर्नुहोस्"
                            >
                                <svg viewBox="0 0 24 24" class="h-4.5 w-4.5 fill-current" aria-hidden="true">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>

                            <!-- X (Twitter) -->
                            <a
                                href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="grid h-10 w-10 place-items-center rounded-full bg-black hover:bg-stone-800 text-white shadow-xs hover:scale-110 transition-all duration-200"
                                title="X मा पोस्ट गर्नुहोस्"
                            >
                                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-current" aria-hidden="true">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                            </a>

                            <!-- LinkedIn -->
                            <a
                                href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="grid h-10 w-10 place-items-center rounded-full bg-[#0077b5] hover:bg-[#006097] text-white shadow-xs hover:scale-110 transition-all duration-200"
                                title="LinkedIn मा सेयर गर्नुहोस्"
                            >
                                <svg viewBox="0 0 24 24" class="h-4.5 w-4.5 fill-current" aria-hidden="true">
                                    <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                </svg>
                            </a>

                            <!-- Share / More -->
                            <button
                                type="button"
                                @click="triggerShare()"
                                class="grid h-10 w-10 place-items-center rounded-full bg-[#8bc34a] hover:bg-[#7cb342] text-white shadow-xs hover:scale-110 transition-all duration-200 cursor-pointer"
                                title="सेयर गर्नुहोस् वा लिङ्क कपी गर्नुहोस्"
                            >
                                <svg viewBox="0 0 24 24" class="h-4.5 w-4.5 fill-current" aria-hidden="true">
                                    <path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92s2.92-1.31 2.92-2.92c0-1.61-1.31-2.92-2.92-2.92z"/>
                                </svg>
                            </button>
                        </div>
                    </aside>

                    <!-- Article Main Content Stream -->
                    <div class="flex-1 min-w-0">
                        <!-- Editorial Lead Excerpt Box with Stylized Accent -->
                        @if ($article->excerpt)
                            <div class="article-excerpt-surface relative overflow-hidden rounded-2xl border-l-[6px] border-[#c92137] bg-gradient-to-r from-red-50/70 via-stone-50/50 to-white p-5 sm:p-6 shadow-2xs mb-6">
                                <svg class="absolute right-3 -bottom-4 h-24 w-24 text-red-100/50 pointer-events-none select-none" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                                </svg>
                                <p class="relative z-10 text-lg sm:text-[21px] font-semibold leading-relaxed text-stone-800">
                                    {{ $article->excerpt }}
                                </p>
                            </div>
                        @endif

                        <!-- Author & Publishing Meta Card (Mobile only, on desktop author is in sticky side scroller) -->
                        <div class="article-meta-surface block lg:hidden mb-6 flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-stone-200/90 bg-gradient-to-r from-stone-50/90 via-white to-stone-50/40 p-4 sm:p-4.5 shadow-2xs">
                    <div class="flex items-center gap-3.5">
                        <span class="profile-avatar-dashed rounded-full w-11 h-11 sm:w-12 sm:h-12 shrink-0 shadow-xs overflow-hidden p-0.5 bg-white">
                            <img src="{{ setting_image_url(setting('news_profile_photo'), 'images/profile-avatar.png') }}" alt="{{ $article->display_author }}" class="h-full w-full rounded-full object-contain">
                        </span>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="font-black text-stone-900 text-base sm:text-lg tracking-tight">{{ $article->display_author }}</span>
                                <span class="rounded bg-red-100/80 px-2 py-0.5 text-[10px] font-black uppercase text-[#c92137]">आधिकारिक</span>
                            </div>
                            <div class="flex items-center gap-2 text-stone-600 mt-0.5 font-semibold text-xs sm:text-sm flex-wrap">
                                <span class="inline-flex items-center gap-1.5 font-bold text-stone-800">
                                    <svg class="h-4 w-4 text-[#c92137] shrink-0 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                                        <circle cx="12" cy="12" r="9.5"></circle>
                                        <polyline points="12 6.5 12 12 15.5 14"></polyline>
                                    </svg>
                                    <time datetime="{{ $article->published_at?->toIso8601String() }}">
                                        {{ $article->nepali_date }}
                                    </time>
                                </span>
                                <span class="text-stone-300">•</span>
                                <span class="text-stone-500 font-medium">{{ $article->published_at?->diffForHumans() }}</span>
                                <span class="text-stone-300">•</span>
                                <span class="text-stone-500 font-medium">{{ $article->nepali_reading_time }} मिनेट पढ्न सकिने</span>
                            </div>
                        </div>
                    </div>

                    @if($showArticleViewCount)
                        <!-- View Count Badge -->
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full bg-red-50/90 px-3.5 py-1.5 text-xs font-black text-[#c92137] border border-red-200/90 shadow-2xs">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>{{ $article->nepali_views }} पटक पढिएको</span>
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Desktop Reader Quick Tools Toolbar -->
                <div class="hidden lg:flex items-center justify-between border-y border-stone-200/80 py-2.5 mb-6 text-xs text-stone-600">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center gap-1.5 font-semibold text-stone-500">
                            <svg class="h-3.5 w-3.5 text-[#c92137] shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9.5"></circle>
                                <polyline points="12 6.5 12 12 15.5 14"></polyline>
                            </svg>
                            <span>{{ $article->published_at?->diffForHumans() }}</span>
                        </span>
                        @if($showArticleViewCount)
                            <span class="text-stone-300">•</span>
                            <span class="inline-flex items-center gap-1 font-semibold text-[#c92137]">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                <span>{{ $article->nepali_views }} पटक पढिएको</span>
                            </span>
                        @endif
                    </div>

                    <div class="flex items-center gap-2">
                        <!-- Copy Link -->
                        <button type="button" @click="copyLink()" class="inline-flex items-center gap-1 rounded-md px-2.5 py-1 text-stone-600 hover:text-[#c92137] hover:bg-stone-100 transition cursor-pointer" title="लिङ्क कपी गर्नुहोस्">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span x-text="copied ? 'कपी भयो! ✓' : 'कपी लिङ्क'"></span>
                        </button>

                        <!-- Print -->
                        <button type="button" onclick="window.print()" class="inline-flex items-center gap-1 rounded-md px-2.5 py-1 text-stone-600 hover:text-[#c92137] hover:bg-stone-100 transition cursor-pointer" title="समाचार प्रिन्ट गर्नुहोस्">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>प्रिन्ट</span>
                        </button>

                        <!-- Reader Interactive Font Size Adjuster -->
                        <div class="flex items-center gap-1 bg-stone-100 rounded-full p-1 border border-stone-200/80 ml-1">
                            <span class="text-[11px] font-bold text-stone-500 px-1.5">अक्षर:</span>
                            <button type="button" @click="fontSize = (fontSize === 'xl' ? 'lg' : (fontSize === 'lg' ? 'md' : 'sm'))" class="grid h-6 w-6 place-items-center rounded-full bg-white text-[11px] font-black text-stone-700 shadow-2xs hover:bg-[#c92137] hover:text-white transition cursor-pointer" title="अक्षर सानो बनाउनुहोस्">A-</button>
                            <button type="button" @click="fontSize = 'md'" class="grid h-6 w-6 place-items-center rounded-full text-[11px] font-black transition cursor-pointer" :class="fontSize === 'md' ? 'bg-[#c92137] text-white shadow-2xs' : 'bg-white text-stone-700 hover:bg-stone-200'" title="साधारण अक्षर">A</button>
                            <button type="button" @click="fontSize = (fontSize === 'sm' ? 'md' : (fontSize === 'md' ? 'lg' : 'xl'))" class="grid h-6 w-6 place-items-center rounded-full bg-white text-[11px] font-black text-stone-700 shadow-2xs hover:bg-[#c92137] hover:text-white transition cursor-pointer" title="अक्षर ठूलो बनाउनुहोस्">A+</button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Interactive Social Sharing & Font Zoom Toolbar -->
                <div class="block lg:hidden mt-5 mb-6 flex flex-wrap items-center justify-between gap-3 border-y border-stone-200 py-3.5">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-bold text-stone-500 mr-1 flex items-center gap-1">
                            <svg class="h-3.5 w-3.5 text-[#c92137]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                            सेयर गर्नुहोस्:
                        </span>

                        <!-- Facebook Share -->
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full bg-[#1877f2] hover:bg-[#0c63d4] px-3.5 py-1.5 text-xs font-bold text-white shadow-2xs hover:scale-105 transition-all duration-200" title="Facebook मा सेयर गर्नुहोस्">
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-current" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            <span>Facebook</span>
                        </a>

                        <!-- X (Twitter) Share -->
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full bg-black hover:bg-stone-800 px-3.5 py-1.5 text-xs font-bold text-white shadow-2xs hover:scale-105 transition-all duration-200" title="X मा पोस्ट गर्नुहोस्">
                            <svg viewBox="0 0 24 24" class="h-3 w-3 fill-current" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            <span>Post</span>
                        </a>

                        <!-- WhatsApp Share -->
                        <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . url()->current()) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-full bg-[#25d366] hover:bg-[#1eb855] px-3.5 py-1.5 text-xs font-bold text-white shadow-2xs hover:scale-105 transition-all duration-200" title="WhatsApp मा पठाउनुहोस्">
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-current" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                            <span>WhatsApp</span>
                        </a>

                        <!-- Copy Link Button -->
                        <button type="button" @click="copyLink()" class="inline-flex items-center gap-1.5 rounded-full border border-stone-300 bg-stone-100 hover:bg-stone-200 px-3.5 py-1.5 text-xs font-bold text-stone-700 shadow-2xs transition-all duration-200 cursor-pointer" title="लिङ्क कपी गर्नुहोस्">
                            <svg class="h-3.5 w-3.5 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span x-text="copied ? 'कपी भयो! ✓' : 'लिङ्क कपी'"></span>
                        </button>

                        <!-- Print Button -->
                        <button type="button" onclick="window.print()" class="inline-flex items-center gap-1.5 rounded-full border border-stone-300 bg-stone-100 hover:bg-stone-200 px-3 py-1.5 text-xs font-bold text-stone-700 shadow-2xs transition-all duration-200 cursor-pointer" title="समाचार प्रिन्ट गर्नुहोस्">
                            <svg class="h-3.5 w-3.5 text-stone-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4H7v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>प्रिन्ट</span>
                        </button>
                    </div>

                    <!-- Reader Interactive Font Size Adjuster -->
                    <div class="flex items-center gap-1.5 bg-stone-100 rounded-full p-1 border border-stone-200/80">
                        <span class="text-[11px] font-bold text-stone-500 px-2">अक्षर:</span>
                        <button type="button" @click="fontSize = (fontSize === 'xl' ? 'lg' : (fontSize === 'lg' ? 'md' : 'sm'))" class="grid h-7 w-7 place-items-center rounded-full bg-white text-xs font-black text-stone-700 shadow-2xs hover:bg-[#c92137] hover:text-white transition cursor-pointer" title="अक्षर सानो बनाउनुहोस्">A-</button>
                        <button type="button" @click="fontSize = 'md'" class="grid h-7 w-7 place-items-center rounded-full text-xs font-black transition cursor-pointer" :class="fontSize === 'md' ? 'bg-[#c92137] text-white shadow-2xs' : 'bg-white text-stone-700 hover:bg-stone-200'" title="साधारण अक्षर">A</button>
                        <button type="button" @click="fontSize = (fontSize === 'sm' ? 'md' : (fontSize === 'md' ? 'lg' : 'xl'))" class="grid h-7 w-7 place-items-center rounded-full bg-white text-xs font-black text-stone-700 shadow-2xs hover:bg-[#c92137] hover:text-white transition cursor-pointer" title="अक्षर ठूलो बनाउनुहोस्">A+</button>
                    </div>
                </div>

                <!-- Featured Image Container -->
                <figure class="mt-6">
                    <div class="aspect-[16/9] sm:aspect-[16/10] rounded-2xl overflow-hidden bg-stone-100 shadow-md border border-stone-200/80 group">
                        <img src="{{ $article->imageUrl(1280) }}" alt="{{ $article->title }}" class="h-full w-full object-cover group-hover:scale-[1.015] transition-transform duration-500" loading="eager" fetchpriority="high" decoding="async">
                    </div>
                    <figcaption class="mt-2.5 flex items-center justify-between text-xs text-stone-600 italic px-1">
                        <span class="flex items-center gap-1.5">
                            <svg class="h-3.5 w-3.5 text-stone-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>तस्बिर: {{ str_contains($article->display_author, 'कपिलवस्तु दर्पण') ? $article->display_author : ($article->display_author . ' / कपिलवस्तु दर्पण') }}</span>
                        </span>
                        <span class="text-[11px] text-stone-600 not-italic">कपिलवस्तु दर्पण डिजिटल</span>
                    </figcaption>
                </figure>

                <!-- Full Article Body with Scalable Typography -->
                <div class="article-body mt-8 max-w-none text-stone-800 leading-relaxed space-y-6" :data-font-size="fontSize">
                    {!! $article->body ?: nl2br(e($article->excerpt)) !!}
                </div>



                <!-- Article Tags Pill List -->
                <div class="mt-8 border-t border-stone-200 pt-5">
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-xs font-bold text-stone-600 flex items-center gap-1 mr-1">
                            <svg class="h-3.5 w-3.5 text-[#c92137]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                            विषयगत ट्यागहरू:
                        </span>
                        @foreach(['कपिलवस्तु दर्पण', 'ताजा समाचार', $article->categories->first()?->name ?? 'समाचार', 'नेपाल', 'राजनीति'] as $tag)
                            <a href="{{ route('search', ['q' => $tag]) }}" wire:navigate.hover class="rounded-full bg-stone-100 hover:bg-[#c92137] hover:text-white px-3.5 py-1 text-xs font-bold text-stone-700 transition-all duration-200 cursor-pointer shadow-2xs">
                                # {{ $tag }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- In-content Advertisement -->
                <aside class="my-8" aria-label="विज्ञापन">
                    <x-ad-banner placement="article_incontent" class="aspect-[97/16] sm:aspect-[97/12] max-w-full rounded-xl overflow-hidden border border-stone-200" />
                </aside>

                <!-- Editorial Author Profile Card -->
                @if(setting_bool('show_article_editor_box', false))
                <div class="article-editor-surface mt-8 rounded-2xl border border-stone-200/90 bg-gradient-to-br from-stone-50 via-white to-red-50/20 p-5 sm:p-6 shadow-xs flex flex-col sm:flex-row items-center sm:items-start gap-4 sm:gap-5">
                    <span class="profile-avatar-dashed w-24 sm:w-28 aspect-[3.5/4.5] shrink-0 shadow-sm !rounded-xl overflow-hidden p-1 bg-white">
                        <img src="{{ asset(setting('editor_photo', 'images/team/tuklal-gaire.png')) }}" alt="{{ setting('editor_name', 'टुकलाल गैरे') }}" class="h-full w-full !rounded-lg object-cover" loading="lazy" decoding="async">
                    </span>
                    <div class="text-center sm:text-left flex-1">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                            <h3 class="text-lg font-black text-stone-900">{{ setting('editor_name', 'टुकलाल गैरे') }}</h3>
                            <span class="rounded-full bg-red-100 px-2.5 py-0.5 text-[11px] font-black text-[#c92137]">प्रधान सम्पादक / सम्पादकीय नेतृत्व</span>
                        </div>
                        <p class="mt-1.5 text-xs sm:text-sm text-stone-600 leading-relaxed font-normal">
                            कपिलवस्तु दर्पण डिजिटल न्युज — निष्पक्ष विचार, खोजमूलक रिपोर्टिङ, र जनताको आवाज। स्थानीयदेखि राष्ट्रिय सरोकारका सत्य-तथ्य सामग्रीहरू निरन्तर पाठकमाझ प्रस्तुत गर्दछ।
                        </p>
                        <div class="mt-3 flex items-center justify-center sm:justify-start gap-4 text-xs font-bold text-stone-500">
                            <span class="flex items-center gap-1">📍 {{ setting('contact_address', 'कपिलवस्तु–१०, पचेहरा, लुम्बिनी प्रदेश, नेपाल') }}</span>
                            <span class="flex items-center gap-1 text-[#0955cf]">✉ {{ setting('contact_email', 'kapilvastudarpannews@gmail.com') }}</span>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Article Comments Section -->
                <section id="comments" x-data="{ replyingTo: null, replyingToAuthor: '', insertEmoji(emoji, targetId) { const el = document.getElementById(targetId); if (!el) return; const start = el.selectionStart ?? el.value.length; const end = el.selectionEnd ?? el.value.length; const val = el.value; el.value = val.substring(0, start) + emoji + val.substring(end); el.focus(); el.selectionStart = el.selectionEnd = start + emoji.length; } }" class="article-comments-surface mt-10 rounded-2xl border border-stone-200 bg-white p-5 sm:p-7 shadow-xs">
                    <!-- Section Header -->
                    <div class="flex items-center justify-between border-b border-stone-200 pb-4 mb-6">
                        <div class="flex items-center gap-2.5">
                            <span class="grid h-8 w-8 place-items-center rounded-lg bg-red-100 text-[#c92137]">
                                <svg class="h-4.5 w-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </span>
                            <h3 class="text-lg sm:text-xl font-black text-stone-900 tracking-tight">
                                प्रतिक्रियाहरू
                            </h3>
                            <span class="rounded-full bg-stone-100 px-2.5 py-0.5 text-xs font-black text-stone-700 border border-stone-200">
                                {{ nepali_number((string)$article->approvedComments->count()) }}
                            </span>
                        </div>
                        <span class="text-xs font-bold text-stone-500 hidden sm:inline">
                            विचार तथा टिप्पणी
                        </span>
                    </div>

                    <!-- Flash Success / Error Messages -->
                    @if(session('comment_success'))
                        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-800 flex items-center gap-2">
                            <svg class="h-5 w-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            <span>{{ session('comment_success') }}</span>
                        </div>
                    @endif

                    @if($errors->has('content'))
                        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-xs font-bold text-[#c92137]">
                            {{ $errors->first('content') }}
                        </div>
                    @endif

                    <!-- Comment Submission Box -->
                    <div class="mb-8">
                        @if(auth()->check())
                            <div class="rounded-2xl border border-stone-200 bg-stone-50/70 p-4 sm:p-5">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="grid h-8 w-8 place-items-center rounded-full bg-[#c92137] text-white font-black text-xs uppercase shadow-xs">
                                            {{ mb_substr(auth()->user()->name, 0, 1) }}
                                        </span>
                                        <div>
                                            <span class="font-extrabold text-stone-900 text-sm block leading-none">{{ auth()->user()->name }}</span>
                                            <span class="text-[11px] text-stone-500 font-medium">{{ auth()->user()->email }}</span>
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-stone-500 hover:text-[#c92137] transition cursor-pointer">
                                            लगआउट
                                        </button>
                                    </form>
                                </div>
                                <form action="{{ route('articles.comments.store', $article->slug) }}" method="POST" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label for="comment-content" class="sr-only">प्रतिक्रिया</label>
                                        <textarea
                                            id="comment-content"
                                            name="content"
                                            rows="3"
                                            required
                                            minlength="2"
                                            maxlength="2000"
                                            placeholder="यस समाचारमा तपाईंको विचार वा प्रतिक्रिया लेख्नुहोस्..."
                                            class="w-full rounded-xl border border-stone-300 bg-white p-3.5 text-sm text-stone-800 placeholder-stone-400 focus:border-[#c92137] focus:ring-2 focus:ring-red-100 outline-none transition resize-y shadow-2xs leading-relaxed"
                                        >{{ old('content') }}</textarea>
                                    </div>

                                    <!-- Interactive Emoji Picker Toolbar -->
                                    <div class="flex items-center gap-1.5 flex-wrap rounded-lg bg-stone-100/80 px-2.5 py-1.5 border border-stone-200/80">
                                        <span class="text-[11px] font-bold text-stone-500 mr-1 flex items-center gap-1">
                                            <span>😀</span>
                                            <span>इमोजि:</span>
                                        </span>
                                        @foreach(['👍', '❤️', '🙏', '👏', '😊', '💡', '🔥', '😮', '😢', '💯', '🇳🇵', '✍️', '💐', '👌'] as $emoji)
                                            <button
                                                type="button"
                                                @click="insertEmoji('{{ $emoji }}', 'comment-content')"
                                                class="hover:scale-125 transition-transform text-base sm:text-lg cursor-pointer px-1 py-0.5"
                                                title="{{ $emoji }}"
                                            >{{ $emoji }}</button>
                                        @endforeach
                                    </div>

                                    <div class="flex items-center justify-between pt-1">
                                        <span class="text-[11px] text-stone-600 font-medium">
                                            शिष्ट र मर्यादित भाषाको प्रयोग गर्नुहोस्।
                                        </span>
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 rounded-xl bg-[#c92137] hover:bg-[#a50404] text-white px-5 py-2.5 text-xs sm:text-sm font-black shadow-xs hover:shadow-md transition-all duration-200 cursor-pointer"
                                        >
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                            <span>प्रतिक्रिया पठाउनुहोस्</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="rounded-2xl border-2 border-dashed border-stone-200 bg-stone-50/80 p-6 text-center sm:p-8">
                                <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-red-100 text-[#c92137] mb-3 shadow-2xs">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                    </svg>
                                </div>
                                <h4 class="font-black text-stone-900 text-base sm:text-lg">
                                    प्रतिक्रिया दिनका लागि कृपया पहिले लगइन गर्नुहोस्
                                </h4>
                                <p class="text-stone-600 text-xs sm:text-sm mt-1.5 max-w-md mx-auto leading-relaxed">
                                    यस समाचारमा आफ्नो विचार, टिप्पणी वा प्रतिक्रिया राख्न प्रयोगकर्ता खातामा लगइन हुनु अनिवार्य छ।
                                </p>
                                <div class="mt-4 flex flex-wrap items-center justify-center gap-3">
                                    <button
                                        type="button"
                                        @click="accountOpen = true; authTab = 'login'"
                                        class="inline-flex items-center gap-1.5 rounded-xl bg-[#0955cf] hover:bg-[#073c61] text-white px-5 py-2.5 text-xs sm:text-sm font-black shadow-xs transition hover:scale-105 cursor-pointer"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                                        <span>लगइन गर्नुहोस् (Log In)</span>
                                    </button>
                                    <button
                                        type="button"
                                        @click="accountOpen = true; authTab = 'register'"
                                        class="inline-flex items-center gap-1.5 rounded-xl border border-stone-300 bg-white hover:bg-stone-100 text-stone-800 px-5 py-2.5 text-xs sm:text-sm font-black shadow-2xs transition hover:scale-105 cursor-pointer"
                                    >
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                        <span>नयाँ खाता खोल्नुहोस् (Register)</span>
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Existing Comments List -->
                    <div class="space-y-5">
                        @forelse($article->approvedRootComments as $comment)
                            <div class="rounded-xl border border-stone-200/90 bg-stone-50/40 p-4 sm:p-5 transition hover:bg-stone-50/90">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-2.5">
                                        <span class="grid h-9 w-9 shrink-0 place-items-center rounded-full bg-stone-800 text-white font-extrabold text-xs uppercase shadow-2xs">
                                            {{ mb_substr($comment->user?->name ?: 'प', 0, 1) }}
                                        </span>
                                        <div>
                                            <span class="font-black text-stone-900 text-sm block leading-tight">
                                                {{ $comment->user?->name ?: 'नागरिक' }}
                                            </span>
                                            <time class="text-[11px] font-semibold text-stone-600 block mt-0.5" datetime="{{ $comment->created_at?->toIso8601String() }}">
                                                {{ $comment->nepali_time }} ({{ $comment->nepali_date }})
                                            </time>
                                        </div>
                                    </div>
                                    @if(auth()->check() && (auth()->id() === $comment->user_id))
                                        <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('के तपाईं यो प्रतिक्रिया मेटाउन चाहनुहुन्छ?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-stone-400 hover:text-red-600 p-1 transition cursor-pointer" title="प्रतिक्रिया मेटाउनुहोस्">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <p class="mt-3 text-sm text-stone-700 leading-relaxed font-normal pl-11">
                                    {!! nl2br(e($comment->content)) !!}
                                </p>

                                <!-- Comment Footer Actions (Reply button) -->
                                <div class="mt-3.5 flex items-center justify-between border-t border-stone-200/70 pt-2.5 pl-11">
                                    <div>
                                        @if(auth()->check())
                                            <button
                                                type="button"
                                                @click="replyingTo = (replyingTo === {{ $comment->id }} ? null : {{ $comment->id }}); replyingToAuthor = '{{ addslashes($comment->user?->name ?: 'नागरिक') }}'"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-[#0955cf] transition cursor-pointer"
                                            >
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                <span x-text="replyingTo === {{ $comment->id }} ? 'रद्द गर्नुहोस् (Cancel)' : 'जवाफ दिनुहोस् (Reply)'"></span>
                                            </button>
                                        @else
                                            <button
                                                type="button"
                                                @click="accountOpen = true; authTab = 'login'"
                                                class="inline-flex items-center gap-1.5 text-xs font-bold text-stone-600 hover:text-[#0955cf] transition cursor-pointer"
                                            >
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                <span>जवाफ दिनुहोस् (Reply)</span>
                                            </button>
                                        @endif
                                    </div>

                                    @if($comment->replies && $comment->replies->count() > 0)
                                        <span class="text-[11px] font-bold text-stone-600">
                                            {{ nepali_number((string)$comment->replies->count()) }} जवाफ
                                        </span>
                                    @endif
                                </div>

                                <!-- Inline Reply Form -->
                                @if(auth()->check())
                                    <div x-show="replyingTo === {{ $comment->id }}" x-cloak x-transition class="mt-4 pl-0 sm:pl-11">
                                        <div class="rounded-xl border border-blue-200 bg-blue-50/50 p-3.5 sm:p-4">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-xs font-bold text-blue-900 flex items-center gap-1.5">
                                                    <svg class="h-3.5 w-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                                                    <span><b>{{ $comment->user?->name ?: 'नागरिक' }}</b> लाई जवाफ लेख्दै...</span>
                                                </span>
                                                <button type="button" @click="replyingTo = null" class="text-[11px] font-bold text-stone-500 hover:text-red-600 transition cursor-pointer">
                                                    ✕ रद्द
                                                </button>
                                            </div>

                                            <form action="{{ route('articles.comments.store', $article->slug) }}" method="POST" class="space-y-2.5">
                                                @csrf
                                                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                                <textarea
                                                    id="reply-content-{{ $comment->id }}"
                                                    name="content"
                                                    rows="2"
                                                    required
                                                    minlength="2"
                                                    maxlength="2000"
                                                    placeholder="{{ $comment->user?->name ?: 'नागरिक' }} लाई आफ्नो जवाफ लेख्नुहोस्..."
                                                    class="w-full rounded-lg border border-blue-200 bg-white p-3 text-xs sm:text-sm text-stone-800 placeholder-stone-400 focus:border-[#0955cf] focus:ring-2 focus:ring-blue-100 outline-none transition resize-y shadow-2xs leading-relaxed"
                                                ></textarea>

                                                <!-- Emoji Bar for Reply -->
                                                <div class="flex items-center gap-1.5 flex-wrap rounded-lg bg-white/80 px-2 py-1 border border-blue-200/80">
                                                    <span class="text-[11px] font-bold text-stone-500 mr-0.5">😀 इमोजि:</span>
                                                    @foreach(['👍', '❤️', '🙏', '👏', '😊', '💡', '🔥', '😮', '😢', '💯', '🇳🇵', '✍️', '💐', '👌'] as $emoji)
                                                        <button
                                                            type="button"
                                                            @click="insertEmoji('{{ $emoji }}', 'reply-content-' + {{ $comment->id }})"
                                                            class="hover:scale-125 transition-transform text-sm sm:text-base cursor-pointer px-0.5"
                                                            title="{{ $emoji }}"
                                                        >{{ $emoji }}</button>
                                                    @endforeach
                                                </div>

                                                <div class="flex items-center justify-end gap-2 pt-1">
                                                    <button
                                                        type="button"
                                                        @click="replyingTo = null"
                                                        class="rounded-lg border border-stone-300 bg-white hover:bg-stone-100 px-3 py-1.5 text-xs font-bold text-stone-600 transition cursor-pointer"
                                                    >
                                                        रद्द
                                                    </button>
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center gap-1.5 rounded-lg bg-[#0955cf] hover:bg-[#073c61] text-white px-4 py-1.5 text-xs font-black shadow-xs transition cursor-pointer"
                                                    >
                                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                                        <span>जवाफ पठाउनुहोस्</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endif

                                <!-- Threaded Replies List -->
                                @if($comment->replies && $comment->replies->count() > 0)
                                    <div class="mt-4 space-y-3 pl-3 sm:pl-8 border-l-2 border-stone-200">
                                        @foreach($comment->replies as $reply)
                                            <div class="rounded-xl border border-stone-200/80 bg-white p-3.5 sm:p-4 shadow-2xs">
                                                <div class="flex items-start justify-between gap-3">
                                                    <div class="flex items-center gap-2">
                                                        <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-blue-700 text-white font-black text-[11px] uppercase shadow-2xs">
                                                            {{ mb_substr($reply->user?->name ?: 'प', 0, 1) }}
                                                        </span>
                                                        <div>
                                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                                <span class="font-black text-stone-900 text-xs sm:text-sm">
                                                                    {{ $reply->user?->name ?: 'नागरिक' }}
                                                                </span>
                                                                <span class="rounded bg-blue-50 px-1.5 py-0.5 text-[10px] font-bold text-blue-700 border border-blue-200/60">
                                                                    जवाफ
                                                                </span>
                                                            </div>
                                                            <time class="text-[10px] font-semibold text-stone-600 block mt-0.5" datetime="{{ $reply->created_at?->toIso8601String() }}">
                                                                {{ $reply->nepali_time }}
                                                            </time>
                                                        </div>
                                                    </div>

                                                    @if(auth()->check() && (auth()->id() === $reply->user_id))
                                                        <form action="{{ route('comments.destroy', $reply->id) }}" method="POST" onsubmit="return confirm('के तपाईं यो जवाफ मेटाउन चाहनुहुन्छ?');" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-stone-400 hover:text-red-600 p-1 transition cursor-pointer" title="जवाफ मेटाउनुहोस्">
                                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                                <p class="mt-2 text-xs sm:text-sm text-stone-700 leading-relaxed pl-9 font-normal">
                                                    {!! nl2br(e($reply->content)) !!}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-xl border border-dashed border-stone-200 p-8 text-center text-stone-500">
                                <svg class="mx-auto h-8 w-8 text-stone-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                <p class="text-sm font-bold text-stone-600">अहिलेसम्म कुनै प्रतिक्रिया आएको छैन।</p>
                                <p class="text-xs text-stone-600 mt-0.5">पहिलो प्रतिक्रिया लेखेर छलफल सुरु गर्नुहोस्।</p>
                            </div>
                        @endforelse
                    </div>
                </section>


                <!-- Previous & Next Article Navigation Cards -->
                <div class="mt-8 grid gap-4 sm:grid-cols-2 border-t border-stone-200 pt-6">
                    @if(isset($previousArticle) && $previousArticle)
                        <a href="{{ article_url($previousArticle) }}" wire:navigate.hover class="group flex gap-3.5 items-center rounded-xl border border-stone-200 bg-white p-3.5 shadow-2xs hover:border-[#c92137] hover:shadow-md transition-all duration-200">
                            <div class="relative h-16 w-20 shrink-0 overflow-hidden rounded-lg bg-stone-100 border border-stone-200/80">
                                <img src="{{ $previousArticle->image_url }}" alt="{{ $previousArticle->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" decoding="async">
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="flex items-center gap-1 text-[11px] font-black text-[#c92137] uppercase tracking-wider mb-1">
                                    <svg class="h-3 w-3 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                    अघिल्लो समाचार
                                </span>
                                <h4 class="text-xs sm:text-sm font-extrabold text-stone-900 group-hover:text-[#c92137] line-clamp-2 leading-snug">
                                    {{ $previousArticle->title }}
                                </h4>
                            </div>
                        </a>
                    @else
                        <div class="hidden sm:block"></div>
                    @endif

                    @if(isset($nextArticle) && $nextArticle)
                        <a href="{{ article_url($nextArticle) }}" wire:navigate.hover class="group flex flex-row-reverse gap-3.5 items-center rounded-xl border border-stone-200 bg-white p-3.5 text-right shadow-2xs hover:border-[#c92137] hover:shadow-md transition-all duration-200">
                            <div class="relative h-16 w-20 shrink-0 overflow-hidden rounded-lg bg-stone-100 border border-stone-200/80">
                                <img src="{{ $nextArticle->image_url }}" alt="{{ $nextArticle->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" decoding="async">
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="flex items-center justify-end gap-1 text-[11px] font-black text-[#c92137] uppercase tracking-wider mb-1">
                                    पछिल्लो समाचार
                                    <svg class="h-3 w-3 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </span>
                                <h4 class="text-xs sm:text-sm font-extrabold text-stone-900 group-hover:text-[#c92137] line-clamp-2 leading-snug">
                                    {{ $nextArticle->title }}
                                </h4>
                            </div>
                        </a>
                    @endif
                </div>

                <!-- Bottom Social Sharing Call-to-Action -->
                <div id="feedback-section" class="article-feedback-surface mt-8 rounded-2xl bg-gradient-to-r from-red-50 via-stone-50 to-amber-50/50 p-4 sm:p-5 border border-red-200/80 shadow-2xs">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
                        <div class="min-w-0 flex-1">
                            <h3 class="text-sm sm:text-[15px] font-extrabold text-stone-900 leading-snug">यो समाचार तपाईंलाई कस्तो लाग्यो?</h3>
                            <p class="text-xs text-stone-600 mt-0.5 leading-relaxed">साथीहरू तथा सामाजिक सञ्जालमा सेयर गरेर विचार आदान-प्रदान गर्नुहोस्।</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0 flex-nowrap">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="whitespace-nowrap shrink-0 inline-flex items-center gap-1.5 rounded-full bg-[#1877f2] hover:bg-[#0c63d4] px-3 py-1.5 text-xs font-bold text-white shadow-2xs transition hover:scale-105">
                                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-current shrink-0" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                <span>Facebook मा सेयर</span>
                            </a>
                            <a href="https://api.whatsapp.com/send?text={{ urlencode($article->title . ' ' . url()->current()) }}" target="_blank" rel="noopener noreferrer" class="whitespace-nowrap shrink-0 inline-flex items-center gap-1.5 rounded-full bg-[#25d366] hover:bg-[#1eb855] px-3 py-1.5 text-xs font-bold text-white shadow-2xs transition hover:scale-105">
                                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-current shrink-0" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                                <span>WhatsApp मा पठाउनुहोस्</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                <!-- Related News (सम्बन्धित समाचार) -->
                @if($relatedArticles->isNotEmpty())
                    <section class="mt-12 border-t-2 border-[#c92137] pt-6" aria-labelledby="related-heading">
                        <div class="flex items-center justify-between mb-6">
                            <h2 id="related-heading" class="text-2xl font-black text-stone-900 flex items-center gap-2.5">
                                <span class="h-6 w-2 bg-[#c92137] rounded-full"></span>
                                सम्बन्धित <span class="text-[#c92137]">समाचार</span>
                            </h2>
                            <span class="text-xs font-bold text-stone-500">थप सामग्रीहरू</span>
                        </div>
                        <div class="grid gap-6 sm:grid-cols-2">
                            @foreach($relatedArticles as $rel)
                                <article class="article-related-surface rounded-xl border border-stone-200/90 bg-white p-3.5 shadow-xs hover:shadow-md hover:border-red-200 transition-all duration-300 group">
                                    <a href="{{ article_url($rel) }}" wire:navigate.hover class="block aspect-[16/9] rounded-lg overflow-hidden bg-stone-100 relative">
                                        <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" decoding="async">
                                        @php $relCat = $rel->categories->first() ?? $rel->category; @endphp
                                        @if($relCat)
                                            <span class="absolute top-2 left-2 rounded-md bg-[#c92137] px-2 py-0.5 text-[10px] font-black text-white shadow-xs">
                                                {{ $relCat->name }}
                                            </span>
                                        @endif
                                    </a>
                                    <h3 class="mt-3">
                                        <a href="{{ article_url($rel) }}" wire:navigate.hover class="block text-base font-extrabold leading-snug text-stone-900 group-hover:text-[#c92137] hover:underline line-clamp-2 transition-colors">
                                            {{ $rel->title }}
                                        </a>
                                    </h3>
                                    <div class="mt-2.5 flex items-center justify-between text-xs text-stone-600 font-medium">
                                        <span class="flex items-center gap-1.5">
                                            <svg class="h-3.5 w-3.5 text-[#c92137]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="12 6 12 12 16 14"></polyline>
                                            </svg>
                                            {{ $rel->published_at?->diffForHumans() }}
                                        </span>
                                        <span class="text-[11px] text-stone-700 font-semibold">{{ $rel->display_author }}</span>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif
            </article>

            <!-- Right Interactive Sticky Sidebar (Decreased Width) -->
            <aside class="w-full lg:w-[310px] xl:w-[325px] shrink-0 space-y-7 lg:sticky lg:top-24">
                <!-- Trending News Box (Matches User Reference Image Styling) -->
                <div class="article-trending-surface rounded-2xl border border-red-200/90 bg-gradient-to-b from-red-50/50 via-white to-white p-4 sm:p-5 shadow-xs">
                    <div class="flex items-center justify-between border-b border-red-200 pb-3.5 mb-4">
                        <h3 class="text-base sm:text-lg font-black text-[#c92137] flex items-center gap-2">
                            <span class="grid h-7 w-7 place-items-center rounded-full bg-red-100 text-sm">🔥</span>
                            <span>ट्रेन्डिङ समाचार</span>
                        </h3>
                        <span class="rounded-full bg-red-100/80 px-2.5 py-0.5 text-[11px] font-black text-[#c92137]">धेरै हेरिएको</span>
                    </div>
                    <ol class="divide-y divide-red-100/80">
                        @foreach($trendingArticles as $trend)
                            <li class="py-3 group first:pt-1 last:pb-1">
                                <a href="{{ article_url($trend) }}" wire:navigate.hover class="flex gap-2.5 items-center">
                                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-gradient-to-br from-[#c92137] to-[#a50404] text-xs font-black text-white shadow-2xs group-hover:scale-110 transition-transform">
                                        {{ nepali_number($loop->iteration) }}
                                    </span>
                                    <div class="relative h-14 w-18 sm:w-20 shrink-0 overflow-hidden rounded-lg bg-stone-100 border border-stone-200/80 shadow-2xs">
                                        <img src="{{ $trend->image_url }}" alt="{{ $trend->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" decoding="async">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-xs sm:text-sm font-extrabold leading-snug text-stone-900 group-hover:text-[#c92137] line-clamp-2 transition-colors">
                                            {{ $trend->title }}
                                        </h4>
                                        <div class="mt-1 flex items-center justify-between text-[11px] text-stone-600 font-medium">
                                            <span class="flex items-center gap-1 text-stone-700">
                                                <svg class="h-3 w-3 text-[#c92137]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                {{ nepali_number(number_format($trend->views)) }}
                                            </span>
                                            <span>{{ $trend->published_at?->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <!-- Latest News Box (ताजा अपडेट) -->
                <div class="article-latest-surface rounded-2xl border border-stone-200/90 bg-white p-4 sm:p-5 shadow-xs">
                    <div class="flex items-center justify-between border-b border-stone-200 pb-3.5 mb-4">
                        <h3 class="text-lg font-black text-stone-900 flex items-center gap-2">
                            <span class="h-4 w-1.5 bg-[#0955cf] rounded-full"></span>
                            <span>ताजा अपडेट</span>
                        </h3>
                        <span class="text-xs font-bold text-stone-600">अहिले भर्खरै</span>
                    </div>
                    <div class="divide-y divide-stone-100">
                        @foreach($latestArticles as $latest)
                            <article class="py-3 group first:pt-1 last:pb-1">
                                <a href="{{ article_url($latest) }}" wire:navigate.hover class="flex gap-3 items-center">
                                    <div class="relative h-16 w-20 shrink-0 overflow-hidden rounded-lg bg-stone-100 border border-stone-200/80 shadow-2xs">
                                        <img src="{{ $latest->image_url }}" alt="{{ $latest->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" decoding="async">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        @php $latestCat = $latest->categories->first() ?? $latest->category; @endphp
                                        @if($latestCat)
                                            <span class="inline-block text-[11px] font-black text-[#c92137] mb-0.5">
                                                {{ $latestCat->name }}
                                            </span>
                                        @endif
                                        <h4 class="text-sm font-extrabold leading-snug text-stone-800 group-hover:text-[#c92137] line-clamp-2 transition-colors">
                                            {{ $latest->title }}
                                        </h4>
                                        <div class="mt-1 flex items-center gap-1.5 text-[11px] text-stone-600 font-medium">
                                            <svg class="h-3 w-3 text-[#c92137]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <time datetime="{{ $latest->published_at?->toIso8601String() }}">{{ $latest->published_at?->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </a>
                            </article>
                        @endforeach
                    </div>
                </div>

                <!-- Sidebar Ad Box -->
                <aside aria-label="विज्ञापन" class="rounded-2xl overflow-hidden border border-stone-200 shadow-2xs">
                    <x-ad-banner placement="sidebar" class="aspect-square w-full" />
                </aside>
            </aside>
        </div>
    </main>

    <!-- Reading Progress Bar Scroll Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let frameQueued = false;
            window.addEventListener('scroll', function() {
                if (frameQueued) return;
                frameQueued = true;
                requestAnimationFrame(function() {
                var el = document.getElementById('article-progress');
                if (!el) {
                    frameQueued = false;
                    return;
                }
                var total = document.documentElement.scrollHeight - window.innerHeight;
                var progress = total > 0 ? (window.scrollY / total) * 100 : 0;
                el.style.width = Math.min(100, Math.max(0, progress)) + '%';
                frameQueued = false;
                });
            }, { passive: true });
        });
    </script>
</x-layouts.app>
