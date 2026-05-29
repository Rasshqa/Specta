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
                ['icon'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 8H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h1v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h3l5 4V4zm9.5 4c0 1.71-.96 3.26-2.5 4V8c1.53.75 2.5 2.3 2.5 4"/></svg>','label'=>'Pengumuman','route'=>'admin.informations.index'],
                ['icon'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M15.58 16.8L12 14.5l-3.58 2.3l1.08-4.12L6.21 10l4.25-.26L12 5.8l1.54 3.94l4.25.26l-3.29 2.68M20 12a2 2 0 0 1 2-2V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v4a2 2 0 0 1 2 2a2 2 0 0 1-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 1-2-2"/></svg>','label'=>'Eskul SMANSA','route'=>'admin.infocenter.eskul'],
                ['icon'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M18 2c-.9 0-2 1-2 2H8c0-1-1.1-2-2-2H2v9c0 1 1 2 2 2h2.2c.4 2 1.7 3.7 4.8 4v2.08C8 19.54 8 22 8 22h8s0-2.46-3-2.92V17c3.1-.3 4.4-2 4.8-4H20c1 0 2-1 2-2V2zM6 11H4V4h2zm14 0h-2V4h2z"/></svg>','label'=>'Pemenang Lomba','route'=>'admin.infocenter.winners'],
                ['icon'=>'<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M4 4h3l2-2h6l2 2h3a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2m8 3a5 5 0 0 0-5 5a5 5 0 0 0 5 5a5 5 0 0 0 5-5a5 5 0 0 0-5-5m0 2a3 3 0 0 1 3 3a3 3 0 0 1-3 3a3 3 0 0 1-3-3a3 3 0 0 1 3-3"/></svg>','label'=>'Dokumentasi','route'=>'admin.infocenter.docs'],
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
            <span><svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M16.36 14c.08-.66.14-1.32.14-2s-.06-1.34-.14-2h3.38c.16.64.26 1.31.26 2s-.1 1.36-.26 2m-5.15 5.56c.6-1.11 1.06-2.31 1.38-3.56h2.95a8.03 8.03 0 0 1-4.33 3.56M14.34 14H9.66c-.1-.66-.16-1.32-.16-2s.06-1.35.16-2h4.68c.09.65.16 1.32.16 2s-.07 1.34-.16 2M12 19.96c-.83-1.2-1.5-2.53-1.91-3.96h3.82c-.41 1.43-1.08 2.76-1.91 3.96M8 8H5.08A7.92 7.92 0 0 1 9.4 4.44C8.8 5.55 8.35 6.75 8 8m-2.92 8H8c.35 1.25.8 2.45 1.4 3.56A8 8 0 0 1 5.08 16m-.82-2C4.1 13.36 4 12.69 4 12s.1-1.36.26-2h3.38c-.08.66-.14 1.32-.14 2s.06 1.34.14 2M12 4.03c.83 1.2 1.5 2.54 1.91 3.97h-3.82c.41-1.43 1.08-2.77 1.91-3.97M18.92 8h-2.95a15.7 15.7 0 0 0-1.38-3.56c1.84.63 3.37 1.9 4.33 3.56M12 2C6.47 2 2 6.5 2 12a10 10 0 0 0 10 10a10 10 0 0 0 10-10A10 10 0 0 0 12 2"/></svg></span> Kembali ke Web
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
