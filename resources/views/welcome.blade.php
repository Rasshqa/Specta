@extends('layouts.app')

@section('title', 'SPECTA XXI: REVELIORA – Celestial Treasure')

@section('content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-slate-950 pt-20 pb-16">
    <!-- Ambient Glow Backgrounds -->
    <div class="absolute top-1/4 left-1/4 w-[600px] h-[600px] bg-purple-600/20 rounded-full blur-[120px] pointer-events-none mix-blend-screen"></div>
    <div class="absolute bottom-1/4 right-1/4 w-[500px] h-[500px] bg-cyan-600/20 rounded-full blur-[100px] pointer-events-none mix-blend-screen"></div>

    <div class="container mx-auto px-6 relative z-10 text-center">
        <div data-aos="zoom-out" data-aos-duration="1200">
            <span class="inline-block px-4 py-1.5 rounded-full bg-slate-900/50 border border-purple-500/30 text-purple-300 text-sm font-bold uppercase tracking-widest mb-6 backdrop-blur-sm">
                SMAN 1 CIANJUR PRESENTS
            </span>
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black tracking-tighter mb-4 text-white drop-shadow-[0_0_15px_rgba(255,255,255,0.2)] uppercase">
                SPECTA <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">XXI</span>
            </h1>
            <h2 class="text-2xl md:text-4xl font-bold text-slate-300 tracking-[0.2em] uppercase mb-8">
                Reveliora
            </h2>
            <p class="text-base md:text-lg text-slate-400 max-w-2xl mx-auto mb-10 leading-relaxed">
                Menjelajahi keajaiban dan harta karun surgawi dalam perayaan seni, budaya, dan kreativitas terbesar tahun ini. Bergabunglah dalam perjalanan epik Celestial Treasure.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ route('tickets.index') }}" class="group relative px-8 py-4 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full font-bold text-white text-lg shadow-[0_0_30px_rgba(168,85,247,0.4)] transition-all hover:scale-105 hover:shadow-[0_0_50px_rgba(168,85,247,0.6)] w-full sm:w-auto">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        🎟️ Beli Tiket Sekarang
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </span>
                </a>
                <a href="#explore" class="px-8 py-4 bg-slate-800/50 hover:bg-slate-800 border border-slate-700/50 hover:border-slate-600 rounded-full font-bold text-slate-300 text-lg backdrop-blur-md transition-all hover:scale-105 w-full sm:w-auto">
                    Jelajahi Event
                </a>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce flex flex-col items-center opacity-50">
        <span class="text-xs text-slate-400 tracking-widest uppercase mb-2">Scroll</span>
        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
    </div>
</section>

