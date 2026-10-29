@props([
    'title' => 'कपिलवस्तु दर्पण | जनताको आवाज - ताजा समाचार, विचार र विश्लेषण',
    'description' => 'कपिलवस्तु दर्पण — जनताको आवाज, स्थानीयदेखि राष्ट्रिय समाचारसम्म। कपिलवस्तु, लुम्बिनी प्रदेश र देशभरका सत्य, तथ्य र निष्पक्ष ताजा समाचार, राजनीति, अर्थतन्त्र, खेलकुद र विचार।',
    'keywords' => 'कपिलवस्तु दर्पण, कपिलवस्तु समाचार, नेपाली समाचार, लुम्बिनी प्रदेश, तौलिहवा, ताजा अपडेट, अनलाइन खबर, kapilvastudarpan',
    'canonical' => null,
    'ogType' => 'website',
    'ogImage' => null,
    'article' => null,
    'robots' => 'index, follow',
])

@php
    $canonicalUrl = $canonical ?? url()->current();
    $shareImage = $ogImage ?? setting_image_url(setting('site_logo'), 'images/kapilvastu-darpan-logo.jpg');
    if ($shareImage && ! str_starts_with($shareImage, 'http://') && ! str_starts_with($shareImage, 'https://')) {
        $shareImage = url($shareImage);
    }
    $siteName = setting('site_name', 'कपिलवस्तु दर्पण');
    $defaultTitle = setting('meta_title', 'कपिलवस्तु दर्पण | जनताको आवाज - ताजा समाचार, विचार र विश्लेषण');
    $pageTitle = (!empty($title) && $title !== 'कपिलवस्तु दर्पण | जनताको आवाज - ताजा समाचार, विचार र विश्लेषण') ? $title : $defaultTitle;
    $siteUrl = rtrim(setting('website_url', url('/')), '/');
    $robots = request()->routeIs('login', 'register', 'password.*', 'dashboard*', 'search') ? 'noindex, follow' : $robots;
    $organizationId = $siteUrl . '/#organization';
    $websiteId = $siteUrl . '/#website';
    $structuredData = [
        [
            '@type' => 'NewsMediaOrganization',
            '@id' => $organizationId,
            'name' => $siteName,
            'url' => $siteUrl,
            'logo' => ['@type' => 'ImageObject', 'url' => setting_image_url(setting('site_logo'), 'images/kapilvastu-darpan-logo.jpg')],
        ],
        [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'url' => $siteUrl,
            'name' => $siteName,
            'publisher' => ['@id' => $organizationId],
            'inLanguage' => 'ne-NP',
        ],
    ];

    if ($article) {
        $primaryCategory = $article->categories->first() ?? $article->category;
        $articleImage = $article->imageUrl(1280);
        $breadcrumbItems = [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'गृहपृष्ठ', 'item' => $siteUrl],
        ];
        if ($primaryCategory) {
            $breadcrumbItems[] = ['@type' => 'ListItem', 'position' => 2, 'name' => $primaryCategory->name, 'item' => route('category.show', $primaryCategory->slug)];
        }
        $breadcrumbItems[] = ['@type' => 'ListItem', 'position' => count($breadcrumbItems) + 1, 'name' => $article->title, 'item' => $canonicalUrl];
        $structuredData[] = [
            '@type' => 'NewsArticle',
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $canonicalUrl],
            'headline' => $article->title,
            'description' => $article->meta_description ?: ($article->excerpt ?: Str::limit(strip_tags($article->body), 160)),
            'image' => [$articleImage],
            'datePublished' => $article->published_at?->toIso8601String(),
            'dateModified' => $article->updated_at?->toIso8601String(),
            'author' => ['@type' => 'Person', 'name' => $article->display_author],
            'publisher' => ['@id' => $organizationId],
            'articleSection' => $primaryCategory?->name ?? 'समाचार',
            'inLanguage' => 'ne-NP',
        ];
        $structuredData[] = ['@type' => 'BreadcrumbList', 'itemListElement' => $breadcrumbItems];
    }

    $robotsContent = collect(explode(',', $robots))
        ->map(fn (string $directive) => trim($directive))
        ->filter()
        ->push('max-image-preview:large')
        ->unique()
        ->implode(', ');
    $structuredDataJson = json_encode(
        ['@context' => 'https://schema.org', '@graph' => $structuredData],
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT,
    );
@endphp
<!DOCTYPE html>
<html lang="ne" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="robots" content="{{ $robotsContent }}">
    <meta name="description" content="{{ $description }}">
    <meta name="theme-color" content="#c92137">

    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="icon" type="image/png" href="{{ setting_image_url(setting('site_favicon'), 'images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ setting_image_url(setting('site_favicon'), 'images/favicon.png') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="ne_NP">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $shareImage }}">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@kapilvastudarpan">
    <meta name="twitter:creator" content="@kapilvastudarpan">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $shareImage }}">

    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <title>{{ $pageTitle }}</title>

    <script type="application/ld+json">{!! $structuredDataJson !!}</script>

    <!-- The converter is only needed on its dedicated page. -->
    @if(request()->routeIs('unicode.converter'))
        <script src="{{ asset('js/nepali-unicode-converter.js') }}" defer></script>
    @endif

    <!-- Preload Critical Mukta Devanagari Fonts (Eliminates LCP Delay & Chaining) -->
    <link rel="preload" href="{{ Vite::asset('resources/fonts/mukta-400-devanagari.woff2') }}" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="{{ Vite::asset('resources/fonts/mukta-700-devanagari.woff2') }}" as="font" type="font/woff2" crossorigin>

    <!-- Preload Article Featured Hero Image Early (Eliminates Speed Index Delay) -->
    @if ($article && !empty($article->image_path))
        <link rel="preload" as="image" href="{{ $article->imageUrl(1280) }}" fetchpriority="high">
    @endif

    {{-- Livewire provides Alpine; the former Vite JS entry only loaded unused Axios. --}}
    @vite(['resources/css/app.css'])
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
        /* Livewire 3 SPA Navigation Progress Bar in Brand Crimson */
        .livewire-progress-bar {
            background-color: #c92137 !important;
            height: 3px !important;
            box-shadow: 0 0 10px rgba(201, 33, 55, 0.8) !important;
            z-index: 99999 !important;
        }
    </style>

    @if(setting('header_scripts'))
        {!! setting('header_scripts') !!}
    @endif
