<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="कपिलवस्तु दर्पण — जनताको आवाज, स्थानीयदेखि राष्ट्रिय समाचारसम्म।">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <title>कपिलवस्तु दर्पण | जनताको आवाज</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body>
    {{ $slot }}
    @livewireScripts
</body>
</html>
