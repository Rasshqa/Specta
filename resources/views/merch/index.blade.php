@extends('layouts.app')

@section('content')
<div x-data="{ showModal: false, activeMerch: null }" class="min-h-screen bg-slate-950 font-sans text-slate-200">
    <!-- Navbar -->
    <nav class="fixed w-full z-[100] top-0 transition-all duration-300 bg-slate-950/80 backdrop-blur-md border-b border-white/10">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <img src="{{ asset('images/smansa-logo.png') }}" alt="Logo SMANSA" class="w-10 h-10 rounded-xl shadow-lg group-hover:scale-105 transition-transform object-contain">
                <span class="font-black text-xl tracking-[0.2em] text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 uppercase hidden sm:block">
                    SPECTA XXI
                </span>
            </a>
            
            <a href="/" class="text-sm font-semibold tracking-wider text-slate-300 hover:text-purple-400 transition-colors uppercase">
                &larr; Kembali ke Beranda
            </a>
        </div>
    </nav>

    <!-- Header -->
    <header class="pt-32 pb-16 px-6 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-b from-purple-900/20 to-transparent"></div>
        <div class="max-w-7xl mx-auto relative z-10 text-center">
            <span class="inline-block px-4 py-1.5 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 font-bold text-xs tracking-widest uppercase mb-6">
                Official Store
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 mb-6 uppercase tracking-tight">
                Merchandise SPECTA
            </h1>
            <p class="text-slate-400 max-w-2xl mx-auto text-sm md:text-base leading-relaxed">
                Eksplorasi koleksi eksklusif SPECTA XXI: REVELIORA. Dukung acara ini dengan memiliki merchandise original dengan desain futuristik yang memukau.
            </p>
        </div>
    </header>

    <!-- Merch Grid -->
    <main class="max-w-7xl mx-auto px-6 pb-32 relative z-10">
        @if($merchandises->isEmpty())
        <div class="text-center py-24 text-slate-500">
            <div class="text-6xl mb-6 opacity-50">🛍️</div>
            <h3 class="text-2xl font-bold text-slate-300 mb-2">Belum ada merchandise</h3>
            <p>Admin sedang menyiapkan koleksi terbaik untukmu.</p>
        </div>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4 md:gap-6">
            @foreach($merchandises as $merch)
            @php
                $waNumber = '6281234567890';
                $waText = urlencode("Halo Admin SPECTA XXI! Saya tertarik untuk membeli merchandise berikut:\n\n*{$merch->name}*\nHarga: Rp " . number_format($merch->price, 0, ',', '.') . "\n\nApakah masih tersedia?");
            @endphp
            <button 
                type="button"
                @click="activeMerch = { name: '{{ addslashes($merch->name) }}', price: 'Rp {{ number_format($merch->price, 0, ',', '.') }}', description: '{{ addslashes($merch->description) }}', image_url: '{{ $merch->image_url }}', wa_url: 'https://wa.me/{{ $waNumber }}?text={{ $waText }}' }; showModal = true"
                class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden hover:border-purple-500/50 hover:shadow-[0_0_15px_rgba(168,85,247,0.2)] transition-all duration-300 group flex flex-col text-left"
            >
                <!-- Image -->
                <div class="aspect-square relative overflow-hidden bg-slate-950 flex items-center justify-center border-b border-slate-800">
                    @if($merch->image_url)
                        <img src="{{ $merch->image_url }}" alt="{{ $merch->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="text-4xl opacity-20 group-hover:scale-110 transition-transform duration-500">🛍️</div>
                    @endif
                </div>

                <!-- Info -->
                <div class="p-3 sm:p-4 flex-1 flex flex-col">
                    <h3 class="text-sm sm:text-base font-bold text-slate-200 mb-1 line-clamp-2 leading-tight group-hover:text-purple-400 transition-colors">{{ $merch->name }}</h3>
                    <p class="text-sm sm:text-lg font-extrabold text-cyan-400 mt-auto">
                        Rp {{ number_format($merch->price, 0, ',', '.') }}
                    </p>
                </div>
            </button>
            @endforeach
        </div>
        @endif
    </main>

    <!-- Footer -->
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

    <!-- Alpine.js Detail Modal (Same as before, moved here) -->
    <div x-show="showModal" class="fixed inset-0 z-[150] flex items-end sm:items-center justify-center p-0 sm:p-4" x-init="$watch('showModal', value => document.body.style.overflow = value ? 'hidden' : '')" x-cloak>
        <!-- Backdrop -->
        <div 
            x-show="showModal" 
            x-transition:enter="transition ease-out duration-300" 
            x-transition:enter-start="opacity-0" 
            x-transition:enter-end="opacity-100" 
            x-transition:leave="transition ease-in duration-200" 
            x-transition:leave-start="opacity-100" 
            x-transition:leave-end="opacity-0" 
            @click="showModal = false" 
            class="absolute inset-0 bg-black/90 backdrop-blur-md"
        ></div>

        <!-- Modal Content Box -->
        <div 
            x-show="showModal" 
            x-transition:enter="transition ease-out duration-300" 
            x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" 
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave="transition ease-in duration-200" 
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
            x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95" 
            class="bg-slate-900 border border-white/10 rounded-t-3xl sm:rounded-3xl w-full sm:max-w-4xl relative z-10 shadow-[0_0_50px_rgba(168,85,247,0.3)] flex flex-col sm:flex-row overflow-hidden max-h-[90vh]"
        >
            <!-- Close Button -->
            <button @click="showModal = false" class="absolute top-4 right-4 z-20 bg-black/50 backdrop-blur border border-white/10 hover:bg-black rounded-full w-10 h-10 flex items-center justify-center text-slate-300 hover:text-white transition-colors cursor-pointer">
                ✕
            </button>

            <!-- Image Side -->
            <div class="sm:w-1/2 h-64 sm:h-auto bg-slate-950 relative flex-shrink-0 flex items-center justify-center border-b sm:border-b-0 sm:border-r border-white/5">
                <template x-if="activeMerch && activeMerch.image_url">
                    <img :src="activeMerch.image_url" :alt="activeMerch.name" class="w-full h-full object-cover">
                </template>
                <template x-if="activeMerch && !activeMerch.image_url">
                    <div class="text-7xl opacity-20">🛍️</div>
                </template>
            </div>

            <!-- Details Side -->
            <div class="p-6 sm:p-10 flex-1 flex flex-col overflow-y-auto">
                <template x-if="activeMerch">
                    <div class="space-y-6">
                        <div>
                            <span class="inline-block px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-bold uppercase tracking-widest mb-3">
                                Official Merchandise
                            </span>
                            <h3 class="text-3xl font-black text-slate-100 mb-2 leading-tight" x-text="activeMerch.name"></h3>
                            <p class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400" x-text="activeMerch.price"></p>
                        </div>
                        
                        <div class="h-px bg-white/10"></div>
                        
                        <div>
                            <p class="text-xs text-slate-500 uppercase tracking-widest font-bold mb-3">Deskripsi Produk</p>
                            <p class="text-sm text-slate-300 leading-relaxed" x-text="activeMerch.description"></p>
                        </div>
                    </div>
                </template>

                <div class="mt-8 pt-4 sm:pt-8 mt-auto space-y-3">
                    <template x-if="activeMerch">
                        <a :href="activeMerch.wa_url" target="_blank" rel="noopener noreferrer" class="w-full py-4 bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 rounded-2xl font-black text-white text-center transition-all flex items-center justify-center gap-3 shadow-[0_0_30px_rgba(168,85,247,0.3)] hover:shadow-[0_0_40px_rgba(168,85,247,0.5)] text-sm uppercase tracking-wider">
                            <span>Beli Sekarang via WhatsApp</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
