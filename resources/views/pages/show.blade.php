<x-layouts.app
    :title="!empty($page->meta_title) ? $page->meta_title : ($page->clean_title . ' | ' . setting('site_name', 'कपिलवस्तु दर्पण'))"
    :description="$page->meta_description ?? $page->excerpt ?? Str::limit(strip_tags($page->body), 160)"
    :keywords="$page->meta_keywords ?? 'कपिलवस्तु दर्पण, ' . $page->clean_title"
    :ogType="'article'"
    :ogImage="$page->image_url"
>
    <!-- Main Unified Container (Matches articles, categories, and homepage: max-w-[1280px]) -->
    <main class="mx-auto max-w-[1280px] px-4 py-6 sm:py-8 sm:px-6 lg:px-8">

        <!-- Breadcrumb Navigation -->
        <nav class="mb-4 flex flex-wrap items-center justify-between gap-3 text-xs font-bold text-stone-500" aria-label="Breadcrumb">
            <div class="flex items-center gap-2 flex-wrap">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 rounded-full bg-stone-100 hover:bg-[#c92137] hover:text-white px-3 py-1 transition-all duration-200">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                    </svg>
                    <span>गृहपृष्ठ</span>
                </a>
                <span class="text-stone-300">/</span>
                <span class="inline-flex items-center gap-1 rounded-full bg-red-50 text-[#c92137] border border-red-200/80 px-3 py-1 font-black">
                    संस्थागत
                </span>
                <span class="text-stone-300">/</span>
                <span class="text-stone-800 font-bold line-clamp-1">{{ $page->clean_title }}</span>
            </div>
        </nav>

        <!-- Main 2-Column Layout: Left Document Canvas + Right Sticky Menu Box Bar -->
        <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start">

            <!-- ================================================================= -->
            <!-- LEFT DOCUMENT CANVAS                                              -->
            <!-- ================================================================= -->
            <article class="flex-1 min-w-0 bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-5 sm:p-7 lg:p-8">

                <!-- Masthead Header -->
                <header class="pb-5 border-b border-stone-100">
                    <div class="flex items-center gap-2 mb-2.5">
                        <span class="h-2 w-2 rounded-full bg-[#c92137]"></span>
                        <span class="text-[11px] font-extrabold uppercase tracking-widest text-[#c92137]">
                            कपिलवस्तु दर्पण डिजिटल न्यूज
                        </span>
                    </div>

                    <!-- Clean Title without English in bracket -->
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-[40px] font-black leading-[1.25] text-stone-900 tracking-tight">
                        {{ $page->clean_title }}
                    </h1>

                    @if($page->excerpt)
                        <p class="text-base sm:text-lg text-stone-600 font-medium leading-relaxed mt-4">
                            {{ $page->excerpt }}
                        </p>
                    @endif
                </header>

                <!-- Featured Image Banner (If present) -->
                @if($page->image_url)
                    <div class="my-8 overflow-hidden rounded-2xl border border-stone-200/80 shadow-xs">
                        <img src="{{ $page->image_url }}" alt="{{ $page->clean_title }}" class="w-full max-h-[440px] object-cover">
                    </div>
                @endif

                <!-- Rich Body Content -->
                <div class="article-body py-6">
                    {!! $page->body !!}
                </div>

                <!-- --------------------------------------------------------- -->
                <!-- TEMPLATE: CONTACT                                         -->
                <!-- --------------------------------------------------------- -->
                @if($page->template === 'contact' || $page->slug === 'contact')
                    <section class="mt-10 pt-8 border-t border-stone-200/80" aria-labelledby="contact-portal-title">
                        @if(session('success'))
                            <div class="mb-6 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800 text-sm font-bold flex items-center gap-3">
                                <span class="grid h-7 w-7 place-items-center rounded-full bg-emerald-600 text-white font-black text-xs shrink-0">✓</span>
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                            
                            <!-- Left: Contact Details Cards -->
                            <div class="space-y-4">
                                <h3 id="contact-portal-title" class="text-xl font-black text-stone-900 border-b-2 border-[#c92137] pb-2 inline-block">
                                    कार्यालय सम्पर्क विवरण
                                </h3>

                                <div class="rounded-2xl bg-stone-50/80 p-6 border border-stone-200/80 space-y-4 text-sm text-stone-700">
                                    <div class="flex items-start gap-3">
                                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-red-100 text-[#c92137] text-base font-bold">📍</span>
                                        <div>
                                            <strong class="block text-stone-900 font-bold">कार्यालय:</strong>
                                            <span>{{ setting('contact_address', 'कपिलवस्तु १०, पचेहरा (तौलिहवा), लुम्बिनी प्रदेश, नेपाल') }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-blue-100 text-[#0955cf] text-base font-bold">✉</span>
                                        <div>
                                            <strong class="block text-stone-900 font-bold">इमेल:</strong>
                                            <a href="mailto:{{ setting('contact_email', 'kapilvastudarpannews@gmail.com') }}" class="text-[#0955cf] font-semibold hover:underline">
                                                {{ setting('contact_email', 'kapilvastudarpannews@gmail.com') }}
                                            </a>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-emerald-100 text-emerald-700 text-base font-bold">☎</span>
                                        <div>
                                            <strong class="block text-stone-900 font-bold">फोन / सम्पर्क:</strong>
                                            <a href="tel:{{ setting('contact_phone', '+९७७-९७६६२५०४४५') }}" class="text-stone-800 font-bold hover:text-[#c92137]">
                                                {{ setting('contact_phone', '+९७७-९७६६२५०४४५') }}@if(setting('contact_hotline')), {{ setting('contact_hotline') }}@endif
                                            </a>
                                        </div>
                                    </div>

                                    <div class="flex items-start gap-3">
                                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-amber-100 text-amber-700 text-base font-bold">⏰</span>
                                        <div>
                                            <strong class="block text-stone-900 font-bold">कार्यालय समय:</strong>
                                            <span>{{ setting('office_hours', 'आइतबार – शुक्रबार, बिहान ९:०० देखि साँझ ६:०० बजेसम्म') }}</span>
                                        </div>
                                    </div>

                                    @php
                                        $pageWebUrl = setting('website_url', setting('site_url', 'https://kapilvastudarpan.com'));
                                        $pageWebDisplay = preg_replace('#^https?://#i', '', rtrim($pageWebUrl, '/'));
                                        $pageWebLink = str_starts_with($pageWebUrl, 'http://') || str_starts_with($pageWebUrl, 'https://') ? $pageWebUrl : 'https://' . $pageWebUrl;
                                    @endphp
                                    <div class="flex items-start gap-3">
                                        <span class="grid h-8 w-8 shrink-0 place-items-center rounded-xl bg-purple-100 text-purple-700 text-base font-bold">🌐</span>
                                        <div>
                                            <strong class="block text-stone-900 font-bold">आधिकारिक वेबसाइट:</strong>
                                            <a href="{{ $pageWebLink }}" target="_blank" rel="noopener noreferrer" class="text-[#0955cf] font-semibold hover:underline">
                                                {{ $pageWebDisplay }}
                                            </a>
                                        </div>
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-gradient-to-br from-red-50/50 to-white p-5 border border-red-100 space-y-1.5 text-xs text-stone-700">
                                    <h4 class="font-extrabold text-[#c92137] text-sm mb-1">सम्पादकीय नेतृत्व</h4>
                                    <p><strong>संचालक:</strong> {{ setting('operator_name', 'तारानाथ केवट') }}</p>
                                    <p><strong>सम्पादक:</strong> {{ setting('editor_name', 'टुकलाल गैरे') }}</p>
                                    <p><strong>प्रबन्ध निर्देशक:</strong> {{ setting('managing_director', 'शेषकान्त मरासिनी') }}</p>
                                    @if($exec = setting('executive_editor'))
                                        <p><strong>कार्यकारी सम्पादक:</strong> {{ $exec }}</p>
                                    @endif
                                    @if($news = setting('news_chief'))
                                        <p><strong>समाचार प्रमुख:</strong> {{ $news }}</p>
                                    @endif
                                    @if($bureau = setting('bureau_chief'))
                                        <p><strong>ब्युरो प्रमुख:</strong> {{ $bureau }}</p>
                                    @endif
                                    @if($legal = setting('legal_advisor'))
                                        <p><strong>कानुनी सल्लाहकार:</strong> {{ $legal }}</p>
                                    @endif
                                </div>
                            </div>

                            <!-- Right: Contact Form -->
                            <div class="rounded-2xl bg-stone-50/80 p-6 sm:p-7 border border-stone-200/80">
                                <h3 class="text-xl font-black text-stone-900 mb-1">
                                    हामीलाई सन्देश पठाउनुहोस्
                                </h3>
                                <p class="text-xs text-stone-500 mb-5">कुनै पनि प्रतिक्रिया, समाचार टिपोट वा सोधपुछका लागि यो फारम भर्नुहोस्।</p>

                                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                                    @csrf

                                    <div>
                                        <label for="contact-name" class="block text-xs font-bold text-stone-700 mb-1">तपाईंको पूरा नाम *</label>
                                        <input type="text" id="contact-name" name="name" required value="{{ old('name') }}" placeholder="उदा: राम शर्मा" class="w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm focus:border-[#c92137] focus:ring-1 focus:ring-[#c92137] focus:outline-none transition">
                                        @error('name') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <label for="contact-email" class="block text-xs font-bold text-stone-700 mb-1">इमेल ठेगाना *</label>
                                            <input type="email" id="contact-email" name="email" required value="{{ old('email') }}" placeholder="name@example.com" class="w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm focus:border-[#c92137] focus:ring-1 focus:ring-[#c92137] focus:outline-none transition">
                                            @error('email') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label for="contact-phone" class="block text-xs font-bold text-stone-700 mb-1">फोन नम्बर</label>
                                            <input type="tel" id="contact-phone" name="phone" value="{{ old('phone') }}" placeholder="९८XXXXXXXX" class="w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm focus:border-[#c92137] focus:ring-1 focus:ring-[#c92137] focus:outline-none transition">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="contact-subject" class="block text-xs font-bold text-stone-700 mb-1">विषय *</label>
                                        <input type="text" id="contact-subject" name="subject" required value="{{ old('subject') }}" placeholder="विज्ञापन / समाचार टिपोट / सुझाव" class="w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm focus:border-[#c92137] focus:ring-1 focus:ring-[#c92137] focus:outline-none transition">
                                        @error('subject') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="contact-message" class="block text-xs font-bold text-stone-700 mb-1">सन्देश *</label>
                                        <textarea id="contact-message" name="message" rows="4" required placeholder="तपाईंको सन्देश यहाँ लेख्नुहोस्..." class="w-full rounded-xl border border-stone-300 bg-white px-3.5 py-2.5 text-sm focus:border-[#c92137] focus:ring-1 focus:ring-[#c92137] focus:outline-none transition">{{ old('message') }}</textarea>
                                        @error('message') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <button type="submit" class="w-full rounded-xl bg-[#c92137] hover:bg-red-800 text-white font-extrabold py-3 text-sm transition-colors shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                                        <span>सन्देश पठाउनुहोस्</span>
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </section>
                @endif

                <!-- --------------------------------------------------------- -->
                <!-- TEMPLATE: TEAM                                            -->
                <!-- --------------------------------------------------------- -->
                @if($page->template === 'team' || $page->slug === 'our-team')
                    <section class="mt-10 pt-8 border-t border-stone-200/80" aria-labelledby="team-heading">
                        <div class="border-b-2 border-[#c92137] pb-2 inline-block mb-6">
                            <h3 id="team-heading" class="text-xl font-black text-stone-900">
                                हाम्रो सम्पादकीय तथा व्यवस्थापकीय नेतृत्व
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Operator / Chairman -->
                            <div class="rounded-2xl border border-stone-200/90 bg-stone-50/60 p-6 text-center space-y-4 hover:shadow-lg transition-all duration-300">
                                <div class="mx-auto w-32 sm:w-36 aspect-[3.5/4.5] rounded-xl overflow-hidden border-2 border-[#c92137]/80 p-1 bg-white shadow-md">
                                    <img src="{{ setting_image_url(setting('operator_photo'), 'images/team/taranath-kewat.png') }}" alt="{{ setting('operator_name', 'तारानाथ केवट') }}" class="h-full w-full object-cover rounded-lg">
                                </div>
                                <div>
                                    <h4 class="text-lg font-black text-stone-900">{{ setting('operator_name', 'तारानाथ केवट') }}</h4>
                                    <p class="text-sm font-extrabold text-[#c92137]">संचालक</p>
                                    <p class="text-xs text-stone-500 mt-1 font-medium">{{ setting('company_name', 'कपिलवस्तु मल्टिपर्पोज मिडिया प्रा.लि.') }}</p>
                                </div>
                            </div>

                            <!-- Editor-in-Chief -->
                            <div class="rounded-2xl border border-stone-200/90 bg-stone-50/60 p-6 text-center space-y-4 hover:shadow-lg transition-all duration-300">
                                <div class="mx-auto w-32 sm:w-36 aspect-[3.5/4.5] rounded-xl overflow-hidden border-2 border-[#c92137]/80 p-1 bg-white shadow-md">
                                    <img src="{{ setting_image_url(setting('editor_photo'), 'images/team/tuklal-gaire.png') }}" alt="{{ setting('editor_name', 'टुकलाल गैरे') }}" class="h-full w-full object-cover rounded-lg">
                                </div>
                                <div>
                                    <h4 class="text-lg font-black text-stone-900">{{ setting('editor_name', 'टुकलाल गैरे') }}</h4>
                                    <p class="text-sm font-extrabold text-[#c92137]">सम्पादक</p>
                                    <p class="text-xs text-stone-500 mt-1 font-medium">सम्पादकीय नेतृत्व एवं नीति</p>
                                </div>
                            </div>

                            <!-- Managing Director -->
                            <div class="rounded-2xl border border-stone-200/90 bg-stone-50/60 p-6 text-center space-y-4 hover:shadow-lg transition-all duration-300">
                                <div class="mx-auto w-32 sm:w-36 aspect-[3.5/4.5] rounded-xl overflow-hidden border-2 border-[#c92137]/80 p-1 bg-white shadow-md">
                                    <img src="{{ setting_image_url(setting('managing_director_photo'), 'images/team/sheskant-marasini.png') }}" alt="{{ setting('managing_director', 'शेषकान्त मरासिनी') }}" class="h-full w-full object-cover rounded-lg">
                                </div>
                                <div>
                                    <h4 class="text-lg font-black text-stone-900">{{ setting('managing_director', 'शेषकान्त मरासिनी') }}</h4>
                                    <p class="text-sm font-extrabold text-[#c92137]">प्रबन्ध निर्देशक</p>
                                    <p class="text-xs text-stone-500 mt-1 font-medium">व्यवस्थापन तथा सञ्चार प्रविधि</p>
                                </div>
                            </div>

                            @if($execName = setting('executive_editor'))
                                <div class="rounded-2xl border border-stone-200/90 bg-stone-50/60 p-6 text-center space-y-4 hover:shadow-lg transition-all duration-300">
                                    <div class="mx-auto w-32 sm:w-36 aspect-[3.5/4.5] rounded-xl overflow-hidden border-2 border-[#c92137]/80 p-1 bg-white shadow-md">
                                        <img src="{{ !empty(setting('executive_editor_photo')) ? setting_image_url(setting('executive_editor_photo')) : 'https://ui-avatars.com/api/?name=' . urlencode($execName) . '&background=c92137&color=fff' }}" alt="{{ $execName }}" class="h-full w-full object-cover rounded-lg">
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-black text-stone-900">{{ $execName }}</h4>
                                        <p class="text-sm font-extrabold text-[#c92137]">कार्यकारी सम्पादक</p>
                                    </div>
                                </div>
                            @endif

                            @if($newsName = setting('news_chief'))
                                <div class="rounded-2xl border border-stone-200/90 bg-stone-50/60 p-6 text-center space-y-4 hover:shadow-lg transition-all duration-300">
                                    <div class="mx-auto w-32 sm:w-36 aspect-[3.5/4.5] rounded-xl overflow-hidden border-2 border-[#c92137]/80 p-1 bg-white shadow-md">
                                        <img src="{{ !empty(setting('news_chief_photo')) ? setting_image_url(setting('news_chief_photo')) : 'https://ui-avatars.com/api/?name=' . urlencode($newsName) . '&background=c92137&color=fff' }}" alt="{{ $newsName }}" class="h-full w-full object-cover rounded-lg">
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-black text-stone-900">{{ $newsName }}</h4>
                                        <p class="text-sm font-extrabold text-[#c92137]">समाचार प्रमुख</p>
                                    </div>
                                </div>
                            @endif

                            @if($bureauName = setting('bureau_chief'))
                                <div class="rounded-2xl border border-stone-200/90 bg-stone-50/60 p-6 text-center space-y-4 hover:shadow-lg transition-all duration-300">
                                    <div class="mx-auto w-32 sm:w-36 aspect-[3.5/4.5] rounded-xl overflow-hidden border-2 border-[#c92137]/80 p-1 bg-white shadow-md">
                                        <img src="{{ !empty(setting('bureau_chief_photo')) ? setting_image_url(setting('bureau_chief_photo')) : 'https://ui-avatars.com/api/?name=' . urlencode($bureauName) . '&background=c92137&color=fff' }}" alt="{{ $bureauName }}" class="h-full w-full object-cover rounded-lg">
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-black text-stone-900">{{ $bureauName }}</h4>
                                        <p class="text-sm font-extrabold text-[#c92137]">ब्युरो प्रमुख</p>
                                    </div>
                                </div>
                            @endif

                            @php
                                $extraTeam = setting('additional_team_members', []);
                                if (is_string($extraTeam)) {
                                    $extraTeam = json_decode($extraTeam, true) ?? [];
                                }
                            @endphp
                            @if(!empty($extraTeam) && is_array($extraTeam))
                                @foreach($extraTeam as $member)
                                    @if(!empty($member['name']))
                                        <div class="rounded-2xl border border-stone-200/90 bg-stone-50/60 p-6 text-center space-y-4 hover:shadow-lg transition-all duration-300">
                                            <div class="mx-auto w-32 sm:w-36 aspect-[3.5/4.5] rounded-xl overflow-hidden border-2 border-[#c92137]/80 p-1 bg-white shadow-md">
                                                <img src="{{ !empty($member['photo']) ? setting_image_url($member['photo']) : 'https://ui-avatars.com/api/?name=' . urlencode($member['name']) . '&background=c92137&color=fff' }}" alt="{{ $member['name'] }}" class="h-full w-full object-cover rounded-lg">
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-black text-stone-900">{{ $member['name'] }}</h4>
                                                <p class="text-sm font-extrabold text-[#c92137]">{{ $member['role'] ?? 'टिम सदस्य' }}</p>
                                                @if(!empty($member['phone']))
                                                    <p class="text-xs text-stone-500 mt-1 font-medium"><a href="tel:{{ $member['phone'] }}" class="hover:text-[#c92137]">{{ $member['phone'] }}</a></p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </section>
                @endif

                <!-- Official Verification Stamp -->
                <div class="mt-12 pt-8 border-t border-stone-100 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-stone-500">
                    <div>
                        <strong class="text-stone-800 font-bold block">कपिलवस्तु मल्टिपर्पोज मिडिया प्रा.लि.</strong>
                        <p>सूचना तथा प्रसारण विभाग दर्ता नं. ५३१४/८२/८३ • कम्पनी दर्ता नं. ३१७९१९/८२/८३ • प्रेस काउन्सिल सूचीकृत</p>
                    </div>
                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200 px-3 py-1 font-bold shrink-0">
                        ✓ प्रमाणित आधिकारिक जानकारी
                    </span>
                </div>

            </article>

            <!-- ================================================================= -->
            <!-- RIGHT SIDE STICKY MENU BOX BAR                                    -->
            <!-- ================================================================= -->
            <aside class="w-full lg:w-64 xl:w-72 shrink-0 lg:sticky lg:top-24 space-y-4" aria-label="संस्थागत मेनु बार">
                
                <div class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-4 sm:p-5">
                    
                    <!-- Box Header -->
                    <div class="flex items-center gap-2.5 border-b border-stone-100 pb-3 mb-3">
                        <span class="grid h-8 w-8 place-items-center rounded-xl bg-[#c92137] text-white shrink-0 shadow-2xs">
                            <svg class="h-4 w-4 fill-current" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
                        </span>
                        <div>
                            <h3 class="font-black text-stone-900 text-base leading-tight">संस्थागत मेनु</h3>
                            <p class="text-[11px] text-stone-500 font-medium">आधिकारिक पृष्ठहरू</p>
                        </div>
                    </div>

                    <!-- Sticky Menu Items List -->
                    @if(isset($allPages) && $allPages->isNotEmpty())
                        <nav class="space-y-1" aria-label="संस्थागत पृष्ठ सूची">
                            @foreach($allPages as $navPage)
                                @php
                                    $isActive = ($navPage->id === $page->id);
                                @endphp
                                <a
                                    href="{{ route('pages.show', $navPage->slug) }}"
                                    class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm sm:text-[15px] font-bold transition-all duration-150 {{ $isActive ? 'bg-[#c92137] text-white shadow-xs' : 'text-stone-700 hover:bg-stone-50 hover:text-stone-900' }}"
                                >
                                    <span class="truncate leading-normal">{{ $navPage->clean_title }}</span>
                                    @if($isActive)
                                        <span class="text-xs shrink-0 ml-1.5">●</span>
                                    @else
                                        <span class="text-stone-300 text-sm shrink-0 ml-1.5">›</span>
                                    @endif
                                </a>
                            @endforeach
                        </nav>
                    @endif

                    <!-- Helpline & Legal Details inside Sticky Box -->
                    <div class="mt-4 pt-3.5 border-t border-stone-100 text-xs text-stone-500 space-y-1.5">
                        <p class="font-bold text-stone-800 text-[11px] uppercase tracking-wider">सम्पर्क हटलाइन:</p>
                        <a href="tel:{{ setting('contact_phone', '+९७७-९७६६२५०४४५') }}" class="block font-black text-sm sm:text-base text-stone-900 hover:text-[#c92137] transition truncate">
                            {{ setting('contact_phone', '+९७७-९७६६२५०४४५') }}
                        </a>
                        <p class="text-[11px] text-stone-400">दर्ता नं. {{ setting('doi_reg_no', '५३१४/८२/८३') }}</p>
                    </div>

                </div>

            </aside>

        </div>

        <!-- Bottom Institutional Pages Directory -->
        @if(isset($otherPages) && $otherPages->isNotEmpty())
            <div class="mt-12 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xs font-black text-stone-400 uppercase tracking-widest flex items-center gap-2">
                        <span>संस्थागत पृष्ठहरू</span>
                    </h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3.5">
                    @foreach ($otherPages as $op)
                        <a
                            href="{{ route('pages.show', $op->slug) }}"
                            class="group p-4 rounded-2xl border border-stone-200/80 bg-white hover:border-[#c92137] hover:shadow-xs transition-all flex flex-col justify-between"
                        >
                            <div>
                                <span class="text-[11px] font-semibold text-stone-400 block mb-1">आधिकारिक दस्ताबेज</span>
                                <h4 class="text-sm font-bold text-stone-800 group-hover:text-[#c92137] transition">
                                    {{ $op->clean_title }}
                                </h4>
                            </div>
                            <span class="text-xs font-extrabold text-[#c92137] mt-3 inline-flex items-center gap-1">
                                <span>अध्ययन गर्नुहोस्</span>
                                <span class="group-hover:translate-x-1 transition-transform">→</span>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </main>
</x-layouts.app>
