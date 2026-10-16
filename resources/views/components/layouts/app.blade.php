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
    $shareImage = $ogImage ?? asset('images/kapilvastu-darpan-logo.png');
    $siteName = 'कपिलवस्तु दर्पण';
@endphp
<!DOCTYPE html>
<html lang="ne" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no">
    <meta name="robots" content="{{ $robots }}">
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ $keywords }}">
    <meta name="theme-color" content="#c92137">

    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}" sizes="32x32">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:locale" content="ne_NP">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $title }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:image" content="{{ $shareImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@kapilvastudarpan">
    <meta name="twitter:creator" content="@kapilvastudarpan">
    <meta name="twitter:title" content="{{ $title }}">
    <meta name="twitter:description" content="{{ $description }}">
    <meta name="twitter:image" content="{{ $shareImage }}">

    <!-- DNS Prefetch for Lightning Fast Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="https://npcdn.ratopati.com">
    <link rel="dns-prefetch" href="https://rpcdn.ratopati.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <title>{{ $title }}</title>

    <!-- Schema.org JSON-LD Structured Data for Rich Search Results -->
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@@graph": [
            {
                "@@type": "NewsMediaOrganization",
                "@@id": "https://www.kapilvastudarpan.com/#organization",
                "name": "कपिलवस्तु दर्पण",
                "url": "https://www.kapilvastudarpan.com",
                "logo": {
                    "@@type": "ImageObject",
                    "url": "{{ asset('images/kapilvastu-darpan-logo.png') }}"
                },
                "address": {
                    "@@type": "PostalAddress",
                    "streetAddress": "कपिलवस्तु १०, पचेहरा",
                    "addressLocality": "तौलिहवा",
                    "addressRegion": "लुम्बिनी प्रदेश",
                    "addressCountry": "NP"
                },
                "contactPoint": {
                    "@@type": "ContactPoint",
                    "telephone": "+977-9766250445",
                    "contactType": "newsroom",
                    "email": "kapilvastudarpannews@gmail.com"
                }
            },
            {
                "@@type": "WebSite",
                "@@id": "https://www.kapilvastudarpan.com/#website",
                "url": "https://www.kapilvastudarpan.com",
                "name": "कपिलवस्तु दर्पण",
                "publisher": {
                    "@@id": "https://www.kapilvastudarpan.com/#organization"
                },
                "potentialAction": {
                    "@@type": "SearchAction",
                    "target": "https://www.kapilvastudarpan.com/search?q={search_term_string}",
                    "query-input": "required name=search_term_string"
                }
            }
            @if ($article)
            ,{
                "@@type": "NewsArticle",
                "mainEntityOfPage": {
                    "@@type": "WebPage",
                    "@@id": "{{ $canonicalUrl }}"
                },
                "headline": "{{ addslashes($article->title) }}",
                "description": "{{ addslashes($article->excerpt ?? Str::limit(strip_tags($article->body), 160)) }}",
                "image": [
                    "{{ $article->image_url }}"
                ],
                "datePublished": "{{ $article->published_at?->toIso8601String() }}",
                "dateModified": "{{ $article->updated_at?->toIso8601String() }}",
                "author": {
                    "@@type": "Person",
                    "name": "{{ addslashes($article->author) }}"
                },
                "publisher": {
                    "@@id": "https://www.kapilvastudarpan.com/#organization"
                },
                "articleSection": "{{ addslashes($article->category?->name ?? 'समाचार') }}"
            }
            @endif
        ]
    }
    </script>

    <!-- SweetAlert2 for Interactive Notifications -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-white text-stone-900 antialiased font-sans selection:bg-[#c92137] selection:text-white min-h-screen flex flex-col"
    :class="{ 'site-dark': darkMode }"
    x-data="{
        flashOpen: false,
        sidebarOpen: false,
        accountOpen: false,
        searchOpen: false,
        authTab: 'login',
        menuPinned: false,
        darkMode: false,
        activeProvince: 'लुम्बिनी प्रदेश',
        setTheme() {
            localStorage.setItem('kapilvastu-theme', this.darkMode ? 'dark' : 'light');
        },
        init() {
            this.darkMode = localStorage.getItem('kapilvastu-theme') === 'dark';
            const updatePinnedState = () => {
                this.menuPinned = this.$refs.menuAnchor?.getBoundingClientRect().top <= 0;
            };
            this.$nextTick(updatePinnedState);
            window.addEventListener('scroll', updatePinnedState, { passive: true });
            window.addEventListener('resize', updatePinnedState);
        }
    }"
    @keydown.escape.window="flashOpen = false; sidebarOpen = false; accountOpen = false; searchOpen = false"
>
    <!-- Unified Global Header -->
    @include('partials.header')

    <!-- Main Content Slot -->
    <div class="flex-1">
        {{ $slot }}
    </div>

    <!-- Unified Global Footer -->
    @include('partials.footer')

    @livewireScripts

    <script>
        // Global SweetAlert2 notification helper
        window.copyShareLink = function(url) {
            const link = url || window.location.href;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(link).then(function() {
                    if (window.Swal) {
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
                    } else {
                        alert('लिङ्क कपी गरियो!');
                    }
                }).catch(function() {
                    prompt('कृपया यो लिङ्क कपी गर्नुहोस्:', link);
                });
            } else {
                prompt('कृपया यो लिङ्क कपी गर्नुहोस्:', link);
            }
        };

        window.notifySuccess = function(msg) {
            if (window.Swal) {
                Swal.fire({
                    icon: 'success',
                    title: 'सफल!',
                    text: msg,
                    confirmButtonColor: '#c92137'
                });
            } else {
                alert(msg);
            }
        };
    </script>
</body>
</html>
