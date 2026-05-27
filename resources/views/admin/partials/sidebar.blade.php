{{-- Sidebar --}}
<aside
    id="admin-sidebar"
    class="fixed inset-y-0 left-0 z-50 w-64 bg-slate-900/95 backdrop-blur-xl border-r border-slate-800/60 flex flex-col shadow-2xl shadow-purple-900/10 transition-transform duration-300 lg:translate-x-0"
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

        {{-- Info Center Group --}}
        <div class="pt-4 pb-1">
            <p class="text-[10px] font-bold text-slate-600 uppercase tracking-[0.2em] px-4">Info Center</p>
        </div>
        @php
            $infoItems = [
                ['icon'=>'📢','label'=>'Pengumuman','route'=>'admin.informations.index'],
                ['icon'=>'🎟️','label'=>'Eskul SMANSA','route'=>'admin.infocenter.eskul'],
                ['icon'=>'🏆','label'=>'Pemenang Lomba','route'=>'admin.infocenter.winners'],
                ['icon'=>'📸','label'=>'Dokumentasi','route'=>'admin.infocenter.docs'],
            ];
        @endphp
        @foreach($infoItems as $item)
        <a
            href="{{ route($item['route']) }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                {{ request()->routeIs($item['route'])
                    ? 'bg-cyan-600/20 text-cyan-200 border border-cyan-500/20'
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
