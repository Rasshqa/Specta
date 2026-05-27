@extends('layouts.app')

@section('title', 'SPECTA REVELIORA – The Dark Fantasy Festival')

@section('content')
<div x-data="{ mobileMenuOpen: false, userDropdownOpen: false }" class="bg-black text-slate-100 min-h-screen relative overflow-hidden font-sans">
    
    <!-- Ambient Universe Glows -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] bg-purple-900/10 rounded-full blur-[140px] mix-blend-screen"></div>
        <div class="absolute bottom-[20%] right-[-10%] w-[600px] h-[600px] bg-cyan-900/10 rounded-full blur-[140px] mix-blend-screen"></div>
        <div class="absolute top-[40%] right-[10%] w-[500px] h-[500px] bg-indigo-900/10 rounded-full blur-[120px] mix-blend-screen"></div>
    </div>

    <!-- Star Field Background -->
    <div class="star-field">
        @for ($i = 0; $i < 60; $i++)
            @php
                $top = rand(0, 100);
                $left = rand(0, 100);
                $size = rand(1, 3);
                $duration = rand(4, 9);
                $delay = rand(0, 7);
            @endphp
            <div class="star" style="top: {{ $top }}%; left: {{ $left }}%; width: {{ $size }}px; height: {{ $size }}px; --duration: {{ $duration }}s; --delay: {{ $delay }}s;"></div>
        @endfor
    </div>

    <!-- ─── NAVIGATION BAR ─── -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-black/60 backdrop-blur-xl border-b border-white/5 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            
            <!-- Left Logo -->
            <a href="#" class="flex items-center gap-3 group focus:outline-none">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-600 to-cyan-500 flex items-center justify-center font-bold text-white shadow-[0_0_15px_rgba(168,85,247,0.3)] group-hover:scale-105 transition-all">
                    SR
                </div>
                <span class="font-black text-lg tracking-[0.2em] text-transparent bg-clip-text bg-gradient-to-r from-slate-100 to-purple-300 uppercase">
                    SPECTA REVELIORA
                </span>
            </a>

            <!-- Center Nav Links (Desktop) -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="#" class="text-sm font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">HOME</a>
                <a href="#lore" class="text-sm font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">LORE</a>
                <a href="/docs" class="text-sm font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">DOCS</a>
                <a href="{{ route('tickets.index') }}" class="text-sm font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">TICKETS</a>
                <a href="/merch" class="text-sm font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">MERCH</a>
                <a href="#gallery" class="text-sm font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">QUIZ</a>
            </div>

            <!-- Right Actions (Desktop) -->
            <div class="hidden lg:flex items-center gap-6">
                <!-- Merchandise Cart Icon -->
                <a href="/merch" class="text-slate-400 hover:text-purple-300 transition-colors relative" title="Merchandise Catalog">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                </a>

                @auth
                    <!-- Authenticated User Dropdown -->
                    <div class="relative">
                        <button @click="userDropdownOpen = !userDropdownOpen" @click.away="userDropdownOpen = false" class="flex items-center gap-2 bg-white/5 border border-white/10 hover:border-purple-500/40 rounded-xl px-4 py-2 text-sm text-slate-200 transition-all cursor-pointer">
                            <span>👤 {{ Auth::user()->name }}</span>
                            <svg class="w-4 h-4 transition-transform duration-200" :class="userDropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        
                        <div x-show="userDropdownOpen" x-transition class="absolute right-0 mt-2 w-48 bg-slate-900 border border-slate-800 rounded-xl shadow-2xl overflow-hidden py-1">
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-purple-600 hover:text-white transition-colors">Dashboard Admin</a>
                            @elseif(Auth::user()->isGatekeeper())
                                <a href="{{ route('gatekeeper.index') }}" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-purple-600 hover:text-white transition-colors">Gatekeeper Scan</a>
                            @endif
                            <a href="{{ route('tickets.index') }}" class="block px-4 py-2.5 text-sm text-slate-300 hover:bg-purple-600 hover:text-white transition-colors">Beli Tiket</a>
                            
                            <form method="POST" action="{{ route('logout') }}" class="block border-t border-slate-800/80">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-400 hover:bg-red-950/20 transition-colors cursor-pointer">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Sign In / Register Buttons -->
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-300 hover:text-white transition-colors">SIGN IN</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-cyan-500 hover:from-purple-500 hover:to-cyan-400 rounded-xl text-xs font-bold text-white shadow-[0_0_15px_rgba(168,85,247,0.3)] transition-all transform hover:scale-[1.03]">
                        REGISTER
                    </a>
                @endauth
            </div>

            <!-- Hamburger Button (Mobile) -->
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden text-slate-300 hover:text-white focus:outline-none cursor-pointer">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Navigation Menu -->
        <div x-show="mobileMenuOpen" x-transition class="lg:hidden bg-slate-950/95 backdrop-blur-2xl border-b border-white/5 px-6 py-8 space-y-6">
            <div class="flex flex-col gap-4">
                <a href="#" @click="mobileMenuOpen = false" class="text-base font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">HOME</a>
                <a href="#lore" @click="mobileMenuOpen = false" class="text-base font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">LORE</a>
                <a href="/docs" @click="mobileMenuOpen = false" class="text-base font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">DOCS</a>
                <a href="{{ route('tickets.index') }}" @click="mobileMenuOpen = false" class="text-base font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">TICKETS</a>
                <a href="/merch" @click="mobileMenuOpen = false" class="text-base font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">MERCH</a>
                <a href="#gallery" @click="mobileMenuOpen = false" class="text-base font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">QUIZ</a>
            </div>
            
            <div class="h-px bg-white/5 my-4"></div>

            <div class="flex flex-col gap-4">
                @auth
                    <p class="text-xs text-slate-500 uppercase tracking-widest">Signed in as {{ Auth::user()->name }}</p>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-purple-400">Dashboard Admin</a>
                    @elseif(Auth::user()->isGatekeeper())
                        <a href="{{ route('gatekeeper.index') }}" class="text-sm font-bold text-purple-400">Gatekeeper Scan</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-red-400 cursor-pointer">Sign Out</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-center py-3 bg-slate-900 border border-slate-800 rounded-xl text-sm font-bold text-slate-300">SIGN IN</a>
                    <a href="{{ route('register') }}" class="text-center py-3 bg-gradient-to-r from-purple-600 to-cyan-500 rounded-xl text-sm font-bold text-white">REGISTER</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ─── HERO SECTION ─── -->
    <section class="min-h-screen flex items-center justify-center pt-28 pb-20 relative z-10 px-6">
        <div class="max-w-5xl text-center" data-aos="zoom-out" data-aos-duration="1200">
            
            <!-- Festival Badge -->
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-purple-950/40 border border-purple-500/30 text-purple-300 text-xs font-bold uppercase tracking-widest mb-8 backdrop-blur-md">
                ✨ PREMIUM SCHOOL FESTIVAL 2026 ✨
            </span>

            <!-- Main Heading -->
            <h1 class="text-6xl md:text-8xl font-extrabold tracking-tighter mb-4 text-white uppercase drop-shadow-[0_0_30px_rgba(186,131,255,0.25)] hero-glitch" data-text="SPECTA REVELIORA">
                SPECTA <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">REVELIORA</span>
            </h1>

            <!-- Subtitle -->
            <h2 class="text-lg md:text-2xl font-bold tracking-[0.3em] uppercase text-transparent bg-clip-text bg-gradient-to-r from-slate-300 via-yellow-200 to-slate-300 mb-8 hero-neon">
                Where Magic Meets Reality
            </h2>

            <!-- Description -->
            <p class="text-slate-400 text-base md:text-lg max-w-3xl mx-auto mb-12 leading-relaxed font-light">
                Program kerja tahunan OSIS &amp; MPK <span class="text-slate-300 font-semibold">SMAN 1 Cianjur</span> yang menghadirkan tiga fase akbar — Grand Opening, Middle Event, hingga Grand Closing berupa konser spektakuler.
                Bertema <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-amber-400 font-bold">Celestial Treasure</span> — merayakan bahwa kebersamaan dan kreativitas adalah harta karun sejati yang tak ternilai.
            </p>

            <!-- Metadata Info Badges -->
            <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-slate-500 mb-12 font-medium">
                <span class="flex items-center gap-2 bg-slate-900/60 border border-slate-800 px-4 py-2 rounded-xl">
                    📅 April 26, 2026
                </span>
                <span class="flex items-center gap-2 bg-slate-900/60 border border-slate-800 px-4 py-2 rounded-xl">
                    📍 Jl. Pangeran Hidayatullah No.62, Sawah Gede, Kec. Cianjur, Kabupaten Cianjur, Jawa Barat 43212
                </span>
                <span class="flex items-center gap-2 bg-slate-900/60 border border-slate-800 px-4 py-2 rounded-xl">
                    ⏰ 13:00 – 23:00 WIB
                </span>
            </div>

            <!-- Call to Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-5 max-w-md mx-auto sm:max-w-none">
                <a href="{{ route('tickets.index') }}" class="w-full sm:w-auto group relative px-10 py-5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 rounded-full font-extrabold text-white text-lg tracking-wider transition-all duration-300 hover:scale-105 shadow-[0_0_35px_rgba(168,85,247,0.45)] hover:shadow-[0_0_55px_rgba(168,85,247,0.65)] flex items-center justify-center gap-2">
                    🎟️ BUY TICKET
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                <a href="#lore" class="w-full sm:w-auto px-10 py-5 bg-black/40 hover:bg-slate-900/50 border border-slate-700/60 hover:border-slate-500 rounded-full font-bold text-slate-300 text-lg tracking-wider backdrop-blur-md transition-all hover:scale-105">
                    EXPLORE LORE
                </a>
            </div>

            <!-- Counter Stats -->
            <div class="grid grid-cols-3 gap-4 max-w-xl mx-auto mt-20 pt-10 border-t border-white/5">
                <div>
                    <p class="text-3xl md:text-4xl font-black text-white">4</p>
                    <p class="text-xs text-slate-500 uppercase tracking-widest mt-1">Guest Stars</p>
                </div>
                <div>
                    <p class="text-3xl md:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">825+</p>
                    <p class="text-xs text-slate-500 uppercase tracking-widest mt-1">Tickets Available</p>
                </div>
                <div>
                    <p class="text-3xl md:text-4xl font-black text-white">9</p>
                    <p class="text-xs text-slate-500 uppercase tracking-widest mt-1">Stages & Events</p>
                </div>
            </div>

        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center opacity-40 animate-bounce">
            <span class="text-[10px] text-slate-500 tracking-[0.3em] uppercase mb-2">Scroll</span>
            <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </div>
    </section>

    <!-- ─── CELESTIAL LORE & PHILOSOPHY SECTION ─── -->
    <section id="reveliora" class="py-28 relative z-10 border-t border-white/5 overflow-hidden">
        <!-- Decorative background glow blobs -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[300px] bg-purple-900/10 blur-[100px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-cyan-900/10 blur-[120px] rounded-full pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6">

            <!-- Section Header -->
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-purple-950/50 border border-purple-500/30 text-purple-300 text-xs font-bold uppercase tracking-[0.25em] mb-6">
                    ✧˖° SPECTA XXI PROUDLY PRESENTS °˖✧
                </span>
                <h2 class="text-4xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-blue-300 to-cyan-400 uppercase tracking-wide leading-tight">
                    SPECTA REVELIORA
                </h2>
                <p class="text-slate-400 max-w-3xl mx-auto mt-5 text-base md:text-lg leading-relaxed italic font-light">
                    "Guided by the spirit of Reveliora, we begin a journey of discovery, courage, and endless creativity, where every step brings us closer to something extraordinary." 🌌
                </p>
            </div>

            <!-- Logo Philosophy — 3 Pillars Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20" data-aos="fade-up" data-aos-delay="100">

                <!-- Pillar 1: Tongkat Biru -->
                <div class="relative group bg-gradient-to-b from-blue-950/40 to-slate-950/60 backdrop-blur-xl border border-blue-500/20 rounded-3xl p-8 overflow-hidden hover:border-blue-400/40 transition-all duration-500">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600/20 to-blue-400/10 border border-blue-500/30 flex items-center justify-center text-3xl mb-6 shadow-inner">
                            🚀
                        </div>
                        <span class="text-xs font-bold text-blue-400 uppercase tracking-[0.2em] block mb-2">Simbol Pertama</span>
                        <h3 class="text-2xl font-black text-slate-100 mb-4">Tongkat Biru</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Melambangkan <span class="text-blue-300 font-semibold">potensi & jati diri</span>. Ia berdiri tegak seperti cahaya yang belum sepenuhnya terungkap — tenang, dalam, dan penuh kemungkinan.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-2">
                            <span class="text-xs px-3 py-1.5 rounded-full bg-blue-950/60 border border-blue-500/20 text-blue-300 font-semibold">Harapan</span>
                            <span class="text-xs px-3 py-1.5 rounded-full bg-blue-950/60 border border-blue-500/20 text-blue-300 font-semibold">Ketulusan</span>
                            <span class="text-xs px-3 py-1.5 rounded-full bg-blue-950/60 border border-blue-500/20 text-blue-300 font-semibold">Kedamaian</span>
                        </div>
                    </div>
                </div>

                <!-- Pillar 2: Angsa Ungu -->
                <div class="relative group bg-gradient-to-b from-purple-950/40 to-slate-950/60 backdrop-blur-xl border border-purple-500/20 rounded-3xl p-8 overflow-hidden hover:border-purple-400/40 transition-all duration-500 md:-mt-4 md:shadow-[0_0_60px_rgba(147,51,234,0.1)]">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-purple-500/15 rounded-full blur-3xl group-hover:bg-purple-500/25 transition-all duration-500"></div>
                    <!-- Featured badge -->
                    <div class="absolute top-4 right-4 text-xs bg-purple-600/80 border border-purple-400/30 text-purple-100 px-3 py-1 rounded-full font-bold uppercase tracking-wider">
                        Utama
                    </div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-600/20 to-purple-400/10 border border-purple-500/30 flex items-center justify-center text-3xl mb-6 shadow-inner">
                            🦢
                        </div>
                        <span class="text-xs font-bold text-purple-400 uppercase tracking-[0.2em] block mb-2">Simbol Kedua</span>
                        <h3 class="text-2xl font-black text-slate-100 mb-4">Angsa Ungu</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Simbol <span class="text-purple-300 font-semibold">keanggunan & transformasi</span>. Menggambarkan perjalanan menuju kedewasaan, dari yang sederhana menjadi luar biasa.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-2">
                            <span class="text-xs px-3 py-1.5 rounded-full bg-purple-950/60 border border-purple-500/20 text-purple-300 font-semibold">Kreativitas</span>
                            <span class="text-xs px-3 py-1.5 rounded-full bg-purple-950/60 border border-purple-500/20 text-purple-300 font-semibold">Kebijaksanaan</span>
                            <span class="text-xs px-3 py-1.5 rounded-full bg-purple-950/60 border border-purple-500/20 text-purple-300 font-semibold">Keberanian</span>
                        </div>
                    </div>
                </div>

                <!-- Pillar 3: Bentuk R & 21 -->
                <div class="relative group bg-gradient-to-b from-cyan-950/40 to-slate-950/60 backdrop-blur-xl border border-cyan-500/20 rounded-3xl p-8 overflow-hidden hover:border-cyan-400/40 transition-all duration-500">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-cyan-500/10 rounded-full blur-3xl group-hover:bg-cyan-500/20 transition-all duration-500"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-600/20 to-cyan-400/10 border border-cyan-500/30 flex items-center justify-center text-3xl mb-6 shadow-inner">
                            ✦
                        </div>
                        <span class="text-xs font-bold text-cyan-400 uppercase tracking-[0.2em] block mb-2">Simbol Ketiga</span>
                        <h3 class="text-2xl font-black text-slate-100 mb-4">Bentuk "R" & 21</h3>
                        <p class="text-sm text-slate-400 leading-relaxed">
                            Huruf "R" yang menyatu dengan angka <span class="text-cyan-300 font-semibold">21</span> menjadi simbol utama SPECTA XXI — merepresentasikan <span class="text-cyan-300 font-semibold">Identitas REVELIORA</span> yang terus berkembang.
                        </p>
                        <div class="mt-6 flex flex-wrap gap-2">
                            <span class="text-xs px-3 py-1.5 rounded-full bg-cyan-950/60 border border-cyan-500/20 text-cyan-300 font-semibold">Identitas</span>
                            <span class="text-xs px-3 py-1.5 rounded-full bg-cyan-950/60 border border-cyan-500/20 text-cyan-300 font-semibold">Revelation</span>
                            <span class="text-xs px-3 py-1.5 rounded-full bg-cyan-950/60 border border-cyan-500/20 text-cyan-300 font-semibold">Euphoria</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Celestial Treasure Philosophy — Full Width Banner -->
            <div class="relative rounded-3xl overflow-hidden border border-white/10 shadow-[0_0_80px_rgba(147,51,234,0.1)]" data-aos="zoom-in" data-aos-delay="150">
                <!-- Gradient background -->
                <div class="absolute inset-0 bg-gradient-to-br from-purple-950/70 via-slate-950/90 to-cyan-950/60"></div>
                <!-- Glow orbs -->
                <div class="absolute top-0 left-0 w-64 h-64 bg-purple-600/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-64 h-64 bg-cyan-600/10 rounded-full blur-3xl"></div>

                <div class="relative z-10 p-10 md:p-16 grid grid-cols-1 lg:grid-cols-5 gap-12 items-center">
                    <!-- Left: Theme Name -->
                    <div class="lg:col-span-2 text-center lg:text-left">
                        <span class="text-xs font-bold text-yellow-400 uppercase tracking-[0.25em] block mb-3">Tema SPECTA XXI</span>
                        <h3 class="text-4xl md:text-5xl font-black text-white leading-tight mb-3">
                            CELESTIAL<br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-amber-400">TREASURE</span>
                        </h3>
                        <p class="text-slate-400 text-sm font-medium italic">"Harta Karun Langit"</p>
                        
                        <div class="mt-8 flex items-center gap-3 justify-center lg:justify-start">
                            <span class="text-2xl">💙</span>
                            <p class="text-slate-300 text-sm font-bold italic">Uncover the Wonders,<br>Beyond the Stars 🌟</p>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="hidden lg:block lg:col-span-1 flex justify-center">
                        <div class="h-full w-px bg-gradient-to-b from-transparent via-white/10 to-transparent mx-auto"></div>
                    </div>

                    <!-- Right: Philosophy Breakdown -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 flex-shrink-0 rounded-xl bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center text-lg mt-0.5">✦</div>
                            <div>
                                <h4 class="text-sm font-black text-yellow-300 uppercase tracking-wider mb-1">CELESTIAL (Langit)</h4>
                                <p class="text-xs text-slate-400 leading-relaxed">Melambangkan sifat luhur, tak terbatas, dan agung dari potensi serta semangat para siswa SMANSA.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 flex-shrink-0 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-lg mt-0.5">💎</div>
                            <div>
                                <h4 class="text-sm font-black text-amber-300 uppercase tracking-wider mb-1">TREASURE (Harta Karun)</h4>
                                <p class="text-xs text-slate-400 leading-relaxed">Menyimbolkan nilai intrinsik hasil dari dedikasi, kebersamaan, dan perjuangan — warisan abadi yang tercipta bersama.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 flex-shrink-0 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-lg mt-0.5">🌌</div>
                            <div>
                                <h4 class="text-sm font-black text-purple-300 uppercase tracking-wider mb-1">Filosofi Inti</h4>
                                <p class="text-xs text-slate-400 leading-relaxed">Kebersamaan & kreativitas yang tak terbatas menciptakan warisan yang sangat berharga dan akan dikenang abadi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Velorans Tagline -->
            <div class="text-center mt-16" data-aos="fade-up" data-aos-delay="200">
                <p class="text-slate-500 text-xs uppercase tracking-[0.3em] font-semibold mb-3">Panggilan Para Penonton</p>
                <p class="text-3xl md:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-300 via-blue-200 to-cyan-300">
                    "So, Velorans…"
                </p>
                <p class="text-slate-400 text-base mt-3 italic font-light">
                    let's uncover the wonders and shine beyond the stars together! ✨
                </p>
            </div>

        </div>
    </section>

    <!-- ─── PUSAT INFORMASI (INFORMATION CENTER) ─── -->
    <section id="lore" :class="selectedEskul !== null ? 'z-50' : 'z-10'" class="py-16 md:py-24 relative border-t border-white/5" x-data="{ infoTab: $persist('eskul'), selectedEskul: null, loaded: false }" x-init="setTimeout(() => loaded = true, 400)">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-10 md:mb-14" data-aos="fade-up">
                <span class="text-xs font-bold text-cyan-400 uppercase tracking-[0.25em] block mb-3">INFORMATION CENTER</span>
                <h2 class="text-3xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 uppercase">
                    Pusat Informasi SPECTA
                </h2>
                <p class="text-slate-400 max-w-2xl mx-auto mt-4 text-sm leading-relaxed">
                    Telusuri profil ekstrakurikuler, daftar pemenang lomba, serta dokumentasi perjalanan sejarah SPECTA SMANSA.
                </p>
            </div>

            <!-- Tab Switchers -->
            <div class="flex items-center justify-center gap-1 mb-8 bg-white/5 border border-white/10 rounded-2xl p-1 max-w-md mx-auto">
                <button @click="infoTab = 'eskul'" :class="infoTab === 'eskul' ? 'bg-gradient-to-r from-purple-600 to-cyan-600 text-white font-bold' : 'text-slate-400 hover:text-slate-200'" class="flex-1 py-2.5 px-3 rounded-xl text-xs uppercase tracking-wider transition-all cursor-pointer text-center">
                    🎟️ <span class="hidden sm:inline">Eskul</span>
                </button>
                <button @click="infoTab = 'winners'" :class="infoTab === 'winners' ? 'bg-gradient-to-r from-purple-600 to-cyan-600 text-white font-bold' : 'text-slate-400 hover:text-slate-200'" class="flex-1 py-2.5 px-3 rounded-xl text-xs uppercase tracking-wider transition-all cursor-pointer text-center">
                    🏆 <span class="hidden sm:inline">Pemenang</span>
                </button>
                <button @click="infoTab = 'timeline'" :class="infoTab === 'timeline' ? 'bg-gradient-to-r from-purple-600 to-cyan-600 text-white font-bold' : 'text-slate-400 hover:text-slate-200'" class="flex-1 py-2.5 px-3 rounded-xl text-xs uppercase tracking-wider transition-all cursor-pointer text-center">
                    📅 <span class="hidden sm:inline">Timeline</span>
                </button>
            </div>

            <!-- ── TAB: ESKUL ── -->
            <div x-show="infoTab === 'eskul'" x-transition>

                <!-- Skeleton loading -->
                <div x-show="!loaded" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @for($i = 0; $i < 3; $i++)
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-6 animate-pulse space-y-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/10"></div>
                        <div class="h-4 bg-white/10 rounded w-3/4"></div>
                        <div class="space-y-2">
                            <div class="h-3 bg-white/10 rounded"></div>
                            <div class="h-3 bg-white/10 rounded w-5/6"></div>
                        </div>
                        <div class="h-9 bg-white/10 rounded-xl w-full"></div>
                    </div>
                    @endfor
                </div>

                @if($eskuls->isEmpty())
                <div x-show="loaded" class="text-center py-16 text-slate-500">
                    <p class="text-5xl mb-4">🎟️</p>
                    <p class="font-semibold text-lg">Belum ada data eskul.</p>
                    <p class="text-sm mt-2">Admin belum menambahkan profil ekstrakurikuler.</p>
                </div>
                @else
                <div x-show="loaded" x-transition class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($eskuls as $index => $eskul)
                    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl overflow-hidden hover:border-purple-500/40 transition-all duration-300 group flex flex-col" data-aos="fade-up" data-aos-delay="{{ min($index * 80, 400) }}">
                        {{-- Foto eskul --}}
                        @if($eskul->image_path)
                        <div class="relative h-40 overflow-hidden">
                            <img src="{{ $eskul->image_url }}" alt="{{ $eskul->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <div class="absolute top-3 left-3 w-11 h-11 rounded-xl bg-black/50 backdrop-blur flex items-center justify-center text-2xl">{{ $eskul->icon }}</div>
                        </div>
                        @else
                        <div class="h-28 bg-gradient-to-br from-purple-950/60 to-slate-900 flex items-center justify-center text-5xl">
                            {{ $eskul->icon }}
                        </div>
                        @endif

                        <div class="p-5 flex flex-col flex-1 gap-3">
                            <div>
                                <h3 class="text-base font-bold text-slate-100">{{ $eskul->name }}</h3>
                                <p class="text-xs text-slate-400 mt-1 leading-relaxed line-clamp-2">{{ $eskul->description }}</p>
                            </div>
                            @if($eskul->achievements)
                            <p class="text-xs text-yellow-400 font-semibold">🏅 {{ $eskul->achievements }}</p>
                            @endif
                            <button @click="selectedEskul = {{ json_encode([
                                'name'         => $eskul->name,
                                'icon'         => $eskul->icon,
                                'detail'       => $eskul->detail,
                                'schedule'     => $eskul->schedule,
                                'contact'      => $eskul->contact,
                                'activities'   => $eskul->activities,
                                'achievements' => $eskul->achievements,
                                'image_url'    => $eskul->image_url,
                            ]) }}" class="mt-auto w-full py-2.5 bg-slate-950 border border-white/10 hover:border-purple-500/40 rounded-xl text-xs font-bold text-slate-300 hover:text-white transition-all cursor-pointer">
                                Lihat Profil Lengkap →
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <!-- ── TAB: PEMENANG ── -->
            <div x-show="infoTab === 'winners'" x-transition x-data="{ searchWinner: '' }">

                <!-- Skeleton -->
                <div x-show="!loaded" class="space-y-3">
                    @for($i = 0; $i < 4; $i++)
                    <div class="bg-white/5 border border-white/10 rounded-xl p-4 animate-pulse flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-white/10 flex-shrink-0"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-3 bg-white/10 rounded w-1/3"></div>
                            <div class="h-3 bg-white/10 rounded w-2/3"></div>
                        </div>
                    </div>
                    @endfor
                </div>

                <div x-show="loaded" x-transition class="max-w-4xl mx-auto">
                    @if($winners->isEmpty())
                    <div class="text-center py-16 text-slate-500">
                        <p class="text-5xl mb-4">🏆</p>
                        <p class="font-semibold text-lg">Belum ada data pemenang.</p>
                        <p class="text-sm mt-2">Admin belum menambahkan daftar pemenang.</p>
                    </div>
                    @else
                    <div class="mb-5">
                        <input type="text" x-model="searchWinner" placeholder="Cari nama, sekolah, atau kategori..." class="w-full bg-slate-900 border border-slate-800 focus:border-purple-500 rounded-xl px-5 py-3 text-sm text-slate-200 outline-none transition-all placeholder:text-slate-600">
                    </div>
                    <div class="bg-slate-900/60 border border-white/10 rounded-2xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[560px]">
                                <thead>
                                    <tr class="border-b border-white/5 bg-white/5">
                                        <th class="py-3.5 px-5 text-xs font-bold uppercase tracking-widest text-slate-400">Foto</th>
                                        <th class="py-3.5 px-5 text-xs font-bold uppercase tracking-widest text-slate-400">Prestasi</th>
                                        <th class="py-3.5 px-5 text-xs font-bold uppercase tracking-widest text-slate-400">Nama</th>
                                        <th class="py-3.5 px-5 text-xs font-bold uppercase tracking-widest text-slate-400 hidden sm:table-cell">Sekolah</th>
                                        <th class="py-3.5 px-5 text-xs font-bold uppercase tracking-widest text-slate-400 hidden md:table-cell">Kategori</th>
                                        @if($winners->whereNotNull('score')->isNotEmpty())
                                        <th class="py-3.5 px-5 text-xs font-bold uppercase tracking-widest text-slate-400 text-right">Nilai</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($winners as $winner)
                                    <tr class="border-b border-white/5 hover:bg-white/5 transition-colors"
                                        x-show="searchWinner === '' || '{{ strtolower($winner->name . ' ' . $winner->school . ' ' . $winner->category) }}'.includes(searchWinner.toLowerCase())">
                                        <td class="py-3 px-5">
                                            @if($winner->image_path)
                                                <img src="{{ $winner->image_url }}" alt="{{ $winner->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-700">
                                            @else
                                                <div class="w-9 h-9 rounded-full bg-slate-800 flex items-center justify-center text-base">🏅</div>
                                            @endif
                                        </td>
                                        <td class="py-3 px-5 text-sm font-black text-yellow-400 whitespace-nowrap">{{ $winner->rank }}</td>
                                        <td class="py-3 px-5 text-sm font-bold text-slate-100 whitespace-nowrap">{{ $winner->name }}</td>
                                        <td class="py-3 px-5 text-sm text-slate-300 hidden sm:table-cell">{{ $winner->school }}</td>
                                        <td class="py-3 px-5 text-xs text-cyan-400 font-semibold hidden md:table-cell">{{ $winner->category }}</td>
                                        @if($winners->whereNotNull('score')->isNotEmpty())
                                        <td class="py-3 px-5 text-sm text-slate-200 font-mono text-right font-bold">{{ $winner->score ?? '—' }}</td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ── TAB: TIMELINE ── -->
            <div x-show="infoTab === 'timeline'" x-transition>

                <!-- Skeleton -->
                <div x-show="!loaded" class="max-w-3xl mx-auto space-y-6 pl-8 border-l border-white/10">
                    @for($i = 0; $i < 3; $i++)
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-5 animate-pulse space-y-3">
                        <div class="h-4 bg-white/10 rounded w-1/4"></div>
                        <div class="h-4 bg-white/10 rounded w-1/2"></div>
                        <div class="h-3 bg-white/10 rounded"></div>
                        <div class="h-3 bg-white/10 rounded w-5/6"></div>
                    </div>
                    @endfor
                </div>

                @if($timelines->isEmpty())
                <div x-show="loaded" class="text-center py-16 text-slate-500">
                    <p class="text-5xl mb-4">📅</p>
                    <p class="font-semibold text-lg">Belum ada data timeline.</p>
                    <p class="text-sm mt-2">Admin belum menambahkan riwayat SPECTA.</p>
                </div>
                @else
                <div x-show="loaded" x-transition class="max-w-3xl mx-auto relative pl-8 sm:pl-12 border-l border-purple-500/20 space-y-8">
                    @foreach($timelines as $tl)
                    <div class="relative" data-aos="fade-left">
                        <div class="absolute -left-[45px] sm:-left-[61px] top-2 w-7 h-7 rounded-full {{ $tl->is_current ? 'bg-cyan-500 shadow-[0_0_14px_rgba(6,182,212,0.6)]' : 'bg-purple-700' }} border-4 border-black flex items-center justify-center">
                            <span class="w-2 h-2 rounded-full bg-white"></span>
                        </div>
                        <div class="bg-white/5 border {{ $tl->is_current ? 'border-cyan-500/30' : 'border-white/10' }} rounded-2xl overflow-hidden">
                            @if($tl->image_path)
                            <img src="{{ $tl->image_url }}" alt="{{ $tl->title }}" class="w-full h-36 object-cover">
                            @endif
                            <div class="p-5">
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <span class="font-mono text-xs font-black {{ $tl->is_current ? 'text-cyan-400' : 'text-purple-400' }}">{{ $tl->year }}</span>
                                    @if($tl->is_current)
                                    <span class="text-xs bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 px-2 py-0.5 rounded-full font-bold">Tahun Berjalan</span>
                                    @endif
                                </div>
                                <h3 class="text-base font-bold text-slate-100">
                                    {{ $tl->title }}
                                    @if($tl->subtitle)<span class="text-sm text-slate-400 font-normal">— {{ $tl->subtitle }}</span>@endif
                                </h3>
                                <p class="text-xs text-slate-400 mt-2 leading-relaxed">{{ $tl->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

        </div>

        <!-- Alpine.js Eskul Detail Modal -->
        <div
            x-show="selectedEskul !== null"
            x-init="$watch('selectedEskul', value => document.body.style.overflow = value ? 'hidden' : '')"
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] flex items-center justify-center p-4"
            x-cloak
        >
            <!-- Backdrop -->
            <div @click="selectedEskul = null" class="absolute inset-0 bg-black/80 backdrop-blur-md"></div>

            <!-- Modal panel -->
            <div
                x-show="selectedEskul !== null"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-3"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-3"
                class="relative z-10 bg-slate-900 border border-white/10 rounded-3xl w-full max-w-md shadow-[0_0_60px_rgba(168,85,247,0.25)] max-h-[88vh] flex flex-col overflow-hidden"
            >
                <!-- Close button -->
                <button
                    @click="selectedEskul = null"
                    class="absolute top-3 right-3 z-20 bg-slate-950/80 border border-white/10 hover:bg-slate-800 rounded-full w-8 h-8 flex items-center justify-center text-slate-400 hover:text-white transition-all cursor-pointer"
                >✕</button>

                <!-- Cover image -->
                <template x-if="selectedEskul && selectedEskul.image_url">
                    <div class="relative h-44 flex-shrink-0 overflow-hidden">
                        <img :src="selectedEskul.image_url" :alt="selectedEskul.name" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/30 to-transparent"></div>
                        <!-- Icon overlay on image -->
                        <div class="absolute bottom-3 left-4 flex items-end gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-slate-900/80 backdrop-blur border border-white/10 flex items-center justify-center text-2xl" x-text="selectedEskul ? selectedEskul.icon : ''"></div>
                            <h3 class="text-lg font-black text-white pb-0.5 drop-shadow" x-text="selectedEskul ? selectedEskul.name : ''"></h3>
                        </div>
                    </div>
                </template>

                <!-- Scrollable body -->
                <div class="p-6 overflow-y-auto flex-1 space-y-4">

                    <!-- Header (if no image) -->
                    <template x-if="!(selectedEskul && selectedEskul.image_url)">
                        <div class="flex items-center gap-3 pb-2">
                            <div class="w-12 h-12 rounded-2xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-2xl flex-shrink-0" x-text="selectedEskul ? selectedEskul.icon : ''"></div>
                            <div>
                                <span class="text-xs font-bold text-purple-400 tracking-wider uppercase block">Profil Eskul</span>
                                <h3 class="text-lg font-black text-slate-100 leading-tight" x-text="selectedEskul ? selectedEskul.name : ''"></h3>
                            </div>
                        </div>
                    </template>

                    <div class="h-px bg-white/5"></div>

                    <div class="space-y-4 text-sm text-slate-300">
                        <template x-if="selectedEskul && selectedEskul.detail">
                            <div>
                                <h4 class="text-xs uppercase text-slate-500 tracking-widest font-bold mb-1.5">Tentang Eskul</h4>
                                <p x-text="selectedEskul.detail" class="leading-relaxed"></p>
                            </div>
                        </template>
                        <template x-if="selectedEskul && selectedEskul.schedule">
                            <div>
                                <h4 class="text-xs uppercase text-slate-500 tracking-widest font-bold mb-1.5">Jadwal Latihan</h4>
                                <p class="text-cyan-400 font-semibold" x-text="selectedEskul.schedule"></p>
                            </div>
                        </template>
                        <template x-if="selectedEskul && selectedEskul.activities">
                            <div>
                                <h4 class="text-xs uppercase text-slate-500 tracking-widest font-bold mb-1.5">Agenda & Kegiatan</h4>
                                <p x-text="selectedEskul.activities" class="leading-relaxed"></p>
                            </div>
                        </template>
                        <template x-if="selectedEskul && selectedEskul.achievements">
                            <div>
                                <h4 class="text-xs uppercase text-slate-500 tracking-widest font-bold mb-1.5">Prestasi Unggulan</h4>
                                <p class="text-yellow-400 font-bold" x-text="selectedEskul.achievements"></p>
                            </div>
                        </template>
                        <template x-if="selectedEskul && selectedEskul.contact">
                            <div>
                                <h4 class="text-xs uppercase text-slate-500 tracking-widest font-bold mb-1.5">Narahubung (WA)</h4>
                                <a :href="'https://wa.me/' + selectedEskul.contact.replace(/[^0-9]/g, '')" target="_blank" class="inline-flex items-center gap-2 text-green-400 hover:text-green-300 font-semibold">
                                    💬 <span x-text="selectedEskul.contact"></span>
                                </a>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Footer action -->
                <div class="px-6 py-4 border-t border-white/5 flex-shrink-0">
                    <button @click="selectedEskul = null" class="w-full py-3 bg-slate-800 hover:bg-slate-700 border border-slate-700/50 rounded-xl font-bold text-slate-300 transition-all cursor-pointer text-xs uppercase tracking-wider">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- ─── INTERACTIVE MINIGAME: STORY QUIZ ─── -->
    <section id="gallery" class="py-28 relative z-10 bg-slate-950/40 border-t border-white/5" x-data="storyQuiz()">
        <div class="max-w-4xl mx-auto px-6">
            
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-xs font-bold text-purple-400 uppercase tracking-[0.25em] block mb-3">MINIGAME CHALLENGE</span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-100 uppercase tracking-wide">
                    Story Quiz
                </h2>
                <p class="text-slate-400 mt-4 text-sm max-w-xl mx-auto leading-relaxed">
                    Uji pengetahuanmu tentang sejarah dan filosofi SPECTA SMANSA! Dapatkan skor tertinggi dan buktikan diri sebagai Velorans sejati.
                </p>
            </div>

            <!-- Quiz Main Widget Container -->
            <div class="bg-slate-900/60 backdrop-blur-xl border border-white/10 rounded-3xl p-8 sm:p-12 shadow-2xl relative overflow-hidden min-h-[400px] flex flex-col justify-between" data-aos="zoom-in">
                
                <!-- Glow elements inside widget -->
                <div class="absolute -top-20 -right-20 w-48 h-48 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-20 -left-20 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

                <!-- STATE: START -->
                <div x-show="gameState === 'start'" x-transition class="text-center space-y-6 my-auto">
                    <div class="text-6xl animate-pulse">🌌</div>
                    <h3 class="text-2xl font-bold text-slate-100">Siap Menjelajahi Lorong Waktu SPECTA?</h3>
                    <p class="text-slate-400 text-sm max-w-md mx-auto leading-relaxed">
                        Pertanyaan kuis bersumber dari data ekstrakurikuler SMANSA dan timeline sejarah yang ada di atas. Jawablah dengan bijak!
                    </p>
                    <button @click="startQuiz()" class="inline-flex items-center gap-3 bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 text-white font-black py-4 px-10 rounded-2xl transition-all duration-300 shadow-lg shadow-purple-950/50 hover:scale-105 active:scale-95 text-xs uppercase tracking-widest cursor-pointer border border-purple-400/30">
                        Mulai Kuis Sekarang
                    </button>
                </div>

                <!-- STATE: QUESTION -->
                <div x-show="gameState === 'question'" x-transition class="space-y-6 w-full">
                    <!-- Progress and Score Bar -->
                    <div class="flex items-center justify-between text-xs text-slate-500 font-bold uppercase tracking-wider pb-4 border-b border-white/5">
                        <span x-text="'Pertanyaan ' + (currentIdx + 1) + ' dari ' + questions.length"></span>
                        <span class="text-purple-400" x-text="'Skor: ' + score"></span>
                    </div>

                    <!-- Progress bar visualization -->
                    <div class="w-full h-1 bg-slate-800 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-purple-600 to-cyan-500 transition-all duration-300" :style="'width: ' + ((currentIdx + 1) / questions.length * 100) + '%'"></div>
                    </div>

                    <!-- Question text -->
                    <h3 class="text-xl font-bold text-slate-100 leading-relaxed pt-2" x-text="getCurrentQuestion().text"></h3>

                    <!-- Options Grid -->
                    <div class="grid grid-cols-1 gap-4 pt-4">
                        <template x-for="(opt, idx) in getCurrentQuestion().options" :key="idx">
                            <button @click="submitAnswer(idx)" class="w-full text-left bg-slate-950 hover:bg-slate-800/80 border border-white/5 hover:border-purple-500/40 rounded-2xl px-6 py-4.5 text-sm text-slate-300 hover:text-white transition-all cursor-pointer flex items-center justify-between group">
                                <span x-text="opt"></span>
                                <span class="w-6 h-6 rounded-full bg-slate-900 border border-white/10 flex items-center justify-center text-xs group-hover:border-purple-500/40 group-hover:bg-purple-950/20 text-slate-500 group-hover:text-purple-300">→</span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- STATE: CORRECT ANSWER BANNER -->
                <div x-show="gameState === 'correct'" x-transition class="text-center space-y-6 my-auto">
                    <div class="w-20 h-20 bg-green-500/10 border border-green-500/30 rounded-full flex items-center justify-center text-4xl mx-auto animate-bounce">
                        ✨
                    </div>
                    <h3 class="text-2xl font-black text-green-400 tracking-wide">Jawaban Kamu Benar!</h3>
                    <p class="text-slate-300 text-sm max-w-md mx-auto leading-relaxed">
                        Luar biasa! Kamu memahami sejarah dan detail program SPECTA dengan sangat baik. Lanjutkan perjalananmu.
                    </p>
                    <button @click="nextQuestion()" class="inline-flex items-center gap-3 bg-green-600 hover:bg-green-500 text-white font-black py-3.5 px-8 rounded-xl transition-all duration-300 shadow-md cursor-pointer text-xs uppercase tracking-widest">
                        Lanjut Pertanyaan →
                    </button>
                </div>

                <!-- STATE: FLASHBACK (INCORRECT ANSWER STORY) -->
                <div x-show="gameState === 'flashback'" x-transition class="space-y-6 my-auto">
                    <div class="flex items-center gap-3 text-red-400 border-b border-red-500/20 pb-3">
                        <span class="text-3xl">📖</span>
                        <div>
                            <h3 class="text-lg font-bold">Jawaban Kurang Tepat!</h3>
                            <p class="text-xs text-slate-500 uppercase tracking-widest font-semibold">Story & Flashback Mode</p>
                        </div>
                    </div>
                    
                    <div class="bg-slate-950 border border-white/5 rounded-2xl p-6 space-y-4">
                        <p class="text-xs text-purple-400 uppercase tracking-widest font-bold">Sejarah Aslinya:</p>
                        <p class="text-sm text-slate-300 leading-relaxed" x-text="getCurrentQuestion().flashbackText"></p>
                    </div>

                    <p class="text-xs text-slate-500 leading-relaxed">
                        💡 Membaca kisah masa lalu akan membantumu memahami esensi sesungguhnya dari program SPECTA.
                    </p>

                    <button @click="nextQuestion()" class="w-full py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700/50 rounded-xl font-bold text-slate-300 transition-all cursor-pointer text-xs uppercase tracking-widest">
                        Paham, Lanjutkan Kuis
                    </button>
                </div>

                <!-- STATE: FINISHED -->
                <div x-show="gameState === 'finished'" x-transition class="text-center space-y-6 my-auto">
                    <div class="text-6xl">🏆</div>
                    <h3 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 tracking-wide">Kuis Selesai!</h3>
                    
                    <div class="bg-slate-950 border border-white/5 rounded-2xl p-6 max-w-sm mx-auto space-y-2">
                        <p class="text-xs text-slate-500 uppercase tracking-widest font-bold">Skor Akhir Anda</p>
                        <p class="text-5xl font-black text-white font-mono" x-text="score + ' / ' + questions.length"></p>
                        <p class="text-xs text-purple-400 font-semibold" x-text="getFeedbackText()"></p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center max-w-sm mx-auto pt-4">
                        <button @click="startQuiz()" class="flex-1 py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700/50 rounded-xl font-bold text-slate-300 transition-all cursor-pointer text-xs uppercase tracking-widest">
                            Ulangi Kuis
                        </button>
                        <a href="{{ route('tickets.index') }}" class="flex-[1.5] py-3.5 bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 rounded-xl font-bold text-white transition-all flex items-center justify-center gap-2 text-xs uppercase tracking-widest shadow-md">
                            🎟️ Amankan Tiket
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ─── DOCUMENTATION / GALLERY SECTION (BENTO LAYOUT) ─── -->
    <section id="documentation" class="py-24 relative z-10 border-t border-white/5 bg-slate-950/20 overflow-hidden">
        <!-- Glows -->
        <div class="absolute top-40 left-10 w-96 h-96 bg-cyan-600/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-600/10 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-xs font-bold text-cyan-400 uppercase tracking-[0.25em] block mb-3">MEMORIES</span>
                <h2 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400">
                    Dokumentasi Momen
                </h2>
                <p class="text-slate-400 max-w-2xl mx-auto mt-4 text-sm md:text-base leading-relaxed">
                    Kilas balik kemeriahan dan kebersamaan di berbagai rangkaian acara SPECTA SMANSA sebelumnya.
                </p>
            </div>

            <!-- Bento Grid: Dynamic if docs exist, else static placeholders -->
            @if($docsPreviews->isNotEmpty())
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 auto-rows-[200px]" x-data="{}">
                @foreach($docsPreviews as $i => $doc)
                @php
                    $spans = [
                        0 => 'md:col-span-2 md:row-span-2',
                        3 => 'md:col-span-2',
                    ];
                    $colors = [
                        'hover:border-cyan-500/50 hover:shadow-[0_0_30px_rgba(6,182,212,0.25)]',
                        'hover:border-purple-500/50 hover:shadow-[0_0_25px_rgba(168,85,247,0.25)]',
                        'hover:border-pink-500/50 hover:shadow-[0_0_25px_rgba(236,72,153,0.25)]',
                        'hover:border-blue-500/50 hover:shadow-[0_0_25px_rgba(59,130,246,0.25)]',
                        'hover:border-yellow-500/50 hover:shadow-[0_0_25px_rgba(234,179,8,0.25)]',
                        'hover:border-green-500/50 hover:shadow-[0_0_25px_rgba(34,197,94,0.25)]',
                    ];
                    $span  = $spans[$i] ?? '';
                    $color = $colors[$i % count($colors)];
                @endphp
                <div class="{{ $span }} group relative rounded-3xl overflow-hidden border border-white/10 {{ $color }} transition-all duration-500 cursor-pointer" data-aos="fade-up" data-aos-delay="{{ min($i * 80, 400) }}" x-data="{ info: false }" @mouseenter="info = true" @mouseleave="info = false" @click="info = !info">
                    @if($doc->file_type === 'image')
                        <img src="{{ $doc->file_url }}" alt="{{ $doc->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                    @else
                        <div class="w-full h-full bg-slate-900 flex items-center justify-center">
                            <span class="text-5xl">🎬</span>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                    <!-- Hover info overlay -->
                    <div class="absolute inset-0 flex flex-col justify-end p-4" x-show="info" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                        @if($doc->event_date)
                        <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest mb-1">{{ $doc->event_date->format('d M Y') }}</span>
                        @endif
                        <h3 class="font-bold text-white text-sm leading-tight">{{ $doc->title }}</h3>
                        @if($doc->description)
                        <p class="text-[11px] text-slate-300 mt-1 line-clamp-2">{{ $doc->description }}</p>
                        @endif
                    </div>
                    <!-- Static title at bottom (visible by default) -->
                    <div class="absolute bottom-3 left-4 right-4" x-show="!info">
                        <h3 class="text-sm font-bold text-white drop-shadow-md line-clamp-1">{{ $doc->title }}</h3>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <!-- Static placeholders -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 auto-rows-[200px]">
                @php $placeholders = [
                    ['emoji'=>'📸','label'=>'Malam Puncak SPECTA','sub'=>'Kemeriahan yang tak terlupakan','span'=>'md:col-span-2 md:row-span-2','color'=>'hover:border-cyan-500/50 hover:shadow-[0_0_30px_rgba(6,182,212,0.25)]','bg'=>'from-slate-800 to-slate-900'],
                    ['emoji'=>'🎸','label'=>'Live Performance','sub'=>'Penampilan band & artis','span'=>'','color'=>'hover:border-purple-500/50 hover:shadow-[0_0_25px_rgba(168,85,247,0.25)]','bg'=>'from-purple-950/60 to-slate-900'],
                    ['emoji'=>'🎭','label'=>'Teater Ekskul','sub'=>'Pentas seni & drama','span'=>'','color'=>'hover:border-pink-500/50 hover:shadow-[0_0_25px_rgba(236,72,153,0.25)]','bg'=>'from-pink-950/40 to-slate-900'],
                    ['emoji'=>'🏅','label'=>'Pembagian Hadiah','sub'=>'Penghargaan para juara','span'=>'md:col-span-2','color'=>'hover:border-blue-500/50 hover:shadow-[0_0_25px_rgba(59,130,246,0.25)]','bg'=>'from-blue-950/40 to-slate-900'],
                ]; @endphp
                @foreach($placeholders as $i => $ph)
                <div class="{{ $ph['span'] }} group relative rounded-3xl overflow-hidden border border-white/10 {{ $ph['color'] }} transition-all duration-500" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                    <div class="absolute inset-0 bg-gradient-to-br {{ $ph['bg'] }}"></div>
                    <div class="absolute inset-0 flex items-center justify-center text-5xl group-hover:scale-110 transition-transform duration-700">{{ $ph['emoji'] }}</div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <h3 class="font-bold text-white text-sm">{{ $ph['label'] }}</h3>
                        <p class="text-[10px] text-slate-400 mt-0.5">{{ $ph['sub'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- CTA -->
            <div class="mt-10 text-center" data-aos="fade-up">
                <a href="/docs" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-slate-900 border border-cyan-500/30 hover:bg-slate-800 text-slate-200 font-bold transition-all text-sm tracking-wider uppercase group">
                    Lihat Semua Dokumentasi
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>
    </section>


    <!-- ─── MERCHANDISE SECTION (PREVIEW) ─── -->
    <section id="merchandise" class="py-24 relative z-10 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="text-xs font-bold text-cyan-400 uppercase tracking-[0.25em] block mb-3">EXCLUSIVE ITEMS</span>
                <h2 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">
                    Official Merchandise
                </h2>
                <p class="text-slate-400 max-w-2xl mx-auto mt-4 text-sm md:text-base leading-relaxed">
                    Koleksi eksklusif SPECTA XXI: REVELIORA. Tampil beda dengan desain Celestial Treasure yang elegan dan futuristik.
                </p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
                @foreach($merchandises->take(4) as $index => $merch)
                <a href="/merch" class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden hover:border-purple-500/50 hover:shadow-[0_0_15px_rgba(168,85,247,0.2)] transition-all duration-300 group flex flex-col text-left" data-aos="fade-up" data-aos-delay="{{ $index * 100 }}">
                    <!-- Image Box -->
                    <div class="aspect-square relative overflow-hidden bg-slate-950 flex items-center justify-center border-b border-slate-800">
                        @if($merch->image_url)
                            <img src="{{ $merch->image_url }}" alt="{{ $merch->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="text-4xl opacity-20 group-hover:scale-110 transition-transform duration-500">🛍️</div>
                        @endif
                    </div>

                    <!-- Content Box -->
                    <div class="p-3 sm:p-4 flex-1 flex flex-col">
                        <h3 class="text-sm sm:text-base font-bold text-slate-200 mb-1 line-clamp-2 leading-tight group-hover:text-purple-400 transition-colors">{{ $merch->name }}</h3>
                        <p class="text-sm sm:text-lg font-extrabold text-cyan-400 mt-auto">
                            Rp {{ number_format($merch->price, 0, ',', '.') }}
                        </p>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-12 text-center" data-aos="fade-up">
                <a href="/merch" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-slate-900 border border-purple-500/30 hover:bg-slate-800 text-slate-200 font-bold transition-all text-sm tracking-wider uppercase group">
                    Lihat Semua Merchandise
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

    </section>

    <!-- ─── FOOTER CTA SECTION ─── -->
    <section class="py-32 relative overflow-hidden border-t border-white/5">
        <div class="absolute inset-0 bg-gradient-to-t from-purple-900/10 to-transparent pointer-events-none"></div>
        
        <div class="max-w-4xl mx-auto px-6 relative z-10 text-center" data-aos="zoom-in">
            <h2 class="text-4xl md:text-5xl font-black text-slate-100 mb-6">Siap Menjadi Bagian dari Sejarah?</h2>
            <p class="text-slate-400 mb-10 max-w-xl mx-auto text-sm md:text-base leading-relaxed">
                Jangan sampai kehabisan. Kuota tiket sangat terbatas. Bergabunglah bersama ribuan Velorans lainnya.
            </p>
            <a href="{{ route('tickets.index') }}" class="inline-block px-12 py-5 bg-gradient-to-r from-purple-600 to-cyan-500 hover:from-purple-500 hover:to-cyan-400 text-white rounded-full font-black text-lg tracking-wider transition-all hover:scale-105 shadow-[0_0_30px_rgba(168,85,247,0.4)]">
                AMANKAN TIKET SAYA
            </a>
        </div>
    </section>

    <!-- ─── FOOTER SECTION ─── -->
    <footer class="bg-black py-12 border-t border-white/5 relative z-10">
        <div class="max-w-7xl mx-auto px-6 text-center">
            <div class="flex items-center justify-center gap-3 mb-6">
                <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-600 to-cyan-500 flex items-center justify-center font-bold text-white text-xs">
                    SR
                </div>
                <span class="font-black text-sm tracking-[0.2em] text-slate-300 uppercase">
                    SPECTA REVELIORA
                </span>
            </div>
            <p class="text-xs text-slate-600 leading-relaxed max-w-md mx-auto">
                &copy; {{ date('Y') }} SPECTA XXI : REVELIORA. Organized by SMAN 1 Cianjur. All Rights Reserved.
            </p>
        </div>
    </footer>

</div>

@push('scripts')
<script>
    function storyQuiz() {
        return {
            gameState: 'start',
            score: 0,
            currentIdx: 0,
            questions: [
                {
                    text: "Apa tema utama SPECTA XXI yang diselenggarakan pada tahun 2026 ini?",
                    options: [
                        "A. Celestial Treasure (Harta Karun Langit)",
                        "B. Starry Path (Jalan Berbintang)",
                        "C. Lunar Eclipse (Gerhana Bulan)"
                    ],
                    answer: 0,
                    flashbackText: "Tema SPECTA XXI (2026) adalah 'Celestial Treasure' (Harta Karun Langit), melambangkan bahwa kebersamaan dan kreativitas yang tak terbatas akhirnya menciptakan warisan yang sangat berharga."
                },
                {
                    text: "Apa makna dari warna Angsa Ungu pada logo SPECTA?",
                    options: [
                        "A. Perdamaian dan Ketulusan",
                        "B. Keanggunan dan Proses Transformasi",
                        "C. Keberanian dan Distorsi Melodi"
                    ],
                    answer: 1,
                    flashbackText: "Angsa Ungu pada logo SPECTA melambangkan proses transformasi dan perjalanan menuju kedewasaan, dari yang sederhana menjadi luar biasa."
                },
                {
                    text: "Siapakah target utama audiens pada Fase 1: Grand Opening SPECTA?",
                    options: [
                        "A. Seluruh Pelajar se-Jawa Barat",
                        "B. Khusus Warga Internal SMANSA",
                        "C. Pengunjung Umum dari Luar Sekolah"
                    ],
                    answer: 1,
                    flashbackText: "Fase 1: Grand Opening merupakan acara internal khusus untuk warga SMANSA dengan konten penampilan band internal dan fashion show."
                },
                {
                    text: "Bagaimana cara peserta Middle Event (Eskul luar) mendapatkan tiket Grand Closing secara gratis?",
                    options: [
                        "A. Menang sebagai juara pertama saja",
                        "B. Tiket diberikan sebagai hadiah partisipasi aktif",
                        "C. Membeli dengan diskon 90%"
                    ],
                    answer: 1,
                    flashbackText: "Tiket Grand Closing menjadi 'hadiah' bagi para partisipan dari event internal maupun eksternal sebelumnya sebagai bentuk apresiasi."
                },
                {
                    text: "Apa makna filosofis dari 'Tongkat Biru' pada logo SPECTA?",
                    options: [
                        "A. Potensi & Jati Diri yang sedang terungkap",
                        "B. Puncak Acara Konser",
                        "C. Perlombaan Antar-Sekul"
                    ],
                    answer: 0,
                    flashbackText: "Tongkat Biru melambangkan potensi dan jati diri yang berdiri tegak seperti cahaya yang tenang, mendalam, dan penuh kemungkinan."
                }
            ],

            startQuiz() {
                this.gameState = 'question';
                this.score = 0;
                this.currentIdx = 0;
            },

            getCurrentQuestion() {
                return this.questions[this.currentIdx];
            },

            submitAnswer(idx) {
                const q = this.getCurrentQuestion();
                if (idx === q.answer) {
                    this.score++;
                    this.gameState = 'correct';
                } else {
                    this.gameState = 'flashback';
                }
            },

            nextQuestion() {
                if (this.currentIdx + 1 < this.questions.length) {
                    this.currentIdx++;
                    this.gameState = 'question';
                } else {
                    this.gameState = 'finished';
                }
            },

            getFeedbackText() {
                if (this.score === this.questions.length) {
                    return "Sempurna! Kamu adalah Velorans Sejati! 🌟";
                } else if (this.score >= 3) {
                    return "Bagus sekali! Kamu sangat mengenal SPECTA! 👍";
                } else {
                    return "Ayo pelajari lebih lanjut timeline di atas and coba lagi! 📖";
                }
            }
        };
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Inisialisasi Vanilla-Tilt pada elemen berkemampuan tilt
        if (typeof VanillaTilt !== 'undefined') {
            VanillaTilt.init(document.querySelectorAll("[data-tilt]"));
        }
    });
</script>
@endpush
@endsection
