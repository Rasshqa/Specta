{{-- Reusable fields for Winner form --}}
@php 
    $w = $winner ?? null; 
    $prefix = isset($winner) ? 'edit-' . $winner->id . '-' : 'add-';
@endphp

<div>
    <label for="{{ $prefix }}rank" class="block text-xs text-slate-400 font-semibold mb-1">Prestasi *</label>
    <input type="text" id="{{ $prefix }}rank" name="rank" value="{{ old('rank', $w?->rank) }}" required class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-500 transition-all" placeholder="<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M18 2c-.9 0-2 1-2 2H8c0-1-1.1-2-2-2H2v9c0 1 1 2 2 2h2.2c.4 2 1.7 3.7 4.8 4v2.08C8 19.54 8 22 8 22h8s0-2.46-3-2.92V17c3.1-.3 4.4-2 4.8-4H20c1 0 2-1 2-2V2zM6 11H4V4h2zm14 0h-2V4h2z"/></svg> Juara 1" autocomplete="off">
</div>

<div>
    <label for="{{ $prefix }}name" class="block text-xs text-slate-400 font-semibold mb-1">Nama Pemenang *</label>
    <input type="text" id="{{ $prefix }}name" name="name" value="{{ old('name', $w?->name) }}" required class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-500 transition-all" placeholder="Nama peserta / grup" autocomplete="name">
</div>

<div>
    <label for="{{ $prefix }}school" class="block text-xs text-slate-400 font-semibold mb-1">Asal Sekolah *</label>
    <input type="text" id="{{ $prefix }}school" name="school" value="{{ old('school', $w?->school) }}" required class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-500 transition-all" placeholder="SMP Negeri 1 Cianjur" autocomplete="organization">
</div>

<div>
    <label for="{{ $prefix }}category" class="block text-xs text-slate-400 font-semibold mb-1">Kategori Lomba *</label>
    <input type="text" id="{{ $prefix }}category" name="category" value="{{ old('category', $w?->category) }}" required class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-500 transition-all" placeholder="Lomba Band Pelajar (Middle Event)" autocomplete="off">
</div>

<div>
    <label for="{{ $prefix }}score" class="block text-xs text-slate-400 font-semibold mb-1">Nilai Akhir <span class="text-slate-600">(opsional)</span></label>
    <input type="text" id="{{ $prefix }}score" name="score" value="{{ old('score', $w?->score) }}" class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-500 transition-all" placeholder="94.5 Poin" autocomplete="off">
</div>

<div x-data="{ preview: '{{ $w?->image_url ?? '' }}' }">
    <label for="{{ $prefix }}image" class="block text-xs text-slate-400 font-semibold mb-1">Foto <span class="text-slate-600">(opsional, maks. 2MB)</span></label>
    <div class="space-y-3">
        <div x-show="preview" class="relative rounded-xl overflow-hidden border border-slate-700 bg-slate-950">
            <img :src="preview" class="w-full h-36 object-contain">
            <button type="button" @click="preview = null; $el.closest('[x-data]').querySelector('input[type=file]').value = ''" class="absolute top-2 right-2 bg-slate-900/80 hover:bg-red-900/80 border border-slate-600 hover:border-red-500 rounded-lg p-1 text-slate-400 hover:text-red-400 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <input type="file" id="{{ $prefix }}image" name="image" accept="image/*" class="w-full bg-slate-950 border border-slate-700 text-slate-400 rounded-xl px-4 py-2.5 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-yellow-600 file:text-white file:text-xs file:cursor-pointer" @change="const f=$event.target.files[0]; if(f){ if(f.size > 2 * 1024 * 1024) { alert('Ukuran file terlalu besar! Maksimal 2MB.'); $event.target.value=''; preview='{{ $w?->image_url ?? '' }}'; return; } const r=new FileReader();r.onload=e=>preview=e.target.result;r.readAsDataURL(f)} else { preview='{{ $w?->image_url ?? '' }}'; }">
    </div>
</div>

<div class="flex items-center gap-6">
    <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-300">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $w?->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-yellow-500">
        Tampilkan di halaman utama
    </label>
    <div>
        <label for="{{ $prefix }}sort_order" class="block text-xs text-slate-400 font-semibold mb-1">Urutan</label>
        <input type="number" id="{{ $prefix }}sort_order" name="sort_order" value="{{ old('sort_order', $w?->sort_order ?? 0) }}" class="w-20 bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-3 py-2 text-sm outline-none" min="0">
    </div>
</div>
