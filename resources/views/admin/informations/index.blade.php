@extends('layouts.app')

@section('title', 'Kelola Pusat Informasi – Admin')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-100" x-data="{ sidebarOpen: false, showAdd: false, editItem: null }">
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
                <h1 class="font-bold text-slate-200">Kelola Pusat Informasi</h1>
            </div>
            <button @click.stop="showAdd = true" class="bg-purple-600 hover:bg-purple-500 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-lg shadow-purple-600/20 transition-all flex items-center gap-2 cursor-pointer">
                <span><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6z"/></svg></span> Tambah Pengumuman
            </button>
        </header>

        <main class="flex-1 px-6 py-8">
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-900/30 border border-emerald-500/30 text-emerald-400 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <p class="font-semibold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-900/30 border border-red-500/30 text-red-400 rounded-xl">
                    <ul class="list-disc list-inside text-sm font-semibold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Add Modal --}}
            <div x-show="showAdd" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
                <div @click.outside="showAdd = false" class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-3xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                    <h2 class="text-xl font-bold mb-6">Tambah Pengumuman</h2>
                    <form action="{{ route('admin.informations.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Judul Pengumuman</label>
                            <input type="text" name="title" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm focus:border-purple-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Kategori</label>
                            <select name="category" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm focus:border-purple-500 focus:outline-none">
                                <option value="Event Info">Event Info</option>
                                <option value="Timeline">Timeline</option>
                                <option value="Rules">Rules / Aturan</option>
                                <option value="General">General Update</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Isi Konten</label>
                            <textarea name="content" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm h-32 focus:border-purple-500 focus:outline-none"></textarea>
                        </div>
                        <div class="flex items-center gap-2 mt-4">
                            <input type="checkbox" name="is_active" id="add_is_active" value="1" checked class="w-4 h-4 rounded text-purple-600 bg-slate-800 border-slate-700 focus:ring-purple-500">
                            <label for="add_is_active" class="text-sm font-semibold text-slate-300">Tampilkan di Landing Page</label>
                        </div>
                        <div class="flex gap-3 pt-4 border-t border-slate-800">
                            <button type="button" @click="showAdd = false" class="flex-1 bg-slate-800 hover:bg-slate-700 py-2.5 rounded-xl font-bold text-sm cursor-pointer transition-colors">Batal</button>
                            <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-500 py-2.5 rounded-xl font-bold text-sm cursor-pointer transition-colors">Simpan & Publikasikan</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Edit Modal --}}
            <template x-if="editItem">
                <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
                    <div @click.outside="editItem = null" class="bg-slate-900 border border-slate-800 w-full max-w-lg rounded-3xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                        <h2 class="text-xl font-bold mb-6">Edit Pengumuman</h2>
                        <form :action="'/admin/informations/' + editItem.id" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Judul Pengumuman</label>
                                <input type="text" name="title" x-model="editItem.title" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm focus:border-purple-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Kategori</label>
                                <select name="category" x-model="editItem.category" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm focus:border-purple-500 focus:outline-none">
                                    <option value="Event Info">Event Info</option>
                                    <option value="Timeline">Timeline</option>
                                    <option value="Rules">Rules / Aturan</option>
                                    <option value="General">General Update</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Isi Konten</label>
                                <textarea name="content" x-model="editItem.content" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm h-32 focus:border-purple-500 focus:outline-none"></textarea>
                            </div>
                            <div class="flex items-center gap-2 mt-4">
                                <input type="checkbox" name="is_active" id="edit_is_active" value="1" x-bind:checked="editItem.is_active" class="w-4 h-4 rounded text-purple-600 bg-slate-800 border-slate-700 focus:ring-purple-500">
                                <label for="edit_is_active" class="text-sm font-semibold text-slate-300">Tampilkan di Landing Page</label>
                            </div>
                            <div class="flex gap-3 pt-4 border-t border-slate-800">
                                <button type="button" @click="editItem = null" class="flex-1 bg-slate-800 hover:bg-slate-700 py-2.5 rounded-xl font-bold text-sm cursor-pointer transition-colors">Batal</button>
                                <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-500 py-2.5 rounded-xl font-bold text-sm cursor-pointer transition-colors">Perbarui Data</button>
                            </div>
                        </form>
                    </div>
                </div>
            </template>

            {{-- Grid List --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($informations as $info)
                <div class="bg-slate-900/60 backdrop-blur-sm border {{ $info->is_active ? 'border-purple-500/30' : 'border-slate-800/60' }} rounded-2xl p-6 group hover:border-purple-500/50 transition-all duration-300 flex flex-col relative" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                    
                    @if(!$info->is_active)
                    <div class="absolute top-4 right-4 text-[10px] bg-red-900/30 text-red-400 px-2 py-1 rounded font-bold uppercase tracking-wider">Draft / Hidden</div>
                    @endif

                    <div class="mb-4">
                        <span class="inline-block px-3 py-1 bg-purple-500/10 border border-purple-500/20 text-purple-400 text-xs font-bold uppercase tracking-widest rounded-md mb-2">
                            {{ $info->category }}
                        </span>
                        <h3 class="font-bold text-lg text-slate-200 leading-tight pr-12">{{ $info->title }}</h3>
                        <p class="text-xs text-slate-500 font-mono mt-2">Dibuat: {{ $info->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    
                    <div class="text-sm text-slate-400 line-clamp-3 mb-6 flex-1">
                        {{ $info->content }}
                    </div>

                    <div class="flex gap-2 mt-auto border-t border-slate-800/60 pt-4">
                        <button 
                            @click.stop="editItem = window.informationsData.find(item => item.id === {{ $info->id }})"
                            class="flex-1 bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-medium py-2 rounded-xl transition-colors cursor-pointer"
                        >
                            Edit
                        </button>
                        <form action="{{ route('admin.informations.destroy', $info) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini secara permanen?')" class="inline-flex">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit"
                                class="px-4 h-full bg-red-900/20 hover:bg-red-900/40 border border-red-500/30 text-red-400 rounded-xl transition-colors cursor-pointer"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                @empty
                <div class="col-span-full py-20 text-center bg-slate-900/40 rounded-3xl border border-slate-800/60 border-dashed">
                    <div class="text-5xl mb-4"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 8H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h1v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h3l5 4V4zm9.5 4c0 1.71-.96 3.26-2.5 4V8c1.53.75 2.5 2.3 2.5 4"/></svg></div>
                    <p class="text-slate-400 text-lg font-semibold">Belum ada pengumuman.</p>
                    <p class="text-sm text-slate-500 mt-2">Buat pengumuman baru untuk ditampilkan di landing page Velorans.</p>
                </div>
                @endforelse
            </div>

            @if($informations->hasPages())
            <div class="mt-8">
                {{ $informations->links() }}
            </div>
            @endif
        </main>
    </div>
</div>

@push('scripts')
<script>
    window.informationsData = @json($informations->items());
</script>
@endpush
@endsection
