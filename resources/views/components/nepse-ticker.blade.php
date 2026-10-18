@php
    $enabled = setting_bool('sharemarket_ticker_enabled', true) && setting_bool('sharemarket_enabled', true);
    if ($enabled) {
        $nepseService = app(\App\Services\NepseService::class);
        $tickerItems = $nepseService->getTickerItems();
        $marketData = $nepseService->getMarketData();
        $isOpen = ($marketData['market_status'] ?? 'CLOSED') === 'OPEN';
    }
@endphp

@if($enabled && !empty($tickerItems))
    <div class="border-b border-stone-200 bg-white/95 backdrop-blur-xs text-xs text-stone-700 shadow-2xs select-none"
         x-data="{
             isPaused: false,
             items: {{ Js::from($tickerItems) }},
             isOpen: {{ $isOpen ? 'true' : 'false' }},
             lastUpdated: '{{ $marketData['as_of_time'] ?? '' }}',
             refreshTicker() {
                 fetch('{{ route('api.nepse.live') }}')
                     .then(res => res.json())
                     .then(data => {
                         if (data && data.ticker) {
                             this.items = data.ticker;
                             this.isOpen = (data.data?.market_status === 'OPEN');
                             this.lastUpdated = data.data?.as_of_time || '';
                         }
                     })
                     .catch(() => {});
             },
             init() {
                 // Auto-refresh ticker data every 45 seconds during live hours
                 setInterval(() => this.refreshTicker(), 45000);
             }
         }">
        <div class="mx-auto max-w-[1280px] flex items-center px-4 sm:px-6 lg:px-8 py-1.5 gap-3">
            <!-- Left Fixed Badge & Status -->
            <a href="{{ route('sharemarket.index') }}" class="shrink-0 flex items-center gap-2 rounded-lg bg-[#0955cf] text-white px-2.5 py-1 font-black shadow-2xs hover:opacity-95 transition" title="नेपाल शेयर बजार (NEPSE) विस्तृत हेर्नुहोस्">
                <span class="flex h-2 w-2 relative">
                    <span x-show="isOpen" class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2" :class="isOpen ? 'bg-emerald-400' : 'bg-rose-400'"></span>
                </span>
                <span class="tracking-wide">NEPSE</span>
                <span class="text-[10px] font-bold px-1.5 py-0.2 rounded" :class="isOpen ? 'bg-emerald-600 text-white' : 'bg-stone-600 text-white'" x-text="isOpen ? 'खुला' : 'बन्द'">
                    {{ $isOpen ? 'खुला' : 'बन्द' }}
                </span>
            </a>

            <!-- Scrolling / Ticker Strip -->
            <div class="relative min-w-0 flex-1 overflow-hidden"
                 @mouseenter="isPaused = true"
                 @mouseleave="isPaused = false">
                <div class="ticker-track flex items-center gap-6 whitespace-nowrap py-0.5"
                     :class="{ 'paused': isPaused }">
                    <!-- Duplicate items for seamless continuous marquee loop -->
                    @for($loopIndex = 0; $loopIndex < 2; $loopIndex++)
                        @foreach($tickerItems as $item)
                            @php
                                $isIndex = in_array(strtoupper($item['symbol']), ['NEPSE', 'SEN', 'FLOAT', 'SENFLOAT', 'BANK', 'DEVBANK', 'HYDRO', 'FINANCE', 'HOTEL', 'MF', 'NONLIFE', 'LIFE', 'TRADE', 'OTHER']);
                                $itemUrl = $isIndex ? route('sharemarket.index') : route('sharemarket.ticker', $item['symbol']);
                            @endphp
                            <a href="{{ $itemUrl }}" class="inline-flex items-center gap-1.5 hover:text-[#0066cc] transition font-semibold shrink-0" title="{{ $item['name'] }}">
                                <span class="font-bold text-stone-900">{{ $item['symbol'] }}</span>
                                <span class="font-mono text-stone-800">{{ $item['ltp'] }}</span>
                                <span class="inline-flex items-center text-[11px] font-bold px-1.5 py-0.5 rounded {{ $item['is_positive'] ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                    <span>{{ $item['is_positive'] ? '▲' : '▼' }}</span>
                                    <span>{{ $item['change'] }}</span>
                                    <span class="hidden sm:inline">({{ $item['per_change'] }})</span>
                                </span>
                            </a>
                        @endforeach
                    @endfor
                </div>
            </div>

            <!-- Right Detail Link -->
            <a href="{{ route('sharemarket.index') }}" class="hidden sm:inline-flex shrink-0 items-center gap-1 font-extrabold text-[#0955cf] hover:text-[#c92137] transition text-xs pl-2 border-l border-stone-200">
                <span>विस्तृत बजार</span>
                <span>↗</span>
            </a>
        </div>
    </div>

    <style>
        .ticker-track {
            display: inline-flex;
            animation: tickerMarquee 42s linear infinite;
        }
        .ticker-track.paused {
            animation-play-state: paused;
        }
        @keyframes tickerMarquee {
            0% {
                transform: translateX(0);
            }
            100% {
                transform: translateX(-50%);
            }
        }
    </style>
@endif
