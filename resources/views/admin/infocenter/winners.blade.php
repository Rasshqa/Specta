@extends('layouts.app')

@section('title', 'Kelola Pemenang Lomba – SPECTA XXI')

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
                <h1 class="font-bold text-slate-200">Pemenang Lomba</h1>
            </div>
        </header>

        <main class="flex-1 px-6 py-8">
            <div class="space-y-6" x-data="{ showAdd: false, editId: null }">

                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-black text-slate-100">Daftar Pemenang Middle Event</h2>
                        <p class="text-slate-500 text-sm mt-1">Daftar pemenang Middle Event. Skor & foto bersifat opsional.</p>
                    </div>
                    <button @click="showAdd = !showAdd" class="flex items-center gap-2 bg-yellow-600 hover:bg-yellow-500 text-white px-5 py-2.5 rounded-xl font-bold text-sm transition-all shadow-lg cursor-pointer">
                        <span x-text="showAdd ? ' Batal' : '+ Tambah Pemenang'"></span>
                    </button>
                </div>

                {{-- ADD FORM --}}
                <div x-show="showAdd" x-transition class="bg-slate-900/80 border border-slate-700/60 rounded-2xl p-6">
                    <h3 class="font-black text-slate-200 text-lg mb-4">Tambah Pemenang Baru</h3>
                    <form action="{{ route('admin.infocenter.winners.store') }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf
                        @include('admin.infocenter._winner_fields')
                        <div class="md:col-span-2 flex justify-end">
                            <button type="submit" class="bg-yellow-600 hover:bg-yellow-500 text-white px-8 py-2.5 rounded-xl font-bold text-sm transition-all cursor-pointer">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- TABLE --}}
                <div class="bg-slate-900/60 border border-slate-800/60 rounded-2xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[700px]">
                            <thead class="border-b border-white/5 bg-white/5">
                                <tr>
                                    <th class="py-3.5 px-5 text-xs font-bold uppercase tracking-widest text-slate-400">Foto</th>
                                    <th class="py-3.5 px-5 text-xs font-bold uppercase tracking-widest text-slate-400">Prestasi</th>
                                    <th class="py-3.5 px-5 text-xs font-bold uppercase tracking-widest text-slate-400">Nama</th>
                                    <th class="py-3.5 px-5 text-xs font-bold uppercase tracking-widest text-slate-400">Sekolah</th>
                                    <th class="py-3.5 px-5 text-xs font-bold uppercase tracking-widest text-slate-400">Kategori</th>
                                    <th class="py-3.5 px-5 text-xs font-bold uppercase tracking-widest text-slate-400">Nilai</th>
                                    <th class="py-3.5 px-5 text-xs font-bold uppercase tracking-widest text-slate-400">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($winners as $winner)
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-3 px-5">
                                        @if($winner->image_path)
                                            <img src="{{ $winner->image_url }}" class="w-10 h-10 rounded-full object-cover border border-slate-700">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-lg"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M20 2H4v2l5.81 4.36a7.004 7.004 0 0 0-4.46 8.84a6.996 6.996 0 0 0 8.84 4.46a7 7 0 0 0 0-13.3L20 4zm-5.06 17.5L12 17.78L9.06 19.5l.78-3.33l-2.59-2.24l3.41-.29L12 10.5l1.34 3.14l3.41.29l-2.59 2.24z"/></svg></div>
                                        @endif
                                    </td>
                                    <td class="py-3 px-5 text-sm font-black text-purple-400">{{ $winner->rank }}</td>
                                    <td class="py-3 px-5 text-sm font-bold text-slate-100">{{ $winner->name }}</td>
                                    <td class="py-3 px-5 text-sm text-slate-300">{{ $winner->school }}</td>
                                    <td class="py-3 px-5 text-sm text-cyan-400">{{ $winner->category }}</td>
                                    <td class="py-3 px-5 text-sm text-slate-400 font-mono">{{ $winner->score ?? '—' }}</td>
                                    <td class="py-3 px-5">
                                        <div class="flex items-center gap-2">
                                            {{-- Edit --}}
                                            <button @click="editId = editId === {{ $winner->id }} ? null : {{ $winner->id }}" class="text-xs bg-slate-800 hover:bg-slate-700 text-slate-300 px-3 py-1.5 rounded-lg font-bold transition-all cursor-pointer">
                                                <svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M20.71 7.04c.39-.39.39-1.04 0-1.41l-2.34-2.34c-.37-.39-1.02-.39-1.41 0l-1.84 1.83l3.75 3.75M3 17.25V21h3.75L17.81 9.93l-3.75-3.75z"/></svg>
                                            </button>

                                            {{-- Delete --}}
                                            <form action="{{ route('admin.infocenter.winners.destroy', $winner) }}" method="POST" onsubmit="return confirm('Hapus data pemenang ini?')" class="m-0 p-0 flex">
                                                @csrf 
                                                @method('DELETE')
                                                <button 
                                                    type="submit" 
                                                    class="text-xs bg-red-900/40 hover:bg-red-800/60 text-red-400 px-3 py-1.5 rounded-lg font-bold transition-all cursor-pointer border border-red-800/40"
                                                >
                                                    <svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6z"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                {{-- Edit inline row --}}
                                <tr x-show="editId === {{ $winner->id }}" x-transition class="bg-slate-900">
                                    <td colspan="7" class="px-5 py-4">
                                        <form action="{{ route('admin.infocenter.winners.update', $winner) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @csrf
                                            @include('admin.infocenter._winner_fields', ['winner' => $winner])
                                            <div class="md:col-span-2 flex gap-2">
                                                <button type="submit" class="bg-yellow-600 hover:bg-yellow-500 text-white px-6 py-2 rounded-xl font-bold text-sm cursor-pointer">Perbarui</button>
                                                <button type="button" @click="editId = null" class="bg-slate-700 hover:bg-slate-600 text-slate-300 px-6 py-2 rounded-xl font-bold text-sm cursor-pointer">Batal</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="py-16 text-center text-slate-500">
                                        <p class="text-3xl mb-2"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M18 2c-.9 0-2 1-2 2H8c0-1-1.1-2-2-2H2v9c0 1 1 2 2 2h2.2c.4 2 1.7 3.7 4.8 4v2.08C8 19.54 8 22 8 22h8s0-2.46-3-2.92V17c3.1-.3 4.4-2 4.8-4H20c1 0 2-1 2-2V2zM6 11H4V4h2zm14 0h-2V4h2z"/></svg></p>
                                        <p>Belum ada data pemenang.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>
</div>
@endsection
