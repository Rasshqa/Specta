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
                    <span>📱</span> Scan Tiket QR
                </button>
            </div>

            {{-- Stats grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-10">
                @php
                    $statCards = [
                        ['label'=>'Transaksi','value'=> $stats['total_transactions'],'icon'=>'📊','color'=>'purple'],
                        ['label'=>'Pending','value'=> $stats['pending_transactions'],'icon'=>'⏳','color'=>'yellow'],
                        ['label'=>'Sukses','value'=> $stats['success_transactions'],'icon'=>'✅','color'=>'green'],
                        ['label'=>'Tiket Terjual','value'=> $stats['tickets_sold'],'icon'=>'🎟️','color'=>'cyan'],
                        ['label'=>'QR Generated','value'=> $stats['qr_generated'],'icon'=>'🎫','color'=>'indigo'],
                        ['label'=>'QR Scanned','value'=> $stats['qr_scanned'],'icon'=>'📱','color'=>'emerald'],
                    ];
                @endphp
                @foreach($statCards as $card)
                <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800/60 rounded-2xl p-4 sm:p-5 hover:border-{{ $card['color'] }}-500/40 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                    <div class="flex items-center justify-between mb-2 sm:mb-3">
                        <p class="text-[10px] sm:text-xs text-slate-500 uppercase tracking-wider sm:tracking-widest">{{ $card['label'] }}</p>
                        <span class="text-lg sm:text-xl">{{ $card['icon'] }}</span>
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
                            <div class="flex justify-between text-sm mb-1.5">
                                <span class="text-slate-300 font-medium">{{ $ticket->ticket_name }}</span>
                                <span class="text-slate-400">{{ $sold }}/{{ $ticket->quota }}</span>
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
                                            'pending' => 'bg-yellow-900/40 text-yellow-400 border-yellow-600/40',
                                            'success' => 'bg-green-900/40 text-green-400 border-green-600/40',
                                            'expired' => 'bg-red-900/40 text-red-400 border-red-600/40',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] sm:text-xs font-semibold border {{ $badgeMap[$order->status] ?? '' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4" data-label="Aksi">
                                    @if($order->isPending())
                                    <div class="flex items-center gap-2">
                                        <form method="POST" action="{{ route('admin.transaction.confirm', $order->invoice_number) }}">
                                            @csrf
                                            <button
                                                id="btn-confirm-{{ $order->id }}"
                                                type="submit"
                                                class="text-xs bg-green-600/20 text-green-400 border border-green-600/30 hover:bg-green-600/40 px-3 py-1.5 rounded-lg transition-all"
                                                onclick="return confirm('Konfirmasi pembayaran {{ $order->invoice_number }}?')"
                                            >Konfirmasi</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.transaction.expire', $order->invoice_number) }}">
                                            @csrf
                                            <button
                                                id="btn-expire-{{ $order->id }}"
                                                type="submit"
                                                class="text-xs bg-red-600/20 text-red-400 border border-red-600/30 hover:bg-red-600/40 px-3 py-1.5 rounded-lg transition-all"
                                                onclick="return confirm('Tandai transaksi ini sebagai expired?')"
                                            >Expire</button>
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
            <button @click="closeModal()" class="absolute top-4 right-4 text-slate-400 hover:text-white">✕</button>
            
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
