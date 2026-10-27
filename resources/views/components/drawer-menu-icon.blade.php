@props(['label'])

@php
    $label = (string) $label;
    $tone = match (true) {
        str_contains($label, 'मुख्य') || str_contains($label, 'समाचार') => 'bg-cyan-500',
        str_contains($label, 'कपिलवस्तु') => 'bg-rose-500',
        str_contains($label, 'राजनीति') => 'bg-amber-500',
        str_contains($label, 'अर्थ') || str_contains($label, 'शेयर') => 'bg-indigo-600',
        str_contains($label, 'प्रदेश') => 'bg-blue-500',
        str_contains($label, 'समाज') => 'bg-emerald-600',
        str_contains($label, 'अपराध') || str_contains($label, 'सुरक्षा') => 'bg-red-500',
        str_contains($label, 'खेल') => 'bg-yellow-400 text-stone-900',
        str_contains($label, 'विचार') => 'bg-cyan-400',
        str_contains($label, 'स्वास्थ्य') => 'bg-rose-400',
        str_contains($label, 'राशि') => 'bg-orange-400',
        default => 'bg-violet-500',
    };
@endphp

<span {{ $attributes->merge(['class' => "grid h-9 w-9 shrink-0 place-items-center rounded-full text-white shadow-sm {$tone}"]) }} aria-hidden="true">
    @if(str_contains($label, 'मुख्य') || str_contains($label, 'समाचार'))
        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-[1.8]"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M3 12h18M12 3c2.5 2.4 3.7 5.4 3.7 9S14.5 18.6 12 21M12 3C9.5 5.4 8.3 8.4 8.3 12S9.5 18.6 12 21"/></svg>
    @elseif(str_contains($label, 'अर्थ') || str_contains($label, 'शेयर'))
        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-[1.9]"><path stroke-linecap="round" stroke-linejoin="round" d="M4 19V9m5 10V5m5 14v-8m5 8V3"/></svg>
    @elseif(str_contains($label, 'स्वास्थ्य'))
        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-[1.9]"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12h4l2-4 3 8 2-4h7"/></svg>
    @elseif(str_contains($label, 'खेल'))
        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-[1.9]"><path stroke-linecap="round" stroke-linejoin="round" d="M8 4h8l-1 5a3 3 0 0 1-6 0L8 4Zm4 8v5m-3 3h6"/></svg>
    @elseif(str_contains($label, 'अपराध') || str_contains($label, 'सुरक्षा'))
        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-[1.9]"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3 5 6v5c0 4.5 3 7.8 7 10 4-2.2 7-5.5 7-10V6l-7-3Z"/><path stroke-linecap="round" d="m9 12 2 2 4-4"/></svg>
    @elseif(str_contains($label, 'विचार'))
        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-[1.9]"><path stroke-linecap="round" stroke-linejoin="round" d="M5 5h14v10H9l-4 4V5Z"/><path stroke-linecap="round" d="M9 9h6m-6 3h4"/></svg>
    @elseif(str_contains($label, 'समाज'))
        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-[1.9]"><circle cx="9" cy="9" r="3"/><path stroke-linecap="round" d="M3 20c.5-3.1 2.5-5 6-5s5.5 1.9 6 5M16 6a3 3 0 0 1 0 6m2 3c2 0 3.5 1.3 4 3.5"/></svg>
    @elseif(str_contains($label, 'राजनीति') || str_contains($label, 'प्रदेश') || str_contains($label, 'कपिलवस्तु'))
        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-[1.9]"><path stroke-linecap="round" stroke-linejoin="round" d="M4 20h16M6 17h12M7 17V9m5 8V9m5 8V9M4 9h16L12 4 4 9Z"/></svg>
    @else
        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-none stroke-current stroke-[1.9]"><circle cx="12" cy="12" r="8"/><path stroke-linecap="round" d="M12 8v4l2.5 2"/></svg>
    @endif
</span>
