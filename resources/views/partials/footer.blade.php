<!-- Global Unified Professional Clean Footer (Pure White Text & Elements) -->
<footer class="bg-gradient-to-b from-[#2055b7] via-[#1b4b9f] to-[#143d87] text-white text-[16px]" aria-labelledby="footer-heading">
    <!-- Top Brand & Social Intro Strip -->
    <div class="border-b border-white/20">
        <div class="mx-auto max-w-[1330px] px-4 sm:px-6 lg:px-8 py-5 flex flex-col md:flex-row items-center justify-between gap-4">
            <!-- Brand Logo & Motto -->
            <div class="flex flex-col sm:flex-row items-center gap-3.5 text-center sm:text-left">
                <a href="{{ route('home') }}" wire:navigate.hover class="inline-block bg-white rounded-lg px-2.5 py-1.5 shadow-sm hover:opacity-95 transition" aria-label="{{ setting('site_name', 'कपिलवस्तु दर्पण') }} गृहपृष्ठ">
                    <img src="{{ setting_image_url(setting('site_logo'), 'images/kapilvastu-darpan-logo.png') }}" alt="{{ setting('site_name', 'कपिलवस्तु दर्पण') }}" class="h-14 sm:h-16 w-auto object-contain">
                </a>
                <div>
                    <h3 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex items-center justify-center sm:justify-start gap-2.5">
                        <span>{{ setting('site_name', 'कपिलवस्तु दर्पण') }}</span>
                        @if($badge = setting('site_subbadge', 'डिजिटल न्युज'))
                            <span class="text-xs font-black bg-[#c92137] text-white px-2.5 py-0.5 rounded uppercase tracking-wider">{{ $badge }}</span>
                        @endif
                    </h3>
                    <p class="text-[16px] text-white font-medium mt-0.5">{{ setting('site_tagline', 'सत्य, तथ्य र निष्पक्ष पत्रकारिता — लुम्बिनी प्रदेशको सशक्त डिजिटल आवाज') }}</p>
                </div>
            </div>

            <!-- Top Right: Back to Top Button -->
            <button
                type="button"
                onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                class="inline-flex items-center gap-2 rounded-full bg-white/20 hover:bg-white hover:text-[#2055b7] text-white px-4.5 py-2 text-[16px] font-bold border border-white/40 transition-all duration-200 cursor-pointer shadow-xs shrink-0"
                title="माथि जानुहोस्"
            >
                <span>माथि जानुहोस्</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
            </button>
        </div>
    </div>

    <!-- Main Footer 4-Column Editorial Layout (Pure White Color Everywhere) -->
    <div class="mx-auto max-w-[1330px] px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-8 lg:gap-0 lg:divide-x lg:divide-white/20">

            <!-- Col 1 (4 cols): About, Legal Registration & Masthead -->
            <section class="lg:col-span-4 lg:pr-8 space-y-4" aria-labelledby="publisher-heading">
                <div>
                    <h2 id="publisher-heading" class="text-xl sm:text-[22px] font-black text-white tracking-wide border-b-2 border-white pb-2 inline-block">
                        {{ setting('site_name', 'कपिलवस्तु दर्पण') }}
                    </h2>
                </div>

                <!-- Registration Details List (All White Text) -->
                <ul class="space-y-2 text-[16px] text-white">
                    <li class="flex items-baseline gap-2">
                        <span class="text-white shrink-0 font-medium">सञ्चालक संस्था:</span>
                        <span class="font-bold text-white">{{ setting('company_name', 'कपिलवस्तु मल्टिपर्पोज मिडिया प्रा.लि.') }}</span>
                    </li>
                    <li class="flex items-baseline gap-2">
                        <span class="text-white shrink-0 font-medium">सूचना विभाग दर्ता नं:</span>
                        <span class="font-bold text-white">{{ setting('doi_reg_no', '५३१४/८२/८३') }}</span>
                    </li>
                    <li class="flex items-baseline gap-2">
                        <span class="text-white shrink-0 font-medium">कम्पनी दर्ता नं:</span>
                        <span class="font-bold text-white">{{ setting('company_reg_no', '३१७९१९/८२/८३') }}</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span class="text-white shrink-0 font-medium">प्रेस काउन्सिल सूचीकरण:</span>
                        <span class="font-bold text-white">{{ setting('press_council_no', '२०८२/८३') }}</span>
                        @if($pressBadge = setting('press_council_badge', 'आधिकारिक'))
                            <span class="text-xs font-bold text-white bg-white/20 px-2 py-0.5 rounded border border-white/40">{{ $pressBadge }}</span>
                        @endif
                    </li>
                </ul>

                <!-- Description -->
                <p class="text-[16px] leading-relaxed text-white pt-1">
                    {{ setting('footer_about', 'कपिलवस्तु दर्पण स्थानीय सरोकार, जनजीवन, विकास र राष्ट्रिय सवालहरूलाई जिम्मेवार र तथ्यपूर्ण ढङ्गले प्रस्तुत गर्ने आधिकारिक डिजिटल समाचार पोर्टल हो।') }}
                </p>

                <!-- Clean Divider Line -->
                <div class="border-t border-white/20 pt-3.5">
                    <h3 class="text-[16px] font-extrabold text-white uppercase tracking-wider mb-2">सम्पादकीय नेतृत्व</h3>
                    <ul class="space-y-1.5 text-[16px] text-white">
                        <li class="flex items-baseline gap-2">
                            <span class="text-white font-medium">संचालक :</span>
                            <span class="font-bold text-white">{{ setting('operator_name', 'तारानाथ केवट') }}</span>
                        </li>
                        <li class="flex items-baseline gap-2">
                            <span class="text-white font-medium">सम्पादक :</span>
                            <span class="font-bold text-white">{{ setting('editor_name', 'टुकलाल गैरे') }}</span>
                        </li>
                        <li class="flex items-baseline gap-2">
                            <span class="text-white font-medium">प्रबन्ध निर्देशक :</span>
                            <span class="font-bold text-white">{{ setting('managing_director', 'शेषकान्त मरासिनी') }}</span>
                        </li>
                        @if($execEditor = setting('executive_editor'))
                            <li class="flex items-baseline gap-2">
                                <span class="text-white font-medium">कार्यकारी सम्पादक :</span>
                                <span class="font-bold text-white">{{ $execEditor }}</span>
                            </li>
                        @endif
                        @if($newsChief = setting('news_chief'))
                            <li class="flex items-baseline gap-2">
                                <span class="text-white font-medium">समाचार प्रमुख :</span>
                                <span class="font-bold text-white">{{ $newsChief }}</span>
                            </li>
                        @endif
                        @if($bureauChief = setting('bureau_chief'))
                            <li class="flex items-baseline gap-2">
                                <span class="text-white font-medium">ब्युरो प्रमुख :</span>
                                <span class="font-bold text-white">{{ $bureauChief }}</span>
                            </li>
                        @endif
                        @if($legalAdvisor = setting('legal_advisor'))
                            <li class="flex items-baseline gap-2">
                                <span class="text-white font-medium">कानुनी सल्लाहकार :</span>
                                <span class="font-bold text-white">{{ $legalAdvisor }}</span>
                            </li>
                        @endif
                        @php
                            $extraMembers = setting('additional_team_members', []);
                            if (is_string($extraMembers)) {
                                $extraMembers = json_decode($extraMembers, true) ?? [];
                            }
                        @endphp
                        @if(!empty($extraMembers) && is_array($extraMembers))
                            @foreach($extraMembers as $member)
                                @if(!empty($member['name']))
                                    <li class="flex items-baseline gap-2">
                                        <span class="text-white font-medium">{{ $member['role'] ?? 'सदस्य' }} :</span>
                                        <span class="font-bold text-white">{{ $member['name'] }}</span>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </div>
            </section>

            <!-- Col 2 (3 cols): Contact Info & Social Links -->
            <section class="lg:col-span-3 lg:px-8 space-y-4" aria-labelledby="contact-heading">
                <div>
                    <h2 id="contact-heading" class="text-xl sm:text-[22px] font-black text-white tracking-wide border-b-2 border-white pb-2 inline-block">
                        सम्पर्क कार्यालय
                    </h2>
                </div>

                <!-- Clean Contact Rows (All White Text & Icons) -->
                <address class="space-y-3.5 not-italic text-[16px] text-white">
                    <div class="flex items-start gap-2.5">
                        <span class="text-white text-base mt-0.5 shrink-0">📍</span>
                        <span class="leading-relaxed">{{ setting('contact_address', 'कपिलवस्तु–१०, पचेहरा, लुम्बिनी प्रदेश, नेपाल') }}</span>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <span class="text-white text-base shrink-0">✉</span>
                        <a href="mailto:{{ setting('contact_email', 'kapilvastudarpannews@gmail.com') }}" class="text-white hover:underline transition font-semibold">
                            {{ setting('contact_email', 'kapilvastudarpannews@gmail.com') }}
                        </a>
                    </div>

                    <div class="flex items-center gap-2.5">
                        <span class="text-white text-base shrink-0">☎</span>
                        <a href="tel:{{ setting('contact_phone', '+९७७-९७६६२५०४४५') }}" class="text-white hover:underline transition font-semibold">
                            {{ setting('contact_phone', '+९७७-९७६६२५०४४५') }}@if(setting('contact_hotline')), {{ setting('contact_hotline') }}@endif
                        </a>
                    </div>

                    @php
                        $siteWebUrl = setting('website_url', setting('site_url', 'https://kapilvastudarpan.com'));
                        $siteWebDisplay = preg_replace('#^https?://#i', '', rtrim($siteWebUrl, '/'));
                        $siteWebLink = str_starts_with($siteWebUrl, 'http://') || str_starts_with($siteWebUrl, 'https://') ? $siteWebUrl : 'https://' . $siteWebUrl;
                    @endphp
                    <div class="flex items-center gap-2.5">
                        <span class="text-white text-base shrink-0">🌐</span>
                        <a href="{{ $siteWebLink }}" target="_blank" rel="noopener noreferrer" class="text-white hover:underline transition font-bold">
                            {{ $siteWebDisplay }}
                        </a>
                    </div>
                </address>

                <!-- Social Connect Strip -->
                @php
                    $footerFacebook = trim((string) setting('social_facebook', ''));
                    $footerTwitter = trim((string) setting('social_twitter', ''));
                    $footerInstagram = trim((string) setting('social_instagram', ''));
                    $footerYoutube = trim((string) setting('social_youtube', ''));
                    $footerTiktok = trim((string) setting('social_tiktok', ''));
                    $footerWhatsapp = trim((string) setting('social_whatsapp', ''));
                    $hasFooterSocial = $footerFacebook !== '' || $footerTwitter !== '' || $footerInstagram !== '' || $footerYoutube !== '' || $footerTiktok !== '' || $footerWhatsapp !== '';
                @endphp

                @if($hasFooterSocial)
                    <div class="border-t border-white/20 pt-3.5">
                        <span class="block text-[16px] font-bold text-white mb-2.5">सामाजिक सञ्जालमा जोडिनुहोस्</span>
                        <div class="flex items-center gap-3 flex-wrap">
                            @if($footerFacebook !== '')
                                <a href="{{ $footerFacebook }}" target="_blank" rel="noopener noreferrer" class="grid h-10 w-10 place-items-center rounded-full bg-white/20 hover:bg-[#1877f2] hover:text-white text-white hover:scale-110 transition-all duration-200" aria-label="Facebook" title="Facebook">
                                    <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                            @endif
                            @if($footerTwitter !== '')
                                <a href="{{ $footerTwitter }}" target="_blank" rel="noopener noreferrer" class="grid h-10 w-10 place-items-center rounded-full bg-white/20 hover:bg-black hover:text-white text-white hover:scale-110 transition-all duration-200" aria-label="X" title="X (Twitter)">
                                    <svg viewBox="0 0 24 24" class="h-4.5 w-4.5 fill-current" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                            @endif
                            @if($footerInstagram !== '')
                                <a href="{{ $footerInstagram }}" target="_blank" rel="noopener noreferrer" class="grid h-10 w-10 place-items-center rounded-full bg-white/20 hover:bg-[#e4405f] hover:text-white text-white hover:scale-110 transition-all duration-200" aria-label="Instagram" title="Instagram">
                                    <svg viewBox="0 0 24 24" class="h-4.5 w-4.5 fill-current" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                </a>
                            @endif
                            @if($footerYoutube !== '')
                                <a href="{{ $footerYoutube }}" target="_blank" rel="noopener noreferrer" class="grid h-10 w-10 place-items-center rounded-full bg-white/20 hover:bg-[#ff0000] hover:text-white text-white hover:scale-110 transition-all duration-200" aria-label="YouTube" title="YouTube">
                                    <svg viewBox="0 0 24 24" class="h-4.5 w-4.5 fill-current" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                </a>
                            @endif
                            @if($footerTiktok !== '')
                                <a href="{{ $footerTiktok }}" target="_blank" rel="noopener noreferrer" class="grid h-10 w-10 place-items-center rounded-full bg-white/20 hover:bg-black hover:text-white text-white hover:scale-110 transition-all duration-200" aria-label="TikTok" title="TikTok">
                                    <svg viewBox="0 0 24 24" class="h-4.5 w-4.5 fill-current" aria-hidden="true"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.96-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                                </a>
                            @endif
                            @if($footerWhatsapp !== '')
                                <a href="{{ str_starts_with($footerWhatsapp, 'http') ? $footerWhatsapp : 'https://wa.me/' . preg_replace('/[^0-9]/', '', $footerWhatsapp) }}" target="_blank" rel="noopener noreferrer" class="grid h-10 w-10 place-items-center rounded-full bg-white/20 hover:bg-[#25d366] hover:text-white text-white hover:scale-110 transition-all duration-200" aria-label="WhatsApp" title="WhatsApp">
                                    <svg viewBox="0 0 24 24" class="h-4.5 w-4.5 fill-current" aria-hidden="true"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </section>

            <!-- Col 3 (3 cols): Categories (All White Links & Bullets) -->
            <nav class="lg:col-span-3 lg:px-8 space-y-4" aria-labelledby="footer-menu-heading">
                <div>
                    <h2 id="footer-menu-heading" class="text-xl sm:text-[22px] font-black text-white tracking-wide border-b-2 border-white pb-2 inline-block">
                        प्रमुख विधाहरू
                    </h2>
                </div>
                <ul class="grid grid-cols-2 gap-x-4 gap-y-2.5 text-[16px] text-white">
                    @if(isset($categories) && $categories->isNotEmpty())
                        @foreach($categories->take(10) as $cat)
                            <li>
                                <a href="{{ route('category.show', $cat->slug) }}" wire:navigate.hover class="inline-flex items-center gap-1.5 font-medium text-white hover:underline hover:translate-x-1 transition-all duration-150 py-0.5">
                                    <span class="text-white text-xs">•</span>
                                    <span>{{ $cat->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    @else
                        @foreach(['मुख्य समाचार', 'कपिलवस्तु विशेष', 'राजनीति', 'अर्थतन्त्र', 'प्रदेश', 'समाज', 'अपराध-सुरक्षा', 'विचार', 'खेलकुद', 'स्वास्थ्य'] as $defaultCat)
                            <li>
                                <a href="#" class="inline-flex items-center gap-1.5 font-medium text-white hover:underline hover:translate-x-1 transition-all duration-150 py-0.5">
                                    <span class="text-white text-xs">•</span>
                                    <span>{{ $defaultCat }}</span>
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </nav>

            <!-- Col 4 (2 cols): Important Links (All White Links & Chevrons) -->
            <nav class="lg:col-span-2 lg:pl-8 space-y-4" aria-labelledby="about-heading">
                <div>
                    <h2 id="about-heading" class="text-xl sm:text-[22px] font-black text-white tracking-wide border-b-2 border-white pb-2 inline-block">
                        अन्य जानकारी
                    </h2>
                </div>
                <ul class="space-y-2.5 text-[16px] text-white">
                    <li>
                        <a href="{{ route('pages.show', 'about-us') }}" wire:navigate.hover class="inline-flex items-center gap-2 font-medium text-white hover:underline hover:translate-x-1 transition-all duration-150 py-0.5">
                            <span class="text-white font-bold text-base">›</span>
                            <span>हाम्रो बारेमा</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.show', 'our-team') }}" wire:navigate.hover class="inline-flex items-center gap-2 font-medium text-white hover:underline hover:translate-x-1 transition-all duration-150 py-0.5">
                            <span class="text-white font-bold text-base">›</span>
                            <span>हाम्रो टिम</span>
                        </a>
                    </li>
                    @if(setting_bool('sharemarket_enabled', true))
                    <li>
                        <a href="{{ route('sharemarket.index') }}" wire:navigate.hover class="inline-flex items-center gap-2 font-medium text-white hover:underline hover:translate-x-1 transition-all duration-150 py-0.5" title="नेपाल शेयर बजार (NEPSE)">
                            <span class="text-white font-bold text-base">›</span>
                            <span>नेपाल शेयर बजार (NEPSE)</span>
                        </a>
                    </li>
                    @endif
                    <li>
                        <a href="{{ route('unicode.converter') }}" wire:navigate.hover class="inline-flex items-center gap-2 font-medium text-white hover:underline hover:translate-x-1 transition-all duration-150 py-0.5" title="नेपाली युनिकोड कन्भर्टर">
                            <span class="text-white font-bold text-base">›</span>
                            <span>नेपाली युनिकोड कन्भर्टर</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.show', 'advertise') }}" wire:navigate.hover class="inline-flex items-center gap-2 font-medium text-white hover:underline hover:translate-x-1 transition-all duration-150 py-0.5">
                            <span class="text-white font-bold text-base">›</span>
                            <span>विज्ञापन दररेट</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.show', 'privacy-policy') }}" wire:navigate.hover class="inline-flex items-center gap-2 font-medium text-white hover:underline hover:translate-x-1 transition-all duration-150 py-0.5">
                            <span class="text-white font-bold text-base">›</span>
                            <span>गोपनीयता नीति</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.show', 'code-of-conduct') }}" wire:navigate.hover class="inline-flex items-center gap-2 font-medium text-white hover:underline hover:translate-x-1 transition-all duration-150 py-0.5">
                            <span class="text-white font-bold text-base">›</span>
                            <span>आचारसंहिता</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('pages.show', 'contact') }}" wire:navigate.hover class="inline-flex items-center gap-2 font-medium text-white hover:underline hover:translate-x-1 transition-all duration-150 py-0.5">
                            <span class="text-white font-bold text-base">›</span>
                            <span>सम्पर्क कार्यालय</span>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>

    <!-- Bottom Copyright & Developer Credit Bar -->
    <div class="border-t border-white/20 bg-[#143d87] py-4 text-[16px] text-white">
        <div class="mx-auto max-w-[1330px] px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
            <p class="leading-relaxed font-medium text-white text-[16px]">
                © {{ nepali_number(now()->year) }} <strong class="text-white font-bold">{{ setting('site_name', 'कपिलवस्तु दर्पण न्यूज') }}</strong> - {{ setting('footer_copyright', 'कपिलवस्तु मल्टिपर्पोज मिडिया प्रा.लि. सर्वाधिकार सुरक्षित।') }}
            </p>
            <div class="flex items-center gap-1.5 text-white font-medium justify-center sm:justify-end text-[16px]">
                <span>Developed By:</span>
                <a href="https://www.siddhitechnepal.com" target="_blank" rel="noopener noreferrer" class="font-bold text-white hover:underline transition inline-flex items-center gap-1">
                    <span>Siddhi Tech Nepal Pvt. Ltd.</span>
                    <span class="text-xs">↗</span>
                </a>
            </div>
        </div>
    </div>
</footer>
