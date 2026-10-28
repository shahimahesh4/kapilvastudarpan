<x-layouts.app :title="$currentRashi['full_name'] . ' - राशिफल | कपिलवस्तु दर्पण'">
    <main class="site-container px-4 py-8 sm:px-6 lg:px-8" id="main-content">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-start">
            <!-- Left 8 Columns: Main Rashifal Content -->
            <div class="lg:col-span-8">
                <!-- Rashi Header: Icon + Title + Share Buttons (Matching Screenshot) -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-stone-200">
                    <div class="flex items-center gap-4">
                        <div class="shrink-0">
                            <span class="rashi-icon rashi-icon--{{ $currentRashi['index'] }} !w-16 !h-16 sm:!w-20 sm:!h-20 shadow-xs" aria-hidden="true"></span>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-stone-900 tracking-tight">
                                {{ $currentRashi['full_name'] }}
                            </h1>
                            <p class="mt-1 text-xs sm:text-sm font-medium text-stone-600 tracking-wide">
                                {{ $currentRashi['letters'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Social Share Buttons (Matching Screenshot Layout) -->
                    <div class="flex items-center gap-1.5 self-start sm:self-center">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="grid h-8 w-8 sm:h-9 sm:w-9 place-items-center rounded bg-[#3b5998] text-white hover:opacity-90 transition font-bold" title="Facebook मा सेयर गर्नुहोस्" aria-label="Facebook">
                            f
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($currentRashi['full_name'] . ' - राशिफल') }}" target="_blank" rel="noopener noreferrer" class="grid h-8 w-8 sm:h-9 sm:w-9 place-items-center rounded bg-black text-white hover:opacity-90 transition font-bold" title="𝕏 मा सेयर गर्नुहोस्" aria-label="X">
                            𝕏
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" rel="noopener noreferrer" class="grid h-8 w-8 sm:h-9 sm:w-9 place-items-center rounded bg-[#0077b5] text-white hover:opacity-90 transition font-bold text-xs" title="LinkedIn मा सेयर गर्नुहोस्" aria-label="LinkedIn">
                            in
                        </a>
                        <button type="button" onclick="if(navigator.share){navigator.share({title:'{{ $currentRashi['full_name'] }}',url:window.location.href})}else{navigator.clipboard.writeText(window.location.href);alert('लिङ्क कपी गरियो!')}" class="grid h-8 w-8 sm:h-9 sm:w-9 place-items-center rounded bg-[#7cb342] text-white hover:opacity-90 transition font-bold" title="सेयर गर्नुहोस्" aria-label="Share">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                        </button>
                    </div>
                </div>

                <!-- Horoscope Timeframe Tabs (Alpine.js State) -->
                <div x-data="{ activeTab: '{{ $activeTab }}' }" class="mt-6">
                    <!-- Tab Headers -->
                    <div class="flex items-center gap-1 sm:gap-2">
                        <button type="button" @click="activeTab = 'dainik'" :class="activeTab === 'dainik' ? 'border-t-2 border-x border-b-0 border-red-300 bg-[#fff8f8] text-[#c92137] font-extrabold rounded-t-lg -mb-[1px] px-5 sm:px-7 py-2.5 text-sm sm:text-base shadow-2xs' : 'text-stone-700 hover:text-stone-900 font-bold px-5 sm:px-7 py-2.5 text-sm sm:text-base border-b border-transparent'">
                            दैनिक
                        </button>
                        <button type="button" @click="activeTab = 'saptahik'" :class="activeTab === 'saptahik' ? 'border-t-2 border-x border-b-0 border-red-300 bg-[#fff8f8] text-[#c92137] font-extrabold rounded-t-lg -mb-[1px] px-5 sm:px-7 py-2.5 text-sm sm:text-base shadow-2xs' : 'text-stone-700 hover:text-stone-900 font-bold px-5 sm:px-7 py-2.5 text-sm sm:text-base border-b border-transparent'">
                            साप्ताहिक
                        </button>
                        <button type="button" @click="activeTab = 'masik'" :class="activeTab === 'masik' ? 'border-t-2 border-x border-b-0 border-red-300 bg-[#fff8f8] text-[#c92137] font-extrabold rounded-t-lg -mb-[1px] px-5 sm:px-7 py-2.5 text-sm sm:text-base shadow-2xs' : 'text-stone-700 hover:text-stone-900 font-bold px-5 sm:px-7 py-2.5 text-sm sm:text-base border-b border-transparent'">
                            मासिक
                        </button>
                        <button type="button" @click="activeTab = 'varshik'" :class="activeTab === 'varshik' ? 'border-t-2 border-x border-b-0 border-red-300 bg-[#fff8f8] text-[#c92137] font-extrabold rounded-t-lg -mb-[1px] px-5 sm:px-7 py-2.5 text-sm sm:text-base shadow-2xs' : 'text-stone-700 hover:text-stone-900 font-bold px-5 sm:px-7 py-2.5 text-sm sm:text-base border-b border-transparent'">
                            वार्षिक
                        </button>
                    </div>

                    <!-- Predictions Container (Matching Screenshot: Red Border & Soft Pinkish Tint) -->
                    <div class="border border-red-300/90 bg-[#fff8f8] rounded-b-xl rounded-tr-xl p-6 sm:p-8 shadow-xs">
                        <!-- Tab 1: दैनिक राशिफल -->
                        <div x-show="activeTab === 'dainik'" x-cloak class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-black text-stone-900">
                                आजको राशिफल
                            </h2>
                            <p class="text-sm sm:text-base leading-relaxed text-stone-800 text-justify">
                                {{ $currentRashi['predictions']['dainik'] }}
                            </p>
                        </div>

                        <!-- Tab 2: साप्ताहिक राशिफल -->
                        <div x-show="activeTab === 'saptahik'" x-cloak class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-black text-stone-900">
                                यस साताको राशिफल
                            </h2>
                            <p class="text-sm sm:text-base leading-relaxed text-stone-800 text-justify">
                                {{ $currentRashi['predictions']['saptahik'] }}
                            </p>
                        </div>

                        <!-- Tab 3: मासिक राशिफल -->
                        <div x-show="activeTab === 'masik'" x-cloak class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-black text-stone-900">
                                यस महिनाको राशिफल (असोज २०८३)
                            </h2>
                            <p class="text-sm sm:text-base leading-relaxed text-stone-800 text-justify">
                                {{ $currentRashi['predictions']['masik'] }}
                            </p>
                        </div>

                        <!-- Tab 4: वार्षिक राशिफल (Matching Exact Screenshot Content) -->
                        <div x-show="activeTab === 'varshik'" x-cloak class="space-y-4">
                            <h2 class="text-xl sm:text-2xl font-black text-stone-900">
                                २०८३ को राशिफल
                            </h2>
                            @foreach(explode("\n\n", $currentRashi['predictions']['varshik']) as $paragraph)
                                <p class="text-sm sm:text-base leading-relaxed text-stone-800 text-justify">
                                    {{ trim($paragraph) }}
                                </p>
                            @endforeach
                        </div>

                        <!-- Auspicious Factors Box -->
                        <div class="mt-8 pt-5 border-t border-red-200">
                            <h3 class="text-xs font-extrabold text-stone-800 uppercase tracking-wider mb-3">
                                🌟 शुभ तत्व र ज्योतिषीय विवरण
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 text-xs sm:text-sm">
                                <div class="rounded-lg bg-white p-2.5 border border-red-200 shadow-2xs">
                                    <span class="block text-stone-500 font-medium">शुभ अङ्क</span>
                                    <strong class="text-stone-900 font-extrabold">{{ $currentRashi['lucky_number'] }}</strong>
                                </div>
                                <div class="rounded-lg bg-white p-2.5 border border-red-200 shadow-2xs">
                                    <span class="block text-stone-500 font-medium">शुभ रङ्ग</span>
                                    <strong class="text-stone-900 font-extrabold">{{ $currentRashi['lucky_color'] }}</strong>
                                </div>
                                <div class="rounded-lg bg-white p-2.5 border border-red-200 shadow-2xs">
                                    <span class="block text-stone-500 font-medium">शुभ दिशा</span>
                                    <strong class="text-stone-900 font-extrabold">{{ $currentRashi['lucky_direction'] }}</strong>
                                </div>
                                <div class="rounded-lg bg-white p-2.5 border border-red-200 shadow-2xs">
                                    <span class="block text-stone-500 font-medium">शुभ वार</span>
                                    <strong class="text-stone-900 font-extrabold">{{ $currentRashi['lucky_day'] }}</strong>
                                </div>
                                @if(!empty($currentRashi['element']))
                                <div class="rounded-lg bg-white p-2.5 border border-red-200 shadow-2xs">
                                    <span class="block text-stone-500 font-medium">तत्व</span>
                                    <strong class="text-stone-900 font-extrabold">{{ $currentRashi['element'] }}</strong>
                                </div>
                                @endif
                                @if(!empty($currentRashi['ruler']))
                                <div class="rounded-lg bg-white p-2.5 border border-red-200 shadow-2xs">
                                    <span class="block text-stone-500 font-medium">स्वामी ग्रह</span>
                                    <strong class="text-stone-900 font-extrabold">{{ $currentRashi['ruler'] }}</strong>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- अन्य राशिफल (Matching Screenshot: Section Title & Cards Container) -->
                <section class="mt-12" aria-labelledby="other-rashifal-heading">
                    <div class="border-b-2 border-[#c92137] pb-1 inline-block mb-4">
                        <h2 id="other-rashifal-heading" class="text-2xl font-black text-[#c92137]">
                            अन्य राशिफल
                        </h2>
                    </div>

                    <div class="rounded-2xl bg-[#eee8e4] p-4 sm:p-6">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @foreach($allRashis as $other)
                                @if($other['slug'] !== $currentRashi['slug'])
                                    <a href="{{ route('rashifal.show', $other['slug']) }}" wire:navigate.hover class="bg-white rounded-xl p-3 sm:p-4 text-center shadow-xs hover:shadow-md hover:-translate-y-1 transition duration-200 block group">
                                        <span class="rashi-icon rashi-icon--{{ $other['index'] }} !w-16 !h-16 group-hover:scale-105 transition-transform" aria-hidden="true"></span>
                                        <h3 class="mt-2 text-sm sm:text-base font-extrabold text-stone-800 group-hover:text-[#c92137] transition-colors">
                                            {{ $other['name'] }}
                                        </h3>
                                        <p class="mt-0.5 sm:mt-1 text-[10px] sm:text-[11px] text-stone-500 truncate" title="{{ $other['letters'] }}">
                                            {{ $other['letters'] }}
                                        </p>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </section>
            </div>

            <!-- Right 4 Columns: Sidebar (Matching Screenshot 'भर्खरै' & 'ट्रेन्डिङ') -->
            <aside class="lg:col-span-4 space-y-8">
                <!-- Top Badge: ताजा (Matching Top Right of Screenshot) -->
                <div class="flex justify-end -mb-4">
                    <span class="inline-flex items-center gap-1 rounded-full bg-[#c92137] px-3.5 py-1 text-xs font-black text-white shadow-xs">
                        🔥 ताजा
                    </span>
                </div>

                <!-- भर्खरै (Recent News with Red Bullet Dots Matching Screenshot) -->
                <div>
                    <h3 class="text-2xl font-black text-[#c92137] flex items-center gap-2 mb-4">
                        <svg class="h-6 w-6 text-[#c92137]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>भर्खरै</span>
                    </h3>
                    <ul class="space-y-4">
                        @foreach($latestArticles as $latest)
                            <li class="flex items-start gap-2.5 group">
                                <span class="text-[#c92137] text-xl leading-none font-black select-none mt-0.5">•</span>
                                <a href="{{ article_url($latest) }}" wire:navigate.hover class="text-sm sm:text-[15px] font-bold leading-snug text-stone-900 group-hover:text-[#c92137] transition line-clamp-2">
                                    {{ $latest->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- ट्रेन्डिङ (Trending News with Solid Red Number Circles Matching Screenshot) -->
                <div class="pt-6 border-t border-stone-200">
                    <h3 class="text-2xl font-black text-[#c92137] flex items-center gap-2 mb-4">
                        <svg class="h-6 w-6 text-[#c92137]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        <span>ट्रेन्डिङ</span>
                    </h3>
                    <ol class="space-y-4">
                        @foreach($trendingArticles as $trend)
                            @php
                                $nepaliNums = ['१', '२', '३', '४', '५', '६', '७', '८', '९', '१०'];
                                $num = $nepaliNums[$loop->index] ?? $loop->iteration;
                            @endphp
                            <li class="flex items-start gap-3 group">
                                <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-[#c92137] text-xs font-black text-white shadow-xs">
                                    {{ $num }}.
                                </span>
                                <div class="min-w-0 flex-1">
                                    <a href="{{ article_url($trend) }}" wire:navigate.hover class="text-sm sm:text-[15px] font-bold leading-snug text-stone-900 group-hover:text-[#c92137] transition line-clamp-2">
                                        {{ $trend->title }}
                                    </a>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <!-- Sidebar Advertisement -->
                <aside aria-label="विज्ञापन" class="pt-4">
                    <x-ad-banner placement="sidebar" class="aspect-square" />
                </aside>
            </aside>
        </div>
    </main>
</x-layouts.app>
