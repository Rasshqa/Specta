@extends('layouts.app')

@section('title', 'Dokumentasi – SPECTA XXI')

@section('content')
<div class="min-h-screen bg-slate-950 font-sans text-slate-200">

    <!-- Navbar -->
    <nav class="fixed w-full z-[100] top-0 bg-slate-950/80 backdrop-blur-md border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-600 to-cyan-500 flex items-center justify-center font-bold text-white shadow-lg group-hover:scale-105 transition-transform">SR</div>
                <span class="font-black text-xl tracking-[0.2em] text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 uppercase hidden sm:block">SPECTA XXI</span>
            </a>
            <a href="/" class="text-sm font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors uppercase">&larr; Kembali</a>
        </div>
    </nav>

    <!-- Background glows -->
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="absolute top-20 left-10 w-96 h-96 bg-cyan-600/8 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-600/8 rounded-full blur-[120px]"></div>
    </div>

    <!-- Header -->
    <header class="pt-32 pb-16 px-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-cyan-900/15 to-transparent"></div>
        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 font-bold text-xs tracking-widest uppercase mb-6">
                MEMORIES
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-400 mb-4 uppercase tracking-tight">
                Dokumentasi
            </h1>
            <p class="text-slate-400 max-w-xl mx-auto text-sm md:text-base leading-relaxed">
                Kumpulan foto dan video kemeriahan rangkaian acara SPECTA SMANSA.
            </p>
        </div>
    </header>

    <!-- Main Gallery -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 pb-24 relative z-10">

        @if($docs->isEmpty())
        <!-- Empty state -->
        <div class="flex flex-col items-center justify-center py-32 text-center">
            <div class="w-24 h-24 rounded-3xl bg-slate-900 border border-white/10 flex items-center justify-center text-5xl mb-6">📸</div>
            <h2 class="text-xl font-bold text-slate-300 mb-2">Belum ada dokumentasi</h2>
            <p class="text-slate-500 text-sm">Nantikan update foto & video keseruan SPECTA XXI!</p>
        </div>

        @else
        <!-- Masonry-style responsive grid -->
        <div class="columns-2 md:columns-3 lg:columns-4 gap-4 space-y-4" x-data="{ open: null }">

            @foreach($docs as $doc)
            <div
                class="break-inside-avoid group relative rounded-2xl overflow-hidden border border-white/10 hover:border-purple-500/40 transition-all duration-500 cursor-pointer hover:shadow-[0_0_25px_rgba(168,85,247,0.2)]"
                @click="open = open === {{ $doc->id }} ? null : {{ $doc->id }}"
            >
                {{-- Media --}}
                @if($doc->file_type === 'image')
                    <img
                        src="{{ $doc->file_url }}"
                        alt="{{ $doc->title }}"
                        class="w-full object-cover group-hover:scale-105 transition-transform duration-700"
                        loading="lazy"
                    >
                @else
                    <div class="relative aspect-video bg-slate-900">
                        <video src="{{ $doc->file_url }}" class="w-full h-full object-cover"></video>
                        <div class="absolute inset-0 flex items-center justify-center bg-black/40">
                            <div class="w-14 h-14 rounded-full bg-white/20 backdrop-blur flex items-center justify-center text-2xl">▶</div>
                        </div>
                    </div>
                @endif

                {{-- Overlay info --}}
                <div
                    class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent flex flex-col justify-end p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                    :class="open === {{ $doc->id }} ? 'opacity-100' : ''"
                >
                    @if($doc->event_date)
                    <span class="text-[10px] font-bold text-cyan-400 uppercase tracking-widest mb-1">{{ $doc->event_date->format('d M Y') }}</span>
                    @endif
                    <h3 class="font-bold text-white text-sm leading-tight">{{ $doc->title }}</h3>
                    @if($doc->description)
                    <p class="text-[11px] text-slate-300 mt-1 line-clamp-2">{{ $doc->description }}</p>
                    @endif
                </div>

                {{-- Type badge --}}
                @if($doc->file_type === 'video')
                <span class="absolute top-2 right-2 px-2 py-0.5 bg-black/60 backdrop-blur text-[10px] font-bold text-cyan-400 rounded-lg border border-cyan-500/30">VIDEO</span>
                @endif
            </div>
            @endforeach

        </div>
        @endif

    </main>

</div>
@endsection
