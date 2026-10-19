<x-layouts.app
    :title="$title"
    :description="$metaDescription"
    keywords="नेपाल शेयर बजार, नेप्से लाइभ, nepse live data, share market nepal, nepse index, top gainers nepse, sharehub nepal, kapilvastudarpan"
>
    <!-- Breadcrumb -->
    <div class="bg-stone-100/80 border-b border-stone-200 py-3">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-stone-500 font-medium" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" wire:navigate.hover class="hover:text-[#c92137] transition flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/></svg>
                    <span>गृहपृष्ठ</span>
                </a>
                <span>›</span>
                <span class="text-stone-400">अर्थतन्त्र तथा बजार</span>
                <span>›</span>
                <span class="text-[#c92137] font-bold">नेपाल शेयर बजार (NEPSE)</span>
            </nav>
        </div>
    </div>

    <!-- Main Container -->
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 py-6 sm:py-8"
         x-data="{
             activeTab: 'gainers',
             isRefreshing: false,
             autoRefresh: true,
             refreshInterval: null,
             market: {{ Js::from($marketData) }},
             refreshData() {
                 this.isRefreshing = true;
                 fetch('{{ route('api.nepse.live') }}')
                     .then(res => res.json())
                     .then(res => {
                         if (res && res.data) {
                             this.market = res.data;
                         }
                     })
                     .catch(err => console.error('NEPSE refresh error', err))
                     .finally(() => {
                         setTimeout(() => this.isRefreshing = false, 400);
                     });
             },
             init() {
                 if (this.autoRefresh) {
                     this.refreshInterval = setInterval(() => this.refreshData(), 45000);
                 }
                 this.$watch('autoRefresh', val => {
                     if (val) {
                         this.refreshInterval = setInterval(() => this.refreshData(), 45000);
                     } else {
                         clearInterval(this.refreshInterval);
                     }
                 });
             }
         }">

        <!-- Header Title Banner -->
        <div class="bg-[#0955cf] text-white rounded-2xl p-5 sm:p-7 shadow-md relative overflow-hidden mb-6">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-white/20 text-white tracking-wide uppercase">
                            <span class="h-2 w-2 rounded-full" :class="market.market_status === 'OPEN' ? 'bg-emerald-400 animate-pulse' : 'bg-rose-300'"></span>
                            <span x-text="market.market_status_text">{{ $marketData['market_status_text'] }}</span>
                        </span>
                        <span class="text-xs text-blue-100 font-medium">
                            मिति: <strong x-text="market.nepali_date">{{ $marketData['nepali_date'] }}</strong> (<span x-text="market.as_of_time">{{ $marketData['as_of_time'] }}</span>)
                        </span>
                    </div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white flex items-center gap-2">
                        <span>📈</span>
                        <span>नेपाल शेयर बजार (NEPSE Live Market)</span>
                    </h1>
                    <p class="text-sm sm:text-base text-blue-100 font-medium mt-1">
                        नेपाल स्टक एक्सचेन्जको प्रत्यक्ष परिसूचक, उप-परिसूचकहरू, टप गेनर्स तथा कारोबार विवरण
                    </p>
                </div>

                <!-- Controls: Refresh + Source Badge -->
                <div class="flex items-center gap-2.5 shrink-0 self-start md:self-center">
                    <button
                        type="button"
                        @click="refreshData()"
                        :disabled="isRefreshing"
                        class="inline-flex items-center gap-2 rounded-xl bg-white text-[#0955cf] px-4 py-2 text-xs sm:text-sm font-bold shadow-md hover:bg-amber-300 hover:text-stone-900 transition-all duration-200 cursor-pointer disabled:opacity-50"
                        title="ताजा डेटा लोड गर्नुहोस्"
                    >
                        <svg class="h-4 w-4" :class="{ 'animate-spin': isRefreshing }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span x-text="isRefreshing ? 'लोड हुँदैछ...' : 'ताजा बनाउनुहोस्'">ताजा बनाउनुहोस्</span>
                    </button>

                    <label class="hidden sm:inline-flex items-center gap-1.5 text-xs text-white/90 bg-white/10 px-3 py-2 rounded-xl border border-white/20 cursor-pointer select-none">
                        <input type="checkbox" x-model="autoRefresh" class="rounded text-[#0955cf] focus:ring-0">
                        <span>स्वचालित अपडेट</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- 4 Major Indices Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <template x-for="(idx, index) in market.indices" :key="index">
                <div class="rounded-xl border border-stone-200/90 bg-white p-4 shadow-xs hover:shadow-md transition">
                    <div class="flex items-start justify-between gap-2">
                        <span class="text-xs font-bold text-stone-500 uppercase tracking-wide" x-text="idx.name"></span>
                        <span
                            class="inline-flex items-center text-[11px] font-extrabold px-2 py-0.5 rounded-full"
                            :class="idx.is_positive ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700'"
                        >
                            <span x-text="idx.is_positive ? '▲ +' : '▼ '"></span>
                            <span x-text="Number(idx.per_change).toFixed(2) + '%'"></span>
                        </span>
                    </div>
                    <div class="mt-2 flex items-baseline justify-between gap-2">
                        <div class="text-2xl font-black text-stone-900 tracking-tight" x-text="Number(idx.current).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></div>
                        <div
                            class="text-xs font-bold"
                            :class="idx.is_positive ? 'text-emerald-600' : 'text-rose-600'"
                        >
                            <span x-text="(idx.is_positive ? '+' : '') + Number(idx.change).toFixed(2)"></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Market Turnover Summary Cards (4 stats) -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 mb-6">
            <div class="rounded-xl bg-stone-50 border border-stone-200 p-3.5 text-center">
                <span class="text-[11px] font-bold text-stone-500 block uppercase">कुल कारोबार रकम (Turnover)</span>
                <span class="text-base sm:text-lg font-black text-[#0955cf] mt-0.5 block truncate" :title="market.total_turnover">
                    रु <span x-text="market.total_turnover_formatted">{{ $marketData['total_turnover_formatted'] }}</span>
                </span>
            </div>
            <div class="rounded-xl bg-stone-50 border border-stone-200 p-3.5 text-center">
                <span class="text-[11px] font-bold text-stone-500 block uppercase">कुल कित्ता (Traded Shares)</span>
                <span class="text-base sm:text-lg font-black text-stone-800 mt-0.5 block truncate">
                    <span x-text="market.total_shares_formatted">{{ $marketData['total_shares_formatted'] }}</span>
                </span>
            </div>
            <div class="rounded-xl bg-stone-50 border border-stone-200 p-3.5 text-center">
                <span class="text-[11px] font-bold text-stone-500 block uppercase">कुल कारोबार संख्या (Transactions)</span>
                <span class="text-base sm:text-lg font-black text-stone-800 mt-0.5 block truncate">
                    <span x-text="market.total_transactions_formatted">{{ $marketData['total_transactions_formatted'] }}</span>
                </span>
            </div>
            <div class="rounded-xl bg-stone-50 border border-stone-200 p-3.5 text-center">
                <span class="text-[11px] font-bold text-stone-500 block uppercase">कारोबार भएका कम्पनी (Scrips)</span>
                <span class="text-base sm:text-lg font-black text-stone-800 mt-0.5 block truncate">
                    <span x-text="market.total_scrips_formatted">{{ $marketData['total_scrips_formatted'] }}</span>
                </span>
            </div>
        </div>

        <!-- Market Breadth / Advance Decline Bar -->
        <div class="rounded-xl border border-stone-200 bg-white p-4 shadow-2xs mb-8">
            <div class="flex items-center justify-between mb-2 text-xs font-bold text-stone-600">
                <span class="flex items-center gap-1.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    <span>बढेका: <strong class="text-emerald-700" x-text="market.breadth.advances">{{ $marketData['breadth']['advances'] }}</strong></span>
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-stone-400"></span>
                    <span>स्थिर: <strong class="text-stone-700" x-text="market.breadth.unchanged">{{ $marketData['breadth']['unchanged'] }}</strong></span>
                </span>
                <span class="flex items-center gap-1.5">
                    <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                    <span>घटेका: <strong class="text-rose-700" x-text="market.breadth.declines">{{ $marketData['breadth']['declines'] }}</strong></span>
                </span>
            </div>
            @php
                $adv = (int) ($marketData['breadth']['advances'] ?? 1);
                $dec = (int) ($marketData['breadth']['declines'] ?? 1);
                $unc = (int) ($marketData['breadth']['unchanged'] ?? 0);
                $tot = max(1, $adv + $dec + $unc);
                $advPct = round(($adv / $tot) * 100, 1);
                $decPct = round(($dec / $tot) * 100, 1);
                $uncPct = round(($unc / $tot) * 100, 1);
            @endphp
            <div class="h-3 rounded-full bg-stone-200 overflow-hidden flex">
                <div class="bg-emerald-500 transition-all duration-500" style="width: {{ $advPct }}%" title="बढेका: {{ $adv }}"></div>
                <div class="bg-stone-400 transition-all duration-500" style="width: {{ $uncPct }}%" title="स्थिर: {{ $unc }}"></div>
                <div class="bg-rose-500 transition-all duration-500" style="width: {{ $decPct }}%" title="घटेका: {{ $dec }}"></div>
            </div>
        </div>

        <!-- Main Content 2-Column Layout (8 cols Market Movers + Sectors, 4 cols Sidebar) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- Left 8 Columns: Top Movers + Sector Indices -->
            <div class="lg:col-span-8 space-y-8">

                <!-- Top Movers Tabbed Container -->
                <div class="rounded-2xl border border-stone-200 bg-white shadow-xs overflow-hidden">
                    <!-- Tab Headers -->
                    <div class="flex items-center border-b border-stone-200 bg-stone-50/80 px-4 pt-3 gap-2 overflow-x-auto no-scrollbar">
                        <button
                            type="button"
                            @click="activeTab = 'gainers'"
                            class="px-4 py-2.5 text-sm font-extrabold border-b-2 transition whitespace-nowrap cursor-pointer"
                            :class="activeTab === 'gainers' ? 'border-emerald-600 text-emerald-700 bg-white rounded-t-lg shadow-2xs' : 'border-transparent text-stone-600 hover:text-stone-900'"
                        >
                            <span>🔥 धेरै कमाउने (Top Gainers)</span>
                        </button>
                        <button
                            type="button"
                            @click="activeTab = 'losers'"
                            class="px-4 py-2.5 text-sm font-extrabold border-b-2 transition whitespace-nowrap cursor-pointer"
                            :class="activeTab === 'losers' ? 'border-rose-600 text-rose-700 bg-white rounded-t-lg shadow-2xs' : 'border-transparent text-stone-600 hover:text-stone-900'"
                        >
                            <span>🔻 धेरै गुमाउने (Top Losers)</span>
                        </button>
                        <button
                            type="button"
                            @click="activeTab = 'turnover'"
                            class="px-4 py-2.5 text-sm font-extrabold border-b-2 transition whitespace-nowrap cursor-pointer"
                            :class="activeTab === 'turnover' ? 'border-[#0955cf] text-[#0955cf] bg-white rounded-t-lg shadow-2xs' : 'border-transparent text-stone-600 hover:text-stone-900'"
                        >
                            <span>💰 उच्च कारोबार (Top Turnover)</span>
                        </button>
                    </div>

                    <!-- Gainers Table -->
                    <div x-show="activeTab === 'gainers'" class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-stone-50 text-stone-600 text-xs font-black uppercase border-b border-stone-200">
                                <tr>
                                    <th class="px-4 py-3">कम्पनी (Symbol)</th>
                                    <th class="px-4 py-3 text-right">LTP (रु)</th>
                                    <th class="px-4 py-3 text-right">परिवर्तन</th>
                                    <th class="px-4 py-3 text-right">% परिवर्तन</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                <template x-for="(stock, idx) in market.top_gainers" :key="idx">
                                    <tr class="hover:bg-emerald-50/40 transition">
                                        <td class="px-4 py-3 font-bold text-stone-900">
                                            <a :href="'/sharemarket/ticker/' + stock.symbol" wire:navigate.hover class="group flex items-center gap-2.5 hover:underline" :title="stock.name + ' (' + stock.symbol + ')'">
                                                <img :src="'https://images.smtmcapital.com.np/logos/' + stock.symbol + '.png'"
                                                     :alt="stock.symbol"
                                                     class="w-7 h-7 rounded-full object-contain bg-white border border-stone-200 shrink-0 p-0.5"
                                                     onerror="this.style.display='none'">
                                                <div class="min-w-0">
                                                    <span class="block text-emerald-800 font-extrabold group-hover:text-[#0066cc]" x-text="stock.symbol"></span>
                                                    <span class="block text-xs font-normal text-stone-500 truncate max-w-xs" x-text="stock.name"></span>
                                                </div>
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono font-bold text-stone-900" x-text="Number(stock.ltp).toFixed(2)"></td>
                                        <td class="px-4 py-3 text-right font-mono font-bold text-emerald-600">
                                            +<span x-text="Number(stock.change).toFixed(2)"></span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="inline-block px-2 py-0.5 rounded text-xs font-extrabold bg-emerald-100 text-emerald-800">
                                                +<span x-text="Number(stock.per_change).toFixed(2)"></span>%
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Losers Table -->
                    <div x-show="activeTab === 'losers'" x-cloak class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-stone-50 text-stone-600 text-xs font-black uppercase border-b border-stone-200">
                                <tr>
                                    <th class="px-4 py-3">कम्पनी (Symbol)</th>
                                    <th class="px-4 py-3 text-right">LTP (रु)</th>
                                    <th class="px-4 py-3 text-right">परिवर्तन</th>
                                    <th class="px-4 py-3 text-right">% परिवर्तन</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                <template x-for="(stock, idx) in market.top_losers" :key="idx">
                                    <tr class="hover:bg-rose-50/40 transition">
                                        <td class="px-4 py-3 font-bold text-stone-900">
                                            <a :href="'/sharemarket/ticker/' + stock.symbol" wire:navigate.hover class="group flex items-center gap-2.5 hover:underline" :title="stock.name + ' (' + stock.symbol + ')'">
                                                <img :src="'https://images.smtmcapital.com.np/logos/' + stock.symbol + '.png'"
                                                     :alt="stock.symbol"
                                                     class="w-7 h-7 rounded-full object-contain bg-white border border-stone-200 shrink-0 p-0.5"
                                                     onerror="this.style.display='none'">
                                                <div class="min-w-0">
                                                    <span class="block text-rose-800 font-extrabold group-hover:text-[#0066cc]" x-text="stock.symbol"></span>
                                                    <span class="block text-xs font-normal text-stone-500 truncate max-w-xs" x-text="stock.name"></span>
                                                </div>
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono font-bold text-stone-900" x-text="Number(stock.ltp).toFixed(2)"></td>
                                        <td class="px-4 py-3 text-right font-mono font-bold text-rose-600">
                                            <span x-text="Number(stock.change).toFixed(2)"></span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span class="inline-block px-2 py-0.5 rounded text-xs font-extrabold bg-rose-100 text-rose-800">
                                                <span x-text="Number(stock.per_change).toFixed(2)"></span>%
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Turnover Table -->
                    <div x-show="activeTab === 'turnover'" x-cloak class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-stone-50 text-stone-600 text-xs font-black uppercase border-b border-stone-200">
                                <tr>
                                    <th class="px-4 py-3">कम्पनी (Symbol)</th>
                                    <th class="px-4 py-3 text-right">LTP (रु)</th>
                                    <th class="px-4 py-3 text-right">कारोबार रकम (रु)</th>
                                    <th class="px-4 py-3 text-right">% परिवर्तन</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                <template x-for="(stock, idx) in market.top_turnover" :key="idx">
                                    <tr class="hover:bg-blue-50/40 transition">
                                        <td class="px-4 py-3 font-bold text-stone-900">
                                            <a :href="'/sharemarket/ticker/' + stock.symbol" wire:navigate.hover class="group flex items-center gap-2.5 hover:underline" :title="stock.name + ' (' + stock.symbol + ')'">
                                                <img :src="'https://images.smtmcapital.com.np/logos/' + stock.symbol + '.png'"
                                                     :alt="stock.symbol"
                                                     class="w-7 h-7 rounded-full object-contain bg-white border border-stone-200 shrink-0 p-0.5"
                                                     onerror="this.style.display='none'">
                                                <div class="min-w-0">
                                                    <span class="block text-[#0955cf] font-extrabold group-hover:text-[#0066cc]" x-text="stock.symbol"></span>
                                                    <span class="block text-xs font-normal text-stone-500 truncate max-w-xs" x-text="stock.name"></span>
                                                </div>
                                            </a>
                                        </td>
                                        <td class="px-4 py-3 text-right font-mono font-bold text-stone-900" x-text="Number(stock.ltp).toFixed(2)"></td>
                                        <td class="px-4 py-3 text-right font-mono font-bold text-stone-800" x-text="Number(stock.turnover).toLocaleString()"></td>
                                        <td class="px-4 py-3 text-right">
                                            <span
                                                class="inline-block px-2 py-0.5 rounded text-xs font-extrabold"
                                                :class="stock.is_positive ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'"
                                            >
                                                <span x-text="(stock.is_positive ? '+' : '') + Number(stock.per_change).toFixed(2) + '%'"></span>
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Sector Performance Table (उप-परिसूचकहरू) -->
                <div class="rounded-2xl border border-stone-200 bg-white shadow-xs overflow-hidden">
                    <div class="border-b border-stone-200 bg-stone-50/80 px-5 py-3.5 flex items-center justify-between">
                        <h2 class="text-base sm:text-lg font-extrabold text-stone-900 flex items-center gap-2">
                            <span>📊</span>
                            <span>उप-परिसूचकहरू (Sector Indices)</span>
                        </h2>
                        <span class="text-xs text-stone-500 font-bold">१३ वटै समूहहरू</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-stone-50 text-stone-600 text-xs font-black uppercase border-b border-stone-200">
                                <tr>
                                    <th class="px-4 py-3">क्षेत्र (Sector)</th>
                                    <th class="px-4 py-3 text-right">परिसूचक (Index)</th>
                                    <th class="px-4 py-3 text-right">अंक परिवर्तन</th>
                                    <th class="px-4 py-3 text-right">% परिवर्तन</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100">
                                <template x-for="(sec, idx) in market.sub_indices" :key="idx">
                                    <tr class="hover:bg-stone-50 transition">
                                        <td class="px-4 py-3 font-bold text-stone-900" x-text="sec.name"></td>
                                        <td class="px-4 py-3 text-right font-mono font-bold text-stone-900" x-text="Number(sec.current).toFixed(2)"></td>
                                        <td
                                            class="px-4 py-3 text-right font-mono font-bold"
                                            :class="sec.is_positive ? 'text-emerald-600' : 'text-rose-600'"
                                        >
                                            <span x-text="(sec.is_positive ? '+' : '') + Number(sec.change).toFixed(2)"></span>
                                        </td>
                                        <td class="px-4 py-3 text-right">
                                            <span
                                                class="inline-block px-2 py-0.5 rounded text-xs font-extrabold"
                                                :class="sec.is_positive ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'"
                                            >
                                                <span x-text="(sec.is_positive ? '+' : '') + Number(sec.per_change).toFixed(2) + '%'"></span>
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

            <!-- Right 4 Columns: Sidebar Information, Business News, Trading Hours -->
            <aside class="lg:col-span-4 space-y-6">

                <!-- NEPSE Trading Hours Card -->
                <div class="rounded-2xl border border-stone-200 bg-stone-50/70 p-5 shadow-2xs">
                    <h3 class="text-base font-extrabold text-stone-900 flex items-center gap-2 border-b border-stone-200 pb-3">
                        <span>⏰</span>
                        <span>NEPSE कारोबार समय तालिका</span>
                    </h3>
                    <ul class="mt-3.5 space-y-2.5 text-xs sm:text-sm text-stone-700">
                        <li class="flex items-start justify-between gap-2">
                            <span class="font-medium text-stone-500">कारोबार दिनहरू:</span>
                            <span class="font-bold text-stone-900">आइतबार देखि बिहीबार</span>
                        </li>
                        <li class="flex items-start justify-between gap-2">
                            <span class="font-medium text-stone-500">नियमित कारोबार:</span>
                            <span class="font-bold text-[#0955cf]">बिहान ११:०० – दिउँसो ३:००</span>
                        </li>
                        <li class="flex items-start justify-between gap-2">
                            <span class="font-medium text-stone-500">प्रि-ओपन सेसन:</span>
                            <span class="font-bold text-stone-800">बिहान १०:३० – १०:४५</span>
                        </li>
                        <li class="flex items-start justify-between gap-2">
                            <span class="font-medium text-stone-500">सार्वजनिक बिदा:</span>
                            <span class="font-bold text-rose-600">शुक्रबार र शनिबार बन्द</span>
                        </li>
                    </ul>
                </div>

                <!-- Business / Economic News Strip -->
                @if(isset($businessNews) && $businessNews->isNotEmpty())
                    <div class="rounded-2xl border border-stone-200 bg-white p-5 shadow-xs">
                        <div class="flex items-center justify-between border-b border-stone-200 pb-3 mb-4">
                            <h3 class="text-base font-extrabold text-[#c92137] flex items-center gap-2">
                                <span>📰</span>
                                <span>अर्थतन्त्र तथा बजार समाचार</span>
                            </h3>
                            <a href="{{ route('category.show', 'arthatantra') }}" wire:navigate.hover class="text-xs font-bold text-[#0955cf] hover:underline">सबै ↗</a>
                        </div>

                        <div class="space-y-3.5 divide-y divide-stone-100">
                            @foreach($businessNews as $news)
                                <article class="pt-3 first:pt-0 group">
                                    <a href="{{ route('articles.show', $news->slug) }}" wire:navigate.hover class="flex items-start gap-3">
                                        <div class="h-16 w-20 shrink-0 rounded-lg overflow-hidden bg-stone-100">
                                            <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="h-full w-full object-cover group-hover:scale-105 transition duration-300">
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-xs sm:text-sm font-bold text-stone-900 group-hover:text-[#c92137] line-clamp-2 leading-snug transition">
                                                {{ $news->title }}
                                            </h4>
                                            <time class="mt-1 block text-[11px] text-stone-400">{{ $news->published_at?->diffForHumans() }}</time>
                                        </div>
                                    </a>
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Sidebar Ad Banner -->
                <div class="rounded-xl overflow-hidden border border-stone-200/80 shadow-2xs">
                    <x-ad-banner placement="sidebar" class="w-full" />
                </div>

                <!-- Nepali Unicode Tool Quick Box -->
                <div class="rounded-2xl bg-gradient-to-br from-red-50 to-blue-50 border border-blue-100 p-5 shadow-2xs">
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">⌨️</span>
                        <div>
                            <h4 class="font-extrabold text-stone-900 text-sm">नेपाली युनिकोड कन्भर्टर</h4>
                            <p class="text-xs text-stone-600 mt-0.5">रोमन तथा प्रीति फन्टलाई तत्काल युनिकोडमा बदल्नुहोस्।</p>
                        </div>
                    </div>
                    <a href="{{ route('unicode.converter') }}" wire:navigate.hover class="mt-3.5 flex items-center justify-center gap-1.5 w-full rounded-xl bg-[#c92137] text-white py-2 text-xs font-extrabold hover:bg-red-800 transition shadow-2xs">
                        <span>कन्भर्टर खोल्नुहोस्</span>
                        <span>→</span>
                    </a>
                </div>

                <!-- Market Disclaimer -->
                <div class="rounded-xl bg-stone-50 border border-stone-200/80 p-4 text-[11px] text-stone-500 leading-relaxed">
                    <strong class="text-stone-700 block mb-1">⚠️ डिस्क्लेमर (Disclaimer):</strong>
                    यहाँ प्रस्तुत शेयर बजार परिसूचक तथा विवरण जानकारी प्रयोजनका लागि मात्र हो। यसलाई शेयर खरिद वा बिक्रीको आधिकारिक वित्तीय परामर्शको रूपमा नलिनुहोला। लगानी गर्नुपूर्व आधिकारिक नेपाल स्टक एक्सचेन्ज (NEPSE) वा मान्यता प्राप्त ब्रोकरसँग परामर्श लिनुहोला।
                </div>

            </aside>

        </div>

    </div>
</x-layouts.app>
