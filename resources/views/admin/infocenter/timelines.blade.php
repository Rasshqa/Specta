@extends('layouts.app')

@section('title', 'Kelola Timeline Dokumentasi – SPECTA XXI')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-100" x-data="{ sidebarOpen: false }">
    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    <div class="lg:pl-64 flex flex-col min-h-screen">
        {{-- Topbar --}}
        <header class="sticky top-0 z-30 bg-slate-950/80 backdrop-blur-xl border-b border-slate-800/40 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors"
                    aria-label="Toggle Sidebar"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <a href="{{ route('admin.dashboard') }}" class="p-2 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors hidden sm:inline-flex" title="Kembali ke Dashboard">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <h1 class="font-bold text-slate-200">Timeline Dokumentasi</h1>
            </div>
        </header>

        <main class="flex-1 px-6 py-8">
            <div class="space-y-6" x-data="{ showAdd: false, editId: null }">

                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-100">Riwayat Perjalanan SPECTA</h2>
                        <p class="text-slate-500 text-sm mt-1">Kelola riwayat SPECTA per tahun. Centang "Tahun Berjalan" untuk highlight di halaman utama.</p>
                    </div>
                    <button @click="showAdd = !showAdd" class="flex items-center gap-2 bg-cyan-600 hover:bg-cyan-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg cursor-pointer">
                        <span x-text="showAdd ? '✕ Batal' : '+ Tambah Timeline'"></span>
                    </button>
                </div>

                {{-- ADD FORM --}}
                <div x-show="showAdd" x-transition class="bg-slate-900/80 border border-slate-700/60 rounded-2xl p-6">
                    <h3 class="font-black text-slate-200 text-lg mb-4">Tambah Entry Timeline</h3>
                    <form action="{{ route('admin.infocenter.timelines.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf
                        @include('admin.infocenter._timeline_fields')
                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white px-8 py-2.5 rounded-xl font-bold text-sm transition-all cursor-pointer">Simpan</button>
                        </div>
                    </form>
                </div>

                {{-- LIST --}}
                <div class="space-y-4">
                    @forelse($timelines as $tl)
                    <div class="bg-slate-900/60 border {{ $tl->is_current ? 'border-cyan-500/40' : 'border-slate-800/60' }} rounded-2xl overflow-hidden">
                        <div class="flex flex-col md:flex-row">
                            {{-- Image --}}
                            @if($tl->image_path)
                            <img src="{{ $tl->image_url }}" alt="{{ $tl->title }}" class="w-full md:w-48 h-36 object-cover flex-shrink-0">
                            @else
                            <div class="w-full md:w-48 h-36 bg-gradient-to-br from-cyan-950/50 to-slate-900 flex items-center justify-center text-4xl flex-shrink-0">
                                📅
                            </div>
                            @endif

                            <div class="p-5 flex-1 space-y-2">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-mono text-sm font-black text-cyan-400">{{ $tl->year }}</span>
                                            @if($tl->is_current)
                                            <span class="text-xs bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 px-2 py-0.5 rounded-full font-bold">Tahun Berjalan</span>
                                            @endif
                                            @if(!$tl->is_active)
                                            <span class="text-xs bg-slate-800 text-slate-500 px-2 py-0.5 rounded-full">Nonaktif</span>
                                            @endif
                                        </div>
                                        <p class="font-black text-slate-100">{{ $tl->title }}</p>
                                        @if($tl->subtitle)<p class="text-xs text-purple-400 font-semibold">{{ $tl->subtitle }}</p>@endif
                                    </div>
                                    <div class="flex gap-2 flex-shrink-0">
                                        <button @click="editId = editId === {{ $tl->id }} ? null : {{ $tl->id }}" class="text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-1.5 rounded-lg font-bold transition-all cursor-pointer">✏️ Edit</button>
                                        <form action="{{ route('admin.infocenter.timelines.destroy', $tl) }}" method="POST" onsubmit="return confirm('Hapus data timeline ini?')" class="m-0 p-0 flex">
                                            @csrf 
                                            @method('DELETE')
                                            <button 
                                               type="submit" 
                                               class="text-xs bg-red-900/40 hover:bg-red-800/60 text-red-400 px-3 py-1.5 rounded-lg font-bold border border-red-800/40 cursor-pointer"
                                            >
                                               🗑️
                                            </button>
                                        </form>
                                    </div>                                </div>
                                <p class="text-sm text-slate-400 leading-relaxed line-clamp-2">{{ $tl->description }}</p>

                                {{-- Edit form --}}
                                <div x-show="editId === {{ $tl->id }}" x-transition class="pt-4 border-t border-slate-800/60">
                                    <form action="{{ route('admin.infocenter.timelines.update', $tl) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @csrf
                                        @include('admin.infocenter._timeline_fields', ['timeline' => $tl])
                                        <div class="md:col-span-2 flex gap-2">
                                            <button type="submit" class="bg-cyan-600 hover:bg-cyan-500 text-white px-6 py-2 rounded-xl font-bold text-sm cursor-pointer">Perbarui</button>
                                            <button type="button" @click="editId = null" class="bg-slate-700 hover:bg-slate-600 text-slate-300 px-6 py-2 rounded-xl font-bold text-sm cursor-pointer">Batal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-16 text-slate-500">
                        <p class="text-4xl mb-3">📅</p>
                        <p class="font-semibold">Belum ada data timeline.</p>
                    </div>
                    @endforelse
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
