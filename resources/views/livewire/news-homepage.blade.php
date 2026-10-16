<div>
    <main id="main-content" class="mx-auto max-w-[1280px] px-4 py-6 sm:px-6 lg:px-8">

        <!-- Mobile Quick Search -->
        <form wire:submit="search" class="mb-6 flex border border-stone-200 rounded overflow-hidden md:hidden shadow-sm" role="search">
            <input wire:model="query" type="search" placeholder="समाचार खोज्नुहोस्..." class="min-h-11 min-w-0 flex-1 px-3 text-sm focus:outline-none">
            <button type="submit" class="min-h-11 bg-[#c92137] px-5 text-sm font-bold text-white">खोज्नुहोस्</button>
        </form>

        <!-- Section: मुख्य समाचार & ट्रेन्डिङ (Lead Grid) -->
        <section aria-label="प्रमुख समाचार" class="mb-10 border-b border-stone-200 pb-8">
            <div class="mb-6 flex items-center justify-between border-b-2 border-[#c92137] pb-2.5">
                <div class="flex items-center gap-2.5">
                    <span class="inline-block h-6 w-2 rounded-full bg-[#c92137]"></span>
                    <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-stone-900">
                        मुख्य <span class="text-[#c92137]">समाचार</span>
                    </h2>
                </div>
                <a href="{{ route('category.show', 'main-news') }}" class="group inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-stone-600 hover:text-[#c92137] transition">
                    <span>सबै मुख्य समाचार</span>
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <div class="grid gap-6 lg:grid-cols-12 items-start">
                <!-- Main Feature Column -->
                @if(isset($headlines[0]))
                    <article class="lg:col-span-5 group">
                        <a href="{{ route('articles.show', $headlines[0]->slug) }}" class="relative block aspect-[16/10] rounded-xl overflow-hidden bg-stone-100 shadow-sm hover:shadow-md transition-all duration-300">
                            <img src="{{ $headlines[0]->image_url }}" alt="{{ $headlines[0]->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 via-30% to-transparent pointer-events-none"></div>
                            @php $hCat = $headlines[0]->categories->first() ?? $headlines[0]->category; @endphp
                            @if($hCat)
                                <span class="absolute bottom-3 left-3 z-10 rounded-md bg-[#c92137] px-2.5 py-1 text-xs font-black text-white shadow-sm">
                                    {{ $hCat->name }}
                                </span>
                            @endif
                        </a>
                        <h2 class="mt-3.5">
                            <a href="{{ route('articles.show', $headlines[0]->slug) }}" class="block text-xl sm:text-2xl font-black leading-snug text-stone-900 group-hover:text-[#c92137] transition-colors duration-150">
                                {{ $headlines[0]->title }}
                            </a>
                        </h2>
                        @if($headlines[0]->excerpt)
                            <p class="mt-2 text-sm text-stone-600 leading-relaxed line-clamp-2">{{ $headlines[0]->excerpt }}</p>
                        @endif
                        <div class="mt-3 flex items-center gap-3 text-xs text-stone-500 font-medium">
                            @if($headlines[0]->published_at)
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    {{ $headlines[0]->published_at->diffForHumans() }}
                                </span>
                            @endif
                            @if($headlines[0]->author)
                                <span>·</span>
                                <span>{{ $headlines[0]->author }}</span>
                            @endif
                        </div>
                    </article>
                @endif

                <!-- Center Middle Column: 3 News items with thumbnails -->
                <div class="space-y-4 lg:col-span-4 lg:border-x lg:border-stone-200 lg:px-5">
                    @foreach($headlines->slice(1, 3) as $art)
                        <article class="grid grid-cols-[7.5rem_1fr] sm:grid-cols-[8.5rem_1fr] items-start gap-3.5 border-b border-stone-200 pb-4 last:border-0 last:pb-0 group">
                            <a href="{{ route('articles.show', $art->slug) }}" class="relative block aspect-[4/3] rounded-lg overflow-hidden bg-stone-100 shadow-2xs ring-1 ring-stone-200/80">
                                <img src="{{ $art->image_url }}" alt="{{ $art->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition duration-300 group-hover:scale-105">
                            </a>
                            <div class="min-w-0">
                                @php $artCat = $art->categories->first() ?? $art->category; @endphp
                                @if($artCat)
                                    <a href="{{ route('category.show', $artCat->slug) }}" class="text-xs font-black text-[#c92137] uppercase tracking-wide">
                                        {{ $artCat->name }}
                                    </a>
                                @endif
                                <h3 class="mt-0.5">
                                    <a href="{{ route('articles.show', $art->slug) }}" class="block text-sm sm:text-base font-extrabold leading-snug text-stone-900 group-hover:text-[#c92137] line-clamp-2 transition-colors">
                                        {{ $art->title }}
                                    </a>
                                </h3>
                                <div class="mt-1.5 flex items-center gap-1.5 text-xs text-stone-400 font-medium">
                                    <svg class="h-3 w-3 text-stone-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    <span>{{ $art->published_at?->diffForHumans() }}</span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Right Column: ट्रेन्डिङ (Trending List 1 to 4) -->
                <aside class="lg:col-span-3">
                    <div class="rounded-2xl border border-stone-200 bg-white p-4 sm:p-5 shadow-xs">
                        <div class="flex items-center justify-between border-b border-stone-200 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg bg-red-100 text-[#c92137] text-sm">🔥</span>
                                <h2 class="text-base font-black text-stone-900">ट्रेन्डिङ <span class="text-[#c92137]">समाचार</span></h2>
                            </div>
                            <span class="inline-flex items-center rounded-full bg-stone-100 px-2 py-0.5 text-[11px] font-bold text-stone-600">धेरै हेरिएको</span>
                        </div>
                        <ol class="mt-2 divide-y divide-stone-100">
                            @foreach($trendingNews->take(4) as $trend)
                                <li class="group -mx-2 flex gap-3 rounded-xl p-2.5 transition-colors hover:bg-stone-50">
                                    <span class="grid h-7 w-7 shrink-0 place-items-center rounded-lg text-xs font-black bg-[#c92137] text-white shadow-xs">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <a href="{{ route('articles.show', $trend->slug) }}" class="block text-sm font-bold leading-snug text-stone-800 group-hover:text-[#c92137] line-clamp-2 transition-colors">
                                            {{ $trend->title }}
                                        </a>
                                        <div class="mt-1 flex items-center gap-1.5 text-[11px] text-stone-400 font-medium">
                                            <svg class="h-3 w-3 text-stone-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span>{{ number_format($trend->views) }} पटक हेरियो</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                    </div>
                </aside>
            </div>
        </section>

        <!-- Section: कपिलवस्तु विशेष (Kapilvastu Special) -->
        <section class="mb-12 border-b border-stone-200 pb-10" aria-labelledby="kapilvastu-heading">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b-2 border-[#c92137] pb-3">
                <div class="flex items-center gap-3">
                    <span class="inline-block h-7 w-2 rounded-full bg-[#c92137]"></span>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 id="kapilvastu-heading" class="text-2xl sm:text-3xl font-black tracking-tight text-stone-900">
                                कपिलवस्तु <span class="text-[#c92137]">विशेष</span>
                            </h2>
                            <span class="hidden sm:inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-black text-[#c92137]">
                                विशेष कभरेज
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-stone-500 font-medium mt-0.5">तिलौराकोट, स्थानीय विकास, सम्पदा र जनसरोकारका प्रमुख समाचारहरू</p>
                    </div>
                </div>
                <a href="{{ route('category.show', 'kapilvastu-special') }}" class="group inline-flex items-center gap-1.5 rounded-lg border border-stone-200 bg-white px-3.5 py-1.5 text-xs sm:text-sm font-bold text-stone-700 shadow-2xs hover:border-[#c92137] hover:bg-[#c92137] hover:text-white transition-all duration-200">
                    <span>सबै कपिलवस्तु समाचार</span>
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($kapilvastuSpecial as $art)
                    <article class="group flex flex-col rounded-2xl border border-stone-200/90 bg-white shadow-2xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        <a href="{{ route('articles.show', $art->slug) }}" class="relative block aspect-[16/10] overflow-hidden bg-stone-100">
                            <img src="{{ $art->image_url }}" alt="{{ $art->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                            <span class="absolute bottom-3 left-3 z-10 inline-flex items-center gap-1 rounded-md bg-[#c92137] px-2.5 py-1 text-[11px] font-black text-white shadow-md">
                                📍 कपिलवस्तु
                            </span>
                        </a>
                        <div class="flex flex-1 flex-col p-5">
                            <h3 class="flex-1">
                                <a href="{{ route('articles.show', $art->slug) }}" class="block text-base sm:text-lg font-black leading-snug text-stone-900 group-hover:text-[#c92137] transition-colors duration-150 line-clamp-2">
                                    {{ $art->title }}
                                </a>
                            </h3>
                            @if($art->excerpt)
                                <p class="mt-2.5 text-xs sm:text-sm text-stone-600 line-clamp-2 leading-relaxed font-normal">
                                    {{ $art->excerpt }}
                                </p>
                            @endif
                            <div class="mt-4 flex items-center justify-between border-t border-stone-100 pt-3 text-xs text-stone-400 font-medium">
                                <span class="inline-flex items-center gap-1.5">
                                    <svg class="h-3.5 w-3.5 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                    {{ $art->published_at?->diffForHumans() }}
                                </span>
                                @if($art->author)
                                    <span class="text-stone-500 font-semibold">{{ $art->author }}</span>
                                @else
                                    <span class="text-stone-400">कपिलवस्तु संवाददाता</span>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <!-- Section: राजनीति (Politics - Pro Modern Magazine) -->
        <section class="mb-10 rounded-2xl border border-stone-200/80 bg-linear-to-b from-stone-50/50 to-white p-5 sm:p-6 shadow-xs" aria-labelledby="politics-heading">
            <div class="mb-5 flex items-center justify-between border-b border-stone-200 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="inline-block h-6 w-1.5 rounded-full bg-[#c92137]"></span>
                    <h2 id="politics-heading" class="text-xl sm:text-2xl font-black text-stone-900 tracking-tight">राजनीति</h2>
                </div>
                <a href="{{ route('category.show', 'politics') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-[#c92137] hover:text-[#9e1526] transition">
                    <span>सबै राजनीति</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($politicsArticles as $art)
                    @php $pCat = $art->categories->first() ?? $art->category; @endphp
                    <article class="flex flex-col rounded-2xl border border-stone-200/90 bg-white shadow-2xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
                        <a href="{{ route('articles.show', $art->slug) }}" class="relative block aspect-[16/10] overflow-hidden bg-stone-100">
                            <img src="{{ $art->image_url }}" alt="{{ $art->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="absolute top-3 left-3 inline-flex items-center gap-1 rounded-md bg-[#c92137] px-2.5 py-1 text-[11px] font-black text-white shadow-md">
                                🏛️ {{ $pCat ? $pCat->name : 'राजनीति' }}
                            </span>
                        </a>

                        <div class="p-4 sm:p-5 flex flex-col flex-1">
                            <h3 class="text-base sm:text-lg font-black leading-snug text-stone-900 group-hover:text-[#c92137] transition-colors line-clamp-2">
                                <a href="{{ route('articles.show', $art->slug) }}" class="block">
                                    {{ $art->title }}
                                </a>
                            </h3>
                            @if($art->excerpt)
                                <p class="mt-2 text-xs sm:text-sm text-stone-600 line-clamp-2 leading-relaxed font-normal">
                                    {{ $art->excerpt }}
                                </p>
                            @endif

                            <div class="mt-auto pt-3 flex items-center justify-between text-[11px] font-medium text-stone-600 border-t border-stone-100">
                                <span class="flex items-center gap-1 text-stone-700 font-semibold truncate max-w-[130px]">
                                    <svg class="h-3 w-3 shrink-0 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ $art->author ?: 'कपिलवस्तु दर्पण' }}
                                </span>
                                <time class="flex items-center gap-1 text-stone-600 shrink-0 font-medium">
                                    <svg class="h-3 w-3 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $art->published_at?->diffForHumans() }}
                                </time>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <!-- Section: अर्थतन्त्र (Economy - Magazine Hero Style Matching Sample) -->
        <section class="mb-10 rounded-2xl border border-stone-200/80 bg-linear-to-b from-stone-50/40 to-white p-5 sm:p-6 shadow-xs" aria-labelledby="economy-heading">
            <div class="mb-5 flex items-center justify-between border-b border-stone-200 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="inline-block h-6 w-1.5 rounded-full bg-emerald-600"></span>
                    <h2 id="economy-heading" class="text-xl sm:text-2xl font-black text-stone-900 tracking-tight">अर्थतन्त्र</h2>
                </div>
                <a href="{{ route('category.show', 'economy') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-emerald-700 hover:text-emerald-800 transition">
                    <span>सबै अर्थतन्त्र</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @php
                $econLead = $economyArticles->first();
                $econSecondary = $economyArticles->slice(1, 4);
            @endphp

            <div class="grid gap-5 lg:grid-cols-12 items-stretch">
                @if($econLead)
                    <article class="lg:col-span-6 group flex flex-col">
                        <a href="{{ route('articles.show', $econLead->slug) }}" class="relative block w-full h-full min-h-[300px] sm:min-h-[360px] lg:min-h-full rounded-2xl overflow-hidden bg-stone-900 shadow-2xs group-hover:shadow-xl transition-all duration-300">
                            <img src="{{ $econLead->image_url }}" alt="{{ $econLead->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/45 via-45% to-transparent pointer-events-none"></div>

                            <div class="absolute top-4 left-4 z-10">
                                <span class="inline-flex items-center gap-1 rounded-md bg-emerald-600 px-2.5 py-1 text-[11px] font-black text-white shadow-md">
                                    💰 अर्थतन्त्र
                                </span>
                            </div>

                            <div class="absolute inset-x-0 bottom-0 z-10 p-5 sm:p-7">
                                <h3 class="text-xl sm:text-2xl font-black leading-snug text-white group-hover:text-emerald-200 transition-colors drop-shadow-sm line-clamp-3">
                                    {{ $econLead->title }}
                                </h3>
                            </div>
                        </a>
                    </article>
                @endif

                <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    @foreach($econSecondary as $art)
                        <article class="group flex flex-col">
                            <a href="{{ route('articles.show', $art->slug) }}" class="relative block aspect-[16/10] rounded-xl overflow-hidden bg-stone-100 shadow-2xs group-hover:shadow-md transition">
                                <img src="{{ $art->image_url }}" alt="{{ $art->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105">
                            </a>
                            <h3 class="mt-2.5">
                                <a href="{{ route('articles.show', $art->slug) }}" class="block text-sm sm:text-base font-extrabold leading-snug text-stone-900 group-hover:text-emerald-700 line-clamp-2 transition-colors">
                                    {{ $art->title }}
                                </a>
                            </h3>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Section: प्रदेश मुख्य समाचार (Tabbed Province News) -->
        <section id="pardesh-section" class="mb-12 border-b border-stone-200 pb-10" aria-labelledby="pardesh-heading" x-data="{ active: 'लुम्बिनी प्रदेश' }">
            <div class="mb-6 flex flex-wrap items-end justify-between gap-4 border-b-2 border-[#c92137] pb-3">
                <div class="flex items-center gap-3">
                    <span class="inline-block h-7 w-2 rounded-full bg-[#c92137]"></span>
                    <div>
                        <div class="flex items-center gap-2">
                            <h2 id="pardesh-heading" class="text-2xl sm:text-3xl font-black tracking-tight text-stone-900">
                                प्रदेश <span class="text-[#c92137]">मुख्य समाचार</span>
                            </h2>
                            <span class="hidden sm:inline-flex items-center gap-1.5 rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-black text-[#c92137]">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-600"></span>
                                </span>
                                सातै प्रदेश
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-stone-500 font-medium mt-0.5">नेपालका सातै प्रदेशका ताजा गतिविधि, विकास र जनजीवनका प्रमुख समाचारहरू</p>
                    </div>
                </div>
                <a href="{{ route('category.show', 'pradesh') }}" class="group inline-flex items-center gap-1.5 rounded-lg border border-stone-200 bg-white px-3.5 py-1.5 text-xs sm:text-sm font-bold text-stone-700 shadow-2xs hover:border-[#c92137] hover:bg-[#c92137] hover:text-white transition-all duration-200">
                    <span>सबै प्रदेश समाचार</span>
                    <svg class="h-4 w-4 transition-transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <!-- Modern Province Selection Tabs Bar -->
            <div class="mb-5 flex gap-2 overflow-x-auto pb-2 pt-1 no-scrollbar" role="tablist">
                @foreach($provincesList as $prov)
                    <button type="button"
                        @click="active = '{{ $prov }}'"
                        :class="active === '{{ $prov }}'
                            ? 'bg-[#c92137] text-white shadow-md shadow-red-600/25 ring-2 ring-red-400/40 font-black scale-[1.02]'
                            : 'bg-white text-stone-700 border border-stone-200/90 hover:bg-stone-50 hover:text-stone-900 hover:border-stone-300 font-bold shadow-2xs'"
                        class="shrink-0 rounded-xl px-4 py-2.5 text-xs sm:text-sm transition-all duration-200 flex items-center gap-1.5 cursor-pointer">
                        <span x-show="active === '{{ $prov }}'" class="text-xs">📍</span>
                        <span>{{ $prov }}</span>
                    </button>
                @endforeach
            </div>

            <!-- Province Articles Container -->
            @foreach($provincesList as $prov)
                <div x-cloak x-show="active === '{{ $prov }}'" class="grid gap-6 lg:grid-cols-12 items-start">
                    @php
                        $pStories = $provinceStories[$prov] ?? collect();
                        $pLead = $pStories->first();
                        $pRest = $pStories->slice(1, 3);
                    @endphp

                    @if($pLead)
                        @php $leadCat = $pLead->categories->first() ?? $pLead->category; @endphp
                        <article class="lg:col-span-6 group">
                            <a href="{{ route('articles.show', $pLead->slug) }}" class="relative block aspect-[16/10] rounded-2xl overflow-hidden bg-stone-900 shadow-md group-hover:shadow-2xl transition-all duration-300">
                                <img src="{{ $pLead->image_url }}" alt="{{ $pLead->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/50 via-40% to-transparent pointer-events-none"></div>

                                <!-- Top Badges -->
                                <div class="absolute top-4 left-4 z-10 flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 rounded-lg bg-[#c92137] px-3 py-1 text-xs font-black text-white shadow-md">
                                        📍 {{ $prov }}
                                    </span>
                                    @if($leadCat)
                                        <span class="inline-flex items-center rounded-lg bg-black/60 backdrop-blur-md px-2.5 py-1 text-xs font-bold text-white border border-white/20">
                                            {{ $leadCat->name }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Bottom Content -->
                                <div class="absolute inset-x-0 bottom-0 z-10 p-5 sm:p-7">
                                    <h3 class="text-xl sm:text-2xl font-black leading-snug text-white group-hover:text-red-100 transition-colors drop-shadow-sm line-clamp-2">
                                        {{ $pLead->title }}
                                    </h3>
                                </div>
                            </a>
                        </article>
                    @endif

                    <div class="lg:col-span-6 space-y-1.5">
                        @forelse($pRest as $story)
                            @php $storyCat = $story->categories->first() ?? $story->category; @endphp
                            <article class="grid grid-cols-[6.5rem_1fr] sm:grid-cols-[8rem_1fr] items-start gap-3.5 p-2 sm:p-2.5 -mx-2 sm:-mx-2.5 rounded-xl border border-transparent hover:border-stone-200/70 hover:bg-stone-50/80 transition-all duration-150 group">
                                <a href="{{ route('articles.show', $story->slug) }}" class="relative block aspect-[4/3] rounded-lg overflow-hidden bg-stone-100 shadow-2xs ring-1 ring-stone-200/80 group-hover:ring-red-200 group-hover:shadow-sm transition-all">
                                    <img src="{{ $story->image_url }}" alt="{{ $story->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                                </a>
                                <div class="min-w-0">
                                    @if($storyCat)
                                        <span class="text-xs font-black text-[#c92137] tracking-wider uppercase">{{ $storyCat->name }}</span>
                                    @endif
                                    <h4 class="mt-0.5">
                                        <a href="{{ route('articles.show', $story->slug) }}" class="block text-sm sm:text-base font-extrabold leading-snug text-stone-900 group-hover:text-[#c92137] line-clamp-2 transition-colors">
                                            {{ $story->title }}
                                        </a>
                                    </h4>
                                    @if($story->excerpt)
                                        <p class="mt-1 text-xs text-stone-600 font-normal line-clamp-2 leading-relaxed">{{ $story->excerpt }}</p>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <p class="text-sm font-medium text-stone-500 py-6">यस प्रदेशमा थप समाचार उपलब्ध छैन।</p>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </section>

        <!-- Section: Middle Banner Ad (गृहपृष्ठ खण्ड ब्यानर) -->
        <aside class="my-10" aria-label="विज्ञापन">
            <x-ad-banner placement="home_middle" class="aspect-[97/16] sm:aspect-[97/12] max-w-[970px] mx-auto" />
        </aside>

                <!-- Section: समाज (Society - Pro Modern Magazine) -->
        <section class="mb-10 rounded-2xl border border-stone-200/80 bg-linear-to-b from-stone-50/50 to-white p-5 sm:p-6 shadow-xs" aria-labelledby="society-heading">
            <div class="mb-5 flex items-center justify-between border-b border-stone-200 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="inline-block h-6 w-1.5 rounded-full bg-blue-600"></span>
                    <h2 id="society-heading" class="text-xl sm:text-2xl font-black text-stone-900 tracking-tight">समाज</h2>
                </div>
                <a href="{{ route('category.show', 'society') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-blue-600 hover:text-blue-700 transition">
                    <span>सबै समाज</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($societyArticles as $art)
                    @php $sCat = $art->categories->first() ?? $art->category; @endphp
                    <article class="flex flex-col rounded-2xl border border-stone-200/90 bg-white shadow-2xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
                        <a href="{{ route('articles.show', $art->slug) }}" class="relative block aspect-[16/10] overflow-hidden bg-stone-100">
                            <img src="{{ $art->image_url }}" alt="{{ $art->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="absolute top-3 left-3 inline-flex items-center gap-1 rounded-md bg-blue-600 px-2.5 py-1 text-[11px] font-black text-white shadow-md">
                                👥 {{ $sCat ? $sCat->name : 'समाज' }}
                            </span>
                        </a>

                        <div class="p-4 sm:p-5 flex flex-col flex-1">
                            <h3 class="text-base sm:text-lg font-black leading-snug text-stone-900 group-hover:text-blue-600 transition-colors line-clamp-2">
                                <a href="{{ route('articles.show', $art->slug) }}" class="block">
                                    {{ $art->title }}
                                </a>
                            </h3>
                            @if($art->excerpt)
                                <p class="mt-2 text-xs sm:text-sm text-stone-600 line-clamp-2 leading-relaxed font-normal">
                                    {{ $art->excerpt }}
                                </p>
                            @endif

                            <div class="mt-auto pt-3 flex items-center justify-between text-[11px] font-medium text-stone-600 border-t border-stone-100">
                                <span class="flex items-center gap-1 text-stone-700 font-semibold truncate max-w-[130px]">
                                    <svg class="h-3 w-3 shrink-0 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ $art->author ?: 'कपिलवस्तु दर्पण' }}
                                </span>
                                <time class="flex items-center gap-1 text-stone-600 shrink-0 font-medium">
                                    <svg class="h-3 w-3 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $art->published_at?->diffForHumans() }}
                                </time>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
        <!-- Section: अपराध-सुरक्षा (Crime & Security - Magazine Hero Style Matching Sample) -->
        <section class="mb-10 rounded-2xl border border-stone-200/80 bg-linear-to-b from-stone-50/40 to-white p-5 sm:p-6 shadow-xs" aria-labelledby="security-heading">
            <div class="mb-5 flex items-center justify-between border-b border-stone-200 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="inline-block h-6 w-1.5 rounded-full bg-slate-800"></span>
                    <h2 id="security-heading" class="text-xl sm:text-2xl font-black text-stone-900 tracking-tight">अपराध र सुरक्षा</h2>
                </div>
                <a href="{{ route('category.show', 'crime-security') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-slate-800 hover:text-red-600 transition">
                    <span>सबै अपराध र सुरक्षा</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @php
                $secLead = $securityArticles->first();
                $secSecondary = $securityArticles->slice(1, 4);
            @endphp

            <div class="grid gap-5 lg:grid-cols-12 items-stretch">
                @if($secLead)
                    <article class="lg:col-span-6 group flex flex-col">
                        <a href="{{ route('articles.show', $secLead->slug) }}" class="relative block w-full h-full min-h-[300px] sm:min-h-[360px] lg:min-h-full rounded-2xl overflow-hidden bg-stone-900 shadow-2xs group-hover:shadow-xl transition-all duration-300">
                            <img src="{{ $secLead->image_url }}" alt="{{ $secLead->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/45 via-45% to-transparent pointer-events-none"></div>

                            <div class="absolute top-4 left-4 z-10">
                                <span class="inline-flex items-center gap-1 rounded-md bg-slate-900/90 text-white px-2.5 py-1 text-[11px] font-black shadow-md border border-white/20">
                                    🚨 अपराध-सुरक्षा
                                </span>
                            </div>

                            <div class="absolute inset-x-0 bottom-0 z-10 p-5 sm:p-7">
                                <h3 class="text-xl sm:text-2xl lg:text-2xl font-black leading-snug text-white group-hover:text-red-200 transition-colors drop-shadow-sm line-clamp-3">
                                    {{ $secLead->title }}
                                </h3>
                            </div>
                        </a>
                    </article>
                @endif

                <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    @foreach($secSecondary as $art)
                        <article class="group flex flex-col">
                            <a href="{{ route('articles.show', $art->slug) }}" class="relative block aspect-[16/10] rounded-xl overflow-hidden bg-stone-100 shadow-2xs group-hover:shadow-md transition">
                                <img src="{{ $art->image_url }}" alt="{{ $art->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105">
                            </a>
                            <h3 class="mt-2.5">
                                <a href="{{ route('articles.show', $art->slug) }}" class="block text-sm sm:text-base font-extrabold leading-snug text-stone-900 group-hover:text-red-600 line-clamp-2 transition-colors">
                                    {{ $art->title }}
                                </a>
                            </h3>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
<!-- Section: खेलकुद (Sports - Magazine Hero Style Matching Sample) -->
        <section class="mb-10 rounded-2xl border border-stone-200/80 bg-linear-to-b from-stone-50/40 to-white p-5 sm:p-6 shadow-xs" aria-labelledby="sports-heading">
            <div class="mb-5 flex items-center justify-between border-b border-stone-200 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="inline-block h-6 w-1.5 rounded-full bg-[#c92137]"></span>
                    <h2 id="sports-heading" class="text-xl sm:text-2xl font-black text-stone-900 tracking-tight">खेलकुद</h2>
                </div>
                <a href="{{ route('category.show', 'sports') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-[#c92137] hover:text-[#9e1526] transition">
                    <span>सबै खेलकुद</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @php
                $sportsLead = $sportsArticles->first();
                $sportsSecondary = $sportsArticles->slice(1, 4);
            @endphp

            <div class="grid gap-5 lg:grid-cols-12 items-stretch">
                @if($sportsLead)
                    <article class="lg:col-span-6 group flex flex-col">
                        <a href="{{ route('articles.show', $sportsLead->slug) }}" class="relative block w-full h-full min-h-[300px] sm:min-h-[360px] lg:min-h-full rounded-2xl overflow-hidden bg-stone-900 shadow-2xs group-hover:shadow-xl transition-all duration-300">
                            <img src="{{ $sportsLead->image_url }}" alt="{{ $sportsLead->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/95 via-black/45 via-45% to-transparent pointer-events-none"></div>

                            <div class="absolute top-4 left-4 z-10">
                                <span class="inline-flex items-center gap-1 rounded-md bg-[#c92137] px-2.5 py-1 text-[11px] font-black text-white shadow-md">
                                    🏆 खेलकुद
                                </span>
                            </div>

                            <div class="absolute inset-x-0 bottom-0 z-10 p-5 sm:p-7">
                                <h3 class="text-xl sm:text-2xl lg:text-2xl font-black leading-snug text-white group-hover:text-red-200 transition-colors drop-shadow-sm line-clamp-3">
                                    {{ $sportsLead->title }}
                                </h3>
                            </div>
                        </a>
                    </article>
                @endif

                <div class="lg:col-span-6 grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    @foreach($sportsSecondary as $art)
                        <article class="group flex flex-col">
                            <a href="{{ route('articles.show', $art->slug) }}" class="relative block aspect-[16/10] rounded-xl overflow-hidden bg-stone-100 shadow-2xs group-hover:shadow-md transition">
                                <img src="{{ $art->image_url }}" alt="{{ $art->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105">
                            </a>
                            <h3 class="mt-2.5">
                                <a href="{{ route('articles.show', $art->slug) }}" class="block text-sm sm:text-base font-extrabold leading-snug text-stone-900 group-hover:text-[#c92137] line-clamp-2 transition-colors">
                                    {{ $art->title }}
                                </a>
                            </h3>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Section 9: विचार (Opinions & Editorials - Pro Modern Magazine) -->
        <section class="mb-10 rounded-2xl border border-stone-200/80 bg-linear-to-b from-stone-50/50 to-white p-5 sm:p-6 shadow-xs" aria-labelledby="opinion-heading">
            <div class="mb-5 flex items-center justify-between border-b border-stone-200 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="inline-block h-6 w-1.5 rounded-full bg-[#c92137]"></span>
                    <h2 id="opinion-heading" class="text-xl sm:text-2xl font-black text-stone-900 tracking-tight">विचार र सम्पादकीय</h2>
                </div>
                <a href="{{ route('category.show', 'opinion') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-[#c92137] hover:text-[#9e1526] transition">
                    <span>सबै विचार</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($opinionArticles as $art)
                    <article class="flex flex-col rounded-2xl border border-stone-200/90 bg-white p-5 shadow-2xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex items-center gap-1 rounded-md bg-red-50 text-[#c92137] px-2.5 py-1 text-[11px] font-black border border-red-200/60">
                                ✍️ विचार
                            </span>
                            <svg class="h-6 w-6 text-stone-200 group-hover:text-red-300 transition-colors" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                        </div>
                        <h3 class="mt-3.5">
                            <a href="{{ route('articles.show', $art->slug) }}" class="block text-base sm:text-lg font-black leading-snug text-stone-900 group-hover:text-[#c92137] line-clamp-3 transition-colors">
                                {{ $art->title }}
                            </a>
                        </h3>
                        @if($art->excerpt)
                            <p class="mt-2.5 text-xs sm:text-sm text-stone-600 line-clamp-3 leading-relaxed font-normal">
                                {{ $art->excerpt }}
                            </p>
                        @endif
                        <div class="mt-auto pt-4 flex items-center gap-3 border-t border-stone-100">
                            <div class="h-10 w-10 shrink-0 rounded-full bg-linear-to-tr from-[#c92137] to-rose-500 text-white font-black text-sm flex items-center justify-center shadow-xs ring-2 ring-red-100">
                                {{ mb_substr($art->author ?: 'वि', 0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <span class="block text-xs sm:text-sm font-black text-stone-900 truncate group-hover:text-[#c92137] transition-colors">
                                    {{ $art->author ?: 'सम्पादक मण्डल' }}
                                </span>
                                <span class="block text-[11px] font-medium text-stone-600 truncate">
                                    स्तम्भकार / विश्लेषक
                                </span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

                <!-- Section: स्वास्थ्य (Health - Pro Modern Magazine) -->
        <section class="mb-10 rounded-2xl border border-stone-200/80 bg-linear-to-b from-stone-50/50 to-white p-5 sm:p-6 shadow-xs" aria-labelledby="health-heading">
            <div class="mb-5 flex items-center justify-between border-b border-stone-200 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="inline-block h-6 w-1.5 rounded-full bg-teal-600"></span>
                    <h2 id="health-heading" class="text-xl sm:text-2xl font-black text-stone-900 tracking-tight">स्वास्थ्य</h2>
                </div>
                <a href="{{ route('category.show', 'health') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-teal-600 hover:text-teal-700 transition">
                    <span>सबै स्वास्थ्य</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($healthArticles as $art)
                    @php $hCat = $art->categories->first() ?? $art->category; @endphp
                    <article class="flex flex-col rounded-2xl border border-stone-200/90 bg-white shadow-2xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden group">
                        <a href="{{ route('articles.show', $art->slug) }}" class="relative block aspect-[16/10] overflow-hidden bg-stone-100">
                            <img src="{{ $art->image_url }}" alt="{{ $art->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover transition-transform duration-500 ease-out group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <span class="absolute top-3 left-3 inline-flex items-center gap-1 rounded-md bg-teal-600 px-2.5 py-1 text-[11px] font-black text-white shadow-md">
                                🩺 {{ $hCat ? $hCat->name : 'स्वास्थ्य' }}
                            </span>
                        </a>

                        <div class="p-4 sm:p-5 flex flex-col flex-1">
                            <h3 class="text-base sm:text-lg font-black leading-snug text-stone-900 group-hover:text-teal-600 transition-colors line-clamp-2">
                                <a href="{{ route('articles.show', $art->slug) }}" class="block">
                                    {{ $art->title }}
                                </a>
                            </h3>
                            @if($art->excerpt)
                                <p class="mt-2 text-xs sm:text-sm text-stone-600 line-clamp-2 leading-relaxed font-normal">
                                    {{ $art->excerpt }}
                                </p>
                            @endif

                            <div class="mt-auto pt-3 flex items-center justify-between text-[11px] font-medium text-stone-600 border-t border-stone-100">
                                <span class="flex items-center gap-1 text-stone-700 font-semibold truncate max-w-[130px]">
                                    <svg class="h-3 w-3 shrink-0 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ $art->author ?: 'कपिलवस्तु दर्पण' }}
                                </span>
                                <time class="flex items-center gap-1 text-stone-600 shrink-0 font-medium">
                                    <svg class="h-3 w-3 text-stone-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $art->published_at?->diffForHumans() }}
                                </time>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
<!-- Section: भिडियो (Video Section - Studio Broadcast Style) -->
        <section class="mb-10 rounded-2xl bg-linear-to-b from-stone-900 via-[#161616] to-[#121212] border border-stone-800/80 p-5 sm:p-7 text-white shadow-xl" aria-labelledby="video-heading">
            <div class="mb-5 flex items-center justify-between border-b border-white/10 pb-3">
                <div class="flex items-center gap-2.5">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-tr from-red-700 to-[#c92137] text-white shadow-lg shadow-red-900/40">
                        <svg class="h-4 w-4 ml-0.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                    </span>
                    <h2 id="video-heading" class="text-xl sm:text-2xl font-black text-white tracking-tight">भिडियो विशेष</h2>
                </div>
                <a href="{{ route('category.show', 'video') }}" class="inline-flex items-center gap-1 text-xs sm:text-sm font-bold text-red-400 hover:text-red-300 transition">
                    <span>सबै भिडियो</span>
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>

            @php
                $vLead = $videoArticles->first();
                $vSecondary = $videoArticles->slice(1, 3);
            @endphp

            <div class="grid gap-6 lg:grid-cols-12 items-start">
                @if($vLead)
                    <article class="lg:col-span-7 group flex flex-col">
                        <a href="{{ route('articles.show', $vLead->slug) }}" class="relative block aspect-video rounded-xl sm:rounded-2xl overflow-hidden bg-stone-950 shadow-2xl">
                            <img src="{{ $vLead->image_url }}" alt="{{ $vLead->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover opacity-85 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/25 to-transparent pointer-events-none"></div>

                            <!-- Top Format Pill -->
                            <div class="absolute top-3.5 left-3.5 z-10">
                                <span class="inline-flex items-center gap-1 rounded-md bg-black/60 backdrop-blur-md px-2.5 py-1 text-xs font-bold text-white border border-white/20">
                                    ▶ विशेष भिडियो
                                </span>
                            </div>

                            <!-- Center Glow Play Button -->
                            <div class="absolute inset-0 grid place-items-center">
                                <span class="grid h-16 w-16 place-items-center rounded-full bg-[#c92137]/95 text-white shadow-2xl backdrop-blur-xs ring-4 ring-white/30 group-hover:scale-110 group-hover:ring-red-400/60 group-hover:bg-[#c92137] transition-all duration-300">
                                    <svg class="h-8 w-8 ml-1 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </span>
                            </div>

                            <!-- Duration Badge -->
                            <div class="absolute bottom-3 right-3 z-10">
                                <span class="rounded-md bg-black/80 backdrop-blur-xs px-2 py-0.5 text-[11px] font-bold text-stone-300">
                                    HD ०५:२४
                                </span>
                            </div>
                        </a>

                        <h3 class="mt-3.5">
                            <a href="{{ route('articles.show', $vLead->slug) }}" class="block text-lg sm:text-xl lg:text-2xl font-black leading-snug text-white group-hover:text-red-400 transition-colors line-clamp-2">
                                {{ $vLead->title }}
                            </a>
                        </h3>
                        @if($vLead->excerpt)
                            <p class="mt-2 text-xs sm:text-sm text-stone-400 line-clamp-2 leading-relaxed font-normal">
                                {{ $vLead->excerpt }}
                            </p>
                        @endif
                    </article>
                @endif

                <div class="lg:col-span-5 flex flex-col justify-between space-y-3">
                    @forelse($vSecondary as $vArt)
                        @php $vCat = $vArt->categories->first() ?? $vArt->category; @endphp
                        <article class="flex items-center gap-3.5 p-2 sm:p-2.5 rounded-xl border border-white/5 bg-white/[0.03] hover:bg-white/[0.08] hover:border-white/10 transition-all duration-200 group">
                            <a href="{{ route('articles.show', $vArt->slug) }}" class="relative block w-28 sm:w-36 shrink-0 aspect-video rounded-lg overflow-hidden bg-stone-950 shadow-md">
                                <img src="{{ $vArt->image_url }}" alt="{{ $vArt->title }}" onerror="this.onerror=null;this.src='{{ asset('images/kapilvastu-darpan-logo.png') }}';" loading="lazy" class="h-full w-full object-cover opacity-85 group-hover:opacity-100 group-hover:scale-105 transition-all duration-300">
                                <div class="absolute inset-0 grid place-items-center">
                                    <span class="grid h-8 w-8 place-items-center rounded-full bg-[#c92137]/90 text-white shadow-md group-hover:scale-110 group-hover:bg-[#c92137] transition">
                                        <svg class="h-4 w-4 ml-0.5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </span>
                                </div>
                            </a>
                            <div class="min-w-0 flex-1">
                                @if($vCat)
                                    <span class="text-[11px] font-black uppercase tracking-wider text-red-400">{{ $vCat->name }}</span>
                                @endif
                                <h4 class="mt-0.5">
                                    <a href="{{ route('articles.show', $vArt->slug) }}" class="block text-xs sm:text-sm font-extrabold leading-snug text-stone-100 group-hover:text-red-400 line-clamp-2 transition-colors">
                                        {{ $vArt->title }}
                                    </a>
                                </h4>
                                <time class="mt-1.5 flex items-center gap-1 text-[11px] font-medium text-stone-400">
                                    <svg class="h-3 w-3 text-stone-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $vArt->published_at?->diffForHumans() }}
                                </time>
                            </div>
                        </article>
                    @empty
                        <p class="text-xs text-stone-500 py-4">थप भिडियोहरू छिट्टै उपलब्ध हुनेछन्।</p>
                    @endforelse
                </div>
            </div>
        </section>

        <!-- Section: राशिफल (Horoscope - Ratopati 12 Zodiacs) -->
        @php
            $rashis = [
                ['slug' => 'mesh', 'name' => 'मेष राशि', 'letters' => 'चु, चे, चो, ला, लि, लु, ले, लो, अ', 'index' => 0],
                ['slug' => 'vrish', 'name' => 'वृष राशि', 'letters' => 'इ, उ, ए, ओ, वा, वि, वु, वे, वो', 'index' => 1],
                ['slug' => 'mithun', 'name' => 'मिथुन राशि', 'letters' => 'का, कि, कु, घ, ङ, छ, के, को, हा', 'index' => 2],
                ['slug' => 'karkat', 'name' => 'कर्कट राशि', 'letters' => 'हि, हु, हे, हो, डा, डि, डु, डे, डो', 'index' => 3],
                ['slug' => 'singha', 'name' => 'सिंह राशि', 'letters' => 'मा, मि, मु, मे, मो, टा, टि, टु, टे', 'index' => 4],
                ['slug' => 'kanya', 'name' => 'कन्या राशि', 'letters' => 'टो, पा, पि, पु, ष, ण, ठ, पे, पो', 'index' => 5],
                ['slug' => 'tula', 'name' => 'तुला राशि', 'letters' => 'रा, रि, रु, रे, रो, ता, ति, तु, ते', 'index' => 6],
                ['slug' => 'vrischik', 'name' => 'वृश्चिक राशि', 'letters' => 'तो, ना, नि, नु, ने, नो, या, यि, यु', 'index' => 7],
                ['slug' => 'dhanu', 'name' => 'धनु राशि', 'letters' => 'ये, यो, भा, भि, भु, धा, फा, ढा, भे', 'index' => 8],
                ['slug' => 'makar', 'name' => 'मकर राशि', 'letters' => 'भो, जा, जि, जु, जे, जो, खि, खु, खे, खो', 'index' => 9],
                ['slug' => 'kumbha', 'name' => 'कुम्भ राशि', 'letters' => 'गु, गे, गो, सा, सि, सु, से, सो, दा', 'index' => 10],
                ['slug' => 'meen', 'name' => 'मीन राशि', 'letters' => 'दि, दु, थ, झ, ञ, दे, दो, चा, चि', 'index' => 11],
            ];
        @endphp
        <section class="mb-10 rounded-xl bg-gradient-to-br from-red-50 via-white to-blue-50 p-6 border border-red-100 shadow-sm" aria-labelledby="rashifal-heading">
            <div class="mb-5 flex items-center justify-between">
                <div class="flex items-baseline gap-3">
                    <h2 id="rashifal-heading" class="text-2xl sm:text-3xl font-extrabold text-[#c92137]">दैनिक राशिफल</h2>
                    <span class="text-xs text-stone-500 font-semibold">शुक्रबार, ३० असोज २०८३</span>
                </div>
                <a href="{{ route('rashifal.show', 'karkat') }}" class="text-xs font-bold text-stone-700 hover:text-[#c92137] transition">विस्तृत राशिफल ↗</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6">
                @foreach($rashis as $r)
                    <a href="{{ route('rashifal.show', $r['slug']) }}" class="group block rounded-lg bg-white p-4 text-center shadow-sm ring-1 ring-red-100 transition hover:-translate-y-1 hover:shadow-md hover:ring-red-300">
                        <span class="rashi-icon rashi-icon--{{ $r['index'] }} group-hover:scale-105 transition-transform" aria-hidden="true"></span>
                        <h3 class="mt-2 text-base font-extrabold text-stone-800 group-hover:text-[#c92137] transition-colors">{{ $r['name'] }}</h3>
                        <p class="mt-1 text-[11px] text-stone-500 truncate" title="{{ $r['letters'] }}">{{ $r['letters'] }}</p>
                    </a>
                @endforeach
            </div>
        </section>

        <!-- Section: Footer Top Banner Ad (फुटर माथिल्लो ब्यानर) -->
        <aside class="my-10" aria-label="विज्ञापन">
            <x-ad-banner placement="footer" class="aspect-[97/16] sm:aspect-[97/12] max-w-[970px] mx-auto" />
        </aside>

    </main>

</div>