<!-- Extracurricular Showcase Section -->
<section id="explore" class="py-24 bg-slate-950 relative border-t border-slate-900">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl md:text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 mb-4">
                Pilar Kreativitas
            </h2>
            <p class="text-slate-400 max-w-2xl mx-auto">Penampilan spektakuler dari berbagai ekstrakurikuler SMAN 1 Cianjur yang akan memukau malam Reveliora.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @php
                $ekskuls = [
                    ['name' => 'Paduan Suara', 'icon' => '🎵', 'desc' => 'Harmoni vokal surgawi yang menyatukan jiwa.', 'color' => 'purple'],
                    ['name' => 'Tari Tradisional', 'icon' => '💃', 'desc' => 'Keindahan gerak yang menceritakan warisan budaya.', 'color' => 'cyan'],
                    ['name' => 'Modern Dance', 'icon' => '⚡', 'desc' => 'Energi kinetik dalam balutan koreografi futuristik.', 'color' => 'blue'],
                    ['name' => 'Teater', 'icon' => '🎭', 'desc' => 'Dramatisasi epik lakon kehidupan dan fantasi.', 'color' => 'purple'],
                    ['name' => 'Vocal Group', 'icon' => '🎤', 'desc' => 'Kolaborasi talenta menyajikan aransemen modern.', 'color' => 'cyan'],
                    ['name' => 'Band SMAN 1', 'icon' => '🎸', 'desc' => 'Guncangan distorsi dan melodi yang membakar panggung.', 'color' => 'blue'],
                ];
            @endphp

            @foreach($ekskuls as $index => $ekskul)
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-8 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] group hover:border-{{ $ekskul['color'] }}-500/50 transition-all duration-300" data-tilt data-tilt-max="10" data-tilt-speed="400" data-tilt-glare data-tilt-max-glare="0.2" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <div class="w-16 h-16 rounded-2xl bg-{{ $ekskul['color'] }}-500/20 flex items-center justify-center text-3xl mb-6 border border-{{ $ekskul['color'] }}-500/30 group-hover:scale-110 transition-transform">
                    {{ $ekskul['icon'] }}
                </div>
                <h3 class="text-xl font-bold text-slate-100 mb-3">{{ $ekskul['name'] }}</h3>
                <p class="text-sm text-slate-400 leading-relaxed">{{ $ekskul['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Documentation Carousel Section (Splide.js) -->
<section class="py-24 relative overflow-hidden bg-slate-900/50">
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiMzMzMiLz48L3N2Zz4=')] opacity-20"></div>
    
    <div class="container mx-auto px-6 relative z-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12" data-aos="fade-right">
            <div>
                <h2 class="text-3xl md:text-5xl font-bold text-slate-100 mb-4">Galeri Bintang</h2>
                <p class="text-slate-400">Momen emas dari SPECTA tahun-tahun sebelumnya.</p>
            </div>
        </div>

        <div class="splide" id="doc-carousel" data-aos="fade-up">
            <div class="splide__track">
                <ul class="splide__list">
                    @for($i = 1; $i <= 5; $i++)
                    <li class="splide__slide p-2">
                        <div class="relative aspect-video rounded-2xl overflow-hidden border border-slate-700/50 group">
                            <!-- Placeholder Image using gradient -->
                            <div class="absolute inset-0 bg-gradient-to-br from-slate-800 to-slate-900 flex items-center justify-center">
                                <span class="text-slate-600 font-mono text-2xl opacity-50">MEMORI #{{ $i }}</span>
                            </div>
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-80"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-4 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                                <p class="text-purple-400 font-bold text-lg">Momen Spektakuler</p>
                                <p class="text-slate-300 text-sm">SPECTA XX: Euphoria</p>
                            </div>
                        </div>
                    </li>
                    @endfor
                </ul>
            </div>
        </div>
    </div>
</section>

<!-- Merchandise Catalog Section -->
<section id="merchandise" class="py-24 bg-slate-950 relative border-t border-slate-900">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-3xl md:text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 mb-4">
                Official Merchandise
            </h2>
            <p class="text-slate-400 max-w-2xl mx-auto">Koleksi eksklusif SPECTA XXI: REVELIORA. Tampil beda dengan desain Celestial Treasure yang elegan dan futuristik.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($merchandises as $index => $merch)
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl overflow-hidden shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] group hover:border-purple-500/50 transition-all duration-300 flex flex-col" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                <!-- Image Box -->
                <div class="aspect-square relative overflow-hidden bg-slate-900 flex items-center justify-center">
                    @if($merch->image_url)
                        <img src="{{ $merch->image_url }}" alt="{{ $merch->name }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="text-6xl opacity-20 group-hover:scale-110 transition-transform duration-700">🛍️</div>
                    @endif
                    <!-- Overlay gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 to-transparent opacity-80"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <p class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 drop-shadow-md">
                            Rp {{ number_format($merch->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <!-- Content Box -->
                <div class="p-6 flex-1 flex flex-col">
                    <h3 class="text-xl font-semibold text-slate-100 mb-3">{{ $merch->name }}</h3>
                    <p class="text-sm text-slate-400 leading-relaxed mb-6 flex-1">{{ $merch->description }}</p>
                    
                    @php
                        $waNumber = '6281234567890'; // Replace with actual admin WA number
                        $waText = urlencode("Halo Admin SPECTA XXI! Saya tertarik untuk membeli merchandise berikut:\n\n*{$merch->name}*\nHarga: Rp " . number_format($merch->price, 0, ',', '.') . "\n\nApakah masih tersedia?");
                    @endphp
                    <a href="https://wa.me/{{ $waNumber }}?text={{ $waText }}" target="_blank" rel="noopener noreferrer" class="w-full py-3 bg-slate-800/80 hover:bg-gradient-to-r hover:from-purple-600 hover:to-cyan-600 border border-slate-700/50 hover:border-transparent rounded-xl font-bold text-slate-200 text-center transition-all flex items-center justify-center gap-2 group-hover:shadow-[0_0_20px_rgba(168,85,247,0.4)]">
                        <span>Beli via WhatsApp</span>
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Footer CTA -->
<section class="py-24 relative overflow-hidden bg-slate-950">
    <div class="absolute inset-0 bg-gradient-to-t from-purple-900/20 to-transparent pointer-events-none"></div>
    <div class="container mx-auto px-6 relative z-10 text-center" data-aos="zoom-in">
        <h2 class="text-3xl md:text-5xl font-bold text-slate-100 mb-6">Siap Menjadi Bagian dari Sejarah?</h2>
        <p class="text-slate-400 mb-10 max-w-xl mx-auto">Jangan sampai kehabisan. Kuota tiket sangat terbatas. Bergabunglah bersama ribuan Velorans lainnya.</p>
        <a href="{{ route('tickets.index') }}" class="inline-block px-10 py-5 bg-white text-slate-950 rounded-full font-black text-xl hover:bg-cyan-400 hover:text-slate-950 transition-colors shadow-[0_0_40px_rgba(255,255,255,0.2)]">
            AMANKAN TIKET SAYA
        </a>
    </div>
</section>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Splide Carousel
        new Splide('#doc-carousel', {
            type   : 'loop',
            perPage: 3,
            focus  : 'center',
            gap    : '1.5rem',
            autoplay: true,
            interval: 3000,
            pauseOnHover: true,
            breakpoints: {
                1024: { perPage: 2 },
                640 : { perPage: 1 },
            }
        }).mount();

        // Initialize Vanilla-Tilt on elements with data-tilt
        VanillaTilt.init(document.querySelectorAll("[data-tilt]"));
    });
</script>
@endpush
@endsection