</head>
<body class="bg-white text-stone-900 antialiased font-sans selection:bg-[#c92137] selection:text-white min-h-screen flex flex-col"
    :class="{ 'site-dark': darkMode }"
    x-data="{
        flashOpen: false,
        sidebarOpen: false,
        accountOpen: {{ (!request()->routeIs('login', 'register', 'password.*', 'dashboard*') && ($errors->any() || session('status_success') || session('status_warning') || session('unverified_email'))) ? 'true' : 'false' }},
        searchOpen: false,
        authTab: '{{ session('auth_tab', ($errors->has('name') || $errors->has('password_confirmation')) ? 'register' : 'login') }}',
        darkMode: false,
        activeProvince: 'लुम्बिनी प्रदेश',
        setTheme() {
            localStorage.setItem('kapilvastu-theme', this.darkMode ? 'dark' : 'light');
        },
        init() {
            this.darkMode = localStorage.getItem('kapilvastu-theme') === 'dark';
        }
    }"
    @keydown.escape.window="flashOpen = false; sidebarOpen = false; accountOpen = false; searchOpen = false"
>
    <!-- Admin Maintenance Mode Preview Banner -->
    @if(app()->isDownForMaintenance() && auth()->check() && auth()->user()->isAdmin())
        <aside aria-label="मेन्टेनेन्स मोड व्यवस्थापक सूचना" class="bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 text-white px-4 py-2 text-center text-sm sm:text-base font-bold shadow-md flex flex-wrap items-center justify-center gap-2 relative z-50">
            <span>⚠️ <strong>मेन्टेनेन्स मोड सक्रिय:</strong> सर्वसाधारण पाठकहरूलाई ५०३ मर्मत सूचना पृष्ठ देखाइएको छ। तपाईं व्यवस्थापक (Admin) भएकोले यो लाइभ साइट देखिरहनु भएको छ।</span>
            <a href="{{ url('/stnapanel') }}" class="inline-flex items-center gap-1 bg-white/20 hover:bg-white/30 text-white px-3 py-1 rounded-full text-xs sm:text-sm font-black transition underline">एडमिन प्यानल →</a>
        </aside>
    @endif

    <!-- Unified Global Header -->
    @include('partials.header')

    <!-- Main Content Slot -->
    <div class="flex-1">
        {{ $slot }}
    </div>

    <!-- Unified Global Footer -->
    @include('partials.footer')

    <!-- Left Bottom Browser Notification Subscriber Widget & Popup -->
    @include('partials.notification-subscriber')

    @livewireScripts

    <script>
        // Load SweetAlert only after a feature actually needs it, instead of on every page view.
        window.loadSweetAlert = function() {
            if (window.Swal) return Promise.resolve(window.Swal);
            if (window.__sweetAlertLoading) return window.__sweetAlertLoading;

            window.__sweetAlertLoading = new Promise(function(resolve, reject) {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
                script.async = true;
                script.onload = function() { resolve(window.Swal); };
                script.onerror = reject;
                document.head.appendChild(script);
            });

            return window.__sweetAlertLoading;
        };

        window.copyShareLink = function(url) {
            const link = url || window.location.href;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(link).then(function() {
                    window.loadSweetAlert().then(function() {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'समाचारको लिङ्क कपी गरियो!',
                            text: 'अब तपाईं यसलाई साथीहरूसँग सेयर गर्न सक्नुहुन्छ।',
                            showConfirmButton: false,
                            timer: 2500,
                            timerProgressBar: true
                        });
                    }).catch(function() {
                        alert('लिङ्क कपी गरियो!');
                    });
                }).catch(function() {
                    prompt('कृपया यो लिङ्क कपी गर्नुहोस्:', link);
                });
            } else {
                prompt('कृपया यो लिङ्क कपी गर्नुहोस्:', link);
            }
        };

        window.notifySuccess = function(msg) {
            window.loadSweetAlert().then(function() {
                Swal.fire({
                    icon: 'success',
                    title: 'सफल!',
                    text: msg,
                    confirmButtonColor: '#c92137'
                });
            }).catch(function() {
                alert(msg);
            });
        };

        // Livewire 3 SPA Navigation Lifecycle Handler
        document.addEventListener('livewire:navigated', function() {
            // Scroll to top instantly on page change
            window.scrollTo({ top: 0, behavior: 'instant' });

            // Close mobile menu / search / flash modals
            try {
                const bodyEl = document.querySelector('body');
                if (bodyEl && bodyEl._x_dataStack && bodyEl._x_dataStack[0]) {
                    const alpineData = bodyEl._x_dataStack[0];
                    if (alpineData.sidebarOpen) alpineData.sidebarOpen = false;
                    if (alpineData.searchOpen) alpineData.searchOpen = false;
                    if (alpineData.flashOpen) alpineData.flashOpen = false;
                }
            } catch(e) {}
        });
    </script>
</body>
</html>
