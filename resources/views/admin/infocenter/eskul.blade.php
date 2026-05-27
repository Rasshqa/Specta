@extends('layouts.app')

@section('title', 'Kelola Eskul SMANSA – SPECTA XXI')

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
                <h1 class="font-bold text-slate-200">Eskul SMANSA</h1>
            </div>
        </header>

        <main class="flex-1 px-6 py-8">
            <div class="space-y-6" x-data="{ showAdd: false, editId: null }">

                {{-- Header --}}
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-100">Profil Ekstrakurikuler</h2>
                        <p class="text-slate-500 text-sm mt-1">Kelola profil ekstrakurikuler yang tampil di landing page.</p>
                    </div>
                    <button @click="showAdd = !showAdd" class="flex items-center gap-2 bg-purple-600 hover:bg-purple-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg shadow-purple-900/30 cursor-pointer">
                        <span x-text="showAdd ? '✕ Batal' : '+ Tambah Eskul'"></span>
                    </button>
                </div>

                {{-- ADD FORM --}}
                <div x-show="showAdd" x-transition class="bg-slate-900/80 border border-slate-700/60 rounded-2xl p-6 space-y-4">
                    <h3 class="font-black text-slate-200 text-lg">Tambah Eskul Baru</h3>
                    <form action="{{ route('admin.infocenter.eskul.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf
                        @include('admin.infocenter._eskul_fields')
                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white px-8 py-2.5 rounded-xl font-bold text-sm transition-all cursor-pointer">
                                Simpan Eskul
                            </button>
                        </div>
                    </form>
                </div>

                {{-- LIST --}}
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    @forelse($eskuls as $eskul)
                    <div class="bg-slate-900/60 border border-slate-800/60 rounded-2xl overflow-hidden">
                        {{-- Image --}}
                        @if($eskul->image_path)
                        <img src="{{ $eskul->image_url }}" alt="{{ $eskul->name }}" class="w-full h-36 object-cover">
                        @else
                        <div class="w-full h-36 bg-gradient-to-br from-purple-950/60 to-slate-900 flex items-center justify-center text-5xl">
                            {{ $eskul->icon }}
                        </div>
                        @endif

                        <div class="p-5 space-y-3">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <p class="font-black text-slate-100">{{ $eskul->name }}</p>
                                    <p class="text-xs text-slate-500 mt-0.5">{{ $eskul->schedule }}</p>
                                </div>
                                <span class="text-xs px-2 py-1 rounded-full {{ $eskul->is_active ? 'bg-green-900/40 text-green-400 border border-green-500/20' : 'bg-slate-800 text-slate-500' }}">
                                    {{ $eskul->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 leading-relaxed line-clamp-2">{{ $eskul->description }}</p>

                            <div class="flex gap-2 pt-1">
                                {{-- Edit toggle --}}
                                <button @click="editId = editId === {{ $eskul->id }} ? null : {{ $eskul->id }}" class="flex-1 text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 py-2 rounded-xl font-bold transition-all cursor-pointer">
                                    ✏️ Edit
                                </button>
                                {{-- Delete --}}
                                <form action="{{ route('admin.infocenter.eskul.destroy', $eskul) }}" method="POST" onsubmit="return confirm('Hapus data eskul ini?')" class="m-0 p-0 flex flex-shrink-0">
                                    @csrf 
                                    @method('DELETE')
                                    <button 
                                       type="submit" 
                                       class="px-4 text-xs bg-red-900/40 hover:bg-red-800/60 text-red-400 py-2 rounded-xl font-bold transition-all cursor-pointer border border-red-800/40"
                                    >
                                        🗑️
                                    </button>
                                </form>                            </div>

                            {{-- Edit form --}}
                            <div x-show="editId === {{ $eskul->id }}" x-transition class="pt-3 border-t border-slate-800/60 space-y-3">
                                <form action="{{ route('admin.infocenter.eskul.update', $eskul) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-3">
                                    @csrf
                                    @include('admin.infocenter._eskul_fields', ['eskul' => $eskul])
                                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-500 text-white py-2.5 rounded-xl font-bold text-sm transition-all cursor-pointer">
                                        Perbarui
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="md:col-span-3 text-center py-16 text-slate-500">
                        <p class="text-4xl mb-3">🎯</p>
                        <p class="font-semibold">Belum ada data eskul.</p>
                        <p class="text-sm mt-1">Klik "+ Tambah Eskul" untuk memulai.</p>
                    </div>
                    @endforelse
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
