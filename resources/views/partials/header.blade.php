<a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:bg-[#c92137] focus:text-white focus:px-4 focus:py-2">मुख्य सामग्रीमा जानुहोस्</a>

<!-- Top Info Utility Bar -->
<div class="border-b border-stone-200/90 bg-stone-50/80 text-xs text-stone-600 transition-colors">
    <div class="mx-auto max-w-[1280px] flex items-center justify-between px-4 sm:px-6 lg:px-8 py-2">
        <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
            <div class="flex items-center gap-1.5 font-medium text-stone-700" x-data="{
                currentTime: '{{ nepali_time() }}',
                init() {
                    const toNepali = (num) => {
                        const digits = ['०', '१', '२', '३', '४', '५', '६', '७', '८', '९'];
                        return String(num).replace(/[0-9]/g, d => digits[d]);
                    };
                    const update = () => {
                        const d = new Date();
                        let h = d.getHours() % 12 || 12;
                        let m = String(d.getMinutes()).padStart(2, '0');
                        this.currentTime = toNepali(h) + ':' + toNepali(m) + ' बजे';
                    };
                    update();
                    setInterval(update, 10000);
                }
            }">
                <svg class="h-3.5 w-3.5 text-stone-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span>{{ nepali_date() }}</span>
                <span class="text-stone-300">,</span>
                <svg class="h-3.5 w-3.5 text-stone-400 shrink-0 ml-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span x-text="currentTime">{{ nepali_time() }}</span>
            </div>
            <span class="hidden sm:inline text-stone-300">|</span>
            <div class="hidden sm:flex items-center gap-1 font-medium text-stone-600">
                <svg class="h-3.5 w-3.5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>कपिलवस्तु, लुम्बिनी प्रदेश, नेपाल</span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @php
                $socialFacebook = trim((string) setting('social_facebook', ''));
                $socialTwitter = trim((string) setting('social_twitter', ''));
                $socialInstagram = trim((string) setting('social_instagram', ''));
                $socialYoutube = trim((string) setting('social_youtube', ''));
                $socialTiktok = trim((string) setting('social_tiktok', ''));
                $socialWhatsapp = trim((string) setting('social_whatsapp', ''));
                $hasHeaderSocial = $socialFacebook !== '' || $socialTwitter !== '' || $socialInstagram !== '' || $socialYoutube !== '' || $socialTiktok !== '' || $socialWhatsapp !== '';
            @endphp

            @if($hasHeaderSocial)
                <!-- Top Header Social Media Icons -->
                <div class="hidden md:flex items-center gap-1.5 border-r border-stone-200/90 pr-3">
                    @if($socialFacebook !== '')
                        <a href="{{ $socialFacebook }}" target="_blank" rel="noopener noreferrer" class="grid h-6 w-6 place-items-center rounded-full bg-stone-200/70 text-stone-600 hover:bg-[#1877F2] hover:text-white hover:scale-110 transition shadow-2xs" aria-label="Facebook" title="Facebook">
                            <svg viewBox="0 0 24 24" class="h-3 w-3 fill-current" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                    @endif
                    @if($socialTwitter !== '')
                        <a href="{{ $socialTwitter }}" target="_blank" rel="noopener noreferrer" class="grid h-6 w-6 place-items-center rounded-full bg-stone-200/70 text-stone-600 hover:bg-black hover:text-white hover:scale-110 transition shadow-2xs" aria-label="X (Twitter)" title="X">
                            <svg viewBox="0 0 24 24" class="h-2.5 w-2.5 fill-current" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                        </a>
                    @endif
                    @if($socialInstagram !== '')
                        <a href="{{ $socialInstagram }}" target="_blank" rel="noopener noreferrer" class="grid h-6 w-6 place-items-center rounded-full bg-stone-200/70 text-stone-600 hover:bg-[#E4405F] hover:text-white hover:scale-110 transition shadow-2xs" aria-label="Instagram" title="Instagram">
                            <svg viewBox="0 0 24 24" class="h-3 w-3 fill-current" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    @endif
                    @if($socialYoutube !== '')
                        <a href="{{ $socialYoutube }}" target="_blank" rel="noopener noreferrer" class="grid h-6 w-6 place-items-center rounded-full bg-stone-200/70 text-stone-600 hover:bg-[#FF0000] hover:text-white hover:scale-110 transition shadow-2xs" aria-label="YouTube" title="YouTube">
                            <svg viewBox="0 0 24 24" class="h-3 w-3 fill-current" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    @endif
                    @if($socialTiktok !== '')
                        <a href="{{ $socialTiktok }}" target="_blank" rel="noopener noreferrer" class="grid h-6 w-6 place-items-center rounded-full bg-stone-200/70 text-stone-600 hover:bg-black hover:text-white hover:scale-110 transition shadow-2xs" aria-label="TikTok" title="TikTok">
                            <svg viewBox="0 0 24 24" class="h-3 w-3 fill-current" aria-hidden="true"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.96-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                        </a>
                    @endif
                    @if($socialWhatsapp !== '')
                        <a href="{{ str_starts_with($socialWhatsapp, 'http') ? $socialWhatsapp : 'https://wa.me/' . preg_replace('/[^0-9]/', '', $socialWhatsapp) }}" target="_blank" rel="noopener noreferrer" class="grid h-6 w-6 place-items-center rounded-full bg-stone-200/70 text-stone-600 hover:bg-[#25D366] hover:text-white hover:scale-110 transition shadow-2xs" aria-label="WhatsApp" title="WhatsApp">
                            <svg viewBox="0 0 24 24" class="h-3 w-3 fill-current" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        </a>
                    @endif
                </div>
            @endif

            @if(setting_bool('sharemarket_enabled', true))
            <a href="{{ route('sharemarket.index') }}" wire:navigate.hover class="hidden md:flex font-bold text-stone-700 hover:text-[#c92137] transition items-center gap-1" title="नेपाल शेयर बजार (NEPSE)">
                <span>📈</span>
                <span>शेयर बजार</span>
            </a>
            <span class="hidden md:inline text-stone-300">|</span>
            @endif
            <a href="{{ route('unicode.converter') }}" wire:navigate.hover class="hidden md:flex font-bold text-stone-700 hover:text-[#c92137] transition items-center gap-1" title="नेपाली युनिकोड कन्भर्टर">
                <span>⌨</span>
                <span>युनिकोड</span>
            </a>
            <span class="hidden md:inline text-stone-300">|</span>
            <a href="{{ route('pages.show', 'contact') }}" wire:navigate.hover class="hidden md:inline font-bold text-stone-700 hover:text-[#c92137] transition">सम्पर्क</a>
            <button type="button" @click="darkMode = !darkMode; setTheme()" class="theme-toggle" :class="darkMode ? 'theme-toggle--dark' : ''" :aria-pressed="darkMode ? 'true' : 'false'" :aria-label="darkMode ? 'लाइट मोडमा फर्कनुहोस्' : 'डार्क मोड खोल्नुहोस्'" title="रङ मोड बदल्नुहोस्">
                <span class="theme-toggle__sun" aria-hidden="true">☀</span>
                <span class="theme-toggle__moon" aria-hidden="true">☾</span>
            </button>
            <style>
                .theme-toggle {
                    display: inline-flex !important;
                    align-items: center !important;
                    gap: 0.35rem !important;
                    min-height: 1.85rem !important;
                    padding: 0.15rem 0.35rem !important;
                    border-radius: 9999px !important;
                    cursor: pointer !important;
                }
                .theme-toggle__sun, .theme-toggle__moon {
                    display: grid !important;
                    width: 1.3rem !important;
                    height: 1.3rem !important;
                    place-items: center !important;
                    border-radius: 9999px !important;
                    font-size: 0.85rem !important;
                    line-height: 1 !important;
                }
            </style>
        </div>
    </div>
