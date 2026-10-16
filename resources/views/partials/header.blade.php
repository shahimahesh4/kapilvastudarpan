<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:bg-[#c92137] focus:text-white focus:px-4 focus:py-2">मुख्य सामग्रीमा जानुहोस्</a>

<!-- Top Info Bar -->
<div class="border-b border-stone-200 bg-stone-50 text-xs text-stone-600">
    <div class="w-full flex items-center justify-between px-4 sm:px-6 lg:px-8 py-2">
        <div class="flex items-center gap-2">
            <span class="inline-block h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <p class="font-medium">
                शुक्रबार, ३० असोज २०८३ <span class="mx-2 text-stone-300">|</span> कपिलवस्तु, लुम्बिनी प्रदेश, नेपाल
            </p>
        </div>
        <div class="flex items-center gap-3.5">
            <!-- Top Header Social Media Icons -->
            <div class="flex items-center gap-1.5 border-r border-stone-200 pr-3.5">
                <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="grid h-6 w-6 place-items-center rounded-full bg-stone-200/80 text-stone-600 hover:bg-[#1877F2] hover:text-white transition shadow-xs" aria-label="Facebook" title="Facebook">
                    <svg viewBox="0 0 24 24" class="h-3 w-3 fill-current" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="https://x.com" target="_blank" rel="noopener noreferrer" class="grid h-6 w-6 place-items-center rounded-full bg-stone-200/80 text-stone-600 hover:bg-black hover:text-white transition shadow-xs" aria-label="X (Twitter)" title="X">
                    <svg viewBox="0 0 24 24" class="h-2.5 w-2.5 fill-current" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="https://youtube.com" target="_blank" rel="noopener noreferrer" class="grid h-6 w-6 place-items-center rounded-full bg-stone-200/80 text-stone-600 hover:bg-[#FF0000] hover:text-white transition shadow-xs" aria-label="YouTube" title="YouTube">
                    <svg viewBox="0 0 24 24" class="h-3 w-3 fill-current" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
                <a href="https://tiktok.com" target="_blank" rel="noopener noreferrer" class="grid h-6 w-6 place-items-center rounded-full bg-stone-200/80 text-stone-600 hover:bg-black hover:text-white transition shadow-xs" aria-label="TikTok" title="TikTok">
                    <svg viewBox="0 0 24 24" class="h-3 w-3 fill-current" aria-hidden="true"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.96-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                </a>
            </div>

            <a href="#contact-heading" class="font-bold hover:text-[#c92137] transition">सम्पर्क</a>
            <button type="button" @click="darkMode = !darkMode; setTheme()" class="theme-toggle" :class="darkMode ? 'theme-toggle--dark' : ''" :aria-pressed="darkMode ? 'true' : 'false'" :aria-label="darkMode ? 'लाइट मोडमा फर्कनुहोस्' : 'डार्क मोड खोल्नुहोस्'" title="रङ मोड बदल्नुहोस्">
                <span class="theme-toggle__sun" aria-hidden="true">☀</span>
                <span class="theme-toggle__moon" aria-hidden="true">☾</span>
            </button>
        </div>
    </div>
</div>

<!-- Main Header Section -->
<header class="bg-white">
    <div class="w-full flex items-center justify-between gap-5 px-4 py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="shrink-0 flex items-center gap-3" aria-label="कपिलवस्तु दर्पण गृहपृष्ठ">
            <img src="{{ asset('images/kapilvastu-darpan-logo.png') }}" alt="कपिलवस्तु दर्पण" class="h-14 sm:h-20 w-auto object-contain">
        </a>

        <!-- Header Banner Ad Space -->
        <x-ad-banner placement="header" class="hidden md:flex h-20 w-full max-w-2xl" />

        <!-- Mobile Hamburger Button -->
        <button @click="sidebarOpen = true" type="button" class="flex h-11 w-12 flex-col items-center justify-center gap-1.5 rounded bg-[#2e5cb0] hover:bg-[#244b92] transition md:hidden shadow-sm p-2 cursor-pointer" :aria-expanded="sidebarOpen ? 'true' : 'false'" aria-controls="site-sidebar" aria-label="मेनु खोल्नुहोस्">
            <span class="h-[3.5px] w-6 rounded-sm bg-white shadow-sm"></span>
            <span class="h-[3.5px] w-6 rounded-sm bg-white shadow-sm"></span>
            <span class="h-[3.5px] w-6 rounded-sm bg-white shadow-sm"></span>
        </button>
    </div>

    <!-- Sticky Primary Navigation Bar -->
    <div x-ref="menuAnchor" class="hidden h-[4.2rem] md:block">
        <div class="inset-x-0 top-0 z-40 bg-[#2e5cb0] text-white transition-all shadow-md" :class="menuPinned ? 'fixed shadow-xl' : 'relative'">
            <nav id="primary-nav" aria-label="मुख्य नेभिगेसन">
                <div class="w-full flex items-stretch h-[4.2rem] pl-4 sm:pl-6 lg:pl-8 pr-0">
                    <a href="{{ route('home') }}" class="grid h-[4.2rem] w-[4.2rem] shrink-0 place-items-center border-x border-white/10 text-white hover:bg-white/15 transition" aria-label="गृहपृष्ठ">
                        <svg viewBox="0 0 24 24" class="h-6 w-6 fill-current" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V10Z"/></svg>
                    </a>

                    <!-- Dynamic Header Menu Items (Dropdown & Mega Menu Support) -->
                    <ul class="flex min-w-0 flex-1 items-stretch h-full text-base font-extrabold text-white">
                        @if(isset($headerMenuItems) && $headerMenuItems->isNotEmpty())
                            @foreach ($headerMenuItems as $item)
                                @if ($item->is_mega_menu)
                                    <!-- Mega Menu Dropdown (Matches Screenshot) -->
                                    <li class="static flex items-stretch h-full" x-data="{ open: false, currentProvince: '{{ $item->children->first()?->title ?? 'कोशी प्रदेश' }}' }" @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false">
                                        <a href="{{ $item->link_url }}" target="{{ $item->target }}" class="flex items-center gap-1.5 whitespace-nowrap px-4 h-full font-extrabold transition duration-150 cursor-pointer" :class="open ? 'bg-white text-[#c92137] shadow-sm' : 'text-white hover:bg-white/15'">
                                            <span>{{ $item->title }}</span>
                                            <svg viewBox="0 0 20 20" class="h-3.5 w-3.5 fill-none stroke-current stroke-[2.4] transition-transform duration-200" :class="open ? 'rotate-180 text-[#c92137]' : 'text-white/80'" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" />
                                            </svg>
                                        </a>
                                        <!-- Full-Width Mega Menu Dropdown Panel -->
                                        <div x-cloak x-show="open"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 translate-y-1"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 translate-y-1"
                                             class="absolute inset-x-0 left-0 right-0 top-full z-50 bg-white text-stone-900 border-b border-stone-200 shadow-[0_20px_45px_rgba(0,0,0,0.18)]">
                                            <div class="w-full max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-6">
                                                <div class="flex gap-8 items-start">
                                                    <!-- Left Column: Provinces List -->
                                                    <div class="w-52 shrink-0 border-r border-stone-200 pr-4 space-y-0.5">
                                                        @foreach ($item->children as $child)
                                                            <a href="{{ $child->link_url }}"
                                                               target="{{ $child->target }}"
                                                               @mouseenter="currentProvince = '{{ $child->title }}'"
                                                               class="block px-3.5 py-1.5 text-[17px] font-bold rounded-lg transition duration-150 cursor-pointer"
                                                               :class="currentProvince === '{{ $child->title }}' ? 'text-[#c92137] bg-red-50/70 font-extrabold shadow-xs' : 'text-stone-700 hover:text-[#c92137] hover:bg-stone-50'">
                                                                {{ $child->title }}
                                                            </a>
                                                        @endforeach
                                                    </div>

                                                    <!-- Right Section: 4-Column News Grid -->
                                                    <div class="flex-1 min-w-0">
                                                        @foreach ($provincesList as $provName)
                                                            <div x-cloak x-show="currentProvince === '{{ $provName }}'"
                                                                 x-transition:enter="transition ease-out duration-200"
                                                                 x-transition:enter-start="opacity-0 translate-y-1"
                                                                 x-transition:enter-end="opacity-100 translate-y-0">
                                                                <div class="grid grid-cols-4 gap-5">
                                                                    @foreach ($provinceStories[$provName] ?? [] as $story)
                                                                        <article class="group flex flex-col">
                                                                            <a href="{{ route('articles.show', $story->slug) }}" class="block aspect-[16/10] rounded-lg overflow-hidden bg-stone-100 shadow-sm">
                                                                                <img src="{{ $story->image_url }}" alt="{{ $story->title }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                                                                            </a>
                                                                            <h4 class="mt-3 text-[16px] sm:text-[17px] font-bold leading-snug text-stone-900 group-hover:text-[#c92137] hover:underline line-clamp-2 transition">
                                                                                <a href="{{ route('articles.show', $story->slug) }}">
                                                                                    {{ $story->title }}
                                                                                </a>
                                                                            </h4>
                                                                        </article>
                                                                    @endforeach
                                                                </div>
                                                                <!-- थप समाचार ↗ Link on Bottom Right -->
                                                                <div class="mt-4 flex justify-end border-t border-stone-100 pt-3">
                                                                    <a href="{{ route('category.show', 'pradesh') }}" class="inline-flex items-center gap-1.5 text-sm font-extrabold text-stone-600 hover:text-[#c92137] transition group">
                                                                        <span>थप समाचार</span>
                                                                        <span class="group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform">↗</span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @elseif ($item->children->isNotEmpty())
                                    <!-- Regular Vertical Dropdown (समाचार, अर्थतन्त्र, etc.) -->
                                    <li class="relative flex items-stretch h-full" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false">
                                        <a href="{{ $item->link_url }}" target="{{ $item->target }}" class="flex items-center gap-1.5 whitespace-nowrap px-4 h-full font-extrabold transition duration-150 cursor-pointer" :class="open ? 'bg-white text-[#c92137] shadow-sm' : 'text-white hover:bg-white/15'">
                                            <span>{{ $item->title }}</span>
                                            <svg viewBox="0 0 20 20" class="h-3.5 w-3.5 fill-none stroke-current stroke-[2.4] transition-transform duration-200" :class="open ? 'rotate-180 text-[#c92137]' : 'text-white/80'" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 7.5 5 5 5-5" />
                                            </svg>
                                        </a>
                                        <!-- Sub-Menu Dropdown Panel (Exact Match to User Screenshot) -->
                                        <div x-cloak x-show="open"
                                             x-transition:enter="transition ease-out duration-150"
                                             x-transition:enter-start="opacity-0 translate-y-1"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-100"
                                             x-transition:leave-start="opacity-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 translate-y-1"
                                             class="absolute left-0 top-full z-50 min-w-[220px] rounded-b-2xl bg-white py-3 shadow-[0_16px_36px_rgba(0,0,0,0.16)] border border-stone-100 border-t-0">
                                            <div class="px-1.5 space-y-0.5">
                                                @foreach ($item->children as $child)
                                                    <a href="{{ $child->link_url }}" target="{{ $child->target }}" class="block px-5 py-2.5 text-[16px] font-bold text-stone-800 hover:text-[#c92137] hover:bg-red-50/60 rounded-lg transition duration-150 whitespace-nowrap">
                                                        {{ $child->title }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </li>
                                @else
                                    <li class="flex items-stretch h-full">
                                        <a href="{{ $item->link_url }}" target="{{ $item->target }}" class="flex items-center gap-1 whitespace-nowrap px-3.5 h-full hover:bg-white/15 transition">
                                            {{ $item->title }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            @foreach ($categories as $cat)
                                <li class="flex items-stretch h-full">
                                    <a href="{{ route('category.show', $cat->slug) }}" class="flex items-center gap-1 whitespace-nowrap px-3.5 h-full hover:bg-white/15 transition">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>

                    <!-- Right Actions: Flash News + Account + Search + Sidebar Menu -->
                    <div class="ml-auto flex shrink-0 items-center gap-2.5">
                        <button type="button" @click="flashOpen = !flashOpen" class="relative flex min-h-10 items-center gap-2 rounded bg-white px-3.5 font-bold text-[#172b4d] shadow-sm hover:bg-blue-50 transition cursor-pointer" :aria-expanded="flashOpen ? 'true' : 'false'" aria-controls="flash-news-panel">
                            <span class="grid h-3 w-3 place-items-center rounded-full bg-red-600 animate-ping"></span>
                            <span class="text-sm">Flash News</span>
                            <span class="absolute -left-2 -top-2 grid h-5 w-5 place-items-center rounded-full bg-[#c92137] text-[11px] font-extrabold text-white">{{ count($flashNews ?? []) }}</span>
                        </button>

                        <div class="flex items-center gap-2 border-l border-white/20 pl-2.5">
                            <!-- User Account Button -->
                            <button type="button" @click="accountOpen = !accountOpen" class="grid h-9 w-9 place-items-center rounded-full border border-white/70 text-white hover:bg-white hover:text-[#2e5cb0] transition cursor-pointer" aria-label="प्रयोगकर्ता खाता" title="प्रयोगकर्ता खाता">
                                <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true"><path d="M12 12a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Zm0 2c-4.5 0-8 2.3-8 5.2V21h16v-1.8C20 16.3 16.5 14 12 14Z"/></svg>
                            </button>

                            <!-- Functional Popup Search Button -->
                            <button type="button" @click="searchOpen = true; $nextTick(() => $refs.modalSearchInput?.focus())" class="grid h-9 w-9 place-items-center rounded-full bg-white text-[#172b4d] shadow-sm hover:bg-blue-50 transition cursor-pointer" aria-label="समाचार खोज्नुहोस्" title="समाचार खोज्नुहोस्">
                                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-none stroke-current stroke-[2.5]" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/></svg>
                            </button>

                            <!-- Slide Menu Hamburger Button (Matches User Screenshot) -->
                            <button type="button" @click="sidebarOpen = true" class="flex h-[4.2rem] w-14 shrink-0 flex-col items-center justify-center gap-1.5 bg-[#244b92] hover:bg-[#1a3a78] border-l border-white/20 transition cursor-pointer px-3 ml-1" aria-label="साइडबार मेनु खोल्नुहोस्" title="मुख्य मेनु खोल्नुहोस्">
                                <span class="h-[3.5px] w-6 rounded-sm bg-white shadow-sm"></span>
                                <span class="h-[3.5px] w-6 rounded-sm bg-white shadow-sm"></span>
                                <span class="h-[3.5px] w-6 rounded-sm bg-white shadow-sm"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Flash News Dropdown Drawer -->
            <section id="flash-news-panel" x-cloak x-show="flashOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-y-5 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-5 opacity-0" class="absolute inset-x-0 top-full z-40 border-b border-stone-200 bg-white text-stone-900 shadow-2xl" aria-label="फ्ल्यास समाचार">
                <div class="relative w-full px-4 py-6 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between pb-4 border-b border-stone-200 mb-5">
                        <h3 class="text-xl font-extrabold text-[#c92137] flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full bg-red-600 animate-ping"></span> फ्ल्यास समाचार (Flash Updates)
                        </h3>
                        <button type="button" @click="flashOpen = false" class="grid h-8 w-8 place-items-center rounded-full bg-stone-200 text-lg font-bold text-stone-700 hover:bg-[#c92137] hover:text-white transition" aria-label="फ्ल्यास समाचार बन्द गर्नुहोस्">×</button>
                    </div>
                    <div class="grid gap-x-6 gap-y-5 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($flashNews ?? [] as $flash)
                            <article class="grid grid-cols-[6.5rem_1fr] items-start gap-3 group">
                                <a href="{{ route('articles.show', $flash->slug) }}" class="block aspect-[4/3] rounded overflow-hidden bg-stone-100">
                                    <img src="{{ $flash->image_url }}" alt="{{ $flash->title }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                                </a>
                                <div>
                                    @php $flashCat = $flash->categories->first() ?? $flash->category; @endphp
                                    @if($flashCat)
                                        <a href="{{ route('category.show', $flashCat->slug) }}" class="text-xs font-bold text-[#c92137] hover:underline">{{ $flashCat->name }}</a>
                                    @endif
                                    <a href="{{ route('articles.show', $flash->slug) }}" class="mt-1 block text-base font-bold leading-snug hover:text-[#c92137] hover:underline line-clamp-2">
                                        {{ $flash->title }}
                                    </a>
                                    <time class="mt-1 block text-[11px] text-stone-400">{{ $flash->published_at?->diffForHumans() }}</time>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </section>
        </div>
    </div>
</header>

<!-- Breaking News Highlights Bar -->
@if(isset($breakingNews) && $breakingNews->isNotEmpty())
    <section class="border-b border-sky-100 bg-[#effaff]" aria-label="समाचार हाइलाइट्स" x-data="{
        current: 0,
        items: {{ Js::from($breakingNews->map(fn($item) => ['title' => $item->title, 'url' => route('articles.show', $item->slug)])) }},
        init() {
            if (this.items.length > 1) {
                setInterval(() => this.current = (this.current + 1) % this.items.length, 5000);
            }
        }
    }">
        <div class="mx-auto max-w-[1280px] flex items-center gap-3 px-4 py-2 sm:px-6 lg:px-8">
            <span class="shrink-0 rounded bg-[#c92137] px-3 py-1 text-xs font-extrabold text-white flex items-center gap-1.5 shadow-sm">
                <span class="h-2 w-2 rounded-full bg-white animate-pulse"></span> ब्रेकिङ न्युज
            </span>
            <div class="relative h-7 min-w-0 flex-1 overflow-hidden" aria-live="polite">
                <template x-for="(item, index) in items" :key="index">
                    <a :href="item.url" x-cloak x-show="current === index" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="-translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0" class="absolute inset-0 flex items-center truncate text-sm font-bold text-stone-800 hover:text-[#c92137] hover:underline" x-text="item.title"></a>
                </template>
            </div>
            <div class="ml-auto hidden shrink-0 items-center gap-1 sm:flex">
                <button type="button" @click="current = (current - 1 + items.length) % items.length" class="grid h-7 w-7 place-items-center rounded border border-sky-200 bg-white text-base leading-none text-stone-700 hover:border-[#c92137] hover:text-[#c92137]" aria-label="अघिल्लो समाचार">‹</button>
                <button type="button" @click="current = (current + 1) % items.length" class="grid h-7 w-7 place-items-center rounded border border-sky-200 bg-white text-base leading-none text-stone-700 hover:border-[#c92137] hover:text-[#c92137]" aria-label="अर्को समाचार">›</button>
            </div>
        </div>
    </section>
@endif

<!-- Mobile / Drawer Sidebar Dialog -->
<div x-cloak x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50" aria-label="साइट मेनु">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="sidebarOpen = false"></div>
    <aside id="site-sidebar" class="relative flex h-full w-[min(88vw,23rem)] flex-col overflow-y-auto bg-white shadow-2xl transition-transform duration-300 ease-out" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" role="dialog" aria-modal="true" aria-label="मुख्य मेनु">
        <div class="flex items-center justify-between border-b border-stone-200 p-5 bg-stone-50">
            <a href="{{ route('home') }}" @click="sidebarOpen = false">
                <img src="{{ asset('images/kapilvastu-darpan-logo.png') }}" alt="कपिलवस्तु दर्पण" class="h-14 w-auto">
            </a>
            <button type="button" @click="sidebarOpen = false" class="grid h-10 w-10 place-items-center rounded-full bg-[#c92137] text-xl font-bold text-white hover:bg-red-800 transition" aria-label="मेनु बन्द गर्नुहोस्">×</button>
        </div>

        <!-- Search Form in Drawer -->
        <div class="p-4 border-b border-stone-200">
            <form action="{{ route('search') }}" method="GET" class="flex rounded border border-stone-300 overflow-hidden">
                <input name="q" type="search" placeholder="समाचार खोज्नुहोस्..." value="{{ request('q') }}" class="min-h-10 min-w-0 flex-1 px-3 text-sm focus:outline-none" required>
                <button type="submit" class="bg-[#c92137] px-4 text-sm font-bold text-white hover:bg-red-800 transition">खोज्नुहोस्</button>
            </form>
        </div>

        <nav class="flex-1 p-3" aria-label="साइडबार नेभिगेसन">
            <ul class="divide-y divide-stone-100">
                @if(isset($headerMenuItems) && $headerMenuItems->isNotEmpty())
                    @foreach ($headerMenuItems as $item)
                        @if ($item->children->isNotEmpty())
                            <li x-data="{ expanded: false }" class="py-1">
                                <div class="flex items-center justify-between px-4 py-3 text-base font-bold text-stone-800 hover:bg-stone-50 rounded transition">
                                    <a href="{{ $item->link_url }}" target="{{ $item->target }}" @click="sidebarOpen = false" class="hover:text-[#c92137] flex-1">
                                        {{ $item->title }}
                                    </a>
                                    <button type="button" @click.stop="expanded = !expanded" class="p-1.5 text-stone-500 hover:text-[#c92137] hover:bg-stone-200/50 rounded transition" aria-label="उप-मेनु खोल्नुहोस्">
                                        <svg viewBox="0 0 20 20" class="h-4 w-4 fill-current transition-transform duration-200" :class="expanded ? 'rotate-180' : ''"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                                    </button>
                                </div>
                                <ul x-cloak x-show="expanded" class="mt-1 space-y-1 rounded-lg bg-stone-50 p-2 pl-4 border-l-2 border-[#2e5cb0]">
                                    @foreach ($item->children as $child)
                                        <li>
                                            <a href="{{ $child->link_url }}" target="{{ $child->target }}" @click="sidebarOpen = false" class="block px-3 py-2 text-sm font-semibold text-stone-700 hover:text-[#c92137] hover:bg-white rounded transition">
                                                • {{ $child->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ $item->link_url }}" target="{{ $item->target }}" @click="sidebarOpen = false" class="flex items-center justify-between px-4 py-3 text-base font-bold text-stone-800 hover:bg-red-50 hover:text-[#c92137] rounded transition">
                                    <span>{{ $item->title }}</span>
                                    <span class="text-stone-400 text-xs">→</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                @else
                    @foreach ($categories ?? [] as $cat)
                        <li>
                            <a href="{{ route('category.show', $cat->slug) }}" @click="sidebarOpen = false" class="flex items-center justify-between px-4 py-3 text-base font-bold text-stone-800 hover:bg-red-50 hover:text-[#c92137] rounded transition">
                                <span>{{ $cat->name }}</span>
                                <span class="text-stone-400 text-xs">→</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </nav>

        <div class="border-t border-stone-200 bg-stone-50 p-5 text-xs text-stone-600">
            <p class="font-extrabold text-stone-800 text-sm">कपिलवस्तु दर्पण न्यूज</p>
            <p class="mt-1">सूचना विभाग दर्ता नं: ५३१४/८२/८३</p>
            <a href="mailto:kapilvastudarpannews@gmail.com" class="mt-2 block font-bold text-[#2e5cb0] hover:underline">kapilvastudarpannews@gmail.com</a>
        </div>
    </aside>
</div>

<!-- User Account Modal -->
<div x-cloak x-show="accountOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-2 opacity-0" @click.outside="accountOpen = false" class="fixed right-4 top-4 z-[60] w-[calc(100%-2rem)] max-w-sm rounded-xl border border-stone-200 bg-white p-6 text-stone-900 shadow-2xl sm:right-6 sm:top-6">
    <div class="flex items-start justify-between gap-4">
        <div>
            <h2 class="text-xl font-extrabold text-[#172b4d]">प्रयोगकर्ता खाता (Account)</h2>
            <p class="mt-1 text-xs text-stone-500">कपिलवस्तु दर्पणमा स्वागत छ</p>
        </div>
        <button type="button" @click="accountOpen = false" class="grid h-8 w-8 place-items-center rounded-full text-lg text-stone-500 hover:bg-stone-100" aria-label="Close">×</button>
    </div>
    <div class="mt-4 grid grid-cols-2 rounded-lg bg-stone-100 p-1">
        <button type="button" @click="authTab = 'login'" :class="authTab === 'login' ? 'bg-white text-[#2e5cb0] shadow-sm font-bold' : 'text-stone-600'" class="min-h-9 rounded text-sm py-1">लगइन</button>
        <button type="button" @click="authTab = 'register'" :class="authTab === 'register' ? 'bg-white text-[#2e5cb0] shadow-sm font-bold' : 'text-stone-600'" class="min-h-9 rounded text-sm py-1">दर्ता गर्नुहोस्</button>
    </div>
    <div class="mt-4 space-y-3">
        <a href="/admin/login" class="flex items-center justify-center gap-2 w-full rounded-lg bg-[#2e5cb0] px-4 py-2.5 font-bold text-white hover:bg-[#244b92] text-sm transition">
            <span>संवाददाता / एडमिन लगइन (Filament)</span>
        </a>
        <p class="text-[11px] text-center text-stone-500">समाचार पोस्टिङ तथा सम्पादनका लागि एडमिन प्यानल प्रयोग गर्नुहोस्।</p>
    </div>
</div>

<!-- Search Popup Modal (Functional Global Search Form) -->
<div x-cloak x-show="searchOpen" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[70] flex items-start justify-center pt-16 sm:pt-24 px-4" aria-modal="true" role="dialog" aria-labelledby="search-modal-title">
    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="searchOpen = false"></div>

    <div x-show="searchOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 -translate-y-4 scale-95" @click.outside="searchOpen = false" class="relative w-full max-w-2xl rounded-2xl border border-stone-200 bg-white p-6 sm:p-8 text-stone-900 shadow-2xl z-10">
        <div class="flex items-center justify-between border-b border-stone-200 pb-4">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-full bg-red-100 text-[#c92137]">
                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-[2.5]" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/></svg>
                </span>
                <div>
                    <h3 id="search-modal-title" class="text-xl font-extrabold text-stone-900">समाचार खोजी गर्नुहोस्</h3>
                    <p class="text-xs text-stone-500">कपिलवस्तु दर्पण डिजिटल खोज प्रणाली</p>
                </div>
            </div>
            <button type="button" @click="searchOpen = false" class="grid h-8 w-8 place-items-center rounded-full bg-stone-100 text-lg font-bold text-stone-500 hover:bg-[#c92137] hover:text-white transition" aria-label="खोजी बन्द गर्नुहोस्">×</button>
        </div>

        <!-- Search Form -->
        <form action="{{ route('search') }}" method="GET" class="mt-6 flex flex-col sm:flex-row gap-2">
            <div class="relative flex-1">
                <input x-ref="modalSearchInput" name="q" type="search" placeholder="शीर्षक, विषय, वा लेखकको नाम खोज्नुहोस्..." value="{{ request('q') }}" class="w-full rounded-xl border-2 border-stone-300 bg-stone-50 px-4 py-3 text-base font-semibold text-stone-900 placeholder-stone-400 focus:border-[#2e5cb0] focus:bg-white focus:outline-none transition shadow-inner" required>
            </div>
            <button type="submit" class="flex items-center justify-center gap-2 rounded-xl bg-[#c92137] px-6 py-3 font-extrabold text-white shadow hover:bg-red-800 transition text-base shrink-0">
                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-none stroke-current stroke-[2.5]" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/></svg>
                <span>खोज्नुहोस्</span>
            </button>
        </form>

        <!-- Suggested Quick Search Tags -->
        <div class="mt-6 border-t border-stone-100 pt-4">
            <p class="text-xs font-bold text-stone-500 uppercase tracking-wider mb-2">लोकप्रिय खोजीहरू (Trending Topics):</p>
            <div class="flex flex-wrap gap-2">
                @foreach(['कपिलवस्तु', 'तिलौराकोट', 'लुम्बिनी', 'राजनीति', 'अर्थतन्त्र', 'नेपाल', 'खेलकुद'] as $tag)
                    <a href="{{ route('search', ['q' => $tag]) }}" class="rounded-full bg-stone-100 px-3 py-1 text-xs font-bold text-stone-700 hover:bg-[#c92137] hover:text-white transition cursor-pointer">
                        # {{ $tag }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
