<!-- Updated alerts component for one‑time display and mobile responsiveness -->
@if(session('success') || session('error') || session('status') || $errors->any())
<div
    x-data="{
        show: true,
        init() {
            // If the alert has been dismissed before, keep it hidden
            if (localStorage.getItem('alert_dismissed') === 'true') {
                this.show = false;
            }
        },
        dismiss() {
            this.show = false;
            // Remember dismissal for this browser session
            localStorage.setItem('alert_dismissed', 'true');
        }
    }"
    x-show="show"
    x-cloak
    class="fixed top-4 right-4 left-4 md:left-auto md:right-6 z-[9999] w-full max-w-sm md:max-w-sm"
>
    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-slate-900/95 backdrop-blur-xl border border-emerald-500/50 p-4 rounded-2xl shadow-2xl flex items-start gap-4">
        <div class="flex-shrink-0 w-10 h-10 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div class="flex-1 pt-0.5">
            <p class="text-sm font-bold text-slate-100">Berhasil!</p>
            <p class="text-xs text-slate-400 mt-1">{{ session('success') }}</p>
        </div>
        <button @click="dismiss()" class="text-slate-500 hover:text-slate-300 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </div>
    @endif

    {{-- Error Message --}}
    @if(session('error'))
    <div class="bg-slate-900/95 backdrop-blur-xl border border-rose-500/50 p-4 rounded-2xl shadow-2xl flex items-start gap-4">
        <div class="flex-shrink-0 w-10 h-10 bg-rose-500/10 border border-rose-500/20 rounded-xl flex items-center justify-center text-rose-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <div class="flex-1 pt-0.5">
            <p class="text-sm font-bold text-slate-100">Gagal!</p>
            <p class="text-xs text-slate-400 mt-1">{{ session('error') }}</p>
        </div>
        <button @click="show = false" class="text-slate-500 hover:text-slate-300 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

    {{-- Status Message --}}
    @if(session('status'))
    <div class="bg-slate-900/95 backdrop-blur-xl border border-blue-500/50 p-4 rounded-2xl shadow-2xl flex items-start gap-4">
        <div class="flex-shrink-0 w-10 h-10 bg-blue-500/10 border border-blue-500/20 rounded-xl flex items-center justify-center text-blue-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="flex-1 pt-0.5">
            <p class="text-sm font-bold text-slate-100">Info</p>
            <p class="text-xs text-slate-400 mt-1">{{ session('status') }}</p>
        </div>
        <button @click="show = false" class="text-slate-500 hover:text-slate-300 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
    <div class="bg-slate-900/95 backdrop-blur-xl border border-cyan-500/50 p-4 rounded-2xl shadow-2xl flex items-start gap-4">
        <div class="flex-shrink-0 w-10 h-10 bg-cyan-500/10 border border-cyan-500/20 rounded-xl flex items-center justify-center text-cyan-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
        </div>
        <div class="flex-1 pt-0.5">
            <p class="text-sm font-bold text-slate-100">Perhatian!</p>
            <ul class="text-xs text-slate-400 mt-1 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        <button @click="show = false" class="text-slate-500 hover:text-slate-300 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
    @endif
</div>
@endif
