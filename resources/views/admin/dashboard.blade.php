@extends('layouts.app')

@section('title', 'Admin Dashboard – SPECTA XXI')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-100" x-data="{ sidebarOpen: false }">

    {{-- Sidebar --}}
    @include('admin.partials.sidebar')

    {{-- Main content --}}
    <div class="lg:pl-64 flex flex-col min-h-screen">

        {{-- Topbar --}}
        <header class="sticky top-0 z-30 bg-slate-950/80 backdrop-blur-xl border-b border-slate-800/40 px-6 py-4 flex items-center justify-between">
            <button
                id="btn-toggle-sidebar"
                @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-xl text-slate-400 hover:text-slate-200 hover:bg-slate-800 transition-colors"
                aria-label="Toggle Sidebar"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div class="flex items-center gap-3 ml-auto">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <p class="text-sm text-slate-400 hidden sm:inline">{{ auth()->user()->name }}</p>
                <div class="w-8 h-8 bg-purple-600/40 border border-purple-500/40 rounded-full flex items-center justify-center text-xs font-bold text-purple-300">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 px-6 py-8">

            <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4" data-aos="fade-right">
                <div>
                    <h1 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">Dashboard</h1>
                    <p class="text-slate-500 text-sm mt-1">Ringkasan operasional SPECTA XXI: REVELIORA</p>
                </div>
                <button onclick="window.dispatchEvent(new CustomEvent('open-scanner'))" class="w-full sm:w-auto justify-center bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 text-white font-bold py-2.5 px-6 rounded-xl shadow-lg shadow-purple-900/30 flex items-center gap-2 transition-all">
                    <span><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M17 19H7V5h10m0-4H7c-1.11 0-2 .89-2 2v18a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2"/></svg></span> Scan Tiket QR
                </button>
            </div>

            {{-- Stats grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-10">
                @php
                    $statCards = [
                        ['label'=>'Transaksi','value'=> $stats['total_transactions'],'icon'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M22 21H2V3h2v16h2v-9h4v9h2V6h4v13h2v-5h4z"/></svg>','color'=>'purple'],
                        ['label'=>'Pending','value'=> $stats['pending_transactions'],'icon'=>'⏳','color'=>'yellow'],
                        ['label'=>'Sukses','value'=> $stats['success_transactions'],'icon'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10s10-4.5 10-10S17.5 2 12 2m-2 15l-5-5l1.41-1.41L10 14.17l7.59-7.59L19 8z"/></svg>','color'=>'green'],
                        ['label'=>'Tiket Terjual','value'=> $stats['tickets_sold'],'icon'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M15.58 16.8L12 14.5l-3.58 2.3l1.08-4.12L6.21 10l4.25-.26L12 5.8l1.54 3.94l4.25.26l-3.29 2.68M20 12a2 2 0 0 1 2-2V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2a2 2 0 0 1-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 1-2-2"/></svg>','color'=>'cyan'],
                        ['label'=>'QR Generated','value'=> $stats['qr_generated'],'icon'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M4 4a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2a2 2 0 0 1-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 1-2-2a2 2 0 0 1 2-2V6a2 2 0 0 0-2-2zm0 2h16v2.54c-1.24.71-2 2.03-2 3.46s.76 2.75 2 3.46V18H4v-2.54c1.24-.71 2-2.03 2-3.46s-.76-2.75-2-3.46z"/></svg>','color'=>'indigo'],
                        ['label'=>'QR Scanned','value'=> $stats['qr_scanned'],'icon'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M17 19H7V5h10m0-4H7c-1.11 0-2 .89-2 2v18a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2"/></svg>','color'=>'emerald'],
                    ];
                @endphp
                @foreach($statCards as $card)
                <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800/60 rounded-2xl p-4 sm:p-5 hover:border-{{ $card['color'] }}-500/40 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <p class="text-[10px] sm:text-xs text-slate-500 uppercase tracking-wider sm:tracking-widest">{{ $card['label'] }}</p>
                        <span class="text-lg sm:text-xl">{!! $card['icon'] !!}</span>
                    </div>
                    <p class="text-2xl sm:text-3xl font-black text-slate-100">{{ number_format($card['value']) }}</p>
                </div>
                @endforeach
            </div>

            {{-- Revenue + Quota row --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">

                {{-- Revenue card --}}
                <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800/60 rounded-2xl p-6" data-aos="fade-up">
                    <p class="text-xs text-slate-500 uppercase tracking-widest mb-2">Total Pendapatan (confirmed)</p>
                    <p class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">
                        Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}
                    </p>
                    <p class="text-xs text-slate-500 mt-3">Scan hari ini: <span class="text-green-400 font-bold">{{ $stats['scanned_today'] }}</span> tiket</p>
                </div>

                {{-- Quota per ticket type --}}
                <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800/60 rounded-2xl p-6" data-aos="fade-up" data-aos-delay="100">
                    <p class="text-xs text-slate-500 uppercase tracking-widest mb-4">Kuota Tiket</p>
                    <div class="space-y-4">
                        @foreach($tickets as $ticket)
                        @php
                            $sold = $ticket->quota - $ticket->remaining_quota;
                            $pct  = $ticket->quota > 0 ? round(($sold / $ticket->quota) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center text-sm mb-1.5">
                                <span class="text-slate-300 font-medium">{{ $ticket->ticket_name }}</span>
                                <div class="flex items-center gap-3">
                                    <span class="text-purple-400 font-bold font-mono text-xs">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                                    <button @click="openPriceModal({{ $ticket->id }}, {{ $ticket->price }}, {{ $ticket->quota }}, '{{ $ticket->ticket_name }}')" class="text-[10px] bg-purple-600/20 text-purple-400 hover:bg-purple-600/40 border border-purple-500/30 px-2 py-0.5 rounded transition-colors">Edit Tiket</button>
                                    <span class="text-slate-400 text-xs">{{ $sold }}/{{ $ticket->quota }}</span>
                                </div>
                            </div>
                            <div class="h-2 bg-slate-800 rounded-full overflow-hidden">
                                <div
                                    class="h-full bg-gradient-to-r from-purple-500 to-cyan-500 rounded-full transition-all duration-700"
                                    style="width: {{ $pct }}%"
                                ></div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Recent transactions table --}}
            <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800/60 rounded-2xl overflow-hidden" data-aos="fade-up">
                <div class="px-6 py-5 border-b border-slate-800/60 flex items-center justify-between">
                    <h2 class="font-bold text-slate-200">Transaksi Terbaru</h2>
                    <a href="{{ route('admin.transactions') }}" id="link-all-transactions" class="text-xs text-purple-400 hover:text-purple-300 font-semibold transition-colors">Lihat Semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm admin-table-card">
                        <thead>
                            <tr class="border-b border-slate-800/60">
                                <th class="px-6 py-3 text-left text-xs text-slate-500 uppercase tracking-widest font-semibold">Invoice</th>
                                <th class="px-6 py-3 text-left text-xs text-slate-500 uppercase tracking-widest font-semibold">Pembeli</th>
                                <th class="px-6 py-3 text-left text-xs text-slate-500 uppercase tracking-widest font-semibold">Tiket</th>
                                <th class="px-6 py-3 text-left text-xs text-slate-500 uppercase tracking-widest font-semibold">Total</th>
                                <th class="px-6 py-3 text-left text-xs text-slate-500 uppercase tracking-widest font-semibold">Status</th>
                                <th class="px-6 py-3 text-left text-xs text-slate-500 uppercase tracking-widest font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/40">
                            @forelse($recentOrders as $order)
                            <tr class="hover:bg-slate-800/30 transition-colors">
                                <td class="px-4 sm:px-6 py-3 sm:py-4 font-mono text-xs text-purple-300" data-label="Invoice">{{ $order->invoice_number }}</td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4" data-label="Pembeli">
                                    <p class="font-medium text-slate-200">{{ $order->buyer_name }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4 text-slate-300" data-label="Tiket">{{ $order->ticket->ticket_name }} ×{{ $order->quantity }}</td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4 font-semibold text-slate-200" data-label="Total">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4" data-label="Status">
                                    @php
                                        $badgeMap = [
                                            'PENDING_PROOF' => 'bg-cyan-900/40 text-cyan-400 border-cyan-600/40',
                                            'SUCCESS'       => 'bg-green-900/40 text-green-400 border-green-600/40',
                                            'REJECTED'      => 'bg-red-900/40 text-red-400 border-red-600/40',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-semibold border {{ $badgeMap[$order->status] ?? '' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4" data-label="Aksi">
                                    @if($order->isPending())
                                    <div class="flex items-center gap-2">
                                        <form method="POST" action="{{ route('admin.transaction.approve', $order->invoice_number) }}">
                                            @csrf
                                            <button
                                                id="btn-confirm-{{ $order->id }}"
                                                type="submit"
                                                class="text-xs bg-green-600/20 text-green-400 border border-green-600/30 hover:bg-green-600/40 px-3 py-1.5 rounded-lg transition-all"
                                                onclick="return confirm('Konfirmasi pembayaran {{ $order->invoice_number }}?')"
                                            >Konfirmasi</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.transaction.reject', $order->invoice_number) }}">
                                            @csrf
                                            <button
                                                id="btn-expire-{{ $order->id }}"
                                                type="submit"
                                                class="text-xs bg-red-600/20 text-red-400 border border-red-600/30 hover:bg-red-600/40 px-3 py-1.5 rounded-lg transition-all"
                                                onclick="return confirm('Tolak transaksi ini?')"
                                            >Tolak</button>
                                        </form>
                                    </div>
                                    @else
                                    <span class="text-slate-600 text-xs">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">Belum ada transaksi.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    {{-- Scanner Modal --}}
    <div x-data="qrScanner()" 
         @open-scanner.window="openModal()" 
         x-show="isOpen" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="closeModal()" class="bg-slate-900 border border-slate-800 w-full max-w-md rounded-3xl p-6 shadow-2xl relative">
            <button @click="closeModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"/></svg></button>
            
            <h2 class="text-xl font-bold mb-2 text-center">Scan Tiket QR</h2>
            <p class="text-xs text-slate-500 text-center mb-6">Arahkan kamera ke QR Code milik pengunjung.</p>
            
            <div id="reader" class="rounded-2xl overflow-hidden border-2 border-slate-700 bg-black aspect-square w-full"></div>
            
            {{-- Manual Input Fallback --}}
            <form @submit.prevent="processManualCode()" class="mt-4 flex gap-2">
                <input type="text" x-model="manualCode" placeholder="Ketik kode (Contoh: TRX-XXX)" class="flex-1 bg-slate-800 border border-slate-700 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500 font-mono uppercase transition-all">
                <button type="submit" class="bg-purple-600 hover:bg-purple-500 text-white px-5 py-2.5 rounded-xl font-bold transition-colors text-sm shadow-lg shadow-purple-900/20">Cek</button>
            </form>
            
            <div x-show="message" x-transition class="mt-4 p-4 rounded-xl text-center text-sm font-bold border" :class="isSuccess ? 'bg-green-900/40 text-green-400 border-green-500/30' : 'bg-red-900/40 text-red-400 border-red-500/30'">
                <span x-text="message"></span>
            </div>
        </div>
    </div>

    {{-- Edit Ticket Modal --}}
    <div x-data="{ 
            isOpen: false, 
            ticketId: '', 
            ticketName: '', 
            price: 0,
            quota: 0,
            init() {
                window.openPriceModal = this.openModal.bind(this);
            },
            openModal(id, currentPrice, currentQuota, name) {
                this.ticketId = id;
                this.price = currentPrice;
                this.quota = currentQuota;
                this.ticketName = name;
                this.isOpen = true;
            }
         }"
         @open-price-modal.window="openModal($event.detail.id, $event.detail.price, $event.detail.quota, $event.detail.name)"
         x-show="isOpen" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-sm"
         style="display: none;">
        
        <div @click.away="isOpen = false" class="bg-slate-900 border border-slate-800 w-full max-w-sm rounded-3xl p-6 shadow-2xl relative">
            <button @click="isOpen = false" class="absolute top-4 right-4 text-slate-400 hover:text-white"><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 6.41L17.59 5L12 10.59L6.41 5L5 6.41L10.59 12L5 17.59L6.41 19L12 13.41L17.59 19L19 17.59L13.41 12z"/></svg></button>
            
            <h2 class="text-xl font-bold mb-1">Edit Tiket</h2>
            <p class="text-sm text-slate-500 mb-5" x-text="ticketName"></p>
            
            <form :action="`/admin/ticket/${ticketId}/update`" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-xs text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Harga Tiket (Rp)</label>
                    <input type="number" name="price" x-model="price" min="0" required class="w-full bg-slate-800/60 border border-slate-700/60 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-purple-500/50 transition-colors">
                </div>
                <div class="mb-6">
                    <label class="block text-xs text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Total Kuota</label>
                    <input type="number" name="quota" x-model="quota" min="1" required class="w-full bg-slate-800/60 border border-slate-700/60 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-cyan-500/50 transition-colors">
                </div>
                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-500 hover:to-cyan-500 text-white font-bold py-3 rounded-xl shadow-lg shadow-purple-900/30 transition-all text-sm uppercase tracking-wider">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('qrScanner', () => ({
        isOpen: false,
        scanner: null,
        message: '',
        isSuccess: false,
        isScanning: false,
        manualCode: '',

        openModal() {
            this.isOpen = true;
            this.message = '';
            this.manualCode = '';
            this.startScanner();
        },

        closeModal() {
            this.isOpen = false;
            this.stopScanner();
        },

        startScanner() {
            if (this.scanner) return;
            
            // Allow small delay for DOM to render
            setTimeout(() => {
                this.scanner = new Html5Qrcode("reader");
                const config = { fps: 10, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };
                
                this.scanner.start({ facingMode: "environment" }, config, this.onScanSuccess.bind(this))
                    .catch(err => {
                        this.message = "Gagal mengakses kamera. Pastikan izin diberikan.";
                        this.isSuccess = false;
                    });
            }, 300);
        },

        stopScanner() {
            if (this.scanner) {
                this.scanner.stop().then(() => {
                    this.scanner.clear();
                    this.scanner = null;
                }).catch(console.error);
            }
        },

        processManualCode() {
            if (!this.manualCode.trim() || this.isScanning) return;
            this.onScanSuccess(this.manualCode.trim().toUpperCase());
            this.manualCode = '';
        },

        onScanSuccess(decodedText) {
            if (this.isScanning) return; // Prevent multiple scans at once
            this.isScanning = true;
            
            // Tunda scan berikutnya sebentar agar tidak spam
            setTimeout(() => { this.isScanning = false; }, 3000);

            // Fetch to backend
            fetch("{{ route('admin.scan') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ code: decodedText })
            })
            .then(res => res.json())
            .then(data => {
                this.message = data.message;
                this.isSuccess = data.success;
                
                // Jika sukses, mainkan bunyi (opsional) atau update stats
                if(data.success) {
                    setTimeout(() => location.reload(), 2000);
                }
            })
            .catch(err => {
                this.message = "Terjadi kesalahan jaringan.";
                this.isSuccess = false;
            });
        }
    }));
});
</script>
@endsection
