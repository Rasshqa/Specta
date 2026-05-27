@extends('layouts.app')

@section('title', 'Daftar Transaksi – SPECTA XXI')

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
                <h1 class="font-bold text-slate-200">Semua Transaksi</h1>
            </div>
            <div class="flex items-center gap-3">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                <p class="text-sm text-slate-400 hidden sm:inline">{{ auth()->user()->name }}</p>
                <div class="w-8 h-8 bg-purple-600/40 border border-purple-500/40 rounded-full flex items-center justify-center text-xs font-bold text-purple-300">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

    <main class="flex-1 px-6 py-8">
        {{-- Filters & Search --}}
        <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800/60 rounded-2xl p-5 mb-6" data-aos="fade-down">
            <form method="GET" action="{{ route('admin.transactions') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-xs text-slate-500 uppercase tracking-widest mb-2 font-semibold">Cari Invoice / Nama / Email</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik kata kunci..." class="w-full bg-slate-800/50 border border-slate-700/50 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none focus:border-purple-500/50 transition-colors">
                </div>
                <div class="md:w-48">
                    <label class="block text-xs text-slate-500 uppercase tracking-widest mb-2 font-semibold">Filter Status</label>
                    <select name="status" class="w-full bg-slate-800/50 border border-slate-700/50 rounded-xl px-4 py-2.5 text-sm text-slate-200 focus:outline-none focus:border-purple-500/50 transition-colors appearance-none">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Success</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <div class="md:self-end flex gap-2">
                    <button type="submit" class="bg-purple-600/80 hover:bg-purple-600 text-purple-100 font-semibold px-6 py-2.5 rounded-xl transition-all">Filter</button>
                    @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('admin.transactions') }}" class="bg-slate-800 hover:bg-slate-700 text-slate-300 font-semibold px-4 py-2.5 rounded-xl transition-all flex items-center justify-center" title="Reset">✕</a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Table --}}
        <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800/60 rounded-2xl overflow-hidden" data-aos="fade-up">
            <div class="overflow-x-auto">
                <table class="w-full text-sm admin-table-card">
                    <thead>
                        <tr class="border-b border-slate-800/60 bg-slate-900/80">
                            <th class="px-6 py-4 text-left text-xs text-slate-500 uppercase tracking-widest font-semibold">Invoice & Waktu</th>
                            <th class="px-6 py-4 text-left text-xs text-slate-500 uppercase tracking-widest font-semibold">Data Pembeli</th>
                            <th class="px-6 py-4 text-left text-xs text-slate-500 uppercase tracking-widest font-semibold">Pesanan</th>
                            <th class="px-6 py-4 text-left text-xs text-slate-500 uppercase tracking-widest font-semibold">Status</th>
                            <th class="px-6 py-4 text-left text-xs text-slate-500 uppercase tracking-widest font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/40">
                        @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-800/30 transition-colors">
                            <td class="px-6 py-4" data-label="Invoice">
                                <p class="font-mono text-sm text-purple-300 font-bold">{{ $trx->invoice_number }}</p>
                                <p class="text-xs text-slate-500 mt-1">{{ $trx->created_at->format('d M Y, H:i') }}</p>
                            </td>
                            <td class="px-6 py-4" data-label="Pembeli">
                                <p class="font-semibold text-slate-200">{{ $trx->buyer_name }} <span class="text-xs text-slate-500 font-normal ml-1">({{ $trx->buyer_class }})</span></p>
                                <p class="text-xs text-slate-400 mt-0.5">{{ $trx->buyer_email }}</p>
                                <p class="text-xs text-green-400/80 mt-0.5 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                    {{ $trx->buyer_whatsapp }}
                                </p>
                            </td>
                            <td class="px-6 py-4" data-label="Pesanan">
                                <p class="text-sm font-semibold text-cyan-300">{{ $trx->ticket->ticket_name }} <span class="text-slate-300 ml-1">×{{ $trx->quantity }}</span></p>
                                <p class="text-sm font-bold text-slate-200 mt-1">Rp {{ number_format($trx->total_price, 0, ',', '.') }}</p>
                                <p class="text-xs text-yellow-400 mt-0.5">Kode: {{ $trx->unique_code }}</p>
                            </td>
                            <td class="px-6 py-4" data-label="Status">
                                @php
                                    $badgeMap = [
                                        'pending' => 'bg-yellow-900/40 text-yellow-400 border-yellow-600/40',
                                        'success' => 'bg-green-900/40 text-green-400 border-green-600/40',
                                        'expired' => 'bg-red-900/40 text-red-400 border-red-600/40',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $badgeMap[$trx->status] ?? '' }}">
                                    {{ ucfirst($trx->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4" data-label="Aksi">
                                @if($trx->isPending())
                                <div class="flex flex-col gap-2 w-full md:w-max md:ml-auto">
                                    <form method="POST" action="{{ route('admin.transaction.confirm', $trx->invoice_number) }}">
                                        @csrf
                                        <button class="w-full text-xs bg-green-600/20 text-green-400 border border-green-600/30 hover:bg-green-600/40 px-3 py-1.5 rounded-lg transition-all cursor-pointer" onclick="return confirm('Konfirmasi pembayaran {{ $trx->invoice_number }}?')">✅ Confirm</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.transaction.expire', $trx->invoice_number) }}">
                                        @csrf
                                        <button class="w-full text-xs bg-red-600/20 text-red-400 border border-red-600/30 hover:bg-red-600/40 px-3 py-1.5 rounded-lg transition-all cursor-pointer" onclick="return confirm('Tandai expired?')">❌ Expire</button>
                                    </form>
                                </div>
                                @elseif($trx->isSuccess())
                                <div class="text-xs text-slate-500">
                                    <p>{{ $trx->ticketCodes->count() }} QR Generated</p>
                                    <p class="mt-0.5">{{ $trx->ticketCodes->where('is_scanned', true)->count() }} Scanned</p>
                                </div>
                                @else
                                <span class="text-slate-600 text-xs">—</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="text-4xl mb-3">📭</div>
                                <p class="text-slate-400 font-medium">Tidak ada transaksi ditemukan.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-slate-800/60 bg-slate-900/40">
                {{ $transactions->links() }}
            </div>
            @endif
        </div>
    </main>
    </div>
</div>
@endsection