</div>

<!-- Main Header Branding Section -->
<header class="bg-white border-b border-stone-100">
    <div class="mx-auto max-w-[1280px] flex items-center justify-between gap-6 px-4 py-3 sm:py-4 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" wire:navigate.hover class="shrink-0 flex items-center gap-3.5 group" aria-label="{{ setting('site_name', 'कपिलवस्तु दर्पण') }} गृहपृष्ठ">
            <img src="{{ setting_image_url(setting('site_logo'), 'images/kapilvastu-darpan-logo.png') }}" alt="{{ setting('site_name', 'कपिलवस्तु दर्पण') }}" width="320" height="96" decoding="async" class="h-16 sm:h-22 md:h-24 w-auto object-contain transition-transform duration-300 group-hover:scale-[1.02]">
        </a>

        <!-- Header Banner Ad Space -->
        <div class="hidden md:flex flex-1 max-w-3xl justify-end">
            <x-ad-banner placement="header" class="h-20 w-full rounded-xl overflow-hidden border border-stone-200/80 shadow-2xs" />
        </div>

        <!-- Mobile Hamburger Button -->
        <button @click="sidebarOpen = true" type="button" class="flex h-11 w-12 flex-col items-center justify-center gap-1.5 rounded-xl bg-[#c92137] hover:bg-[#a50404] transition md:hidden shadow-sm p-2 cursor-pointer" :aria-expanded="sidebarOpen ? 'true' : 'false'" aria-controls="site-sidebar" aria-label="मेनु खोल्नुहोस्">
            <span class="h-[3px] w-6 rounded-full bg-white shadow-xs"></span>
            <span class="h-[3px] w-6 rounded-full bg-white shadow-xs"></span>
            <span class="h-[3px] w-6 rounded-full bg-white shadow-xs"></span>
        </button>
    </div>

    <!-- Sticky Primary Navigation Bar -->
    <div x-ref="menuAnchor" class="hidden h-[4.2rem] md:block">
        <div class="inset-x-0 top-0 z-40 bg-[#0955cf] text-white transition-all shadow-md" :class="menuPinned ? 'fixed shadow-xl' : 'relative'">
            <nav id="primary-nav" aria-label="मुख्य नेभिगेसन">
                <style>
                    /* Immediate Header Main Menu Font Increase (Bypasses Browser Cache) */
                    #primary-nav ul.kd-main-nav,
                    #primary-nav ul.kd-main-nav > li > a,
                    #primary-nav ul.kd-main-nav > li > a > span,
                    #primary-nav ul.kd-main-nav > li > button,
                    #primary-nav ul.kd-main-nav > li > button > span {
                        font-size: 18px !important;
                        font-weight: 800 !important;
                    }
                    @media (min-width: 1280px) {
                        #primary-nav ul.kd-main-nav,
                        #primary-nav ul.kd-main-nav > li > a,
                        #primary-nav ul.kd-main-nav > li > a > span {
                            font-size: 19px !important;
                        }
                    }
                </style>
                <div class="w-full flex items-stretch h-[4.2rem] px-4 sm:px-6 lg:px-8">
                    <a href="{{ route('home') }}" wire:navigate.hover class="grid h-[4.2rem] w-14 shrink-0 place-items-center border-x border-white/15 bg-black/10 hover:bg-black/25 text-white transition duration-200" aria-label="गृहपृष्ठ">
                        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1V10Z"/></svg>
                    </a>

                    <!-- Dynamic Header Menu Items (Dropdown & Mega Menu Support) -->
                    <ul class="kd-main-nav flex min-w-0 flex-1 items-stretch h-full font-extrabold text-white overflow-visible">
                        @if(isset($headerMenuItems) && $headerMenuItems->isNotEmpty())
                            @foreach ($headerMenuItems as $item)
                                @php
                                    $isItemInternal = $item->target !== '_blank' && (!str_starts_with($item->link_url, 'http://') && !str_starts_with($item->link_url, 'https://') || str_starts_with($item->link_url, url('/')));
                                @endphp
                                @if ($item->is_mega_menu)
                                    <!-- Mega Menu Dropdown (Matches Screenshot) -->
                                    <li class="static flex items-stretch h-full shrink-0" x-data="{ open: false, currentProvince: '{{ $item->children->first()?->title ?? 'कोशी प्रदेश' }}' }" @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false">
                                        <a href="{{ $item->link_url }}" target="{{ $item->target }}" {{ $isItemInternal ? 'wire:navigate.hover' : '' }} class="flex items-center gap-1 whitespace-nowrap px-2 lg:px-2.5 xl:px-3 h-full font-extrabold text-[16px] transition duration-150 cursor-pointer" :class="open ? 'bg-white text-[#c92137] shadow-sm' : 'text-white hover:bg-white/15'">
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
                                            <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 py-6">
                                                <div class="flex gap-8 items-start">
                                                    <!-- Left Column: Provinces List -->
                                                    <div class="w-52 shrink-0 border-r border-stone-200 pr-4 space-y-0.5">
                                                        @foreach ($item->children as $child)
                                                            @php
                                                                $isChildInternal = $child->target !== '_blank' && (!str_starts_with($child->link_url, 'http://') && !str_starts_with($child->link_url, 'https://') || str_starts_with($child->link_url, url('/')));
                                                            @endphp
                                                            <a href="{{ $child->link_url }}"
                                                               target="{{ $child->target }}"
                                                               {{ $isChildInternal ? 'wire:navigate.hover' : '' }}
                                                               @mouseenter="currentProvince = '{{ $child->title }}'"
                                                               class="block px-3.5 py-1.5 text-[16px] font-bold rounded-lg transition duration-150 cursor-pointer"
                                                               :class="currentProvince === '{{ $child->title }}' ? 'text-[#c92137] bg-red-50/70 font-extrabold shadow-xs' : 'text-stone-700 hover:text-[#c92137] hover:bg-stone-50'">
                                                                {{ $child->title }}
                                                            </a>
                                                        @endforeach
                                                    </div>

                                                    <!-- Right Section: 4-Column News Grid -->
                                                    <div class="flex-1 min-w-0">
                                                        @foreach ($provincesList ?? [] as $provName)
                                                            <div x-cloak x-show="currentProvince === '{{ $provName }}'"
                                                                 x-transition:enter="transition ease-out duration-200"
                                                                 x-transition:enter-start="opacity-0 translate-y-1"
                                                                 x-transition:enter-end="opacity-100 translate-y-0">
                                                                <div class="grid grid-cols-4 gap-5">
                                                                    @foreach ($provinceStories[$provName] ?? [] as $story)
                                                                        <article class="group flex flex-col">
                                                                            <a href="{{ route('articles.show', $story->slug) }}" wire:navigate.hover class="block aspect-[16/10] rounded-lg overflow-hidden bg-stone-100 shadow-sm">
                                                                                <img src="{{ $story->image_url }}" alt="{{ $story->title }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                                                                            </a>
                                                                            <h4 class="mt-3 text-[16px] sm:text-[17px] font-bold leading-snug text-stone-900 group-hover:text-[#c92137] line-clamp-2 transition">
                                                                                <a href="{{ route('articles.show', $story->slug) }}" wire:navigate.hover>
                                                                                    {{ $story->title }}
                                                                                </a>
                                                                            </h4>
                                                                        </article>
                                                                    @endforeach
                                                                </div>
                                                                <!-- थप समाचार ↗ Link on Bottom Right -->
                                                                <div class="mt-4 flex justify-end border-t border-stone-100 pt-3">
                                                                    <a href="{{ route('category.show', 'pradesh') }}" wire:navigate.hover class="inline-flex items-center gap-1.5 text-sm font-extrabold text-stone-600 hover:text-[#c92137] transition group">
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
                                    <li class="relative flex items-stretch h-full shrink-0" x-data="{ open: false }" @mouseenter="open = true" @mouseleave="open = false" @click.outside="open = false">
                                        <a href="{{ $item->link_url }}" target="{{ $item->target }}" {{ $isItemInternal ? 'wire:navigate.hover' : '' }} class="flex items-center gap-1 whitespace-nowrap px-2 lg:px-2.5 xl:px-3 h-full font-extrabold text-[16px] transition duration-150 cursor-pointer" :class="open ? 'bg-white text-[#c92137] shadow-sm' : 'text-white hover:bg-white/15'">
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
                                                    @php
                                                        $isChildInternal = $child->target !== '_blank' && (!str_starts_with($child->link_url, 'http://') && !str_starts_with($child->link_url, 'https://') || str_starts_with($child->link_url, url('/')));
                                                    @endphp
                                                    <a href="{{ $child->link_url }}" target="{{ $child->target }}" {{ $isChildInternal ? 'wire:navigate.hover' : '' }} class="block px-5 py-2.5 text-[16px] font-bold text-stone-800 hover:text-[#c92137] hover:bg-red-50/60 rounded-lg transition duration-150 whitespace-nowrap">
                                                        {{ $child->title }}
                                                    </a>
                                                @endforeach
                                            </div>
                                        </div>
                                    </li>
                                @else
                                    <li class="flex items-stretch h-full shrink-0">
                                        <a href="{{ $item->link_url }}" target="{{ $item->target }}" {{ $isItemInternal ? 'wire:navigate.hover' : '' }} class="flex items-center gap-1 whitespace-nowrap px-2 lg:px-2.5 xl:px-3 h-full hover:bg-white/15 transition text-[16px] font-extrabold">
                                            {{ $item->title }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            @foreach ($categories as $cat)
                                <li class="flex items-stretch h-full shrink-0">
                                    <a href="{{ route('category.show', $cat->slug) }}" wire:navigate.hover class="flex items-center gap-1 whitespace-nowrap px-2 lg:px-2.5 xl:px-3 h-full hover:bg-white/15 transition text-[16px] font-extrabold">
                                        {{ $cat->name }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>

                    <!-- Right Actions: Flash News + Account + Search + Sidebar Menu -->
                    <div class="ml-auto flex shrink-0 items-center gap-2 pl-2 bg-[#0955cf] z-10">
                        <button type="button" @click="flashOpen = !flashOpen" class="relative flex h-10 items-center gap-2 rounded-full bg-white px-3.5 font-extrabold text-[#0955cf] shadow-xs hover:bg-amber-300 hover:text-stone-900 transition-all duration-200 cursor-pointer" :aria-expanded="flashOpen ? 'true' : 'false'" aria-controls="flash-news-panel">
                            <span class="relative flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-600"></span>
                            </span>
                            <span class="text-xs tracking-wide">Flash News</span>
                            <span class="grid h-5 min-w-5 px-1.5 place-items-center rounded-full bg-[#c92137] text-[10px] font-black text-white shadow-2xs">
                                {{ nepali_number(count($flashNews ?? [])) }}
                            </span>
                        </button>

                        <div class="flex items-center gap-2 border-l border-white/20 pl-2.5">
                            <!-- User Account Button -->
                            <button type="button" @click="accountOpen = !accountOpen" class="grid h-9 w-9 place-items-center rounded-full bg-white/10 text-white hover:bg-white hover:text-[#0955cf] hover:scale-105 transition-all duration-200 cursor-pointer shadow-2xs" aria-label="User Account" title="{{ auth()->check() ? auth()->user()->name : 'User Account' }}">
                                @auth
                                    <span class="font-extrabold text-xs uppercase">{{ mb_substr(auth()->user()->name, 0, 1) }}</span>
                                @else
                                    <svg viewBox="0 0 24 24" class="h-4.5 w-4.5 fill-current" aria-hidden="true"><path d="M12 12a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Zm0 2c-4.5 0-8 2.3-8 5.2V21h16v-1.8C20 16.3 16.5 14 12 14Z"/></svg>
                                @endauth
                            </button>

                            <!-- Functional Popup Search Button -->
                            <button type="button" @click="searchOpen = true; $nextTick(() => $refs.modalSearchInput?.focus())" class="grid h-9 w-9 place-items-center rounded-full bg-white text-[#0955cf] shadow-sm hover:bg-amber-300 hover:text-stone-900 hover:scale-105 transition-all duration-200 cursor-pointer" aria-label="समाचार खोज्नुहोस्" title="समाचार खोज्नुहोस्">
                                <svg viewBox="0 0 24 24" class="h-4 w-4 fill-none stroke-current stroke-[2.5]" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5"/><path d="m16 16 5 5"/></svg>
                            </button>

                            <!-- Slide Menu Hamburger Button -->
                            <button type="button" @click="sidebarOpen = true" class="flex h-[4.2rem] w-14 shrink-0 flex-col items-center justify-center gap-1.5 bg-black/15 hover:bg-black/30 border-x border-white/20 transition-colors cursor-pointer px-3 ml-1" aria-label="साइडबार मेनु खोल्नुहोस्" title="मुख्य मेनु खोल्नुहोस्">
                                <span class="h-[3px] w-6 rounded-full bg-white shadow-xs"></span>
                                <span class="h-[3px] w-6 rounded-full bg-white shadow-xs"></span>
                                <span class="h-[3px] w-6 rounded-full bg-white shadow-xs"></span>
                            </button>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Flash News Dropdown Drawer -->
            <section id="flash-news-panel" x-cloak x-show="flashOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="-translate-y-5 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-5 opacity-0" class="absolute inset-x-0 top-full z-40 border-b border-stone-200 bg-white text-stone-900 shadow-2xl" aria-label="फ्ल्यास समाचार">
                <div class="relative mx-auto max-w-[1280px] px-4 py-6 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between pb-4 border-b border-stone-200 mb-5">
                        <h3 class="text-xl font-extrabold text-[#c92137] flex items-center gap-2">
                            <span class="h-3 w-3 rounded-full bg-red-600 animate-ping"></span> फ्ल्यास समाचार (Flash Updates)
                        </h3>
                        <button type="button" @click="flashOpen = false" class="grid h-8 w-8 place-items-center rounded-full bg-stone-200 text-lg font-bold text-stone-700 hover:bg-[#c92137] hover:text-white transition" aria-label="फ्ल्यास समाचार बन्द गर्नुहोस्">×</button>
                    </div>
                    <div class="grid gap-x-6 gap-y-5 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($flashNews ?? [] as $flash)
                            <article class="grid grid-cols-[6.5rem_1fr] items-start gap-3 group">
                                <a href="{{ route('articles.show', $flash->slug) }}" wire:navigate.hover class="block aspect-[4/3] rounded overflow-hidden bg-stone-100">
                                    <img src="{{ $flash->image_url }}" alt="{{ $flash->title }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                                </a>
                                <div>
                                    @php $flashCat = $flash->categories->first() ?? $flash->category; @endphp
                                    @if($flashCat)
                                        <a href="{{ route('category.show', $flashCat->slug) }}" wire:navigate.hover class="text-xs font-bold text-[#c92137]">{{ $flashCat->name }}</a>
                                    @endif
                                    <a href="{{ route('articles.show', $flash->slug) }}" wire:navigate.hover class="mt-1 block text-base font-bold leading-snug hover:text-[#c92137] line-clamp-2">
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
                    <a :href="item.url" wire:navigate.hover x-cloak x-show="current === index" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="-translate-x-full opacity-0" x-transition:enter-end="translate-x-0 opacity-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0 opacity-100" x-transition:leave-end="translate-x-full opacity-0" class="absolute inset-0 flex items-center truncate text-sm font-bold text-stone-800 hover:text-[#c92137]" x-text="item.title"></a>
                </template>
            </div>
            <div class="ml-auto hidden shrink-0 items-center gap-1 sm:flex">
                <button type="button" @click="current = (current - 1 + items.length) % items.length" class="grid h-7 w-7 place-items-center rounded border border-sky-200 bg-white text-base leading-none text-stone-700 hover:border-[#c92137] hover:text-[#c92137]" aria-label="अघिल्लो समाचार">‹</button>
                <button type="button" @click="current = (current + 1) % items.length" class="grid h-7 w-7 place-items-center rounded border border-sky-200 bg-white text-base leading-none text-stone-700 hover:border-[#c92137] hover:text-[#c92137]" aria-label="अर्को समाचार">›</button>
            </div>
        </div>
    </section>
@endif

<!-- Live NEPSE Share Market Ticker Strip -->
<x-nepse-ticker />

<!-- Mobile / Drawer Sidebar Dialog -->
<div x-cloak x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50" aria-label="साइट मेनु">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="sidebarOpen = false"></div>
    <aside id="site-sidebar" class="relative flex h-full w-[min(88vw,23rem)] flex-col overflow-y-auto no-scrollbar bg-white shadow-2xl transition-transform duration-300 ease-out" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" role="dialog" aria-modal="true" aria-label="मुख्य मेनु">
        <div class="flex items-center justify-between border-b border-stone-200 p-5 bg-stone-50">
            <a href="{{ route('home') }}" wire:navigate.hover @click="sidebarOpen = false">
                <img src="{{ setting_image_url(setting('site_logo'), 'images/kapilvastu-darpan-logo.png') }}" alt="{{ setting('site_name', 'कपिलवस्तु दर्पण') }}" width="320" height="64" loading="lazy" decoding="async" class="h-14 sm:h-16 w-auto object-contain">
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
                        @php
                            $isDrawerItemInternal = $item->target !== '_blank' && (!str_starts_with($item->link_url, 'http://') && !str_starts_with($item->link_url, 'https://') || str_starts_with($item->link_url, url('/')));
                        @endphp
                        @if ($item->children->isNotEmpty())
                            <li x-data="{ expanded: false }" class="py-1">
                                <div class="flex items-center justify-between px-4 py-3 text-[16px] font-bold text-stone-800 hover:bg-stone-50 rounded transition">
                                    @if($item->link_url === '#' || $item->is_mega_menu)
                                        <button type="button" @click="expanded = !expanded" class="flex-1 text-left hover:text-[#c92137] flex items-center justify-between">
                                            <span>{{ $item->title }}</span>
                                        </button>
                                    @else
                                        <a href="{{ $item->link_url }}" target="{{ $item->target }}" {{ $isDrawerItemInternal ? 'wire:navigate.hover' : '' }} @click="sidebarOpen = false" class="hover:text-[#c92137] flex-1">
                                            {{ $item->title }}
                                        </a>
                                    @endif
                                    <button type="button" @click.stop="expanded = !expanded" class="p-1.5 text-stone-500 hover:text-[#c92137] hover:bg-stone-200/50 rounded transition" aria-label="उप-मेनु खोल्नुहोस्">
                                        <svg viewBox="0 0 20 20" class="h-4 w-4 fill-current transition-transform duration-200" :class="expanded ? 'rotate-180 text-[#c92137]' : ''"><path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                                    </button>
                                </div>
                                <ul x-cloak x-show="expanded" class="mt-1 space-y-1 rounded-lg bg-stone-50 p-2 pl-4 border-l-2 border-[#0955cf]">
                                    @foreach ($item->children as $child)
                                        @php
                                            $isDrawerChildInternal = $child->target !== '_blank' && (!str_starts_with($child->link_url, 'http://') && !str_starts_with($child->link_url, 'https://') || str_starts_with($child->link_url, url('/')));
                                        @endphp
                                        <li>
                                            <a href="{{ $child->link_url }}" target="{{ $child->target }}" {{ $isDrawerChildInternal ? 'wire:navigate.hover' : '' }} @click="sidebarOpen = false" class="block px-3 py-2 text-[16px] font-semibold text-stone-700 hover:text-[#c92137] hover:bg-white rounded transition">
                                                • {{ $child->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @else
                            <li>
                                <a href="{{ $item->link_url }}" target="{{ $item->target }}" {{ $isDrawerItemInternal ? 'wire:navigate.hover' : '' }} @click="sidebarOpen = false" class="flex items-center justify-between px-4 py-3 text-[16px] font-bold text-stone-800 hover:bg-red-50 hover:text-[#c92137] rounded transition">
                                    <span>{{ $item->title }}</span>
                                    <span class="text-stone-400 text-xs">→</span>
                                </a>
                            </li>
                        @endif
                    @endforeach
                @else
                    @foreach ($categories ?? [] as $cat)
                        <li>
                            <a href="{{ route('category.show', $cat->slug) }}" wire:navigate.hover @click="sidebarOpen = false" class="flex items-center justify-between px-4 py-3 text-[16px] font-bold text-stone-800 hover:bg-red-50 hover:text-[#c92137] rounded transition">
                                <span>{{ $cat->name }}</span>
                                <span class="text-stone-400 text-xs">→</span>
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </nav>

        @if(setting_bool('sharemarket_enabled', true))
        <div class="px-4 py-2 border-t border-stone-200 bg-emerald-50/40">
            <a href="{{ route('sharemarket.index') }}" wire:navigate.hover @click="sidebarOpen = false" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white border border-emerald-200/80 text-emerald-800 font-extrabold text-sm hover:bg-emerald-50 transition shadow-2xs">
                <span class="flex items-center gap-2">
                    <span>📈</span>
                    <span>नेपाल शेयर बजार (NEPSE)</span>
                </span>
                <span class="text-[11px] bg-emerald-600 text-white px-2 py-0.5 rounded font-black tracking-wide uppercase">लाइभ</span>
            </a>
        </div>
        @endif

        <div class="px-4 py-2 border-t border-stone-200 bg-red-50/50">
            <a href="{{ route('unicode.converter') }}" wire:navigate.hover @click="sidebarOpen = false" class="flex items-center justify-between px-3.5 py-2.5 rounded-xl bg-white border border-red-200/80 text-[#c92137] font-extrabold text-sm hover:bg-red-50 transition shadow-2xs">
                <span class="flex items-center gap-2">
                    <span>⌨️</span>
                    <span>नेपाली युनिकोड कन्भर्टर</span>
                </span>
                <span class="text-[11px] bg-[#c92137] text-white px-2 py-0.5 rounded font-black tracking-wide uppercase">टुल</span>
            </a>
        </div>

        <div class="p-4 border-t border-stone-200 space-y-2">
            @auth
                <a href="{{ route('dashboard') }}" wire:navigate.hover @click="sidebarOpen = false" class="flex items-center justify-center gap-2 w-full rounded-xl bg-gradient-to-r from-[#0955cf] to-[#c92137] px-4 py-2.5 font-bold text-white hover:opacity-95 text-sm transition shadow-xs">
                    <svg viewBox="0 0 24 24" class="h-4 w-4 fill-none stroke-current stroke-2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>मेरो ड्यासबोर्ड (User Dashboard)</span>
                </a>
            @else
                <a href="{{ route('login') }}" wire:navigate.hover @click="sidebarOpen = false" class="flex items-center justify-center gap-2 w-full rounded-xl bg-[#0955cf] px-4 py-2.5 font-bold text-white hover:bg-[#0747ad] text-sm transition shadow-xs">
                    <svg viewBox="0 0 24 24" class="h-4.5 w-4.5 fill-current" aria-hidden="true"><path d="M12 12a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9Zm0 2c-4.5 0-8 2.3-8 5.2V21h16v-1.8C20 16.3 16.5 14 12 14Z"/></svg>
                    <span>लगइन / नयाँ दर्ता (Login / Register)</span>
                </a>
            @endauth
        </div>

        <div class="border-t border-stone-200 bg-stone-50 p-5 text-xs text-stone-600">
            <p class="font-extrabold text-stone-800 text-sm">{{ setting('site_name', 'कपिलवस्तु दर्पण न्यूज') }}</p>
            <p class="mt-1">सूचना विभाग दर्ता नं: {{ setting('doi_reg_no', '५३१४/८२/८३') }}</p>
            <a href="mailto:{{ setting('contact_email', 'kapilvastudarpannews@gmail.com') }}" class="mt-2 block font-bold text-[#0955cf]">{{ setting('contact_email', 'kapilvastudarpannews@gmail.com') }}</a>
        </div>
    </aside>
</div>

<!-- User Account Modal -->
<div x-cloak x-show="accountOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-2 opacity-0" @click.outside="accountOpen = false" class="fixed right-4 top-4 z-[60] w-[calc(100%-2rem)] max-w-sm sm:max-w-md rounded-2xl border border-stone-200 bg-white p-5 sm:p-6 text-stone-900 shadow-2xl sm:right-6 sm:top-6 max-h-[92vh] overflow-y-auto">
    <div class="flex items-start justify-between gap-4 border-b border-stone-100 pb-3">
        <div>
            <h2 class="text-xl font-extrabold text-[#172b4d]">User Account</h2>
            <p class="mt-0.5 text-xs text-stone-500">Welcome to Kapilvastu Darpan</p>
        </div>
        <button type="button" @click="accountOpen = false" class="grid h-8 w-8 place-items-center rounded-full text-lg font-bold text-stone-400 hover:bg-stone-100 hover:text-stone-700 transition" aria-label="Close">×</button>
    </div>

    <!-- Session Feedback Alerts -->
    @if(session('status_success'))
        <div class="mt-3.5 rounded-lg bg-emerald-50 border border-emerald-200 p-3 text-xs font-semibold text-emerald-800 flex items-start gap-2">
            <span class="text-base text-emerald-600 shrink-0">✓</span>
            <p class="flex-1">{{ session('status_success') }}</p>
        </div>
    @endif

    @if(session('status_warning'))
        <div class="mt-3.5 rounded-lg bg-amber-50 border border-amber-200 p-3 text-xs font-semibold text-amber-900 space-y-2">
            <div class="flex items-start gap-2">
                <span class="text-base text-amber-600 shrink-0">⚠</span>
                <p class="flex-1">{{ session('status_warning') }}</p>
            </div>
            @if(session('unverified_email'))
                <form method="POST" action="{{ route('verification.send') }}" class="pt-1">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('unverified_email') }}">
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-md bg-amber-600 px-3 py-1.5 text-[11px] font-bold text-white hover:bg-amber-700 transition cursor-pointer">
                        <span>Resend Verification Link</span>
                    </button>
                </form>
            @endif
        </div>
    @endif

    @auth
        <!-- Logged-in User Card -->
        <div class="mt-4 space-y-4">
            <div class="flex items-center gap-3.5 rounded-xl border border-stone-200/80 bg-stone-50/80 p-3.5">
                <div class="grid h-12 w-12 shrink-0 place-items-center rounded-full bg-gradient-to-br from-[#0955cf] to-[#12356b] text-white font-black text-lg shadow-xs">
                    {{ mb_substr(auth()->user()->name, 0, 1) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <h3 class="font-extrabold text-stone-900 text-sm truncate">{{ auth()->user()->name }}</h3>
                        @if(auth()->user()->hasVerifiedEmail() || !setting_bool('enable_email_verification', false))
                            <span class="rounded bg-emerald-100 px-1.5 py-0.5 text-[10px] font-bold text-emerald-800 shrink-0">Verified</span>
                        @else
                            <span class="rounded bg-amber-100 px-1.5 py-0.5 text-[10px] font-bold text-amber-800 shrink-0">Unverified</span>
                        @endif
                    </div>
                    <p class="text-xs text-stone-500 truncate mt-0.5">{{ auth()->user()->email }}</p>
                </div>
            </div>

            @if(!auth()->user()->hasVerifiedEmail() && setting_bool('enable_email_verification', false))
                <div class="rounded-lg bg-amber-50 border border-amber-200 p-3 text-xs text-amber-800">
                    <p class="font-bold">Email Verification Pending:</p>
                    <p class="mt-0.5 text-[11px]">Please check your inbox and verify your email address to complete registration.</p>
                    <form method="POST" action="{{ route('verification.send') }}" class="mt-2">
                        @csrf
                        <button type="submit" class="text-[11px] font-bold text-[#0955cf] hover:underline cursor-pointer">Resend Verification Link ↗</button>
                    </form>
                </div>
            @endif

            <div class="space-y-2">
                <a href="{{ route('dashboard') }}" wire:navigate.hover class="flex items-center justify-center gap-2 w-full rounded-xl bg-gradient-to-r from-[#0955cf] to-[#c92137] px-4 py-2.5 font-bold text-white hover:opacity-95 text-xs transition shadow-xs">
                    <svg viewBox="0 0 24 24" class="h-4 w-4 fill-none stroke-current stroke-2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    <span>User Dashboard</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center justify-center gap-1.5 w-full rounded-xl border border-red-200 bg-red-50/50 hover:bg-red-100/70 px-4 py-2 font-bold text-red-700 text-xs transition cursor-pointer">
                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        <span>Log Out</span>
                    </button>
                </form>
            </div>
        </div>
    @else
        <!-- Guest Tabs: Login / Register -->
        <div class="mt-4 grid grid-cols-2 rounded-xl bg-stone-100 p-1">
            <button type="button" @click="authTab = 'login'" :class="authTab === 'login' ? 'bg-white text-[#0955cf] shadow-xs font-black' : 'text-stone-600 hover:text-stone-900 font-medium'" class="min-h-9 rounded-lg text-sm py-1.5 transition">Log In</button>
            <button type="button" @click="authTab = 'register'" :class="authTab === 'register' ? 'bg-white text-[#c92137] shadow-xs font-black' : 'text-stone-600 hover:text-stone-900 font-medium'" class="min-h-9 rounded-lg text-sm py-1.5 transition">Register</button>
        </div>

        <!-- 1. LOGIN TAB FORM -->
        <div x-show="authTab === 'login'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="mt-4" x-data="{ showLoginPass: false }">
            <form method="POST" action="{{ route('login') }}" class="space-y-3.5">
                @csrf

                <div>
                    <label for="login_email" class="block text-xs font-bold text-stone-700 mb-1">
                        Email Address
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-stone-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                        </span>
                        <input id="login_email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="name@example.com" class="w-full rounded-xl border border-stone-300 bg-white pl-9 pr-3 py-2.5 text-sm text-stone-900 placeholder-stone-400 focus:border-[#0955cf] focus:ring-2 focus:ring-blue-100 outline-none transition @error('email') border-red-500 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="login_password" class="block text-xs font-bold text-stone-700">
                            Password
                        </label>
                        <button type="button" @click="showLoginPass = !showLoginPass" class="text-[11px] font-semibold text-stone-500 hover:text-stone-800 cursor-pointer" x-text="showLoginPass ? 'Hide' : 'Show'"></button>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-stone-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input id="login_password" :type="showLoginPass ? 'text' : 'password'" name="password" required placeholder="••••••••" class="w-full rounded-xl border border-stone-300 bg-white pl-9 pr-10 py-2.5 text-sm text-stone-900 placeholder-stone-400 focus:border-[#0955cf] focus:ring-2 focus:ring-blue-100 outline-none transition @error('password') border-red-500 @enderror">
                        <button type="button" @click="showLoginPass = !showLoginPass" class="absolute inset-y-0 right-0 flex items-center pr-3 text-stone-400 hover:text-stone-700 cursor-pointer">
                            <svg x-show="!showLoginPass" class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg x-show="showLoginPass" x-cloak class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-xs pt-0.5">
                    <label class="flex items-center gap-2 cursor-pointer text-stone-600 select-none">
                        <input type="checkbox" name="remember" class="rounded border-stone-300 text-[#0955cf] focus:ring-[#0955cf]">
                        <span>Remember Me</span>
                    </label>
                    <a href="{{ route('password.request') }}" wire:navigate.hover class="font-bold text-[#0955cf] hover:text-[#c92137] hover:underline transition">
                        Forgot Password?
                    </a>
                </div>

                <button type="submit" class="flex items-center justify-center gap-2 w-full rounded-xl bg-gradient-to-r from-[#0955cf] to-[#143d87] hover:from-[#073c61] hover:to-[#0955cf] px-4 py-2.5 font-black text-white text-sm transition shadow-md hover:shadow-lg cursor-pointer">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                    <span>Log In</span>
                </button>
            </form>

            <div class="mt-4 border-t border-stone-100 pt-3">
                <div class="flex items-center justify-between text-xs text-stone-500">
                    <span>Don't have an account? <button type="button" @click="authTab = 'register'" class="font-extrabold text-[#c92137] hover:underline cursor-pointer">Register here</button></span>
                    <a href="{{ route('login') }}" wire:navigate.hover class="font-bold text-[#0955cf] hover:underline inline-flex items-center gap-0.5">
                        <span>Full Page</span>
                        <span>↗</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- 2. REGISTER TAB FORM -->
        <div x-show="authTab === 'register'" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="mt-4" x-data="{ newPassword: '', showPass: false }">
            <form method="POST" action="{{ route('register') }}" class="space-y-3">
                @csrf

                <div>
                    <label for="reg_name" class="block text-xs font-bold text-stone-700 mb-1">
                        Full Name
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-stone-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </span>
                        <input id="reg_name" type="text" name="name" value="{{ old('name') }}" required placeholder="Your full name" class="w-full rounded-xl border border-stone-300 bg-white pl-9 pr-3 py-2.5 text-sm text-stone-900 placeholder-stone-400 focus:border-[#c92137] focus:ring-2 focus:ring-red-100 outline-none transition @error('name') border-red-500 @enderror">
                    </div>
                    @error('name')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reg_email" class="block text-xs font-bold text-stone-700 mb-1">
                        Email Address
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-stone-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206"/></svg>
                        </span>
                        <input id="reg_email" type="email" name="email" value="{{ old('email') }}" required placeholder="name@example.com" class="w-full rounded-xl border border-stone-300 bg-white pl-9 pr-3 py-2.5 text-sm text-stone-900 placeholder-stone-400 focus:border-[#c92137] focus:ring-2 focus:ring-red-100 outline-none transition @error('email') border-red-500 @enderror">
                    </div>
                    @error('email')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="reg_password" class="block text-xs font-bold text-stone-700">
                            Password
                        </label>
                        <button type="button" @click="showPass = !showPass" class="text-[11px] font-semibold text-stone-500 hover:text-stone-700 cursor-pointer" x-text="showPass ? 'Hide' : 'Show'"></button>
                    </div>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-stone-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </span>
                        <input id="reg_password" :type="showPass ? 'text' : 'password'" name="password" x-model="newPassword" required placeholder="••••••••" class="w-full rounded-xl border border-stone-300 bg-white pl-9 pr-3 py-2.5 text-sm text-stone-900 placeholder-stone-400 focus:border-[#c92137] focus:ring-2 focus:ring-red-100 outline-none transition @error('password') border-red-500 @enderror">
                    </div>
                    @error('password')
                        <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror

                    <!-- Dynamic Password Requirements Checklist -->
                    <div class="mt-2 rounded-xl bg-stone-50 border border-stone-200/80 p-2.5 text-[11px] space-y-1">
                        <p class="font-bold text-stone-700 mb-1">Password must contain:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-2 gap-y-1">
                            <div class="flex items-center gap-1.5 transition-colors" :class="newPassword.length >= 8 ? 'text-emerald-700 font-bold' : 'text-stone-500'">
                                <span class="text-xs" x-text="newPassword.length >= 8 ? '✓' : '•'">•</span>
                                <span>At least 8 characters</span>
                            </div>
                            <div class="flex items-center gap-1.5 transition-colors" :class="(/[a-z]/.test(newPassword) && /[A-Z]/.test(newPassword)) ? 'text-emerald-700 font-bold' : 'text-stone-500'">
                                <span class="text-xs" x-text="(/[a-z]/.test(newPassword) && /[A-Z]/.test(newPassword)) ? '✓' : '•'">•</span>
                                <span>Upper and lowercase letters</span>
                            </div>
                            <div class="flex items-center gap-1.5 transition-colors" :class="/[0-9]/.test(newPassword) ? 'text-emerald-700 font-bold' : 'text-stone-500'">
                                <span class="text-xs" x-text="/[0-9]/.test(newPassword) ? '✓' : '•'">•</span>
                                <span>At least one number (0-9)</span>
                            </div>
                            <div class="flex items-center gap-1.5 transition-colors" :class="/[^A-Za-z0-9]/.test(newPassword) ? 'text-emerald-700 font-bold' : 'text-stone-500'">
                                <span class="text-xs" x-text="/[^A-Za-z0-9]/.test(newPassword) ? '✓' : '•'">•</span>
                                <span>At least one symbol (@$!%*#?&)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="reg_password_confirmation" class="block text-xs font-bold text-stone-700 mb-1">
                        Confirm Password
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-stone-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        </span>
                        <input id="reg_password_confirmation" :type="showPass ? 'text' : 'password'" name="password_confirmation" required placeholder="••••••••" class="w-full rounded-xl border border-stone-300 bg-white pl-9 pr-3 py-2.5 text-sm text-stone-900 placeholder-stone-400 focus:border-[#c92137] focus:ring-2 focus:ring-red-100 outline-none transition">
                    </div>
                </div>

                @if(setting_bool('enable_email_verification', false))
                    <div class="rounded-xl bg-amber-50 border border-amber-200/80 p-2.5 text-[11px] text-amber-900">
                        <span class="font-bold">ℹ Email verification required:</span> A verification link will be sent to your email after registration.
                    </div>
                @else
                    <div class="rounded-xl bg-emerald-50 border border-emerald-200/80 p-2.5 text-[11px] text-emerald-900">
                        <span class="font-bold">✓ Fast Registration:</span> Email verification is optional; your account is active immediately.
                    </div>
                @endif

                <button type="submit" class="flex items-center justify-center gap-2 w-full rounded-xl bg-gradient-to-r from-[#c92137] to-[#a50404] hover:from-[#a50404] hover:to-[#830202] px-4 py-2.5 font-black text-white text-sm transition shadow-md hover:shadow-lg cursor-pointer">
                    <span>Create Account</span>
                </button>
            </form>

            <div class="mt-4 border-t border-stone-100 pt-3">
                <div class="flex items-center justify-between text-xs text-stone-500">
                    <span>Already have an account? <button type="button" @click="authTab = 'login'" class="font-extrabold text-[#0955cf] hover:underline cursor-pointer">Log in here</button></span>
                    <a href="{{ route('register') }}" wire:navigate.hover class="font-bold text-[#c92137] hover:underline inline-flex items-center gap-0.5">
                        <span>Full Page</span>
                        <span>↗</span>
                    </a>
                </div>
            </div>
        </div>
    @endauth
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
                <input x-ref="modalSearchInput" name="q" type="search" placeholder="शीर्षक, विषय, वा लेखकको नाम खोज्नुहोस्..." value="{{ request('q') }}" class="w-full rounded-xl border-2 border-stone-300 bg-stone-50 px-4 py-3 text-base font-semibold text-stone-900 placeholder-stone-400 focus:border-[#0955cf] focus:bg-white focus:outline-none transition shadow-inner" required>
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
                    <a href="{{ route('search', ['q' => $tag]) }}" wire:navigate.hover class="rounded-full bg-stone-100 px-3 py-1 text-xs font-bold text-stone-700 hover:bg-[#c92137] hover:text-white transition cursor-pointer">
                        # {{ $tag }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
