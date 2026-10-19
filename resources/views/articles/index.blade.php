<x-layouts.app
    :title="!empty($category->meta_title) ? $category->meta_title : ($heading . ' | ' . setting('site_name', 'कपिलवस्तु दर्पण'))"
    :description="!empty($category->meta_description) ? $category->meta_description : ('कपिलवस्तु दर्पण — ' . $heading . ' सम्बन्धी सम्पूर्ण ताजा समाचार, विचार, भिडियो र विश्लेषण।')"
    :keywords="!empty($category->meta_keywords) ? $category->meta_keywords : ('कपिलवस्तु दर्पण, ' . $heading . ', नेपाली समाचार, ताजा अपडेट')"
>
    <main class="mx-auto max-w-[1280px] px-4 py-6 sm:py-8 sm:px-6 lg:px-8" x-data="{
        viewMode: localStorage.getItem('kd_cat_view') || 'grid',
        setView(mode) {
            this.viewMode = mode;
            try { localStorage.setItem('kd_cat_view', mode); } catch(e) {}
        }
    }">

        <!-- Breadcrumb Navigation Bar -->
        <nav class="mb-6 flex flex-wrap items-center justify-between gap-3 text-xs font-bold text-stone-500" aria-label="Breadcrumb">
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('home') }}" wire:navigate.hover class="inline-flex items-center gap-1.5 rounded-full bg-stone-100 hover:bg-[#c92137] hover:text-white px-3 py-1 transition-all duration-200">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    <span>गृहपृष्ठ</span>
                </a>
                <span class="text-stone-300">/</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-red-50 text-[#c92137] border border-red-200/80 px-3 py-1 font-black">
                    {{ $heading }}
                </span>
            </div>

            <!-- Total Articles Count Badge -->
            <div class="inline-flex items-center gap-1.5 rounded-full bg-stone-100 px-3 py-1 text-xs font-bold text-stone-700">
                <span class="h-2 w-2 rounded-full bg-[#c92137]"></span>
                <span>कुल समाचार: <strong>{{ nepali_number($articles->total()) }}</strong></span>
            </div>
        </nav>

        <!-- Main Category Page Header Banner -->
        <header class="mb-8 border-b-2 border-[#c92137] pb-4">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div class="flex items-center gap-3">
                    <span class="inline-block h-8 w-2 rounded-full bg-[#c92137] shrink-0"></span>
                    <div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-stone-900 tracking-tight flex items-center gap-3">
                            <span>{{ $heading }}</span>
                        </h1>
                        @if(isset($category) && $category->description)
                            <p class="mt-1 text-xs sm:text-sm text-stone-600 font-medium">{{ $category->description }}</p>
                        @endif
                    </div>
                </div>

                <!-- Right Side Controls: Search Bar or View Mode Switcher -->
                <div class="flex items-center gap-3">
                    <!-- Grid vs List View Mode Toggle -->
                    <div class="flex items-center bg-stone-100 rounded-lg p-0.5 border border-stone-200 shadow-2xs">
                        <button
                            type="button"
                            @click="setView('grid')"
                            :class="viewMode === 'grid' ? 'bg-white text-[#c92137] shadow-xs font-bold' : 'text-stone-500 hover:text-stone-800'"
                            class="p-1.5 rounded transition inline-flex items-center gap-1 text-xs"
                            title="ग्रिड भ्यु"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            <span class="hidden md:inline">ग्रिड</span>
                        </button>
                        <button
                            type="button"
                            @click="setView('list')"
                            :class="viewMode === 'list' ? 'bg-white text-[#c92137] shadow-xs font-bold' : 'text-stone-500 hover:text-stone-800'"
                            class="p-1.5 rounded transition inline-flex items-center gap-1 text-xs"
                            title="सूची भ्यु"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            <span class="hidden md:inline">सूची</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- In Search Mode: Refine Search Form -->
            @if(isset($query))
                <div class="mt-4 pt-3 border-t border-stone-100">
                    <form action="{{ route('search') }}" method="GET" class="flex max-w-xl rounded-xl border-2 border-stone-300 focus-within:border-[#0955cf] overflow-hidden shadow-inner">
                        <input name="q" type="search" value="{{ $query }}" placeholder="अर्को विषय वा शब्द खोज्नुहोस्..." class="flex-1 px-4 py-2.5 text-sm font-semibold focus:outline-none bg-stone-50 focus:bg-white" required>
                        <button type="submit" class="bg-[#c92137] hover:bg-red-800 text-white px-5 text-sm font-bold transition">खोज्नुहोस्</button>
                    </form>
                </div>
            @endif
        </header>

        <!-- Main Content 2-Column Grid: Articles Stream + Sidebar -->
        <div class="grid gap-8 lg:grid-cols-12 items-start">

            <!-- Left 8 Columns: Articles Grid / Stream -->
            <section class="lg:col-span-8 min-w-0" aria-label="समाचार सूची">

                @if($articles->isNotEmpty())
                    @php
                        // First article on page 1 is highlighted as the prominent Lead Story
                        $isFirstPage = $articles->currentPage() === 1;
                        $leadStory = $isFirstPage ? $articles->first() : null;
                        $remainingArticles = $isFirstPage ? $articles->slice(1) : $articles;
                    @endphp

                    <!-- Featured Hero / Lead Story (Page 1 Exclusive) -->
                    @if($leadStory)
                        <article class="mb-8 rounded-2xl border border-stone-200/90 bg-white p-5 sm:p-6 shadow-sm hover:shadow-md transition-all duration-300 group">
                            <div class="grid grid-cols-1 md:grid-cols-12 gap-5 sm:gap-6 items-center">
                                <!-- Big Feature Photo -->
                                <a href="{{ route('articles.show', $leadStory->slug) }}" wire:navigate.hover class="md:col-span-7 block aspect-[16/10] rounded-xl overflow-hidden bg-stone-100 relative shadow-xs">
                                    <img src="{{ $leadStory->image_url }}" alt="{{ $leadStory->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" loading="eager" fetchpriority="high">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                                    @php $leadCat = $leadStory->categories->first() ?? $leadStory->category; @endphp
                                    @if($leadCat)
                                        <span class="absolute bottom-3 left-3 rounded-md bg-[#c92137] px-2.5 py-1 text-xs font-black text-white shadow-xs">
                                            {{ $leadCat->name }}
                                        </span>
                                    @endif
                                </a>

                                <!-- Lead Content -->
                                <div class="md:col-span-5 flex flex-col justify-between">
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="inline-flex items-center gap-1 text-[11px] font-extrabold uppercase tracking-wider text-[#c92137] bg-red-50 border border-red-200/70 px-2.5 py-0.5 rounded-full">
                                                ★ मुख्य समाचार
                                            </span>
                                            @if($leadStory->province)
                                                <span class="text-stone-300">•</span>
                                                <span class="text-xs font-semibold text-stone-500">{{ $leadStory->province }}</span>
                                            @endif
                                        </div>

                                        <h2 class="text-xl sm:text-2xl lg:text-[25px] font-black leading-snug text-stone-900 group-hover:text-[#c92137] transition">
                                            <a href="{{ route('articles.show', $leadStory->slug) }}" wire:navigate.hover>
                                                {{ $leadStory->title }}
                                            </a>
                                        </h2>

                                        @if($leadStory->excerpt)
                                            <p class="mt-3 text-sm text-stone-600 leading-relaxed line-clamp-3">
                                                {{ $leadStory->excerpt }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="mt-5 pt-3.5 border-t border-stone-100 flex items-center justify-between text-xs text-stone-500 font-medium">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-stone-800">{{ $leadStory->display_author }}</span>
                                        </div>
                                        <div class="flex items-center gap-1.5 text-stone-400">
                                            <svg class="h-3.5 w-3.5 text-[#c92137]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                            <time>{{ $leadStory->published_at?->diffForHumans() }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    @endif

                    <!-- Remaining Articles: Dynamic Grid or List Layout -->
                    <div
                        :class="viewMode === 'grid' ? 'grid gap-6 sm:grid-cols-2' : 'flex flex-col divide-y divide-stone-200 border-t border-stone-200'"
                    >
                        @foreach($remainingArticles as $index => $article)
                            <!-- Grid Card View -->
                            <article
                                x-show="viewMode === 'grid'"
                                class="rounded-2xl border border-stone-200/90 bg-white p-4.5 shadow-2xs hover:shadow-md hover:border-red-200 transition-all duration-300 group flex flex-col justify-between"
                            >
                                <div>
                                    <!-- Card Image with Hover Zoom -->
                                    <a href="{{ route('articles.show', $article->slug) }}" wire:navigate.hover class="block aspect-[16/10] rounded-xl overflow-hidden bg-stone-100 relative shadow-2xs">
                                        <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy" decoding="async">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent pointer-events-none"></div>

                                        @php $artCat = $article->categories->first() ?? $article->category; @endphp
                                        @if($artCat)
                                            <span class="absolute bottom-2.5 left-2.5 rounded bg-[#c92137] px-2 py-0.5 text-[11px] font-black text-white shadow-xs">
                                                {{ $artCat->name }}
                                            </span>
                                        @endif
                                    </a>

                                    <!-- Tags Row -->
                                    <div class="mt-3 flex items-center gap-2">
                                        @if($article->province)
                                            <span class="inline-flex items-center gap-1 rounded-full bg-stone-100 text-stone-600 px-2.5 py-0.5 text-[11px] font-semibold">
                                                <svg class="h-3 w-3 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                                <span>{{ $article->province }}</span>
                                            </span>
                                        @endif
                                        <span class="text-[11px] text-stone-400 ml-auto">{{ $article->nepali_reading_time }} मिनेट पढ्न सकिने</span>
                                    </div>

                                    <!-- Headline -->
                                    <h2 class="mt-2">
                                        <a href="{{ route('articles.show', $article->slug) }}" wire:navigate.hover class="block text-base sm:text-[18px] font-bold leading-snug text-stone-900 group-hover:text-[#c92137] transition line-clamp-2">
                                            {{ $article->title }}
                                        </a>
                                    </h2>

                                    <!-- Excerpt -->
                                    @if($article->excerpt)
                                        <p class="mt-2 text-xs sm:text-[13.5px] text-stone-600 line-clamp-2 leading-relaxed">
                                            {{ $article->excerpt }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Card Footer: Author + Published Time -->
                                <div class="mt-4 flex items-center justify-between border-t border-stone-100 pt-3 text-[11px] sm:text-xs text-stone-500 font-medium">
                                    <span class="font-bold text-stone-700">{{ $article->display_author }}</span>
                                    <div class="flex items-center gap-1 text-stone-400">
                                        <svg class="h-3 w-3 text-[#c92137]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                        <time>{{ $article->published_at?->diffForHumans() }}</time>
                                    </div>
                                </div>
                            </article>

                            <!-- List Item View -->
                            <article
                                x-show="viewMode === 'list'"
                                class="py-5 first:pt-2 group flex flex-col sm:flex-row gap-4 sm:gap-6 items-start"
                            >
                                <a href="{{ route('articles.show', $article->slug) }}" wire:navigate.hover class="w-full sm:w-56 aspect-[16/10] shrink-0 rounded-xl overflow-hidden bg-stone-100 relative shadow-2xs">
                                    <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy" decoding="async">
                                    @php $artCat = $article->categories->first() ?? $article->category; @endphp
                                    @if($artCat)
                                        <span class="absolute bottom-2 left-2 rounded bg-[#c92137] px-2 py-0.5 text-[10px] font-black text-white">
                                            {{ $artCat->name }}
                                        </span>
                                    @endif
                                </a>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1.5 text-xs text-stone-500">
                                        <span class="font-bold text-stone-800">{{ $article->display_author }}</span>
                                        <span class="text-stone-300">•</span>
                                        <time>{{ $article->published_at?->diffForHumans() }}</time>
                                        @if($article->province)
                                            <span class="text-stone-300">•</span>
                                            <span class="text-stone-500">{{ $article->province }}</span>
                                        @endif
                                    </div>

                                    <h2 class="text-base sm:text-xl font-bold leading-snug text-stone-900 group-hover:text-[#c92137] transition">
                                        <a href="{{ route('articles.show', $article->slug) }}" wire:navigate.hover>
                                            {{ $article->title }}
                                        </a>
                                    </h2>

                                    @if($article->excerpt)
                                        <p class="mt-2 text-xs sm:text-sm text-stone-600 line-clamp-2 leading-relaxed">
                                            {{ $article->excerpt }}
                                        </p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <!-- Clean Styled Pagination -->
                    <div class="mt-10 border-t border-stone-200 pt-6">
                        {{ $articles->links() }}
                    </div>

                @else
                    <!-- Empty State -->
                    <div class="rounded-2xl border border-stone-200 bg-white p-12 text-center shadow-xs">
                        <div class="mx-auto h-16 w-16 grid place-items-center rounded-full bg-red-50 text-[#c92137] text-2xl mb-4">
                            📰
                        </div>
                        <h3 class="text-xl font-bold text-stone-800 mb-1">कुनै समाचार फेला परेन</h3>
                        <p class="text-sm text-stone-500 max-w-md mx-auto mb-6">
                            यस शीर्षक अन्तर्गत हाल कुनै सामग्री प्रकाशित गरिएको छैन वा तपाईंले खोज्नुभएको शब्दसँग मिल्ने परिणाम भेटिएन।
                        </p>
                        <a href="{{ route('home') }}" wire:navigate.hover class="inline-flex items-center gap-2 rounded-xl bg-[#c92137] hover:bg-red-800 px-5 py-2.5 text-sm font-bold text-white transition shadow-sm">
                            <span>गृहपृष्ठमा जानुहोस्</span>
                            <span>→</span>
                        </a>
                    </div>
                @endif

            </section>

            <!-- Right 4 Columns: Interactive Sidebar -->
            <aside class="lg:col-span-4 space-y-6" aria-label="साइडबार">

                <!-- Tabbed Widget: Trending & Latest News -->
                <div
                    class="rounded-2xl border border-stone-200/90 bg-white p-5 shadow-xs"
                    x-data="{ tab: 'trending' }"
                >
                    <!-- Tabs Header -->
                    <div class="grid grid-cols-2 rounded-xl bg-stone-100 p-1 mb-4 border border-stone-200">
                        <button
                            type="button"
                            @click="tab = 'trending'"
                            :class="tab === 'trending' ? 'bg-white text-[#c92137] font-black shadow-xs' : 'text-stone-600 font-bold hover:text-stone-900'"
                            class="py-2 text-xs sm:text-sm rounded-lg transition flex items-center justify-center gap-1.5 cursor-pointer"
                        >
                            <span>🔥</span>
                            <span>ट्रेन्डिङ</span>
                        </button>
                        <button
                            type="button"
                            @click="tab = 'latest'"
                            :class="tab === 'latest' ? 'bg-white text-[#0955cf] font-black shadow-xs' : 'text-stone-600 font-bold hover:text-stone-900'"
                            class="py-2 text-xs sm:text-sm rounded-lg transition flex items-center justify-center gap-1.5 cursor-pointer"
                        >
                            <span>🕒</span>
                            <span>ताजा</span>
                        </button>
                    </div>

                    <!-- Trending News Tab Content -->
                    <div x-show="tab === 'trending'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <ol class="divide-y divide-stone-100">
                            @foreach($trendingArticles as $trend)
                                <li class="flex items-start gap-3 py-3 first:pt-0 last:pb-0 group">
                                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-[#c92137] text-xs font-black text-white shadow-2xs group-hover:scale-110 transition">
                                        {{ nepali_number($loop->iteration) }}
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('articles.show', $trend->slug) }}" wire:navigate.hover class="text-sm font-bold leading-snug text-stone-900 group-hover:text-[#c92137] transition line-clamp-2">
                                            {{ $trend->title }}
                                        </a>
                                        <div class="mt-1 flex items-center gap-2 text-[11px] text-stone-400">
                                            <span>{{ nepali_number(number_format($trend->views)) }} पटक हेरियो</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>

                    <!-- Latest News Tab Content -->
                    <div x-show="tab === 'latest'" x-cloak x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                        <ol class="divide-y divide-stone-100">
                            @foreach($latestArticles as $latest)
                                <li class="flex items-start gap-3 py-3 first:pt-0 last:pb-0 group">
                                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-[#0955cf] text-xs font-black text-white shadow-2xs group-hover:scale-110 transition">
                                        {{ nepali_number($loop->iteration) }}
                                    </span>
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('articles.show', $latest->slug) }}" wire:navigate.hover class="text-sm font-bold leading-snug text-stone-900 group-hover:text-[#0955cf] transition line-clamp-2">
                                            {{ $latest->title }}
                                        </a>
                                        <time class="mt-1 block text-[11px] text-stone-400 font-medium">
                                            {{ $latest->published_at?->diffForHumans() }}
                                        </time>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </div>

                <!-- Commercial Sidebar Banner Ad -->
                <div class="w-full">
                    <x-ad-banner placement="sidebar" class="rounded-2xl overflow-hidden border border-stone-200/90 shadow-2xs" />
                </div>

                <!-- Other Categories Browser Card -->
                <div class="rounded-2xl border border-stone-200/90 bg-white p-5 shadow-xs">
                    <div class="flex items-center justify-between border-b border-stone-200 pb-3 mb-4">
                        <h3 class="text-base font-black text-stone-900 flex items-center gap-2">
                            <span class="h-4 w-1 bg-[#c92137] rounded"></span>
                            <span>अन्य प्रमुख विधाहरू</span>
                        </h3>
                    </div>

                    <ul class="divide-y divide-stone-100">
                        @foreach($categories as $c)
                            <li>
                                <a
                                    href="{{ route('category.show', $c->slug) }}"
                                    class="flex items-center justify-between py-2.5 text-sm font-bold text-stone-700 hover:text-[#c92137] hover:translate-x-1 transition-all duration-150"
                                >
                                    <span class="flex items-center gap-2">
                                        <span class="text-[#c92137] text-xs">•</span>
                                        <span>{{ $c->name }}</span>
                                    </span>
                                    <span class="grid h-5 px-2 place-items-center rounded-full bg-stone-100 text-[11px] font-semibold text-stone-500">
                                        {{ nepali_number($c->published_articles_count ?? $c->articles()->published()->count()) }}
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Official Kapilvastu Darpan Masthead / Registration Card -->
                <div class="rounded-2xl border border-stone-200/90 bg-gradient-to-br from-stone-50 to-stone-100/70 p-5 shadow-2xs space-y-3">
                    <div class="flex items-center gap-2 border-b border-stone-200 pb-2.5">
                        <span class="grid h-7 w-7 place-items-center rounded-lg bg-[#c92137] text-white text-xs font-black">क</span>
                        <h4 class="font-black text-stone-900 text-sm">कपिलवस्तु दर्पण न्यूज</h4>
                    </div>
                    <ul class="space-y-1.5 text-xs text-stone-600">
                        <li><strong class="text-stone-800">सञ्चालक:</strong> कपिलवस्तु मल्टिपर्पोज मिडिया प्रा.लि.</li>
                        <li><strong class="text-stone-800">सूचना विभाग:</strong> ५३१४/८२/८३</li>
                        <li><strong class="text-stone-800">सम्पर्क:</strong> +९७७-९७६६२५०४४५</li>
                    </ul>
                </div>

            </aside>
        </div>
    </main>
</x-layouts.app>
