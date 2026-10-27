@props(['label'])

@php
    $label = (string) $label;
    $type = match (true) {
        str_contains($label, 'मुख्य') || str_contains($label, 'समाचार') => 'news',
        str_contains($label, 'कपिलवस्तु') => 'local',
        str_contains($label, 'राजनीति') => 'politics',
        str_contains($label, 'अर्थ') || str_contains($label, 'शेयर') => 'business',
        str_contains($label, 'प्रदेश') => 'province',
        str_contains($label, 'समाज') => 'society',
        str_contains($label, 'अपराध') || str_contains($label, 'सुरक्षा') => 'security',
        str_contains($label, 'खेल') => 'sports',
        str_contains($label, 'विचार') => 'opinion',
        str_contains($label, 'स्वास्थ्य') => 'health',
        str_contains($label, 'राशि') => 'rashifal',
        default => 'default',
    };
@endphp

<span {{ $attributes->merge(['class' => "drawer-menu-icon drawer-menu-icon--{$type}"]) }} aria-hidden="true"></span>
