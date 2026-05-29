@extends('layouts.app')

@section('title', 'Merchandise – SPECTA XXI')

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
                <h1 class="font-bold text-slate-200">Katalog Merchandise</h1>
            </div>
            <button @click.stop="showAdd = true" class="bg-purple-600 hover:bg-purple-500 text-white text-sm font-semibold px-4 py-2 rounded-xl shadow-lg shadow-purple-600/20 transition-all flex items-center gap-2 cursor-pointer">
                <span><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6z"/></svg></span> Tambah
            </button>
        </header>

    <main class="flex-1 px-6 py-8">
        {{-- Add Modal --}}
        <div x-show="showAdd" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
            <div @click.outside="showAdd = false" class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-3xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                <h2 class="text-xl font-bold mb-6">Tambah Merchandise</h2>
                <form action="{{ route('admin.merchandises.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Produk</label>
                        <input type="text" name="name" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Deskripsi</label>
                        <textarea name="description" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm h-24"></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Harga (Rp)</label>
                        <input type="number" name="price" required min="0" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Foto Produk (JPG/PNG/WebP, maks 4MB)</label>
                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-300 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-500 cursor-pointer">
                        <p class="text-[10px] text-slate-500 mt-1">Otomatis dikonversi ke WebP</p>
                    </div>
                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="showAdd = false" class="flex-1 bg-slate-800 hover:bg-slate-700 py-2.5 rounded-xl font-bold text-sm cursor-pointer">Batal</button>
                        <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-500 py-2.5 rounded-xl font-bold text-sm cursor-pointer">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Edit Modal --}}
        <template x-if="editItem">
            <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
                <div @click.outside="editItem = null" class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-3xl p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
                    <h2 class="text-xl font-bold mb-6">Edit Merchandise</h2>
                    <form :action="'/admin/merchandises/' + editItem.id" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Nama Produk</label>
                            <input type="text" name="name" x-model="editItem.name" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Deskripsi</label>
                            <textarea name="description" x-model="editItem.description" required class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm h-24"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Harga (Rp)</label>
                            <input type="number" name="price" x-model="editItem.price" required min="0" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Ganti Foto (opsional)</label>
                            <template x-if="editItem.image_url">
                                <img :src="editItem.image_url" class="w-full h-32 object-cover rounded-xl mb-2 border border-slate-700">
                            </template>
                            <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="w-full bg-slate-800 border border-slate-700 rounded-xl px-4 py-2 text-sm text-slate-300 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-purple-600 file:text-white hover:file:bg-purple-500 cursor-pointer">
                            <p class="text-[10px] text-slate-500 mt-1">Otomatis dikonversi ke WebP. Kosongkan jika tidak ingin mengubah foto.</p>
                        </div>
                        <div class="flex gap-3 pt-2">
                            <button type="button" @click="editItem = null" class="flex-1 bg-slate-800 hover:bg-slate-700 py-2.5 rounded-xl font-bold text-sm cursor-pointer">Batal</button>
                            <button type="submit" class="flex-1 bg-purple-600 hover:bg-purple-500 py-2.5 rounded-xl font-bold text-sm cursor-pointer">Perbarui</button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($merchandises as $merch)
            <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800/60 rounded-2xl overflow-hidden group hover:border-purple-500/40 transition-all duration-300 flex flex-col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                {{-- Image Placeholder --}}
                <div class="aspect-square bg-slate-800 relative flex items-center justify-center overflow-hidden">
                    @if($merch->image_url)
                        <img src="{{ $merch->image_url }}" alt="{{ $merch->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="text-6xl opacity-20 group-hover:scale-110 transition-transform duration-500"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 6h-2c0-2.8-2.2-5-5-5S7 3.2 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2m-7-3c1.7 0 3 1.3 3 3H9c0-1.7 1.3-3 3-3m7 17H5V8h14zm-7-8c-1.7 0-3-1.3-3-3H7c0 2.8 2.2 5 5 5s5-2.2 5-5h-2c0 1.7-1.3 3-3 3"/></svg></div>
                    @endif
                    <div class="absolute top-3 right-3 bg-slate-900/80 backdrop-blur-md px-3 py-1 rounded-full border border-slate-700/50">
                        <p class="text-sm font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">
                            Rp {{ number_format($merch->price, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
                
                {{-- Content --}}
                <div class="p-5 flex-1 flex flex-col">
                    <h3 class="font-bold text-lg text-slate-200 mb-2 leading-tight">{{ $merch->name }}</h3>
                    <p class="text-sm text-slate-400 line-clamp-3 mb-4 flex-1">{{ $merch->description }}</p>
                    <div class="flex gap-2">
                        <button 
                            @click.stop="editItem = window.merchandisesData.find(item => item.id === {{ $merch->id }})"
                            class="flex-1 bg-slate-800 hover:bg-slate-700 text-slate-300 text-sm font-medium py-2 rounded-xl transition-colors cursor-pointer"
                        >
                            Edit
                        </button>
                        <form action="{{ route('admin.merchandises.destroy', $merch) }}" method="POST" onsubmit="return confirm('Hapus merchandise ini?')" class="inline-flex">
                            @csrf
                            @method('DELETE')
                            <button 
                                type="submit"
                                class="px-3 h-full bg-red-900/20 hover:bg-red-900/40 border border-red-500/30 text-red-400 rounded-xl transition-colors cursor-pointer"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center">
                <div class="text-5xl mb-4"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M17 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2M1 2v2h2l3.6 7.59l-1.36 2.45c-.15.28-.24.61-.24.96a2 2 0 0 0 2 2h12v-2H7.42a.25.25 0 0 1-.25-.25q0-.075.03-.12L8.1 13h7.45c.75 0 1.41-.42 1.75-1.03l3.58-6.47c.07-.16.12-.33.12-.5a1 1 0 0 0-1-1H5.21l-.94-2M7 18c-1.11 0-2 .89-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2"/></svg></div>
                <p class="text-slate-400 text-lg">Belum ada merchandise yang ditambahkan.</p>
            </div>
            @endforelse
        </div>

        @if($merchandises->hasPages())
        <div class="mt-8">
            {{ $merchandises->links() }}
        </div>
        @endif
    </main>
    </div>
</div>

@push('scripts')
<script>
    window.merchandisesData = @json($merchandises->items());
</script>
@endpush
@endsection
