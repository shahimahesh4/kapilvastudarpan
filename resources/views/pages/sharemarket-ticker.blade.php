<x-layouts.app
    :title="$title"
    :description="$metaDescription"
    keywords="{{ $company['name'] }}, {{ $symbol }}, nepse {{ strtolower($symbol) }}, share price nepal, nepse stock quotes, kapilvastudarpan"
>
    <!-- Breadcrumb & Top Bar -->
    <div class="bg-stone-100/90 border-b border-stone-200 py-2.5">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 flex flex-wrap items-center justify-between gap-3">
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-stone-500 font-medium" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" wire:navigate.hover class="hover:text-[#0066cc] transition flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/></svg>
                    <span>गृहपृष्ठ</span>
                </a>
                <span>›</span>
                <a href="{{ route('sharemarket.index') }}" wire:navigate.hover class="hover:text-[#0066cc] transition">नेपाल शेयर बजार</a>
                <span>›</span>
                <span class="text-[#0066cc] font-bold">{{ $symbol }}</span>
            </nav>

            <!-- Quick Stock Search Jump -->
            <div class="relative w-full sm:w-64" x-data="{
                search: '',
                allStocks: [
                    { symbol: 'RADHI', name: 'Radhi Bidyut Company' },
                    { symbol: 'NABIL', name: 'Nabil Bank Limited' },
                    { symbol: 'CHCL', name: 'Chilime Hydropower' },
                    { symbol: 'SHIVM', name: 'Shivam Cements' },
                    { symbol: 'GBIME', name: 'Global IME Bank' },
                    { symbol: 'HATHY', name: 'Hathway Investment' },
                    { symbol: 'NLIC', name: 'Nepal Life Insurance' },
                    { symbol: 'UPPER', name: 'Upper Tamakoshi Hydro' },
                    { symbol: 'API', name: 'Api Power Company' },
                    { symbol: 'MBJC', name: 'Madhya Bhotekoshi Hydro' },
                    { symbol: 'NABBC', name: 'Narayani Development Bank' },
                    { symbol: 'SPHL', name: 'Sayapatri Hydropower' },
                    { symbol: 'NHPC', name: 'National Hydro Power' },
                    { symbol: 'CIT', name: 'Citizen Investment Trust' },
                    { symbol: 'HDL', name: 'Himalayan Distillery' }
                ],
                isOpen: false,
                get filtered() {
                    if (!this.search.trim()) return [];
                    const q = this.search.toUpperCase();
                    return this.allStocks.filter(s => s.symbol.includes(q) || s.name.toUpperCase().includes(q)).slice(0, 5);
                }
            }">
                <div class="relative">
                    <input type="text"
                           x-model="search"
                           @focus="isOpen = true"
                           @click.outside="isOpen = false"
                           placeholder="Search Stock Quotes (e.g. RADHI)"
                           class="w-full text-xs bg-white border border-stone-300 rounded-lg pl-8 pr-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#0066cc]/30 focus:border-[#0066cc] transition">
                    <svg class="w-3.5 h-3.5 text-stone-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <div x-show="isOpen && filtered.length > 0"
                     class="absolute z-50 left-0 right-0 mt-1 bg-white border border-stone-200 rounded-lg shadow-xl overflow-hidden py-1"
                     style="display: none;">
                    <template x-for="s in filtered" :key="s.symbol">
                        <a :href="'/sharemarket/ticker/' + s.symbol" wire:navigate.hover class="flex items-center justify-between px-3 py-1.5 text-xs hover:bg-stone-50 transition">
                            <span class="font-bold text-[#0066cc]" x-text="s.symbol"></span>
                            <span class="text-stone-500 truncate ml-2" x-text="s.name"></span>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 py-5 sm:py-7 space-y-6"
         x-data="{
             activeTab: 'overview',
             rangeMode: '24hr',
             brokerTime: 'today',
             brokerSide: 'buy',
             financialMetric: 'Revenue',
             financialSubtab: 'overview',
             chartRange: '1D',
             historyPage: 1,
             historyPerPage: 10,
             historyData: {{ Js::from($company['historical_trades'] ?? []) }},
             financials: {{ Js::from($company['financials'] ?? []) }},
             brokers: {{ Js::from($company['active_brokers'] ?? []) }},
             chartSeries: {{ Js::from($company['chart_series'] ?? []) }},
             inWatchlist: false,
             hoverChartPoint: null,
             hoverBarIndex: null,
             init() {
                 try {
                     const list = JSON.parse(localStorage.getItem('nepse_watchlist') || '[]');
                     this.inWatchlist = list.includes('{{ $symbol }}');
                 } catch (e) {}
             },
             toggleWatchlist() {
                 try {
                     let list = JSON.parse(localStorage.getItem('nepse_watchlist') || '[]');
                     if (this.inWatchlist) {
                         list = list.filter(s => s !== '{{ $symbol }}');
                         this.inWatchlist = false;
                     } else {
                         list.push('{{ $symbol }}');
                         this.inWatchlist = true;
                     }
                     localStorage.setItem('nepse_watchlist', JSON.stringify(list));
                 } catch (e) {}
             },
             get paginatedHistory() {
                 const start = (this.historyPage - 1) * this.historyPerPage;
                 return this.historyData.slice(start, start + this.historyPerPage);
             },
             get totalHistoryPages() {
                 return Math.ceil(this.historyData.length / this.historyPerPage);
             },
             get currentActiveBrokers() {
                 const timeData = this.brokers[this.brokerTime] || this.brokers['today'] || {};
                 return timeData[this.brokerSide] || [];
             },
             get currentMetricData() {
                 return this.financials.metrics ? this.financials.metrics[this.financialMetric] : null;
             },
             get currentChartPoints() {
                 return this.chartSeries[this.chartRange] || this.chartSeries['1D'] || [];
             },
             get mainChartComputed() {
                 const raw = this.chartSeries[this.chartRange] || this.chartSeries['1D'] || [];
                 if (!raw || raw.length === 0) return { points: [], linePath: '', areaPath: '', lastPt: null, min: 0, max: 0 };
                 const prices = raw.map(p => Number(p.price));
                 const min = Math.min(...prices);
                 const max = Math.max(...prices);
                 const range = (max === min) ? 1 : (max - min);
                 const svgW = 600;
                 const svgH = 240;
                 const padX = 15;
                 const padTop = 20;
                 const padBottom = 25;
                 const plotH = svgH - padTop - padBottom;
                 const count = raw.length;

                 const points = raw.map((pt, i) => {
                     const x = count > 1 ? padX + (i / (count - 1)) * (svgW - 2 * padX) : svgW / 2;
                     const y = padTop + (1 - (Number(pt.price) - min) / range) * plotH;
                     return {
                         x: Math.round(x * 10) / 10,
                         y: Math.round(y * 10) / 10,
                         price: Number(pt.price).toFixed(2),
                         time: pt.time || '',
                         datetime: pt.datetime || ('2026-10-16 ' + (pt.time || '')),
                     };
                 });

                 let linePath = '';
                 points.forEach((p, i) => {
                     linePath += (i === 0 ? `M ${p.x} ${p.y}` : ` L ${p.x} ${p.y}`);
                 });
                 const last = points[points.length - 1];
                 const first = points[0];
                 const areaPath = `${linePath} L ${last.x} ${svgH} L ${first.x} ${svgH} Z`;

                 return { points, linePath, areaPath, lastPt: last, min, max };
             },
             onMainChartMove(e) {
                 const data = this.mainChartComputed;
                 if (!data.points || data.points.length === 0) return;
                 const rect = e.currentTarget.getBoundingClientRect();
                 const mouseX = Math.max(0, Math.min(rect.width, e.clientX - rect.left));
                 const ratio = mouseX / rect.width;
                 const idx = Math.min(data.points.length - 1, Math.max(0, Math.round(ratio * (data.points.length - 1))));
                 const pt = data.points[idx];

                 const tipW = 150;
                 const pxX = (pt.x / 600) * rect.width;
                 const pxY = (pt.y / 240) * rect.height;
                 const tipLeft = Math.max(8, Math.min(rect.width - tipW - 8, pxX - (tipW / 2)));
                 const tipTop = Math.max(10, Math.min(rect.height - 75, pxY - 55));

                 this.hoverChartPoint = {
                     ...pt,
                     pxX,
                     pxY,
                     tipLeft,
                     tipTop,
                 };
             }
         }">

        <!-- Trending Strip (Screenshot 1) -->
        <div class="bg-white rounded-xl border border-stone-200/90 shadow-2xs p-2 flex flex-wrap lg:flex-nowrap items-center gap-3">
            <div class="bg-[#0066cc] text-white text-xs font-bold px-3.5 py-2 rounded-lg shrink-0 tracking-wide shadow-2xs">
                Trending..
            </div>
            <div class="flex-1 flex items-center gap-4 sm:gap-6 overflow-x-auto no-scrollbar py-1">
                @foreach($company['trending_stocks'] ?? [] as $tStock)
                    <a href="{{ route('sharemarket.ticker', $tStock['symbol']) }}"
                       wire:navigate.hover
                       class="shrink-0 flex items-center gap-2 text-xs hover:opacity-80 transition {{ $tStock['symbol'] === $symbol ? 'ring-2 ring-[#0066cc]/40 bg-blue-50/50 px-2 py-1 rounded-md' : '' }}">
                        <img src="https://images.smtmcapital.com.np/logos/{{ $tStock['symbol'] }}.png"
                             alt="{{ $tStock['symbol'] }}"
                             class="w-5 h-5 rounded-full object-contain bg-white border border-stone-200 shrink-0"
                             onerror="this.style.display='none'">
                        <div>
                            <span class="font-bold {{ $tStock['is_positive'] ? 'text-[#0066cc]' : 'text-stone-800' }}">{{ $tStock['symbol'] }}</span>
                            <span class="font-mono font-semibold text-stone-900 ml-1">{{ number_format($tStock['ltp'], 2) }}</span>
                        </div>
                        <span class="inline-flex items-center text-[11px] font-bold {{ $tStock['is_positive'] ? 'text-emerald-600' : 'text-rose-600' }}">
                            {{ $tStock['is_positive'] ? '+' : '' }}{{ number_format($tStock['per_change'], 2) }}%
                            {{ $tStock['is_positive'] ? '+' : '' }}{{ number_format($tStock['change'], 2) }}
                            <span>{{ $tStock['is_positive'] ? '↗' : '↘' }}</span>
                        </span>
                    </a>
                @endforeach
            </div>
            <a href="{{ route('sharemarket.index') }}"
               wire:navigate.hover
               class="shrink-0 ml-auto bg-amber-50 hover:bg-amber-100/80 border border-amber-200/80 text-stone-800 text-xs font-bold px-3.5 py-2 rounded-lg flex items-center gap-2 transition shadow-2xs">
                <span>View Stock Live</span>
                <span class="flex items-center gap-1 text-[11px] {{ ($marketData['market_status'] ?? 'CLOSED') === 'OPEN' ? 'text-emerald-600' : 'text-rose-600' }}">
                    <span class="h-2 w-2 rounded-full {{ ($marketData['market_status'] ?? 'CLOSED') === 'OPEN' ? 'bg-emerald-500 animate-pulse' : 'bg-rose-500' }}"></span>
                    <span>{{ ($marketData['market_status'] ?? 'CLOSED') === 'OPEN' ? 'Market Open' : 'Market Closed' }}</span>
                </span>
            </a>
        </div>

        <!-- Company Hero Header (Screenshot 1 - Highlighted by User) -->
        <div class="space-y-4">
                <div class="flex items-center gap-3.5">
                    <!-- Company Logo with Graceful Monogram Fallback -->
                    <div class="w-13 h-13 sm:w-14 sm:h-14 rounded-full bg-white flex items-center justify-center shadow-xs shrink-0 border border-stone-200 overflow-hidden relative ring-2 ring-blue-100 p-1"
                         x-data="{ imgFailed: false }">
                        <img src="{{ $company['logo_url'] ?? ('https://images.smtmcapital.com.np/logos/' . $symbol . '.png') }}"
                             alt="{{ $company['name'] }} Logo"
                             class="w-full h-full object-contain rounded-full"
                             loading="eager"
                             x-show="!imgFailed"
                             x-on:error="imgFailed = true">
                        <div x-show="imgFailed"
                             class="w-full h-full rounded-full bg-[#0955cf] text-white flex items-center justify-center font-black text-base sm:text-lg"
                             style="display: none;">
                            {{ substr($symbol, 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap">
                            <h1 class="text-xl sm:text-2xl font-black text-stone-900 tracking-tight">{{ $company['name'] }}</h1>
                            <span class="text-xs text-stone-500 font-medium">({{ $company['nepali_name'] }})</span>
                        </div>
                        <p class="text-xs sm:text-sm text-stone-500 font-medium">
                            Sector: <span class="text-stone-800 font-semibold">{{ $company['sector'] }}</span>
                        </p>
                    </div>
                </div>

                <!-- Add to Watchlist Button -->
                <button @click="toggleWatchlist()"
                        class="inline-flex items-center gap-2 text-xs sm:text-sm font-semibold px-4 py-2 rounded-lg border transition shadow-2xs"
                        :class="inWatchlist ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'bg-white border-stone-300 text-stone-700 hover:bg-stone-50'">
                    <span x-text="inWatchlist ? '✓' : '+'" class="text-sm font-bold"></span>
                    <span x-text="inWatchlist ? 'In Watchlist' : 'Add to Watch list'">Add to Watch list</span>
                </button>
            </div>

            <!-- Main Quote Card (Screenshot 1) -->
            <div class="bg-white rounded-2xl border border-stone-200/90 shadow-sm p-5 sm:p-6 grid grid-cols-1 md:grid-cols-12 gap-6 items-center">
                <!-- Left Quote Summary -->
                <div class="md:col-span-4 space-y-1">
                    <span class="text-xs font-bold text-stone-500 tracking-wider uppercase">{{ $symbol }}</span>
                    <div class="text-3xl sm:text-4xl lg:text-5xl font-black text-stone-900 font-mono tracking-tight">
                        {{ number_format($company['ltp'], 2) }}
                    </div>
                    <div class="flex items-center gap-2 pt-1">
                        <span class="inline-flex items-center gap-1 text-sm sm:text-base font-bold font-mono {{ $company['is_positive'] ? 'text-emerald-600' : 'text-rose-600' }}">
                            <span>{{ $company['is_positive'] ? '+' : '' }}{{ number_format($company['per_change'], 2) }}%</span>
                            <span>{{ $company['is_positive'] ? '+' : '' }}{{ number_format($company['change'], 2) }}</span>
                            <span class="text-xs">{{ $company['is_positive'] ? '↗' : '↘' }}</span>
                        </span>
                    </div>
                    <p class="text-[11px] text-stone-400 font-medium pt-1">
                        As on {{ $company['as_of_timestamp'] }}
                    </p>
                </div>

                <!-- Center Area Sparkline Visual (Screenshot 1) -->
                @php
                    $sparklineRaw = $company['chart_series']['1D'] ?? [];
                    $sparkPrices = array_column($sparklineRaw, 'price');
                    $sparkMin = !empty($sparkPrices) ? min($sparkPrices) : 0;
                    $sparkMax = !empty($sparkPrices) ? max($sparkPrices) : 1;
                    if ($sparkMax == $sparkMin) {
                        $sparkMax = $sparkMin + 1;
                    }
                    $svgW = 240;
                    $svgH = 80;
                    $padX = 8;
                    $padY = 10;
                    $sparkCoords = [];
                    $count = count($sparklineRaw);
                    foreach ($sparklineRaw as $i => $pt) {
                        $x = $count > 1 ? $padX + ($i / ($count - 1)) * ($svgW - 2 * $padX) : $svgW / 2;
                        $y = $padY + (1 - ($pt['price'] - $sparkMin) / ($sparkMax - $sparkMin)) * ($svgH - 2 * $padY);
                        $sparkCoords[] = [
                            'x' => round($x, 2),
                            'y' => round($y, 2),
                            'price' => number_format($pt['price'], 2, '.', ''),
                            'time' => $pt['time'] ?? '',
                            'datetime' => $pt['datetime'] ?? ('2026-10-16 ' . ($pt['time'] ?? '15:00') . ':00'),
                        ];
                    }
                    $sparkLinePath = '';
                    foreach ($sparkCoords as $i => $pt) {
                        $sparkLinePath .= ($i === 0 ? "M {$pt['x']} {$pt['y']}" : " L {$pt['x']} {$pt['y']}");
                    }
                    $lastX = !empty($sparkCoords) ? end($sparkCoords)['x'] : $svgW;
                    $lastY = !empty($sparkCoords) ? end($sparkCoords)['y'] : ($svgH / 2);
                    $firstX = !empty($sparkCoords) ? $sparkCoords[0]['x'] : 0;
                    $sparkAreaPath = !empty($sparkLinePath) ? "{$sparkLinePath} L {$lastX} {$svgH} L {$firstX} {$svgH} Z" : '';
                    $sparkColor = $company['is_positive'] ? '#10b981' : '#f43f5e';
                    $sparkGradientId = 'sparkGrad_' . preg_replace('/[^A-Za-z0-9]/', '', $symbol);
                @endphp
                <div class="md:col-span-4 h-24 sm:h-28 flex items-center justify-center relative overflow-hidden select-none cursor-crosshair"
                     x-data="{
                         sparkCoords: {{ Js::from($sparkCoords) }},
                         hoverPt: null,
                         tooltipLeft: 0,
                         onMove(e) {
                             if (!this.sparkCoords || this.sparkCoords.length === 0) return;
                             const rect = e.currentTarget.getBoundingClientRect();
                             const mouseX = Math.max(0, Math.min(rect.width, e.clientX - rect.left));
                             const ratio = mouseX / rect.width;
                             const idx = Math.min(this.sparkCoords.length - 1, Math.max(0, Math.round(ratio * (this.sparkCoords.length - 1))));
                             this.hoverPt = this.sparkCoords[idx];
                             const tipW = 140;
                             const rawTipLeft = mouseX - (tipW / 2);
                             this.tooltipLeft = Math.max(4, Math.min(rect.width - tipW - 4, rawTipLeft));
                         },
                         onLeave() {
                             this.hoverPt = null;
                         }
                     }"
                     @mousemove="onMove($event)"
                     @mouseleave="onLeave()">
                    <!-- Floating Tooltip Box matching Screenshot 1 -->
                    <div x-show="hoverPt"
                         class="absolute z-20 bg-white/95 backdrop-blur-xs border border-stone-200/90 rounded-md shadow-md px-2.5 py-1.5 text-xs pointer-events-none transition-transform duration-75"
                         :style="'left: ' + tooltipLeft + 'px; top: 6px;'"
                         style="display: none;">
                        <div class="text-[10px] text-stone-400 font-mono leading-tight" x-text="hoverPt?.datetime || ''"></div>
                        <div class="flex items-center gap-1.5 text-xs font-semibold text-stone-700 pt-0.5 leading-tight">
                            <span class="inline-block w-2 h-2 rounded-full" style="background-color: {{ $sparkColor }};"></span>
                            <span>{{ $symbol }}:</span>
                            <span class="font-bold font-mono text-stone-900" x-text="hoverPt?.price || ''"></span>
                        </div>
                    </div>

                    <!-- SVG Sparkline Visualizer -->
                    <svg viewBox="0 0 240 80" class="w-full h-full pointer-events-none" fill="none">
                        <defs>
                            <linearGradient id="{{ $sparkGradientId }}" x1="0%" y1="0%" x2="0%" y2="100%">
                                <stop offset="0%" stop-color="{{ $sparkColor }}" stop-opacity="0.30" />
                                <stop offset="100%" stop-color="{{ $sparkColor }}" stop-opacity="0.0" />
                            </linearGradient>
                        </defs>
                        <!-- Shaded Area Path -->
                        @if($sparkAreaPath)
                            <path d="{{ $sparkAreaPath }}" fill="url(#{{ $sparkGradientId }})"/>
                        @endif
                        <!-- Smooth Curve Line -->
                        @if($sparkLinePath)
                            <path d="{{ $sparkLinePath }}"
                                  stroke="{{ $sparkColor }}"
                                  stroke-width="2.2"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        @endif

                        <!-- Static Peak/Last Dot (Shown when not hovering) -->
                        <circle x-show="!hoverPt" cx="{{ $lastX }}" cy="{{ $lastY }}" r="3.5" fill="{{ $sparkColor }}" />

                        <!-- Vertical Dashed Crosshair Line (Shown on hover) -->
                        <line x-show="hoverPt"
                              :x1="hoverPt ? hoverPt.x : 0"
                              :x2="hoverPt ? hoverPt.x : 0"
                              y1="0"
                              y2="80"
                              stroke="#94a3b8"
                              stroke-width="1.2"
                              stroke-dasharray="2,2"
                              style="display: none;" />

                        <!-- Active Dot on Curve (Shown on hover) -->
                        <circle x-show="hoverPt"
                                :cx="hoverPt ? hoverPt.x : 0"
                                :cy="hoverPt ? hoverPt.y : 0"
                                r="4"
                                fill="{{ $sparkColor }}"
                                stroke="#ffffff"
                                stroke-width="1.5"
                                style="display: none;" />
                    </svg>
                </div>

                <!-- Right Metrics & Market Range Slider -->
                <div class="md:col-span-4 grid grid-cols-2 gap-4 border-t md:border-t-0 md:border-l border-stone-200/80 md:pl-6 pt-4 md:pt-0">
                    <div>
                        <div class="text-[11px] font-semibold text-stone-500 uppercase tracking-wider">Share Traded</div>
                        <div class="text-base sm:text-lg font-bold font-mono text-stone-900">{{ number_format($company['shares_traded']) }}</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-stone-500 uppercase tracking-wider">Market Cap</div>
                        <div class="text-base sm:text-lg font-bold font-mono text-stone-900">{{ $company['market_cap_arba'] }} Ar</div>
                    </div>
                    <div>
                        <div class="text-[11px] font-semibold text-stone-500 uppercase tracking-wider">Volume</div>
                        <div class="text-sm sm:text-base font-bold font-mono text-stone-900">{{ $company['turnover_formatted'] }}</div>
                    </div>

                    <!-- Market Range Dropdown & Slider -->
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-semibold text-stone-500">
                            <span>Market Range</span>
                            <select x-model="rangeMode" class="text-[11px] bg-stone-100 rounded px-1.5 py-0.5 border border-stone-200 focus:outline-none">
                                <option value="24hr">24hr</option>
                                <option value="52w">52W</option>
                            </select>
                        </div>
                        <div class="pt-2">
                            <!-- Low / High Slider Bar -->
                            <div class="relative h-1.5 bg-stone-200 rounded-full overflow-visible">
                                <div class="absolute top-0 bottom-0 bg-[#0066cc] rounded-full"
                                     :style="'left: 0%; width: ' + (rangeMode === '24hr' ? '92%' : '88%')"></div>
                                <div class="absolute top-1/2 -mt-1.5 h-3 w-3 rounded-full bg-emerald-500 border-2 border-white shadow"
                                     :style="'left: ' + (rangeMode === '24hr' ? '90%' : '86%')"></div>
                            </div>
                            <div class="flex items-center justify-between text-[11px] font-mono font-bold text-stone-600 pt-1.5">
                                <span><span class="text-rose-500 font-sans font-bold">L</span> <span x-text="rangeMode === '24hr' ? '{{ number_format($company['range_24h']['low'], 2) }}' : '{{ number_format($company['range_52w']['low'], 2) }}'">{{ number_format($company['range_24h']['low'], 2) }}</span></span>
                                <span><span class="text-emerald-500 font-sans font-bold">H</span> <span x-text="rangeMode === '24hr' ? '{{ number_format($company['range_24h']['high'], 2) }}' : '{{ number_format($company['range_52w']['high'], 2) }}'">{{ number_format($company['range_24h']['high'], 2) }}</span></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sticky Horizontal Tab Navigation (Screenshot 1 & 2) -->
            <div class="border-b border-stone-200/90 overflow-x-auto no-scrollbar">
                <nav class="flex items-center gap-6 sm:gap-8 whitespace-nowrap text-sm font-semibold text-stone-600">
                    @php
                        $tabs = [
                            'overview' => 'Overview',
                            'chart' => 'Chart',
                            'financials' => 'Financials',
                            'dividend' => 'Dividend History',
                            'movement' => 'Price Movement',
                            'keystats' => 'KeyStats',
                            'mf' => 'MF Ownership',
                            'announcements' => 'Announcement',
                            'faqs' => 'FAQs',
                            'news' => 'News',
                            'about' => 'About',
                        ];
                    @endphp
                    @foreach($tabs as $tabKey => $tabLabel)
                        <button @click="activeTab = '{{ $tabKey }}'"
                                class="py-3 border-b-2 transition relative text-xs sm:text-sm font-bold"
                                :class="activeTab === '{{ $tabKey }}' ? 'border-[#0066cc] text-[#0066cc]' : 'border-transparent text-stone-600 hover:text-stone-900'">
                            {{ $tabLabel }}
                        </button>
                    @endforeach
                </nav>
            </div>

        <!-- TAB 1: OVERVIEW (Screenshot 2) -->
        <div x-show="activeTab === 'overview'" class="space-y-6">
            <!-- 3 Column Cards: Quick View, Technical Indicators, Active Brokers -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Quick View Card (Screenshot 2) -->
                <div class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-5 sm:p-6 space-y-4">
                    <h3 class="text-base sm:text-lg font-black text-[#0066cc] tracking-tight">Quick View</h3>
                    <div class="divide-y divide-stone-100 text-xs sm:text-sm font-medium">
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">Open</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['quick_view']['open'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">EPS (Diluted)</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['quick_view']['eps_diluted'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">Avg Vol</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['quick_view']['avg_vol'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">PE (Diluted)</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['quick_view']['pe_diluted'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">PB Ratio</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['quick_view']['pb_ratio'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">Div Yield</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['quick_view']['div_yield'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">Beta</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['quick_view']['beta'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Technical Indicators Card (Screenshot 2) -->
                <div class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-5 sm:p-6 space-y-4">
                    <h3 class="text-base sm:text-lg font-black text-[#0066cc] tracking-tight">Technical Indicators</h3>
                    <div class="divide-y divide-stone-100 text-xs sm:text-sm font-medium">
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">RSI</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['technical_indicators']['rsi'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">MFI</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['technical_indicators']['mfi'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">MACD</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['technical_indicators']['macd'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">MA20</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['technical_indicators']['ma20'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">MA50</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['technical_indicators']['ma50'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">MA200</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['technical_indicators']['ma200'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">ADI</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['technical_indicators']['adi'] }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2.5">
                            <span class="text-stone-500 italic">CCI</span>
                            <span class="font-bold font-mono text-stone-900">{{ $company['technical_indicators']['cci'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Active Brokers Card (Screenshot 2) -->
                <div class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-5 sm:p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base sm:text-lg font-black text-[#0066cc] tracking-tight">Active Brokers</h3>
                        <!-- Time Filters -->
                        <div class="inline-flex rounded-lg bg-stone-100 p-0.5 text-xs font-bold">
                            <button @click="brokerTime = 'today'"
                                    :class="brokerTime === 'today' ? 'bg-white text-stone-900 shadow-2xs' : 'text-stone-500 hover:text-stone-900'"
                                    class="px-2.5 py-1 rounded-md transition">Today</button>
                            <button @click="brokerTime = '1w'"
                                    :class="brokerTime === '1w' ? 'bg-white text-stone-900 shadow-2xs' : 'text-stone-500 hover:text-stone-900'"
                                    class="px-2.5 py-1 rounded-md transition">1W</button>
                            <button @click="brokerTime = '1m'"
                                    :class="brokerTime === '1m' ? 'bg-white text-stone-900 shadow-2xs' : 'text-stone-500 hover:text-stone-900'"
                                    class="px-2.5 py-1 rounded-md transition">1M</button>
                        </div>
                    </div>

                    <!-- Buy Side / Sell Side Pills -->
                    <div class="flex items-center gap-2">
                        <button @click="brokerSide = 'buy'"
                                :class="brokerSide === 'buy' ? 'bg-blue-50 text-[#0066cc] font-bold border border-blue-200' : 'text-stone-600 hover:text-stone-900'"
                                class="text-xs px-3 py-1.5 rounded-lg transition">
                            Buy Side
                        </button>
                        <button @click="brokerSide = 'sell'"
                                :class="brokerSide === 'sell' ? 'bg-rose-50 text-rose-600 font-bold border border-rose-200' : 'text-stone-600 hover:text-stone-900'"
                                class="text-xs px-3 py-1.5 rounded-lg transition">
                            Sell Side
                        </button>
                    </div>

                    <!-- Scrollable Table -->
                    <div class="overflow-y-auto max-h-72 border border-stone-100 rounded-xl">
                        <table class="w-full text-xs text-left">
                            <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider sticky top-0 border-b border-stone-200">
                                <tr>
                                    <th class="py-2.5 px-3 font-semibold">Broker</th>
                                    <th class="py-2.5 px-3 font-semibold text-right">Qty ↕</th>
                                    <th class="py-2.5 px-3 font-semibold text-right">Avg. Price ↕</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 font-mono">
                                <template x-for="row in currentActiveBrokers" :key="row.broker">
                                    <tr class="hover:bg-stone-50/70 transition">
                                        <td class="py-2 px-3 font-bold text-stone-900" x-text="row.broker"></td>
                                        <td class="py-2 px-3 text-right text-stone-700" x-text="Number(row.qty).toLocaleString()"></td>
                                        <td class="py-2 px-3 text-right font-bold text-stone-900" x-text="Number(row.avg_price).toFixed(2)"></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Charts & Price Actions (Screenshot 3) -->
            <div class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-5 sm:p-7 space-y-6">
                <!-- Header with Blue Underline -->
                <div class="flex items-center justify-between border-b border-stone-100 pb-3">
                    <div class="relative">
                        <h2 class="text-xl sm:text-2xl font-black text-stone-900 tracking-tight">Charts</h2>
                        <div class="absolute -bottom-3.5 left-0 w-12 h-1 bg-[#0066cc] rounded-full"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <!-- Left: Interactive Area Chart (Screenshot 3) -->
                    <div class="lg:col-span-8 space-y-4">
                        <div class="relative h-72 sm:h-80 w-full bg-stone-50/50 rounded-xl border border-stone-200/70 p-4 flex flex-col justify-between overflow-hidden cursor-crosshair select-none"
                             @mousemove="onMainChartMove($event)"
                             @mouseleave="hoverChartPoint = null">
                            <!-- Floating Tooltip Box matching Screenshot 3 -->
                            <div x-show="hoverChartPoint"
                                 class="absolute z-20 bg-white/95 backdrop-blur-xs border border-stone-200/90 rounded-md shadow-md px-2.5 py-1.5 text-xs pointer-events-none transition-transform duration-75"
                                 :style="'left: ' + (hoverChartPoint?.tipLeft || 50) + 'px; top: ' + (hoverChartPoint?.tipTop || 20) + 'px;'"
                                 style="display: none;">
                                <div class="text-[10px] text-stone-400 font-mono leading-tight" x-text="hoverChartPoint?.datetime || ''"></div>
                                <div class="flex items-center gap-1.5 text-xs font-semibold text-stone-700 pt-0.5 leading-tight">
                                    <span class="inline-block w-2 h-2 rounded-full" style="background-color: {{ $company['is_positive'] ? '#10b981' : '#f43f5e' }};"></span>
                                    <span>{{ $symbol }}:</span>
                                    <span class="font-bold font-mono text-stone-900" x-text="hoverChartPoint?.price || ''"></span>
                                </div>
                            </div>

                            <!-- SVG Curve Visualizer -->
                            <svg class="w-full h-full pointer-events-none" viewBox="0 0 600 240" preserveAspectRatio="none">
                                <defs>
                                    <linearGradient id="chartGradientBig" x1="0%" y1="0%" x2="0%" y2="100%">
                                        <stop offset="0%" stop-color="{{ $company['is_positive'] ? '#10b981' : '#f43f5e' }}" stop-opacity="0.35" />
                                        <stop offset="100%" stop-color="{{ $company['is_positive'] ? '#10b981' : '#f43f5e' }}" stop-opacity="0.0" />
                                    </linearGradient>
                                </defs>
                                <!-- Area under curve -->
                                <path :d="mainChartComputed.areaPath"
                                      fill="url(#chartGradientBig)" />
                                <!-- Main Line Curve -->
                                <path :d="mainChartComputed.linePath"
                                      stroke="{{ $company['is_positive'] ? '#10b981' : '#f43f5e' }}"
                                      stroke-width="2.5"
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      fill="none" />
                                <!-- End Dot (Shown when not hovering) -->
                                <circle x-show="!hoverChartPoint && mainChartComputed.lastPt"
                                        :cx="mainChartComputed.lastPt ? mainChartComputed.lastPt.x : 570"
                                        :cy="mainChartComputed.lastPt ? mainChartComputed.lastPt.y : 30"
                                        r="4.5"
                                        fill="{{ $company['is_positive'] ? '#10b981' : '#f43f5e' }}"
                                        class="animate-pulse" />

                                <!-- Vertical Crosshair (Shown on hover) -->
                                <line x-show="hoverChartPoint"
                                      :x1="hoverChartPoint ? hoverChartPoint.x : 0"
                                      :x2="hoverChartPoint ? hoverChartPoint.x : 0"
                                      y1="0"
                                      y2="240"
                                      stroke="#94a3b8"
                                      stroke-width="1.2"
                                      stroke-dasharray="3,3"
                                      style="display: none;" />

                                <!-- Active Dot on Curve (Shown on hover) -->
                                <circle x-show="hoverChartPoint"
                                        :cx="hoverChartPoint ? hoverChartPoint.x : 0"
                                        :cy="hoverChartPoint ? hoverChartPoint.y : 0"
                                        r="5"
                                        fill="{{ $company['is_positive'] ? '#10b981' : '#f43f5e' }}"
                                        stroke="#ffffff"
                                        stroke-width="2"
                                        style="display: none;" />
                            </svg>

                            <!-- Bottom Range Selector Pills (Screenshot 3) -->
                            <div class="flex items-center justify-end gap-1.5 pt-2 relative z-10">
                                <template x-for="r in ['1D', '1M', '3M', '1Y', '5Y', 'All']" :key="r">
                                    <button @click="chartRange = r; hoverChartPoint = null"
                                            :class="chartRange === r ? 'bg-[#0066cc] text-white shadow-xs' : 'bg-white text-stone-600 hover:bg-stone-100 border border-stone-200'"
                                            class="text-xs font-bold px-2.5 py-1 rounded-md transition cursor-pointer"
                                            x-text="r">
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Price Actions Card (Screenshot 3) -->
                    <div class="lg:col-span-4 space-y-4">
                        <h3 class="text-base sm:text-lg font-black text-[#0066cc] tracking-tight">Price Actions</h3>
                        <div class="space-y-3.5">
                            @foreach($company['price_actions'] ?? [] as $action)
                                <div class="bg-stone-50/70 border border-stone-200/80 rounded-xl p-4 flex items-start gap-3.5">
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 font-bold text-lg">
                                        @if(($action['icon'] ?? '') === 'chevrons-up')
                                            <span>⇡</span>
                                        @else
                                            <span>↗</span>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        <div class="text-[11px] font-semibold text-stone-400">{{ $action['subtitle'] }}</div>
                                        <div class="text-sm font-bold text-stone-900">{{ $action['title'] }}</div>
                                        <p class="text-xs text-stone-600 leading-relaxed">{{ $action['description'] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Price Movement Badges Strip (Screenshot 3) -->
                <div class="space-y-3 pt-4 border-t border-stone-100">
                    <h3 class="text-sm font-black text-stone-900 tracking-tight">Price Movement</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
                        <!-- 1 Week -->
                        <div class="bg-stone-50 border border-stone-200/80 rounded-xl p-3.5 flex items-center justify-between">
                            <span class="text-xs font-bold text-stone-600">1 Week</span>
                            <div class="flex items-center gap-1.5 font-mono font-bold text-xs sm:text-sm {{ $company['price_movement']['1_week'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                <span class="h-2 w-1 rounded {{ $company['price_movement']['1_week'] >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                <span>{{ $company['price_movement']['1_week'] >= 0 ? '+' : '' }}{{ number_format($company['price_movement']['1_week'], 2) }}%</span>
                            </div>
                        </div>

                        <!-- 1 Month -->
                        <div class="bg-stone-50 border border-stone-200/80 rounded-xl p-3.5 flex items-center justify-between">
                            <span class="text-xs font-bold text-stone-600">1 Month</span>
                            <div class="flex items-center gap-1.5 font-mono font-bold text-xs sm:text-sm {{ $company['price_movement']['1_month'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                <span class="h-2 w-1 rounded {{ $company['price_movement']['1_month'] >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                <span>{{ $company['price_movement']['1_month'] >= 0 ? '+' : '' }}{{ number_format($company['price_movement']['1_month'], 2) }}%</span>
                            </div>
                        </div>

                        <!-- 3 Months -->
                        <div class="bg-stone-50 border border-stone-200/80 rounded-xl p-3.5 flex items-center justify-between">
                            <span class="text-xs font-bold text-stone-600">3 Months</span>
                            <div class="flex items-center gap-1.5 font-mono font-bold text-xs sm:text-sm {{ $company['price_movement']['3_months'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                <span class="h-2 w-1 rounded {{ $company['price_movement']['3_months'] >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                <span>{{ $company['price_movement']['3_months'] >= 0 ? '+' : '' }}{{ number_format($company['price_movement']['3_months'], 2) }}%</span>
                            </div>
                        </div>

                        <!-- 1 Year -->
                        <div class="bg-stone-50 border border-stone-200/80 rounded-xl p-3.5 flex items-center justify-between">
                            <span class="text-xs font-bold text-stone-600">1 Year</span>
                            <div class="flex items-center gap-1.5 font-mono font-bold text-xs sm:text-sm {{ $company['price_movement']['1_year'] >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                <span class="h-2 w-1 rounded {{ $company['price_movement']['1_year'] >= 0 ? 'bg-emerald-500' : 'bg-rose-500' }}"></span>
                                <span>{{ $company['price_movement']['1_year'] >= 0 ? '+' : '' }}{{ number_format($company['price_movement']['1_year'], 2) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Historical Data (Screenshot 4) -->
            <div class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-5 sm:p-7 space-y-5">
                <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-100 pb-3">
                    <div class="relative">
                        <h2 class="text-xl sm:text-2xl font-black text-stone-900 tracking-tight">Historical Data</h2>
                        <div class="absolute -bottom-3.5 left-0 w-14 h-1 bg-[#0066cc] rounded-full"></div>
                    </div>
                    <!-- Date Picker Filter -->
                    <div class="flex items-center gap-2 text-xs font-semibold bg-stone-50 border border-stone-300 rounded-lg px-3 py-1.5 text-stone-700">
                        <span>Apr 21, 2026 - Oct 18, 2026</span>
                        <svg class="w-4 h-4 text-[#0066cc]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>

                <!-- Table Component matching Screenshot 4 -->
                <div class="overflow-x-auto border border-stone-200/80 rounded-xl">
                    <table class="w-full text-xs sm:text-sm text-left">
                        <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider text-[11px] font-semibold border-b border-stone-200">
                            <tr>
                                <th class="py-3 px-4">Date</th>
                                <th class="py-3 px-4 text-right">Open</th>
                                <th class="py-3 px-4 text-right">High</th>
                                <th class="py-3 px-4 text-right">Low</th>
                                <th class="py-3 px-4 text-right">Close</th>
                                <th class="py-3 px-4 text-right">Change</th>
                                <th class="py-3 px-4 text-right">% Change</th>
                                <th class="py-3 px-4 text-right">Turnover</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 font-mono">
                            <template x-for="row in paginatedHistory" :key="row.date">
                                <tr class="hover:bg-blue-50/40 transition">
                                    <td class="py-3 px-4 font-sans font-medium text-stone-800" x-text="row.date"></td>
                                    <td class="py-3 px-4 text-right text-stone-700" x-text="Number(row.open).toFixed(2)"></td>
                                    <td class="py-3 px-4 text-right text-stone-700" x-text="Number(row.high).toFixed(2)"></td>
                                    <td class="py-3 px-4 text-right text-stone-700" x-text="Number(row.low).toFixed(2)"></td>
                                    <td class="py-3 px-4 text-right font-bold text-stone-900" x-text="Number(row.close).toFixed(2)"></td>
                                    <td class="py-3 px-4 text-right font-bold"
                                        :class="row.change >= 0 ? 'text-emerald-600' : 'text-rose-600'"
                                        x-text="(row.change >= 0 ? '+' : '') + Number(row.change).toFixed(2)">
                                    </td>
                                    <td class="py-3 px-4 text-right font-bold"
                                        :class="row.per_change >= 0 ? 'text-emerald-600' : 'text-rose-600'"
                                        x-text="(row.per_change >= 0 ? '+' : '') + Number(row.per_change).toFixed(2) + '%'">
                                    </td>
                                    <td class="py-3 px-4 text-right text-stone-700" x-text="Number(row.turnover).toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 })"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer (Screenshot 4) -->
                <div class="flex flex-wrap items-center justify-between gap-3 pt-2 text-xs text-stone-500 font-medium">
                    <div>
                        Showing <span class="font-bold text-stone-900" x-text="paginatedHistory.length">10</span> of <span class="font-bold text-stone-900" x-text="historyData.length">121</span>
                    </div>
                    <div class="flex items-center gap-1 font-mono">
                        <button @click="if (historyPage > 1) historyPage--"
                                :disabled="historyPage === 1"
                                class="px-2.5 py-1 rounded border border-stone-200 bg-white text-stone-700 disabled:opacity-40 hover:bg-stone-50">
                            &lt;
                        </button>
                        <template x-for="p in [1, 2, 3, 4, 5]" :key="p">
                            <button @click="historyPage = p"
                                    :class="historyPage === p ? 'bg-[#0066cc] text-white border-[#0066cc]' : 'bg-white text-stone-700 border-stone-200 hover:bg-stone-50'"
                                    class="px-2.5 py-1 rounded border font-bold"
                                    x-text="p">
                            </button>
                        </template>
                        <span class="px-1 text-stone-400">...</span>
                        <button @click="historyPage = totalHistoryPages"
                                :class="historyPage === totalHistoryPages ? 'bg-[#0066cc] text-white border-[#0066cc]' : 'bg-white text-stone-700 border-stone-200 hover:bg-stone-50'"
                                class="px-2.5 py-1 rounded border font-bold"
                                x-text="totalHistoryPages">
                        </button>
                        <button @click="if (historyPage < totalHistoryPages) historyPage++"
                                :disabled="historyPage === totalHistoryPages"
                                class="px-2.5 py-1 rounded border border-stone-200 bg-white text-stone-700 disabled:opacity-40 hover:bg-stone-50">
                            &gt;
                        </button>
                    </div>
                </div>
            </div>

            <!-- Financials & Announcements (Screenshot 5) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Left: Financials Bar Chart (Screenshot 5) -->
                <div class="lg:col-span-8 bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-5 sm:p-7 space-y-6">
                    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-stone-100 pb-3">
                        <div class="relative">
                            <h2 class="text-xl sm:text-2xl font-black text-stone-900 tracking-tight">Financials</h2>
                            <div class="absolute -bottom-3.5 left-0 w-14 h-1 bg-[#0066cc] rounded-full"></div>
                        </div>
                        <!-- Subtabs -->
                        <div class="flex items-center gap-4 text-xs font-bold text-stone-500">
                            <button @click="financialSubtab = 'overview'"
                                    :class="financialSubtab === 'overview' ? 'text-[#0066cc] border-b-2 border-[#0066cc] pb-1' : 'hover:text-stone-900'">
                                Overview
                            </button>
                            <button @click="financialSubtab = 'income'"
                                    :class="financialSubtab === 'income' ? 'text-[#0066cc] border-b-2 border-[#0066cc] pb-1' : 'hover:text-stone-900'">
                                Income Statement
                            </button>
                            <button @click="financialSubtab = 'balance'"
                                    :class="financialSubtab === 'balance' ? 'text-[#0066cc] border-b-2 border-[#0066cc] pb-1' : 'hover:text-stone-900'">
                                Balance Sheet
                            </button>
                        </div>
                    </div>

                    <!-- Layout: Metric Sidebar + Bar Graph -->
                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-6 items-start">
                        <!-- Metric List (Screenshot 5) -->
                        <div class="sm:col-span-3 space-y-1">
                            <template x-for="m in ['Revenue', 'Net Profit', 'EPS', 'BVPS', 'ROE', 'Debt to Equity']" :key="m">
                                <button @click="financialMetric = m"
                                        :class="financialMetric === m ? 'bg-blue-50 text-[#0066cc] font-bold border-l-4 border-[#0066cc]' : 'text-stone-600 hover:bg-stone-50 font-medium'"
                                        class="w-full text-left px-3 py-2 text-xs rounded transition"
                                        x-text="m">
                                </button>
                            </template>
                        </div>

                        <!-- Bar Graph Visualization (Screenshot 5) -->
                        <div class="sm:col-span-9 relative pt-2">
                            <!-- Tooltip over Bar -->
                            <div x-show="hoverBarIndex !== null"
                                 class="absolute z-20 top-2 left-1/2 -translate-x-1/2 bg-stone-900/90 text-white rounded-lg px-3 py-1.5 text-xs shadow-xl pointer-events-none"
                                 style="display: none;">
                                <div class="font-semibold text-stone-300" x-text="financials.quarters ? financials.quarters[hoverBarIndex] : ''"></div>
                                <div class="font-bold flex items-center gap-1.5 pt-0.5">
                                    <span class="h-2 w-2 rounded-full bg-blue-400"></span>
                                    <span x-text="financialMetric + ': ' + (currentMetricData?.short_labels[hoverBarIndex] || '')"></span>
                                </div>
                            </div>

                            <!-- Bar Graph Area -->
                            <div class="h-64 flex items-end justify-between gap-3 sm:gap-4 border-b border-stone-200 px-2 pb-1 relative">
                                <!-- Background Y-grid lines -->
                                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none opacity-40">
                                    <div class="border-b border-stone-200 w-full"></div>
                                    <div class="border-b border-stone-200 w-full"></div>
                                    <div class="border-b border-stone-200 w-full"></div>
                                    <div class="border-b border-stone-200 w-full"></div>
                                    <div class="border-b border-stone-200 w-full"></div>
                                </div>

                                <template x-for="(q, idx) in financials.quarters" :key="q">
                                    <div class="flex-1 flex flex-col items-center justify-end h-full group relative"
                                         @mouseenter="hoverBarIndex = idx"
                                         @mouseleave="hoverBarIndex = null">
                                        <!-- Top formatted amount label above bar -->
                                        <div class="text-[9px] sm:text-[10px] font-mono text-stone-600 truncate max-w-full mb-1 text-center font-semibold"
                                             x-text="currentMetricData?.formatted_values[idx] || ''">
                                        </div>

                                        <!-- The Vertical Blue Bar -->
                                        <div class="w-full max-w-[48px] rounded-t-md transition-all duration-300 relative cursor-pointer"
                                             :class="idx === (financials.quarters.length - 1) ? 'bg-[#0066cc]' : 'bg-[#2997ff] hover:bg-[#0066cc]'"
                                             :style="'height: ' + Math.min(100, Math.max(12, ((currentMetricData?.values[idx] || 0) / (currentMetricData?.y_max || 1)) * 92)) + '%'">
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- X-Axis Labels (Quarters) -->
                            <div class="flex items-center justify-between text-[11px] font-semibold text-stone-500 pt-2 px-1">
                                <template x-for="q in financials.quarters" :key="q">
                                    <span class="flex-1 text-center truncate" x-text="q"></span>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Announcements Timeline (Screenshot 5) -->
                <div class="lg:col-span-4 bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-5 sm:p-7 space-y-5">
                    <h2 class="text-xl sm:text-2xl font-black text-[#0066cc] tracking-tight">Announcements</h2>

                    <!-- Timeline items matching Screenshot 5 -->
                    <div class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-stone-200">
                        @foreach($company['announcements'] ?? [] as $ann)
                            <div class="relative space-y-1">
                                <!-- Blue Dot -->
                                <div class="absolute -left-6 top-1 h-3 w-3 rounded-full bg-[#0066cc] ring-4 ring-blue-50"></div>
                                <div class="text-[11px] font-medium text-stone-400">{{ $ann['date'] }}</div>
                                <div class="text-xs sm:text-sm font-bold text-stone-900 hover:text-[#0066cc] transition cursor-pointer">
                                    {{ $ann['title'] }}
                                </div>
                                <div class="text-[10px] font-semibold uppercase tracking-wider text-stone-500">
                                    {{ $ann['category'] }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Bottom Pagination for Announcements -->
                    <div class="flex items-center justify-center gap-1.5 pt-4 border-t border-stone-100 font-mono text-xs">
                        <button class="px-2 py-0.5 rounded border border-stone-200 text-stone-400">&lt;</button>
                        <button class="px-2.5 py-0.5 rounded bg-[#0066cc] text-white font-bold">1</button>
                        <button class="px-2 py-0.5 rounded border border-stone-200 text-stone-400">&gt;</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: CHART EXPANDED VIEW -->
        <div x-show="activeTab === 'chart'" class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-6 space-y-4" style="display: none;">
            <div class="flex items-center justify-between border-b border-stone-100 pb-3">
                <h2 class="text-xl font-black text-stone-900">{{ $company['name'] }} ({{ $symbol }}) Interactive Price Chart</h2>
                <div class="flex items-center gap-1.5">
                    <template x-for="r in ['1D', '1M', '3M', '1Y', '5Y', 'All']" :key="r">
                        <button @click="chartRange = r; hoverChartPoint = null"
                                :class="chartRange === r ? 'bg-[#0066cc] text-white shadow-xs' : 'bg-stone-100 text-stone-700 hover:bg-stone-200'"
                                class="text-xs font-bold px-3 py-1 rounded-md transition cursor-pointer"
                                x-text="r">
                        </button>
                    </template>
                </div>
            </div>
            <div class="h-96 w-full bg-stone-50/70 rounded-xl border border-stone-200 flex items-center justify-center relative p-6 cursor-crosshair select-none"
                 @mousemove="onMainChartMove($event)"
                 @mouseleave="hoverChartPoint = null">
                <!-- Floating Tooltip Box -->
                <div x-show="hoverChartPoint"
                     class="absolute z-20 bg-white/95 backdrop-blur-xs border border-stone-200/90 rounded-md shadow-md px-2.5 py-1.5 text-xs pointer-events-none transition-transform duration-75"
                     :style="'left: ' + (hoverChartPoint?.tipLeft || 50) + 'px; top: ' + (hoverChartPoint?.tipTop || 20) + 'px;'"
                     style="display: none;">
                    <div class="text-[10px] text-stone-400 font-mono leading-tight" x-text="hoverChartPoint?.datetime || ''"></div>
                    <div class="flex items-center gap-1.5 text-xs font-semibold text-stone-700 pt-0.5 leading-tight">
                        <span class="inline-block w-2 h-2 rounded-full" style="background-color: {{ $company['is_positive'] ? '#10b981' : '#f43f5e' }};"></span>
                        <span>{{ $symbol }}:</span>
                        <span class="font-bold font-mono text-stone-900" x-text="hoverChartPoint?.price || ''"></span>
                    </div>
                </div>

                <svg class="w-full h-full pointer-events-none" viewBox="0 0 600 240" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="chartGradientFull" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="{{ $company['is_positive'] ? '#10b981' : '#f43f5e' }}" stop-opacity="0.35" />
                            <stop offset="100%" stop-color="{{ $company['is_positive'] ? '#10b981' : '#f43f5e' }}" stop-opacity="0.0" />
                        </linearGradient>
                    </defs>
                    <path :d="mainChartComputed.areaPath" fill="url(#chartGradientFull)" />
                    <path :d="mainChartComputed.linePath"
                          stroke="{{ $company['is_positive'] ? '#10b981' : '#f43f5e' }}"
                          stroke-width="3"
                          stroke-linecap="round"
                          stroke-linejoin="round"
                          fill="none" />
                    <!-- End Dot -->
                    <circle x-show="!hoverChartPoint && mainChartComputed.lastPt"
                            :cx="mainChartComputed.lastPt ? mainChartComputed.lastPt.x : 570"
                            :cy="mainChartComputed.lastPt ? mainChartComputed.lastPt.y : 30"
                            r="5"
                            fill="{{ $company['is_positive'] ? '#10b981' : '#f43f5e' }}" />

                    <!-- Vertical Crosshair -->
                    <line x-show="hoverChartPoint"
                          :x1="hoverChartPoint ? hoverChartPoint.x : 0"
                          :x2="hoverChartPoint ? hoverChartPoint.x : 0"
                          y1="0"
                          y2="240"
                          stroke="#94a3b8"
                          stroke-width="1.2"
                          stroke-dasharray="3,3"
                          style="display: none;" />

                    <!-- Active Dot -->
                    <circle x-show="hoverChartPoint"
                            :cx="hoverChartPoint ? hoverChartPoint.x : 0"
                            :cy="hoverChartPoint ? hoverChartPoint.y : 0"
                            r="5"
                            fill="{{ $company['is_positive'] ? '#10b981' : '#f43f5e' }}"
                            stroke="#ffffff"
                            stroke-width="2"
                            style="display: none;" />
                </svg>
            </div>
            <div class="flex items-center justify-between text-xs text-stone-500 font-medium">
                <span>Timeframe: <span class="font-bold text-stone-800" x-text="chartRange"></span></span>
                <span>Current Price: <span class="font-bold font-mono {{ $company['is_positive'] ? 'text-emerald-600' : 'text-rose-600' }}">NPR {{ number_format($company['ltp'], 2) }}</span></span>
            </div>
        </div>

        <!-- TAB 3: FINANCIALS FULL VIEW -->
        <div x-show="activeTab === 'financials'" class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-6 space-y-6" style="display: none;">
            <h2 class="text-xl font-black text-stone-900">{{ $company['name'] }} Quarterly Financial Performance</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/60">
                    <div class="text-xs text-stone-500 font-bold uppercase">Quarterly Revenue</div>
                    <div class="text-xl font-mono font-bold text-stone-900 pt-1">13.76 Cr</div>
                    <div class="text-xs text-emerald-600 font-semibold pt-1">↑ +25.5% vs Q3</div>
                </div>
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/60">
                    <div class="text-xs text-stone-500 font-bold uppercase">Net Profit</div>
                    <div class="text-xl font-mono font-bold text-stone-900 pt-1">4.95 Cr</div>
                    <div class="text-xs text-emerald-600 font-semibold pt-1">↑ +27.1% vs Q3</div>
                </div>
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/60">
                    <div class="text-xs text-stone-500 font-bold uppercase">Annualized EPS</div>
                    <div class="text-xl font-mono font-bold text-stone-900 pt-1">Rs. {{ $company['quick_view']['eps_diluted'] }}</div>
                    <div class="text-xs text-stone-500 font-semibold pt-1">Diluted</div>
                </div>
            </div>
        </div>

        <!-- TAB 4: DIVIDEND HISTORY -->
        <div x-show="activeTab === 'dividend'" class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-6 space-y-5" style="display: none;">
            <h2 class="text-xl font-black text-stone-900">Dividend Distribution History</h2>
            <div class="overflow-x-auto border border-stone-200 rounded-xl">
                <table class="w-full text-xs sm:text-sm text-left">
                    <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider font-semibold border-b border-stone-200">
                        <tr>
                            <th class="py-3 px-4">Fiscal Year</th>
                            <th class="py-3 px-4 text-right">Bonus Share %</th>
                            <th class="py-3 px-4 text-right">Cash Dividend %</th>
                            <th class="py-3 px-4 text-right font-bold">Total Dividend %</th>
                            <th class="py-3 px-4 text-right">Book Closure Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 font-mono">
                        @foreach($company['dividend_history'] ?? [] as $div)
                            <tr class="hover:bg-stone-50/60">
                                <td class="py-3 px-4 font-sans font-bold text-stone-800">{{ $div['fiscal_year'] }}</td>
                                <td class="py-3 px-4 text-right text-emerald-600 font-semibold">{{ $div['bonus_per'] }}</td>
                                <td class="py-3 px-4 text-right text-stone-700">{{ $div['cash_per'] }}</td>
                                <td class="py-3 px-4 text-right font-bold text-stone-900">{{ $div['total_per'] }}</td>
                                <td class="py-3 px-4 text-right font-sans text-stone-500">{{ $div['book_closure'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB: PRICE MOVEMENT -->
        <div x-show="activeTab === 'movement'" class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-6 space-y-6" style="display: none;">
            <div class="border-b border-stone-100 pb-3 flex items-center justify-between flex-wrap gap-2">
                <h2 class="text-xl font-black text-stone-900">{{ $company['name'] }} ({{ $symbol }}) Price Movement & Performance</h2>
                <span class="text-xs text-stone-500 font-medium">As on {{ $company['as_of_timestamp'] }}</span>
            </div>

            <!-- Historical Performance Cards -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/60 text-center">
                    <span class="text-xs font-bold text-stone-500 uppercase block">1 Week</span>
                    <span class="text-lg font-mono font-black mt-1 inline-flex items-center gap-1 {{ ($company['price_movement']['1_week'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ ($company['price_movement']['1_week'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($company['price_movement']['1_week'] ?? 0, 2) }}%
                    </span>
                </div>
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/60 text-center">
                    <span class="text-xs font-bold text-stone-500 uppercase block">1 Month</span>
                    <span class="text-lg font-mono font-black mt-1 inline-flex items-center gap-1 {{ ($company['price_movement']['1_month'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ ($company['price_movement']['1_month'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($company['price_movement']['1_month'] ?? 0, 2) }}%
                    </span>
                </div>
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/60 text-center">
                    <span class="text-xs font-bold text-stone-500 uppercase block">3 Months</span>
                    <span class="text-lg font-mono font-black mt-1 inline-flex items-center gap-1 {{ ($company['price_movement']['3_months'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ ($company['price_movement']['3_months'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($company['price_movement']['3_months'] ?? 0, 2) }}%
                    </span>
                </div>
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/60 text-center">
                    <span class="text-xs font-bold text-stone-500 uppercase block">1 Year</span>
                    <span class="text-lg font-mono font-black mt-1 inline-flex items-center gap-1 {{ ($company['price_movement']['1_year'] ?? 0) >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ ($company['price_movement']['1_year'] ?? 0) >= 0 ? '+' : '' }}{{ number_format($company['price_movement']['1_year'] ?? 0, 2) }}%
                    </span>
                </div>
            </div>

            <!-- Price Actions & Signals -->
            <div class="space-y-3 pt-2">
                <h3 class="text-base font-black text-stone-900">Price Action Signals</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($company['price_actions'] ?? [] as $action)
                        <div class="bg-stone-50/80 border border-stone-200 rounded-xl p-4 flex items-start gap-3.5">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 font-bold text-lg">
                                ↗
                            </div>
                            <div class="space-y-1">
                                <div class="text-[11px] font-semibold text-stone-400">{{ $action['subtitle'] }}</div>
                                <div class="text-sm font-bold text-stone-900">{{ $action['title'] }}</div>
                                <p class="text-xs text-stone-600 leading-relaxed">{{ $action['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- 52-Week Range & Intraday High/Low -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                <div class="p-5 rounded-xl border border-stone-200 bg-stone-50/50 space-y-2">
                    <h4 class="text-xs font-black uppercase text-stone-500">24-Hour Range</h4>
                    <div class="flex items-center justify-between text-sm font-mono font-bold">
                        <span class="text-rose-600">Low: Rs. {{ number_format($company['range_24h']['low'], 2) }}</span>
                        <span class="text-emerald-600">High: Rs. {{ number_format($company['range_24h']['high'], 2) }}</span>
                    </div>
                </div>
                <div class="p-5 rounded-xl border border-stone-200 bg-stone-50/50 space-y-2">
                    <h4 class="text-xs font-black uppercase text-stone-500">52-Week Range</h4>
                    <div class="flex items-center justify-between text-sm font-mono font-bold">
                        <span class="text-rose-600">52W Low: Rs. {{ number_format($company['range_52w']['low'], 2) }}</span>
                        <span class="text-emerald-600">52W High: Rs. {{ number_format($company['range_52w']['high'], 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 5: KEY STATS -->
        <div x-show="activeTab === 'keystats'" class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-6 space-y-5" style="display: none;">
            <h2 class="text-xl font-black text-stone-900">Company Key Statistics</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs sm:text-sm">
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/50 flex justify-between">
                    <span class="text-stone-500 font-medium">Listed Shares:</span>
                    <span class="font-bold font-mono text-stone-900">{{ $company['company_info']['listed_shares'] }}</span>
                </div>
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/50 flex justify-between">
                    <span class="text-stone-500 font-medium">Paid-Up Capital:</span>
                    <span class="font-bold font-mono text-stone-900">Rs. {{ $company['company_info']['paid_up_capital'] }}</span>
                </div>
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/50 flex justify-between">
                    <span class="text-stone-500 font-medium">Face Value:</span>
                    <span class="font-bold font-mono text-stone-900">Rs. {{ $company['company_info']['face_value'] }}</span>
                </div>
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/50 flex justify-between">
                    <span class="text-stone-500 font-medium">52-Week High:</span>
                    <span class="font-bold font-mono text-emerald-600">{{ number_format($company['range_52w']['high'], 2) }}</span>
                </div>
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/50 flex justify-between">
                    <span class="text-stone-500 font-medium">52-Week Low:</span>
                    <span class="font-bold font-mono text-rose-600">{{ number_format($company['range_52w']['low'], 2) }}</span>
                </div>
                <div class="p-4 rounded-xl border border-stone-200 bg-stone-50/50 flex justify-between">
                    <span class="text-stone-500 font-medium">Share Registrar (RTS):</span>
                    <span class="font-bold text-stone-900">{{ $company['company_info']['rts'] }}</span>
                </div>
            </div>
        </div>

        <!-- TAB 6: MUTUAL FUND OWNERSHIP -->
        <div x-show="activeTab === 'mf'" class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-6 space-y-5" style="display: none;">
            <h2 class="text-xl font-black text-stone-900">Mutual Fund Schemes Holding {{ $symbol }}</h2>
            <div class="overflow-x-auto border border-stone-200 rounded-xl">
                <table class="w-full text-xs sm:text-sm text-left">
                    <thead class="bg-stone-50 text-stone-500 uppercase tracking-wider font-semibold border-b border-stone-200">
                        <tr>
                            <th class="py-3 px-4">Scheme Name</th>
                            <th class="py-3 px-4 text-right">Units Held</th>
                            <th class="py-3 px-4 text-right">Holding %</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-stone-100 font-mono">
                        @foreach($company['mf_ownership'] ?? [] as $mf)
                            <tr class="hover:bg-stone-50/60">
                                <td class="py-3 px-4 font-sans font-bold text-stone-800">{{ $mf['scheme'] }}</td>
                                <td class="py-3 px-4 text-right text-stone-700">{{ $mf['units'] }}</td>
                                <td class="py-3 px-4 text-right font-bold text-[#0066cc]">{{ $mf['holding'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TAB: ANNOUNCEMENTS -->
        <div x-show="activeTab === 'announcements'" class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-6 space-y-5" style="display: none;">
            <div class="border-b border-stone-100 pb-3 flex items-center justify-between flex-wrap gap-2">
                <h2 class="text-xl font-black text-stone-900">{{ $company['name'] }} Announcements & Notices</h2>
                <span class="text-xs text-stone-500">Total: {{ count($company['announcements'] ?? []) }} notices</span>
            </div>
            <div class="divide-y divide-stone-100 border border-stone-200 rounded-xl overflow-hidden">
                @forelse($company['announcements'] ?? [] as $notice)
                    <div class="p-4 sm:p-5 flex items-start gap-4 hover:bg-stone-50 transition">
                        <div class="bg-blue-50 text-[#0066cc] border border-blue-200 rounded-lg px-2.5 py-1 text-xs font-bold shrink-0 text-center">
                            <span class="block text-[10px] uppercase font-extrabold">{{ $notice['category'] ?? 'Notice' }}</span>
                            <span class="font-mono text-[11px]">{{ $notice['date'] ?? '' }}</span>
                        </div>
                        <div class="space-y-1 flex-1">
                            <h3 class="text-sm font-bold text-stone-900 hover:text-[#0066cc] cursor-pointer transition">
                                {{ $notice['title'] }}
                            </h3>
                            <p class="text-xs text-stone-500">
                                प्रकाशित मिति: {{ $notice['date'] }} • नेपाल स्टक एक्सचेन्ज (NEPSE) मा सूचीकृत सूचना
                            </p>
                        </div>
                        <span class="text-stone-400 font-mono text-sm shrink-0">📄</span>
                    </div>
                @empty
                    <div class="p-8 text-center text-stone-400 text-sm font-medium">हाल कुनै सूचना उपलब्ध छैन।</div>
                @endforelse
            </div>
        </div>

        <!-- TAB: FAQS -->
        <div x-show="activeTab === 'faqs'" class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-6 space-y-5" style="display: none;">
            <div class="border-b border-stone-100 pb-3">
                <h2 class="text-xl font-black text-stone-900">Frequently Asked Questions (FAQs) - {{ $symbol }}</h2>
                <p class="text-xs text-stone-500 pt-1">{{ $company['name'] }} सम्बन्धी बारम्बार सोधिने प्रश्नहरू</p>
            </div>
            <div class="space-y-3" x-data="{ openFaq: 1 }">
                <div class="border border-stone-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = (openFaq === 1 ? null : 1)" class="w-full text-left p-4 bg-stone-50/60 hover:bg-stone-100/60 font-bold text-sm text-stone-900 flex justify-between items-center transition">
                        <span>१. {{ $company['name'] }} ({{ $symbol }}) को हालको शेयर मूल्य कति छ?</span>
                        <span x-text="openFaq === 1 ? '−' : '+'" class="text-base font-bold text-[#0066cc]"></span>
                    </button>
                    <div x-show="openFaq === 1" class="p-4 text-xs sm:text-sm text-stone-700 border-t border-stone-100 space-y-1">
                        {{ $company['name'] }} को अन्तिम कारोबार मूल्य (LTP) रु. <strong>{{ number_format($company['ltp'], 2) }}</strong> रहेको छ। यसको आजको मूल्य परिवर्तन {{ $company['is_positive'] ? '+' : '' }}{{ number_format($company['per_change'], 2) }}% छ।
                    </div>
                </div>

                <div class="border border-stone-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = (openFaq === 2 ? null : 2)" class="w-full text-left p-4 bg-stone-50/60 hover:bg-stone-100/60 font-bold text-sm text-stone-900 flex justify-between items-center transition">
                        <span>२. यस कम्पनीको ५२ हप्ताको उच्च र न्यून मूल्य कति हो?</span>
                        <span x-text="openFaq === 2 ? '−' : '+'" class="text-base font-bold text-[#0066cc]"></span>
                    </button>
                    <div x-show="openFaq === 2" class="p-4 text-xs sm:text-sm text-stone-700 border-t border-stone-100 space-y-1">
                        विगत ५२ हप्तामा यस शेयरको उच्चतम मूल्य रु. <strong>{{ number_format($company['range_52w']['high'], 2) }}</strong> र न्यूनतम मूल्य रु. <strong>{{ number_format($company['range_52w']['low'], 2) }}</strong> सम्म पुगेको छ।
                    </div>
                </div>

                <div class="border border-stone-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = (openFaq === 3 ? null : 3)" class="w-full text-left p-4 bg-stone-50/60 hover:bg-stone-100/60 font-bold text-sm text-stone-900 flex justify-between items-center transition">
                        <span>३. {{ $company['name'] }} कुन क्षेत्र (Sector) मा पर्दछ र शेयर रजिष्ट्रार को हो?</span>
                        <span x-text="openFaq === 3 ? '−' : '+'" class="text-base font-bold text-[#0066cc]"></span>
                    </button>
                    <div x-show="openFaq === 3" class="p-4 text-xs sm:text-sm text-stone-700 border-t border-stone-100 space-y-1">
                        यो कम्पनी नेप्सेको <strong>{{ $company['sector'] }} ({{ $company['sector_np'] }})</strong> क्षेत्रअन्तर्गत पर्दछ। यसको सेयर निष्कासन तथा बिक्री प्रबन्धक / सेयर रजिष्ट्रार (RTS) <strong>{{ $company['company_info']['rts'] }}</strong> रहेको छ।
                    </div>
                </div>

                <div class="border border-stone-200 rounded-xl overflow-hidden">
                    <button @click="openFaq = (openFaq === 4 ? null : 4)" class="w-full text-left p-4 bg-stone-50/60 hover:bg-stone-100/60 font-bold text-sm text-stone-900 flex justify-between items-center transition">
                        <span>४. यस शेयरको प्रतिशेयर आम्दानी (EPS) र P/E अनुपात कति छ?</span>
                        <span x-text="openFaq === 4 ? '−' : '+'" class="text-base font-bold text-[#0066cc]"></span>
                    </button>
                    <div x-show="openFaq === 4" class="p-4 text-xs sm:text-sm text-stone-700 border-t border-stone-100 space-y-1">
                        यस कम्पनीको प्रतिशेयर आम्दानी (Diluted EPS) रु. <strong>{{ $company['quick_view']['eps_diluted'] }}</strong> र मूल्य-आम्दानी अनुपात (P/E Ratio) <strong>{{ $company['quick_view']['pe_diluted'] }}</strong> गुणा रहेको छ।
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: NEWS -->
        <div x-show="activeTab === 'news'" class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-6 space-y-5" style="display: none;">
            <div class="border-b border-stone-100 pb-3 flex items-center justify-between flex-wrap gap-2">
                <h2 class="text-xl font-black text-stone-900">{{ $company['name'] }} तथा सम्बन्धित बजार समाचार</h2>
                <a href="{{ route('category.show', 'arthatantra') }}" wire:navigate.hover class="text-xs font-bold text-[#0066cc] hover:underline">थप समाचार हेर्नुहोस् &rarr;</a>
            </div>
            @if(!empty($businessNews) && $businessNews->isNotEmpty())
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($businessNews as $art)
                        <a href="{{ article_url($art) }}" wire:navigate.hover class="group block space-y-2">
                            <div class="aspect-16/10 rounded-xl overflow-hidden bg-stone-100 border border-stone-200">
                                @if($art->featured_image)
                                    <img src="{{ setting_image_url($art->featured_image) }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-stone-400 font-bold text-xs">कपिलवस्तु दर्पण</div>
                                @endif
                            </div>
                            <h3 class="text-xs sm:text-sm font-bold text-stone-900 group-hover:text-[#0066cc] transition line-clamp-2 leading-snug">
                                {{ $art->title }}
                            </h3>
                            <div class="text-[11px] text-stone-400">
                                {{ nepali_date($art->published_at) }}
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="p-8 text-center text-stone-400 text-sm font-medium">यस कम्पनीसँग सम्बन्धित समाचार फेला परेन।</div>
            @endif
        </div>

        <!-- TAB 7: ABOUT COMPANY -->
        <div x-show="activeTab === 'about'" class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-6 space-y-4" style="display: none;">
            <h2 class="text-xl font-black text-stone-900">About {{ $company['name'] }}</h2>
            <p class="text-sm text-stone-700 leading-relaxed">
                {{ $company['company_info']['about'] }}
            </p>
            <div class="pt-3 border-t border-stone-100 flex flex-wrap items-center gap-6 text-xs text-stone-600">
                <div>Official Website: <a href="{{ $company['company_info']['website'] }}" target="_blank" rel="noopener" class="text-[#0066cc] font-semibold underline">{{ $company['company_info']['website'] }}</a></div>
                <div>Share Registrar: <span class="font-semibold text-stone-800">{{ $company['company_info']['rts'] }}</span></div>
            </div>
        </div>

        <!-- Related Portal News -->
        @if(!empty($businessNews) && $businessNews->isNotEmpty())
            <div class="bg-white rounded-2xl border border-stone-200/90 shadow-2xs p-5 sm:p-7 space-y-4">
                <div class="flex items-center justify-between border-b border-stone-100 pb-3">
                    <h2 class="text-lg sm:text-xl font-black text-stone-900 tracking-tight flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-[#0066cc]"></span>
                        <span>सम्बन्धित अर्थतन्त्र तथा बजार समाचार</span>
                    </h2>
                    <a href="{{ route('category.show', 'arthatantra') }}" wire:navigate.hover class="text-xs font-bold text-[#0066cc] hover:underline">
                        थप समाचार &rarr;
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach($businessNews as $art)
                        <a href="{{ article_url($art) }}" wire:navigate.hover class="group block space-y-2">
                            <div class="aspect-16/10 rounded-xl overflow-hidden bg-stone-100 border border-stone-200">
                                @if($art->featured_image)
                                    <img src="{{ setting_image_url($art->featured_image) }}" alt="{{ $art->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-stone-400 font-bold text-xs">कपिलवस्तु दर्पण</div>
                                @endif
                            </div>
                            <h3 class="text-xs sm:text-sm font-bold text-stone-900 group-hover:text-[#0066cc] transition line-clamp-2 leading-snug">
                                {{ $art->title }}
                            </h3>
                            <div class="text-[11px] text-stone-400">
                                {{ nepali_date($art->published_at) }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</x-layouts.app>
