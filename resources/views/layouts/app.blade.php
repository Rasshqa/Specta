<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SPECTA XXI: REVELIORA — Perayaan seni, budaya, dan kreativitas terbesar SMAN 1 Cianjur. Celestial Treasure.">
    <meta name="theme-color" content="#020617">
    <title>@yield('title', 'SPECTA XXI: REVELIORA – Celestial Treasure')</title>
    <link rel="icon" href="{{ asset('images/smansa-logo.png') }}" type="image/png">

    {{-- Vite: Tailwind CSS & JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- AOS – Animate On Scroll --}}
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />

    {{-- Splide.js – Carousel --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">

    {{-- Alpine.js (defer) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/persist@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- Iconify – Icon API (200,000+ icons) --}}


    {{-- x-cloak: hide Alpine.js elements until JS is loaded --}}
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-slate-950 text-slate-100 overflow-x-hidden antialiased">

    @include('partials.alerts')

    <main>
        @yield('content')
    </main>

    {{-- AOS –– must be loaded before init --}}
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>

    {{-- Splide.js --}}
    <script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>

    {{-- Vanilla-Tilt.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vanilla-tilt/1.8.1/vanilla-tilt.min.js"></script>

    {{-- HTML5 QR Code (lazy — only needed on gatekeeper page) --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        // Inisialisasi AOS
        AOS.init({
            duration: 800,
            once: true,
            offset: 60,
            easing: 'ease-out-cubic'
        });
    </script>

    @stack('scripts')
</body>
</html>