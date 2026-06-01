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
                ['icon'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M10 20v-6h4v6h5v-8h3L12 3L2 12h3v8z"/></svg>','label'=>'Dashboard','route'=>'admin.dashboard'],
                ['icon'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 3h-4.18C14.4 1.84 13.3 1 12 1s-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2M7 8h2v4H8V9H7zm3 9v1H7v-.92L9 15H7v-1h2.25c.41 0 .75.34.75.75c0 .2-.08.39-.21.52L8.12 17zm1-13c0-.55.45-1 1-1s1 .45 1 1s-.45 1-1 1s-1-.45-1-1m6 13h-5v-2h5zm0-6h-5V9h5z"/></svg>','label'=>'Transaksi','route'=>'admin.transactions'],
                ['icon'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M19 6h-2c0-2.8-2.2-5-5-5S7 3.2 7 6H5c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2m-7-3c1.7 0 3 1.3 3 3H9c0-1.7 1.3-3 3-3m7 17H5V8h14zm-7-8c-1.7 0-3-1.3-3-3H7c0 2.8 2.2 5 5 5s5-2.2 5-5h-2c0 1.7-1.3 3-3 3"/></svg>','label'=>'Merchandise','route'=>'admin.merchandises'],
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
            <span class="text-base">{!! $item['icon'] !!}</span>
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
                ['icon'=>'📅','label'=>'Timeline','route'=>'admin.infocenter.timelines'],
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
            <span class="text-base">{!! $item['icon'] !!}</span>
            {{ $item['label'] }}
        </a>
        @endforeach
    </nav>

    {{-- Footer --}}
    <div class="flex-shrink-0 px-6 py-5 border-t border-slate-800/60 space-y-3">
        <a href="{{ url('/') }}" class="w-full flex items-center gap-2 text-sm text-cyan-400 hover:text-cyan-300 font-medium transition-colors">
            <span>🌐</span> Kembali ke Web
        </a>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                id="btn-logout"
                type="submit"
                class="w-full flex items-center gap-2 text-sm text-red-400 hover:text-red-300 font-medium transition-colors"
            >
                <span><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="m17 7l-1.41 1.41L18.17 11H8v2h10.17l-2.58 2.58L17 17l5-5M4 5h8V3H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h8v-2H4z"/></svg></span> Keluar
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
