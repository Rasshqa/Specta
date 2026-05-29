@extends('layouts.app')

@section('title', 'SPECTA REVELIORA – The Dark Fantasy Festival')

@section('content')
<div x-data="{ mobileMenuOpen: false, userDropdownOpen: false, lastScroll: 0, showNav: true }" 
     @scroll.window="showNav = (window.pageYOffset < lastScroll || window.pageYOffset < 80); lastScroll = window.pageYOffset"
     class="bg-black text-slate-100 min-h-screen relative overflow-hidden font-sans">
    
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
    <nav :class="showNav ? 'translate-y-0' : '-translate-y-full'" class="fixed top-0 left-0 right-0 z-50 bg-slate-950/80 backdrop-blur-xl border-b border-white/5 transition-transform duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            
            <!-- Left Logo -->
            <a href="#" class="flex items-center gap-3 group focus:outline-none">
                <img src="{{ asset('images/smansa-logo.png') }}" alt="Logo SMANSA" class="w-10 h-10 rounded-xl shadow-[0_0_15px_rgba(168,85,247,0.3)] group-hover:scale-105 transition-all object-contain">
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
                            <span><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 10c4.42 0 8 1.79 8 4v2H4v-2c0-2.21 3.58-4 8-4"/></svg> {{ Auth::user()->name }}</span>
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
            

            <!-- Main Heading Logo -->
            <div class="mb-8 w-full max-w-4xl mx-auto px-4" data-aos="fade-down">
                <img src="{{ asset('images/logo_specta.png') }}" alt="SPECTA REVELIORA Logo" class="mx-auto w-full drop-shadow-[0_0_30px_rgba(168,85,247,0.4)]">
            </div>


            <!-- Subtitle -->
            <h2 class="text-lg md:text-2xl font-bold tracking-[0.3em] uppercase text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-purple-300 to-purple-400 mb-8 hero-neon">
                Where Magic Meets Reality
            </h2>

            <!-- Description -->
            <p class="text-slate-400 text-base md:text-lg max-w-3xl mx-auto mb-12 leading-relaxed font-light">
                Program kerja tahunan OSIS &amp; MPK <span class="text-slate-300 font-semibold">SMAN 1 Cianjur</span> yang menghadirkan tiga fase akbar — Grand Opening, Middle Event, hingga Grand Closing berupa konser spektakuler.
                Bertema <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-purple-300 to-purple-400 mb-8 hero-neon font-bold">Celestial Treasure</span> — merayakan bahwa kebersamaan dan kreativitas adalah harta karun sejati yang tak ternilai.
            </p>

            <!-- Metadata Info Badges -->
            <div class="flex flex-wrap items-center justify-center gap-6 text-sm text-slate-500 mb-8 font-medium">
                <span class="flex items-center gap-2 bg-slate-900/60 border border-slate-800 px-4 py-2 rounded-xl">
                    <svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 11.5A2.5 2.5 0 0 1 9.5 9A2.5 2.5 0 0 1 12 6.5A2.5 2.5 0 0 1 14.5 9a2.5 2.5 0 0 1-2.5 2.5M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7"/></svg> Jl. Pangeran Hidayatullah No.62, Sawah Gede, Kec. Cianjur, Kabupaten Cianjur, Jawa Barat 43212
                </span>
            </div>

            <!-- Real-Time Ticket Quota -->
            @if(isset($quotaData))
            <div class="max-w-2xl mx-auto mb-12 bg-white/5 backdrop-blur-xl border border-white/10 p-6 rounded-3xl shadow-[0_0_40px_rgba(168,85,247,0.1)] relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 to-cyan-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="flex justify-between items-end mb-3 relative z-10">
                    <div class="text-left">
                        <p class="text-sm text-slate-400 font-medium tracking-wider uppercase">Kapasitas Tersedia</p>
                        <p class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">
                            {{ number_format($quotaData->remaining) }} <span class="text-sm font-medium text-slate-500">/ {{ number_format($quotaData->capacity) }} Kursi</span>
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-white">{{ number_format($quotaData->percentage, 1) }}% Terjual</p>
                    </div>
                </div>
                <div class="w-full h-3 bg-slate-900/80 rounded-full overflow-hidden border border-slate-800 relative z-10">
                    <div class="h-full bg-gradient-to-r from-purple-500 to-cyan-400 rounded-full transition-all duration-1000 shadow-[0_0_15px_rgba(168,85,247,0.8)]" style="width: {{ $quotaData->percentage }}%"></div>
                </div>
            </div>
            @endif

            <!-- Call to Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-5 max-w-md mx-auto sm:max-w-none">
                <a href="{{ route('tickets.index') }}" class="w-full sm:w-auto group relative px-10 py-5 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 rounded-full font-extrabold text-white text-lg tracking-wider transition-all duration-300 hover:scale-105 shadow-[0_0_35px_rgba(168,85,247,0.45)] hover:shadow-[0_0_55px_rgba(168,85,247,0.65)] flex items-center justify-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                    BUY TICKET
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
                
                @auth
                    @if(\App\Models\Transaction::where('buyer_email', Auth::user()->email)->orWhere('buyer_whatsapp', Auth::user()->email)->exists())
                    <a href="{{ route('user.dashboard') }}" class="w-full sm:w-auto px-10 py-5 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 rounded-full font-extrabold text-white text-lg tracking-wider transition-all duration-300 hover:scale-105 shadow-[0_0_35px_rgba(6,182,212,0.45)] hover:shadow-[0_0_55px_rgba(6,182,212,0.65)] flex items-center justify-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        MY TICKETS
                    </a>
                    @endif
                @endauth

                <a href="#lore" class="w-full sm:w-auto px-10 py-5 bg-black/40 hover:bg-slate-900/50 border border-slate-700/60 hover:border-slate-500 rounded-full font-bold text-slate-300 text-lg tracking-wider backdrop-blur-md transition-all hover:scale-105">
                    EXPLORE LORE
                </a>
            </div>

            <!-- Counter Stats -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 sm:gap-4 max-w-xl mx-auto mt-20 pt-10 border-t border-white/5">
                <div>
                    <p class="text-3xl md:text-4xl font-black text-white">4</p>
                    <p class="text-xs text-slate-500 uppercase tracking-widest mt-1">Guest Stars</p>
                </div>
                <div>
                    <p class="text-3xl md:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">
                        {{ isset($quotaData) ? number_format($quotaData->remaining) : '825+' }}
                    </p>
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
                    <svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 1L9 9l-8 3l8 3l3 8l3-8l8-3l-8-3z"/></svg>˖° SPECTA XXI PROUDLY PRESENTS °˖<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 1L9 9l-8 3l8 3l3 8l3-8l8-3l-8-3z"/></svg>
                </span>
                <h2 class="text-4xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 via-blue-300 to-cyan-400 uppercase tracking-wide leading-tight">
                    SPECTA REVELIORA
                </h2>
                <p class="text-slate-400 max-w-3xl mx-auto mt-5 text-base md:text-lg leading-relaxed italic font-light">
                    "Guided by the spirit of Reveliora, we begin a journey of discovery, courage, and endless creativity, where every step brings us closer to something extraordinary." <svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3zm3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95zm-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31"/></svg>
                </p>
            </div>

            <!-- Logo Philosophy — 3 Pillars Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-20" data-aos="fade-up" data-aos-delay="100">

                <!-- Pillar 1: Tongkat Biru -->
                <div class="relative group bg-gradient-to-b from-blue-950/40 to-slate-950/60 backdrop-blur-xl border border-blue-500/20 rounded-3xl p-8 overflow-hidden hover:border-blue-400/40 transition-all duration-500">
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/20 transition-all duration-500"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-blue-600/20 to-blue-400/10 border border-blue-500/30 flex items-center justify-center mb-6 shadow-inner overflow-hidden">
                            {{-- Tongkat Biru --}}
                            <svg viewBox="0 0 40 56" width="32" height="44" fill="none" xmlns="http://www.w3.org/2000/svg">
                                {{-- Mahkota --}}
                                <path d="M16 10 L12 4 L20 8 L28 4 L24 10 Z" fill="#60a5fa" opacity="0.9"/>
                                <rect x="17" y="9" width="6" height="3" rx="1" fill="#3b82f6"/>
                                {{-- Batang tongkat --}}
                                <rect x="18" y="12" width="4" height="36" rx="2" fill="url(#blueWand)"/>
                                {{-- Ujung bawah --}}
                                <ellipse cx="20" cy="48" rx="3" ry="2" fill="#2563eb"/>
                                {{-- Glow tengah --}}
                                <rect x="19" y="14" width="2" height="30" rx="1" fill="#93c5fd" opacity="0.5"/>
                                <defs>
                                    <linearGradient id="blueWand" x1="18" y1="12" x2="22" y2="48" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#60a5fa"/>
                                        <stop offset="50%" stop-color="#3b82f6"/>
                                        <stop offset="100%" stop-color="#1d4ed8"/>
                                    </linearGradient>
                                </defs>
                            </svg>
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
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-purple-600/20 to-purple-400/10 border border-purple-500/30 flex items-center justify-center mb-6 shadow-inner overflow-hidden">
                            {{-- Angsa Ungu --}}
                            <svg viewBox="0 0 48 48" width="40" height="40" fill="none" xmlns="http://www.w3.org/2000/svg">
                                {{-- Badan angsa --}}
                                <ellipse cx="26" cy="32" rx="14" ry="9" fill="url(#swanBody)"/>
                                {{-- Sayap --}}
                                <path d="M14 30 Q8 22 12 16 Q16 22 18 28Z" fill="#c084fc" opacity="0.7"/>
                                <path d="M38 28 Q44 20 42 14 Q37 20 35 27Z" fill="#a855f7" opacity="0.5"/>
                                {{-- Leher --}}
                                <path d="M20 28 Q18 20 22 12 Q26 8 28 12 Q24 18 24 26Z" fill="url(#swanNeck)"/>
                                {{-- Kepala --}}
                                <ellipse cx="27" cy="10" rx="5" ry="4.5" fill="#d8b4fe"/>
                                {{-- Paruh --}}
                                <path d="M31 10 L36 10.5 L31 11.5Z" fill="#f59e0b"/>
                                {{-- Mata --}}
                                <circle cx="29" cy="9" r="1" fill="#1e1b38"/>
                                {{-- Ekor --}}
                                <path d="M38 34 Q44 38 42 42 Q38 40 36 36Z" fill="#9333ea" opacity="0.8"/>
                                <defs>
                                    <linearGradient id="swanBody" x1="12" y1="32" x2="40" y2="40" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#c084fc"/>
                                        <stop offset="100%" stop-color="#7e22ce"/>
                                    </linearGradient>
                                    <linearGradient id="swanNeck" x1="20" y1="12" x2="26" y2="28" gradientUnits="userSpaceOnUse">
                                        <stop offset="0%" stop-color="#e9d5ff"/>
                                        <stop offset="100%" stop-color="#c084fc"/>
                                    </linearGradient>
                                </defs>
                            </svg>
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
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-cyan-600/20 to-cyan-400/10 border border-cyan-500/30 flex items-center justify-center mb-6 shadow-inner overflow-hidden p-1">
                            {{-- Logo R SPECTA --}}
                            <img src="{{ asset('images/specta-R.png') }}" alt="Simbol R SPECTA" class="w-full h-full object-contain" style="filter: drop-shadow(0 0 4px rgba(34,211,238,0.4));"/>
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
                        <span class="text-xs font-bold text-purple-400 uppercase tracking-[0.25em] block mb-3">Tema SPECTA XXI</span>
                        <h3 class="text-4xl md:text-5xl font-black text-white leading-tight mb-3">
                            CELESTIAL<br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-300 to-blue-400">TREASURE</span>
                        </h3>
                        <p class="text-slate-400 text-sm font-medium italic">"Harta Karun Langit"</p>
                        
                        <div class="mt-8 flex items-center gap-3 justify-center lg:justify-start">
                            <span class="text-2xl"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m12 21.35l-1.45-1.32C5.4 15.36 2 12.27 2 8.5C2 5.41 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.08C13.09 3.81 14.76 3 16.5 3C19.58 3 22 5.41 22 8.5c0 3.77-3.4 6.86-8.55 11.53z"/></svg></span>
                            <p class="text-slate-300 text-sm font-bold italic">Uncover the Wonders,<br>Beyond the Stars <svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.62L12 2L9.19 8.62L2 9.24l5.45 4.73L5.82 21z"/></svg></p>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="hidden lg:block lg:col-span-1 flex justify-center">
                        <div class="h-full w-px bg-gradient-to-b from-transparent via-white/10 to-transparent mx-auto"></div>
                    </div>

                    <!-- Right: Philosophy Breakdown -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 flex-shrink-0 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-lg mt-0.5"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m12 6.7l1.45 3.85L17.3 12l-3.85 1.45L12 17.3l-1.45-3.85L6.7 12l3.85-1.45zM12 1L9 9l-8 3l8 3l3 8l3-8l8-3l-8-3z"/></svg></div>
                            <div>
                                <h4 class="text-sm font-black text-purple-300 uppercase tracking-wider mb-1">CELESTIAL (Langit)</h4>
                                <p class="text-xs text-slate-400 leading-relaxed">Melambangkan sifat luhur, tak terbatas, dan agung dari potensi serta semangat para siswa SMANSA.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 flex-shrink-0 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-lg mt-0.5"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M6 2L2 8l10 14L22 8l-4-6z"/></svg></div>
                            <div>
                                <h4 class="text-sm font-black text-cyan-300 uppercase tracking-wider mb-1">TREASURE (Harta Karun)</h4>
                                <p class="text-xs text-slate-400 leading-relaxed">Menyimbolkan nilai intrinsik hasil dari dedikasi, kebersamaan, dan perjuangan — warisan abadi yang tercipta bersama.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 items-start">
                            <div class="w-10 h-10 flex-shrink-0 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-lg mt-0.5"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3zm3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95zm-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31"/></svg></div>
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
                    let's uncover the wonders and shine beyond the stars together! <svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m19 1l-1.26 2.75L15 5l2.74 1.26L19 9l1.25-2.74L23 5l-2.75-1.25M9 4L6.5 9.5L1 12l5.5 2.5L9 20l2.5-5.5L17 12l-5.5-2.5M19 15l-1.26 2.74L15 19l2.74 1.25L19 23l1.25-2.75L23 19l-2.75-1.26"/></svg>
                </p>
            </div>

        </div>
    </section>

    <!-- ─── ANNOUNCEMENT SECTION ─── -->
    @if(isset($announcements) && $announcements->isNotEmpty())
    <section class="py-16 relative z-10 border-t border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <div class="text-center mb-12" data-aos="fade-up">
                <span class="text-xs font-bold text-cyan-400 uppercase tracking-[0.25em] block mb-3">LATEST UPDATES</span>
                <h2 class="text-3xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 uppercase">
                    Pengumuman Resmi
                </h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($announcements as $announcement)
                <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-2xl hover:border-purple-500/40 transition-all duration-300 flex flex-col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="mb-4 flex items-center justify-between">
                        <span class="inline-block px-3 py-1 bg-purple-500/20 border border-purple-500/30 text-purple-300 text-[10px] font-bold uppercase tracking-widest rounded-md">
                            {{ $announcement->category }}
                        </span>
                        <span class="text-xs text-slate-500 font-mono">{{ $announcement->created_at->format('d M Y') }}</span>
                    </div>
                    <h3 class="text-xl font-bold text-slate-100 mb-3 leading-tight">{{ $announcement->title }}</h3>
                    <div class="text-sm text-slate-400 leading-relaxed">
                        {!! nl2br(e($announcement->content)) !!}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

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
                <button @click="infoTab = 'eskul'" :class="infoTab === 'eskul' ? 'bg-gradient-to-r from-purple-600 to-cyan-600 text-white font-bold' : 'text-slate-400 hover:text-slate-200'" class="flex-1 py-2.5 px-3 rounded-xl text-xs uppercase tracking-wider transition-all cursor-pointer text-center flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span class="hidden sm:inline">Eskul</span>
                </button>
                <button @click="infoTab = 'winners'" :class="infoTab === 'winners' ? 'bg-gradient-to-r from-purple-600 to-cyan-600 text-white font-bold' : 'text-slate-400 hover:text-slate-200'" class="flex-1 py-2.5 px-3 rounded-xl text-xs uppercase tracking-wider transition-all cursor-pointer text-center flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    <span class="hidden sm:inline">Pemenang</span>
                </button>
                <button @click="infoTab = 'timeline'" :class="infoTab === 'timeline' ? 'bg-gradient-to-r from-purple-600 to-cyan-600 text-white font-bold' : 'text-slate-400 hover:text-slate-200'" class="flex-1 py-2.5 px-3 rounded-xl text-xs uppercase tracking-wider transition-all cursor-pointer text-center flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="hidden sm:inline">Timeline</span>
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
                    <div class="flex justify-center mb-4 text-purple-400">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
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
                            <img src="{{ $eskul->image_url ?? asset('images/placeholder.svg') }}" alt="{{ $eskul->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute top-3 left-3 w-11 h-11 rounded-xl bg-black/50 backdrop-blur flex items-center justify-center text-2xl">{!! $eskul->icon !!}</div>
                        </div>
                        @else
                        <div class="h-28 bg-gradient-to-br from-purple-950/60 to-slate-900 flex items-center justify-center text-5xl">
                            {!! $eskul->icon !!}
                        </div>
                        @endif

                        <div class="p-5 flex flex-col flex-1 gap-3">
                            <div>
                                <h3 class="text-base font-bold text-slate-100">{{ $eskul->name }}</h3>
                                <p class="text-xs text-slate-400 mt-1 leading-relaxed line-clamp-2">{{ $eskul->description }}</p>
                            </div>
                            @if($eskul->achievements)
                            <p class="text-xs text-purple-400 font-semibold flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                                {{ $eskul->achievements }}
                            </p>
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
                        <div class="flex justify-center mb-4 text-yellow-400">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
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
                                            <img src="{{ $winner->image_url ?? asset('images/placeholder.svg') }}" alt="{{ $winner->name }}" class="w-9 h-9 rounded-full object-cover border border-slate-700">
                                        </td>
                                        <td class="py-3 px-5 text-sm font-black text-purple-400 whitespace-nowrap">{{ $winner->rank }}</td>
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
                    <div class="flex justify-center mb-4 text-cyan-400">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
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
                            <img src="{{ $tl->image_url ?? asset('images/placeholder.svg') }}" alt="{{ $tl->title }}" class="w-full h-36 object-cover">
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
                ><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"/></svg></button>

                <!-- Cover image -->
                <template x-if="selectedEskul && selectedEskul.image_url">
                    <div class="relative h-44 flex-shrink-0 overflow-hidden">
                        <img :src="selectedEskul.image_url" :alt="selectedEskul.name" class="w-full h-full object-cover">
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
                                <p class="text-purple-400 font-bold" x-text="selectedEskul.achievements"></p>
                            </div>
                        </template>
                        <template x-if="selectedEskul && selectedEskul.contact">
                            <div>
                                <h4 class="text-xs uppercase text-slate-500 tracking-widest font-bold mb-1.5">Narahubung (WA)</h4>
                                <a :href="'https://wa.me/' + selectedEskul.contact.replace(/[^0-9]/g, '')" target="_blank" class="inline-flex items-center gap-2 text-green-400 hover:text-green-300 font-semibold">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    <span x-text="selectedEskul.contact"></span>
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
                    <div class="flex justify-center">
                        <div class="w-24 h-24 rounded-full bg-purple-500/10 border border-purple-500/20 flex items-center justify-center animate-pulse">
                            <svg class="w-12 h-12 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        </div>
                    </div>
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
                    <div class="w-20 h-20 bg-green-500/10 border border-green-500/30 rounded-full flex items-center justify-center mx-auto animate-bounce">
                        <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                        <svg class="w-8 h-8 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <div>
                            <h3 class="text-lg font-bold">Jawaban Kurang Tepat!</h3>
                            <p class="text-xs text-slate-500 uppercase tracking-widest font-semibold">Story & Flashback Mode</p>
                        </div>
                    </div>
                    
                    <div class="bg-slate-950 border border-white/5 rounded-2xl p-6 space-y-4">
                        <p class="text-xs text-purple-400 uppercase tracking-widest font-bold">Sejarah Aslinya:</p>
                        <p class="text-sm text-slate-300 leading-relaxed" x-text="getCurrentQuestion().flashbackText"></p>
                    </div>

                    <p class="text-xs text-slate-500 leading-relaxed flex items-start gap-2">
                        <svg class="w-4 h-4 text-slate-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                        Membaca kisah masa lalu akan membantumu memahami esensi sesungguhnya dari program SPECTA.
                    </p>

                    <button @click="nextQuestion()" class="w-full py-3.5 bg-slate-800 hover:bg-slate-700 border border-slate-700/50 rounded-xl font-bold text-slate-300 transition-all cursor-pointer text-xs uppercase tracking-widest">
                        Paham, Lanjutkan Kuis
                    </button>
                </div>

                <!-- STATE: FINISHED -->
                <div x-show="gameState === 'finished'" x-transition class="text-center space-y-6 my-auto">
                    <div class="flex justify-center">
                        <div class="w-24 h-24 rounded-full bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center">
                            <svg class="w-12 h-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        </div>
                    </div>
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
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                            Amankan Tiket
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
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 auto-rows-[200px]" x-data="{}">
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
                            <svg class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.82v6.36a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent"></div>
                    <!-- Hover info overlay -->
                    <div class="absolute inset-0 flex flex-col justify-end p-4" x-show="info" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-cloak>
                        @if($doc->event_date)
                        <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest mb-1">{{ \Carbon\Carbon::parse($doc->event_date)->format('d M Y') }}</span>
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
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 auto-rows-[200px]">
                @php $placeholders = [
                    ['emoji'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2m8 3a5 5 0 0 0-5 5a5 5 0 0 0 5 5a5 5 0 0 0 5-5a5 5 0 0 0-5-5m0 2a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3"/></svg>','label'=>'Malam Puncak SPECTA','sub'=>'Kemeriahan yang tak terlupakan','span'=>'md:col-span-2 md:row-span-2','color'=>'hover:border-cyan-500/50 hover:shadow-[0_0_30px_rgba(6,182,212,0.25)]','bg'=>'from-slate-800 to-slate-900'],
                    ['emoji'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19.59 3H22v2h-1.59l-5.29 5.29l-1.41-1.39zM12 9c.26 0 .5.1.71.3l2 2c.18.2.29.43.29.7l-.1.4l-4 8c-.19.35-.54.53-.9.53c-.35 0-.71-.18-.89-.53l-1.86-3.7l-3.7-1.8c-.37-.2-.55-.55-.55-.9s.18-.7.55-.9l8-4c.14-.1.29-.1.45-.1m-2.65 2.82l-.7.68l2.85 2.85l.68-.7zm-1.41 1.41l-.71.71l2.83 2.83l.71-.71z"/></svg>','label'=>'Live Performance','sub'=>'Penampilan band & artis','span'=>'','color'=>'hover:border-purple-500/50 hover:shadow-[0_0_25px_rgba(168,85,247,0.25)]','bg'=>'from-purple-950/60 to-slate-900'],
                    ['emoji'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M8.11 19.45a6.95 6.95 0 0 1-4.4-5.1L2.05 6.54c-.24-1.08.45-2.14 1.53-2.37l9.77-2.07l.03-.01c1.07-.21 2.12.48 2.34 1.54l.35 1.67l4.35.93h.03c1.05.24 1.73 1.3 1.51 2.36l-1.66 7.82a6.993 6.993 0 0 1-8.3 5.38a6.9 6.9 0 0 1-3.89-2.34M20 8.18L10.23 6.1l-1.66 7.82v.03c-.57 2.68 1.16 5.32 3.85 5.89s5.35-1.15 5.92-3.84zm-4 8.32a2.96 2.96 0 0 1-3.17 1.39a2.97 2.97 0 0 1-2.33-2.55zM8.47 5.17L4 6.13l1.66 7.81l.01.03c.15.71.45 1.35.86 1.9c-.1-.77-.08-1.57.09-2.37l.43-2c-.45-.08-.84-.33-1.05-.69c.06-.61.56-1.15 1.25-1.31h.25l.78-3.81c.04-.19.1-.36.19-.52m6.56 7.06c.32-.53 1-.81 1.69-.66c.69.14 1.19.67 1.28 1.29c-.33.52-1 .8-1.7.64c-.69-.13-1.19-.66-1.27-1.27m-4.88-1.04c.32-.53.99-.81 1.68-.66c.67.14 1.2.68 1.28 1.29c-.33.52-1 .81-1.69.68c-.69-.17-1.19-.7-1.27-1.31m1.82-6.76l1.96.42l-.16-.8z"/></svg>','label'=>'Teater Ekskul','sub'=>'Pentas seni & drama','span'=>'','color'=>'hover:border-pink-500/50 hover:shadow-[0_0_25px_rgba(236,72,153,0.25)]','bg'=>'from-pink-950/40 to-slate-900'],
                    ['emoji'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M20 2H4v2l5.81 4.36a7.004 7.004 0 0 0-4.46 8.84a6.996 6.996 0 0 0 8.84 4.46a7 7 0 0 0 0-13.3L20 4zm-5.06 17.5L12 17.78L9.06 19.5l.78-3.33l-2.59-2.24l3.41-.29L12 10.5l1.34 3.14l3.41.29l-2.59 2.24z"/></svg>','label'=>'Pembagian Hadiah','sub'=>'Penghargaan para juara','span'=>'md:col-span-2','color'=>'hover:border-blue-500/50 hover:shadow-[0_0_25px_rgba(59,130,246,0.25)]','bg'=>'from-blue-950/40 to-slate-900'],
                ]; @endphp
                @foreach($placeholders as $i => $ph)
                <div class="{{ $ph['span'] }} group relative rounded-3xl overflow-hidden border border-white/10 {{ $ph['color'] }} transition-all duration-500" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                    <div class="absolute inset-0 bg-gradient-to-br {{ $ph['bg'] }}"></div>
                    <div class="absolute inset-0 flex items-center justify-center group-hover:scale-110 transition-transform duration-700">
                        @if($ph['emoji'] === '<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2m8 3a5 5 0 0 0-5 5a5 5 0 0 0 5 5a5 5 0 0 0 5-5a5 5 0 0 0-5-5m0 2a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3"/></svg>')
                        <svg class="w-12 h-12 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        @elseif($ph['emoji'] === '<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19.59 3H22v2h-1.59l-5.29 5.29l-1.41-1.39zM12 9c.26 0 .5.1.71.3l2 2c.18.2.29.43.29.7l-.1.4l-4 8c-.19.35-.54.53-.9.53c-.35 0-.71-.18-.89-.53l-1.86-3.7l-3.7-1.8c-.37-.2-.55-.55-.55-.9s.18-.7.55-.9l8-4c.14-.1.29-.1.45-.1m-2.65 2.82l-.7.68l2.85 2.85l.68-.7zm-1.41 1.41l-.71.71l2.83 2.83l.71-.71z"/></svg>')
                        <svg class="w-12 h-12 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/></svg>
                        @elseif($ph['emoji'] === '<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M8.11 19.45a6.95 6.95 0 0 1-4.4-5.1L2.05 6.54c-.24-1.08.45-2.14 1.53-2.37l9.77-2.07l.03-.01c1.07-.21 2.12.48 2.34 1.54l.35 1.67l4.35.93h.03c1.05.24 1.73 1.3 1.51 2.36l-1.66 7.82a6.993 6.993 0 0 1-8.3 5.38a6.9 6.9 0 0 1-3.89-2.34M20 8.18L10.23 6.1l-1.66 7.82v.03c-.57 2.68 1.16 5.32 3.85 5.89s5.35-1.15 5.92-3.84zm-4 8.32a2.96 2.96 0 0 1-3.17 1.39a2.97 2.97 0 0 1-2.33-2.55zM8.47 5.17L4 6.13l1.66 7.81l.01.03c.15.71.45 1.35.86 1.9c-.1-.77-.08-1.57.09-2.37l.43-2c-.45-.08-.84-.33-1.05-.69c.06-.61.56-1.15 1.25-1.31h.25l.78-3.81c.04-.19.1-.36.19-.52m6.56 7.06c.32-.53 1-.81 1.69-.66c.69.14 1.19.67 1.28 1.29c-.33.52-1 .8-1.7.64c-.69-.13-1.19-.66-1.27-1.27m-4.88-1.04c.32-.53.99-.81 1.68-.66c.67.14 1.2.68 1.28 1.29c-.33.52-1 .81-1.69.68c-.69-.17-1.19-.7-1.27-1.31m1.82-6.76l1.96.42l-.16-.8z"/></svg>')
                        <svg class="w-12 h-12 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @else
                        <svg class="w-12 h-12 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                        @endif
                    </div>
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
                            <div class="flex items-center justify-center w-12 h-12 text-slate-600">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </div>
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
                <img src="{{ asset('images/smansa-logo.png') }}" alt="Logo SMANSA" class="w-8 h-8 rounded-lg object-contain">
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
                    return "Sempurna! Kamu adalah Velorans Sejati!";
                } else if (this.score >= 3) {
                    return "Bagus sekali! Kamu sangat mengenal SPECTA!";
                } else {
                    return "Ayo pelajari lebih lanjut timeline di atas and coba lagi!";
                }
            }
        };
    }

    // Disable automatic scroll restoration
    if ('scrollRestoration' in history) {
        history.scrollRestoration = 'manual';
    }
    // Scroll to top immediately
    window.scrollTo(0, 0);

    document.addEventListener('DOMContentLoaded', function () {
        // Double check on DOM load
        if (!window.location.hash) {
            window.scrollTo(0, 0);
        }
        
        // Inisialisasi Vanilla-Tilt pada elemen berkemampuan tilt
        if (typeof VanillaTilt !== 'undefined') {
            VanillaTilt.init(document.querySelectorAll("[data-tilt]"));
        }
    });
</script>
@endpush
@endsection
