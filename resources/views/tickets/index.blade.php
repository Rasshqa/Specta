@extends('layouts.app')

@section('title', 'Beli Tiket – SPECTA XXI')

@section('content')
<div class="min-h-screen bg-slate-950 flex flex-col pt-24 px-4 pb-12 relative overflow-hidden">
    {{-- Background Ornaments --}}
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[100px] pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-cyan-600/10 rounded-full blur-[100px] pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

    <div class="max-w-4xl w-full mx-auto relative z-10">
        
        <div class="text-center mb-12" data-aos="fade-up">
            <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight mb-3">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">Pilih Tiket Kamu</span>
            </h1>
            <p class="text-sm md:text-base text-slate-300 leading-relaxed max-w-xl mx-auto">
                Amankan tempatmu di acara paling spektakuler tahun ini. Kuota terbatas!
            </p>
        </div>

        @if($errors->any())
        <div class="bg-red-900/40 border border-red-500/40 text-red-300 p-5 rounded-2xl mb-8" data-aos="fade-down">
            <p class="font-bold mb-2">Terjadi Kesalahan:</p>
            <ul class="list-disc list-inside text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($tickets as $ticket)
            <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)] flex flex-col transform hover:-translate-y-2 transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="mb-6">
                    <span class="inline-block px-3 py-1 rounded-full bg-purple-500/20 border border-purple-500/30 text-purple-300 text-xs font-bold uppercase tracking-widest mb-3">
                        Tersedia {{ $ticket->remaining_quota }} Tiket
                    </span>
                    <h2 class="text-xl font-semibold text-slate-100 mb-2">{{ $ticket->ticket_name }}</h2>
                    <p class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400">
                        Rp {{ number_format($ticket->price, 0, ',', '.') }}
                    </p>
                </div>
                
                <form method="POST" action="{{ route('ticket.checkout') }}" class="mt-auto space-y-4" x-data="{ quantity: 1 }">
                    @csrf
                    <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                    
                    <div>
                        <label class="block text-xs text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Nama Lengkap</label>
                        <input type="text" name="buyer_name" required value="{{ old('buyer_name') }}" class="w-full bg-slate-900/50 border border-slate-700/50 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-purple-500/50 transition-colors">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Email</label>
                            <input type="email" name="buyer_email" required value="{{ old('buyer_email') }}" class="w-full bg-slate-900/50 border border-slate-700/50 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-purple-500/50 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 uppercase tracking-widest mb-1.5 ml-1">WhatsApp</label>
                            <input type="text" name="buyer_whatsapp" required value="{{ old('buyer_whatsapp') }}" placeholder="08..." class="w-full bg-slate-900/50 border border-slate-700/50 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-purple-500/50 transition-colors">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Asal / Kelas</label>
                            <input type="text" name="buyer_class" required value="{{ old('buyer_class') }}" placeholder="Masyarakat Umum / XII IPA 1" class="w-full bg-slate-900/50 border border-slate-700/50 rounded-xl px-4 py-3 text-sm text-slate-200 focus:outline-none focus:border-purple-500/50 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Jumlah</label>
                            <div class="flex items-center bg-slate-900/50 border border-slate-700/50 rounded-xl overflow-hidden">
                                <button type="button" @click="quantity > 1 ? quantity-- : null" class="px-4 py-3 text-slate-400 hover:text-purple-400 hover:bg-slate-800 transition-colors">-</button>
                                <input type="number" name="quantity" min="1" max="5" x-model="quantity" readonly class="w-full bg-transparent text-center text-sm text-slate-200 focus:outline-none pointer-events-none">
                                <button type="button" @click="quantity < 5 && quantity < {{ $ticket->remaining_quota }} ? quantity++ : null" class="px-4 py-3 text-slate-400 hover:text-cyan-400 hover:bg-slate-800 transition-colors">+</button>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-500 hover:to-blue-500 text-white font-bold py-3.5 rounded-xl shadow-[0_0_20px_rgba(168,85,247,0.4)] transition-all transform hover:scale-[1.02] mt-4">
                        Beli Tiket
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
