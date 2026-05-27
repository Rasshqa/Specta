@extends('layouts.app')

@section('title', 'Admin Dashboard – SPECTA XXI')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-100" x-data="{ sidebarOpen: false }">

    {{-- Sidebar --}}
    <aside
        id="admin-sidebar"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900/95 backdrop-blur-xl border-r border-slate-800/60 flex flex-col shadow-2xl shadow-purple-900/10 transition-transform duration-300"
        :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }"
        x-cloak
    >
        {{-- Brand --}}
        <div class="flex-shrink-0 px-6 py-6 border-b border-slate-800/60">
            <p class="text-xs text-slate-500 uppercase tracking-widest mb-1">Control Panel</p>
            <h2 class="text-xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400 tracking-widest">SPECTA XXI</h2>
            <p class="text-xs text-slate-500 mt-0.5">REVELIORA Admin</p>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            @php
                $navItems = [
                    ['icon'=>'🏠','label'=>'Dashboard','route'=>'admin.dashboard'],
                    ['icon'=>'📋','label'=>'Transaksi','route'=>'admin.transactions'],
                    ['icon'=>'🛍️','label'=>'Merchandise','route'=>'admin.merchandises'],
                ];
            @endphp
            @foreach($navItems as $item)
            <a
                href="{{ route($item['route']) }}"
                id="nav-{{ $item['route'] }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                    {{ request()->routeIs($item['route'])
                        ? 'bg-purple-600/30 text-purple-200 border border-purple-500/30'
                        : 'text-slate-400 hover:text-slate-200 hover:bg-slate-800/60' }}"
            >
                <span class="text-base">{{ $item['icon'] }}</span>
                {{ $item['label'] }}
            </a>
            @endforeach
        </nav>

        {{-- Footer --}}
        <div class="flex-shrink-0 px-6 py-5 border-t border-slate-800/60">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    id="btn-logout"
                    type="submit"
                    class="w-full flex items-center gap-2 text-sm text-red-400 hover:text-red-300 font-medium transition-colors"
                >
                    <span>🚪</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- Sidebar overlay (mobile) --}}
    <div
        class="fixed inset-0 z-40 bg-black/60 backdrop-blur-sm lg:hidden"
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        x-cloak
    ></div>

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
                <p class="text-sm text-slate-400">{{ auth()->user()->name }}</p>
                <div class="w-8 h-8 bg-purple-600/40 border border-purple-500/40 rounded-full flex items-center justify-center text-xs font-bold text-purple-300">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 px-6 py-8">

            {{-- Flash messages --}}
            @if(session('success'))
            <div class="mb-6 bg-green-900/40 border border-green-500/40 text-green-300 text-sm px-5 py-4 rounded-2xl flex items-center gap-3">
                <span>✅</span> {{ session('success') }}
            </div>
            @endif

            <div class="mb-8" data-aos="fade-right">
                <h1 class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">Dashboard</h1>
                <p class="text-slate-500 text-sm mt-1">Ringkasan operasional SPECTA XXI: REVELIORA</p>
            </div>

            {{-- Stats grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-10">
                @php
                    $statCards = [
                        ['label'=>'Total Transaksi','value'=> $stats['total_transactions'],'icon'=>'📊','color'=>'purple'],
                        ['label'=>'Menunggu Bayar','value'=> $stats['pending_transactions'],'icon'=>'⏳','color'=>'yellow'],
                        ['label'=>'Terkonfirmasi','value'=> $stats['success_transactions'],'icon'=>'✅','color'=>'green'],
                        ['label'=>'Tiket Terjual','value'=> $stats['tickets_sold'],'icon'=>'🎟️','color'=>'cyan'],
                    ];
                @endphp
                @foreach($statCards as $card)
                <div class="bg-slate-900/60 backdrop-blur-sm border border-slate-800/60 rounded-2xl p-5 hover:border-{{ $card['color'] }}-500/40 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs text-slate-500 uppercase tracking-widest">{{ $card['label'] }}</p>
                        <span class="text-xl">{{ $card['icon'] }}</span>
                    </div>
                    <p class="text-3xl font-black text-slate-100">{{ number_format($card['value']) }}</p>
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
                    <table class="w-full text-sm">
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
                                <td class="px-6 py-4 font-mono text-xs text-purple-300">{{ $order->invoice_number }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-slate-200">{{ $order->buyer_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $order->buyer_class }}</p>
                                </td>
                                <td class="px-6 py-4 text-slate-300">{{ $order->ticket->ticket_name }} ×{{ $order->quantity }}</td>
                                <td class="px-6 py-4 font-semibold text-slate-200">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    @php
                                        $badgeMap = [
                                            'pending' => 'bg-yellow-900/40 text-yellow-400 border-yellow-600/40',
                                            'success' => 'bg-green-900/40 text-green-400 border-green-600/40',
                                            'expired' => 'bg-red-900/40 text-red-400 border-red-600/40',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold border {{ $badgeMap[$order->status] ?? '' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
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
</div>
@endsection
