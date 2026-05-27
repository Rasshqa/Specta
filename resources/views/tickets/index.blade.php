@extends('layouts.app')

@section('title', 'Beli Tiket – SPECTA REVELIORA')

@section('content')
<div x-data="{ mobileMenuOpen: false, userDropdownOpen: false }" class="bg-black text-slate-100 min-h-screen relative overflow-hidden font-sans">
    
    <!-- Ambient Universe Glows -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute top-[-10%] right-[-10%] w-[600px] h-[600px] bg-purple-900/10 rounded-full blur-[140px] mix-blend-screen"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] bg-cyan-900/10 rounded-full blur-[140px] mix-blend-screen"></div>
    </div>

    <!-- ─── NAVIGATION BAR ─── -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-black/60 backdrop-blur-xl border-b border-white/5 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            
            <!-- Left Logo -->
            <a href="{{ url('/') }}" class="flex items-center gap-3 group focus:outline-none">
                <img src="{{ asset('images/smansa-logo.png') }}" alt="Logo SMANSA" class="w-10 h-10 rounded-xl shadow-[0_0_15px_rgba(168,85,247,0.3)] group-hover:scale-105 transition-all object-contain">
                <span class="font-black text-lg tracking-[0.2em] text-transparent bg-clip-text bg-gradient-to-r from-slate-100 to-purple-300 uppercase">
                    SPECTA REVELIORA
                </span>
            </a>

            <!-- Center Nav Links (Desktop) -->
            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ url('/') }}" class="text-sm font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">HOME</a>
                <a href="{{ url('/#lore') }}" class="text-sm font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">LORE</a>
                <a href="{{ url('/#documentation') }}" class="text-sm font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">DOCUMENTATION</a>
                <a href="#" class="text-sm font-semibold tracking-wider text-purple-400 transition-colors">TICKETS</a>
                <a href="{{ url('/#merchandise') }}" class="text-sm font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">MERCH</a>
                <a href="{{ url('/#gallery') }}" class="text-sm font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">GALLERY</a>
            </div>

            <!-- Right Actions (Desktop) -->
            <div class="hidden lg:flex items-center gap-6">
                <!-- Merchandise Cart Icon -->
                <a href="{{ url('/#merchandise') }}" class="text-slate-400 hover:text-purple-300 transition-colors relative" title="Merchandise Catalog">
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
                            
                            <form method="POST" action="{{ route('logout') }}" class="block border-t border-slate-800/80">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-400 hover:bg-red-950/20 transition-colors cursor-pointer">
                                    Sign Out
                                </button>
                            </form>
                        </div>
                    </div>
                @else
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
                <a href="{{ url('/') }}" @click="mobileMenuOpen = false" class="text-base font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">HOME</a>
                <a href="{{ url('/#lore') }}" @click="mobileMenuOpen = false" class="text-base font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">LORE</a>
                <a href="{{ url('/#documentation') }}" @click="mobileMenuOpen = false" class="text-base font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">DOCUMENTATION</a>
                <a href="#" @click="mobileMenuOpen = false" class="text-base font-semibold tracking-wider text-purple-400 transition-colors">TICKETS</a>
                <a href="{{ url('/#merchandise') }}" @click="mobileMenuOpen = false" class="text-base font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">MERCH</a>
                <a href="{{ url('/#gallery') }}" @click="mobileMenuOpen = false" class="text-base font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors">GALLERY</a>
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

    <!-- ─── TICKETS CONTENT ─── -->
    <div class="min-h-screen pt-32 px-6 pb-20 relative z-10 flex flex-col justify-center">
        <div class="max-w-4xl w-full mx-auto">
            
            <div class="text-center mb-12" data-aos="fade-up">
                <span class="text-xs font-bold text-purple-400 uppercase tracking-[0.25em] block mb-3">TICKET SELECTION</span>
                <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-3">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">Pilih Tiket Kamu</span>
                </h1>
                <p class="text-sm md:text-base text-slate-400 leading-relaxed max-w-xl mx-auto font-light">
                    Amankan tempatmu di acara paling spektakuler tahun ini. Kuota terbatas!
                </p>
            </div>

            <div class="max-w-xl mx-auto">
                @php $ticket = $tickets->first(); @endphp
                @if($ticket)
                <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-[0_8px_32px_0_rgba(186,131,255,0.03)] hover:border-[#ba83ff]/40 transition-all duration-300 flex flex-col" data-aos="fade-up">
                    
                    <div class="mb-6">
                        <span class="inline-block px-3 py-1 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-400 text-xs font-bold uppercase tracking-widest mb-4">
                            Tersedia {{ $ticket->remaining_quota }} Tiket
                        </span>
                        <h2 class="text-2xl font-black text-slate-100 mb-2">{{ $ticket->ticket_name }}</h2>
                        <p class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">
                            Rp {{ number_format($ticket->price, 0, ',', '.') }}
                        </p>
                    </div>
                    
                    <form method="POST" action="{{ route('ticket.checkout') }}" class="mt-auto space-y-4" x-data="{ quantity: 1 }">
                        @csrf
                        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                        
                        <div>
                            <label class="block text-xs text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama Pembeli</label>
                            <input type="text" name="buyer_name" required value="{{ old('buyer_name', Auth::user()->name) }}" class="w-full bg-slate-800/60 border border-slate-700/60 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-purple-500/50 transition-colors">
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Email</label>
                                <input type="email" name="buyer_email" required value="{{ old('buyer_email', Auth::user()->email) }}" class="w-full bg-slate-800/60 border border-slate-700/60 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-purple-500/50 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs text-slate-400 uppercase tracking-widest mb-1.5 ml-1">WhatsApp</label>
                                <input type="text" name="buyer_whatsapp" required value="{{ old('buyer_whatsapp') }}" placeholder="08..." class="w-full bg-slate-800/60 border border-slate-700/60 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-purple-500/50 transition-colors">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jumlah</label>
                            <div class="flex items-center bg-slate-800/60 border border-slate-700/60 rounded-xl overflow-hidden">
                                <button type="button" @click="quantity > 1 ? quantity-- : null" class="px-4 py-3 text-slate-400 hover:text-purple-400 hover:bg-slate-700 transition-colors cursor-pointer">-</button>
                                <input type="number" name="quantity" min="1" max="5" x-model="quantity" readonly class="w-full bg-transparent text-center text-sm text-slate-200 focus:outline-none pointer-events-none">
                                <button type="button" @click="quantity < 5 && quantity < {{ $ticket->remaining_quota }} ? quantity++ : null" class="px-4 py-3 text-slate-400 hover:text-cyan-400 hover:bg-slate-700 transition-colors cursor-pointer">+</button>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 text-white font-bold py-3.5 rounded-xl shadow-[0_0_20px_rgba(168,85,247,0.3)] transition-all transform hover:scale-[1.02] mt-6 cursor-pointer text-xs uppercase tracking-wider">
                            Beli Tiket
                        </button>
                    </form>
                </div>
                @else
                <div class="text-center p-8 bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl">
                    <p class="text-slate-400 text-lg">Mohon maaf, tiket belum tersedia saat ini.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
