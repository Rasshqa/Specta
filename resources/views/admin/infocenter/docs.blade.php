@extends('layouts.app')

@section('title', 'Kelola Dokumentasi – SPECTA XXI')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-100" x-data="{ sidebarOpen: false }">
    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    <div class="lg:pl-64 flex flex-col min-h-screen">
        {{-- Topbar --}}
        <header class="sticky top-0 z-30 bg-slate-950/80 backdrop-blur-xl border-b border-slate-800/40 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <h1 class="font-bold text-slate-200">Dokumentasi Acara</h1>
            </div>
        </header>

        <main class="flex-1 px-6 py-8">
            <div class="space-y-6" x-data="{ showAdd: false }">
                
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-100">Galeri Dokumentasi</h2>
                        <p class="text-slate-500 text-sm mt-1">Upload foto (WebP) atau video (maks 50MB).</p>
                    </div>
                    <button @click="showAdd = !showAdd" class="flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-purple-900/20 cursor-pointer">
                        <span x-text="showAdd ? '✕ Batal' : '+ Upload Baru'"></span>
                    </button>
                </div>

                {{-- Add Form --}}
                <div x-show="showAdd" x-transition class="bg-slate-900/80 border border-slate-800/60 rounded-2xl p-6 shadow-xl">
                    <form action="{{ route('admin.infocenter.docs.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="title" class="block text-xs font-bold text-slate-500 uppercase mb-2">Judul Foto/Video</label>
                                <input type="text" id="title" name="title" required class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-purple-500 transition-all" placeholder="Contoh: Keseruan Konser Day 1">
                            </div>
                            <div>
                                <label for="event_date" class="block text-xs font-bold text-slate-500 uppercase mb-2">Tanggal Acara</label>
                                <input type="date" id="event_date" name="event_date" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-purple-500 transition-all">
                            </div>
                            <div>
                                <label for="description" class="block text-xs font-bold text-slate-500 uppercase mb-2">Keterangan</label>
                                <textarea id="description" name="description" rows="3" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-purple-500 transition-all" placeholder="Ceritakan sedikit tentang momen ini..."></textarea>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label for="file" class="block text-xs font-bold text-slate-500 uppercase mb-2">File (Image/Video)</label>
                                <div class="relative group">
                                    <input type="file" id="file" name="file" required accept="image/*,video/*" class="absolute inset-0 w-full h-full opacity-0 z-10 cursor-pointer">
                                    <div class="border-2 border-dashed border-slate-700 group-hover:border-purple-500/50 rounded-2xl p-8 text-center transition-all bg-slate-950/50">
                                        <div class="text-3xl mb-2">📁</div>
                                        <p class="text-sm text-slate-400">Klik atau drop file di sini</p>
                                        <p class="text-[10px] text-slate-500 mt-2 uppercase tracking-widest">Image auto-convert ke WebP | Video maks 50MB</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end pt-4">
                                <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white px-10 py-3 rounded-xl font-bold text-sm shadow-lg shadow-purple-900/40 transition-all">
                                    Upload Sekarang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Grid Display --}}
                <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-6 space-y-6">
                    @forelse($docs as $doc)
                    <div 
                        class="break-inside-avoid group relative bg-slate-900 rounded-2xl overflow-hidden border border-slate-800/60 hover:border-purple-500/40 transition-all duration-500 shadow-lg"
                        x-data="{ info: false }"
                        @mouseenter="info = true"
                        @mouseleave="info = false"
                        @click="info = !info"
                    >
                        {{-- Media Preview --}}
                        <div class="bg-slate-800 flex items-center justify-center overflow-hidden w-full relative">
                            @if($doc->file_type === 'image')
                                <img src="{{ $doc->file_url }}" alt="{{ $doc->title }}" class="w-full h-auto object-contain group-hover:scale-105 transition-transform duration-700">
                            @else
                                <div class="w-full aspect-video relative">
                                    <video src="{{ $doc->file_url }}" class="w-full h-full object-cover"></video>
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/40">
                                        <span class="text-3xl">▶️</span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Hover Overlay Info --}}
                        <div 
                            class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/80 to-transparent p-6 flex flex-col justify-end transition-all duration-300"
                            x-show="info"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-4"
                            x-cloak
                        >
                            <span class="text-[10px] font-bold text-purple-400 uppercase tracking-[0.2em] mb-1">
                                {{ $doc->event_date ? \Carbon\Carbon::parse($doc->event_date)->format('d M Y') : 'Spekta Event' }}
                            </span>
                            <h3 class="font-bold text-slate-100 leading-tight mb-2">{{ $doc->title }}</h3>
                            <p class="text-xs text-slate-400 line-clamp-3 mb-4 leading-relaxed">
                                {{ $doc->description ?: 'Tidak ada deskripsi tambahan.' }}
                            </p>
                            
                            <div class="flex items-center justify-between pt-4 border-t border-white/10">
                                <span class="text-[10px] text-slate-500 font-mono uppercase">{{ $doc->file_type }}</span>
                                <form action="{{ route('admin.infocenter.docs.destroy', $doc) }}" method="POST" onsubmit="return confirm('Hapus dokumentasi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-500 hover:text-rose-400 transition-colors p-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full py-20 text-center">
                        <div class="text-5xl mb-4">📸</div>
                        <p class="text-slate-400">Belum ada dokumentasi yang diupload.</p>
                    </div>
                    @endforelse
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
