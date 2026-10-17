@props([
    'placement' => 'home_top',
    'class' => '',
])

@php
    $ads = \App\Models\Advertisement::active()->placement($placement)->get();
@endphp

@if($ads->isNotEmpty())
    @if($ads->count() === 1)
        @php
            $ad = $ads->first();
            // Record impression
            $ad->recordImpression();
        @endphp
        <div class="relative overflow-hidden rounded-lg border border-stone-200 bg-stone-50 shadow-sm {{ $class }}">
            <span class="absolute left-1.5 top-1.5 z-10 rounded bg-black/60 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-white">विज्ञापन</span>
            @if($ad->type === 'code' && $ad->code_snippet)
                <div class="p-2 flex items-center justify-center">
                    {!! $ad->code_snippet !!}
                </div>
            @elseif($ad->image_url)
                <a href="{{ route('ads.click', $ad) }}" target="_blank" rel="sponsored noopener noreferrer" class="block w-full h-full group">
                    <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="h-full w-full object-cover transition duration-300 group-hover:opacity-95" loading="lazy">
                </a>
            @endif
        </div>
    @else
        <!-- Multi-ad Carousel -->
        <div class="relative overflow-hidden rounded-lg border border-stone-200 bg-stone-50 shadow-sm {{ $class }}"
             x-data="{
                 current: 0,
                 total: {{ $ads->count() }},
                 init() {
                     setInterval(() => { this.current = (this.current + 1) % this.total; }, 6000);
                 }
             }">
            <span class="absolute left-1.5 top-1.5 z-10 rounded bg-black/60 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-white">विज्ञापन</span>
            <div class="relative w-full h-full min-h-[90px]">
                @foreach($ads as $index => $ad)
                    @php $ad->recordImpression(); @endphp
                    <div x-cloak x-show="current === {{ $index }}"
                         x-transition:enter="transition-opacity duration-500"
                         x-transition:enter-start="opacity-0"
                         x-transition:enter-end="opacity-100"
                         class="absolute inset-0">
                        @if($ad->type === 'code' && $ad->code_snippet)
                            <div class="p-2 flex items-center justify-center h-full">
                                {!! $ad->code_snippet !!}
                            </div>
                        @elseif($ad->image_url)
                            <a href="{{ route('ads.click', $ad) }}" target="_blank" rel="sponsored noopener noreferrer" class="block w-full h-full group">
                                <img src="{{ $ad->image_url }}" alt="{{ $ad->title }}" class="h-full w-full object-cover transition duration-300 group-hover:opacity-95" loading="lazy">
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
@else
    <!-- Placeholder when no ad is active for this slot -->
    <div class="relative flex flex-col items-center justify-center rounded-lg border border-dashed border-stone-300 bg-stone-50/80 p-4 text-center text-xs text-stone-500 {{ $class }}">
        <span class="absolute left-2 top-1.5 text-[9px] font-bold uppercase tracking-wider text-stone-400">विज्ञापन</span>
        <p class="font-extrabold text-stone-700">तपाईंको विज्ञापन यहाँ राख्नुहोस्</p>
        <p class="mt-1 text-[11px] text-stone-500">सम्पर्क: {{ setting('contact_phone', '+९७७-९७६६२५०४४५') }} | {{ setting('contact_email', 'kapilvastudarpannews@gmail.com') }}</p>
    </div>
@endif
