{{-- Reusable fields for Timeline form --}}
@php $t = $timeline ?? null; @endphp

<div>
    <label class="block text-xs text-slate-400 font-semibold mb-1">Tahun *</label>
    <input type="text" name="year" value="{{ old('year', $t?->year) }}" required maxlength="10" class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-cyan-500 transition-all" placeholder="2026">
</div>

<div>
    <label class="block text-xs text-slate-400 font-semibold mb-1">Judul / Nama SPECTA *</label>
    <input type="text" name="title" value="{{ old('title', $t?->title) }}" required class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-cyan-500 transition-all" placeholder="SPECTA XXI: REVELIORA">
</div>

<div>
    <label class="block text-xs text-slate-400 font-semibold mb-1">Subtitle / Tema</label>
    <input type="text" name="subtitle" value="{{ old('subtitle', $t?->subtitle) }}" class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-cyan-500 transition-all" placeholder="Celestial Treasure">
</div>

<div class="md:col-span-2">
    <label class="block text-xs text-slate-400 font-semibold mb-1">Deskripsi / Cerita *</label>
    <textarea name="description" rows="3" required class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-cyan-500 transition-all resize-none" placeholder="Ringkasan kisah dan pencapaian SPECTA tahun ini...">{{ old('description', $t?->description) }}</textarea>
</div>

<div class="md:col-span-2" x-data="{ preview: '{{ $t?->image_url ?? '' }}' }">
    <label class="block text-xs text-slate-400 font-semibold mb-1">Foto / Banner (jpg/png/webp, maks. 3MB)</label>
    <div class="space-y-3">
        <div x-show="preview" class="relative rounded-xl overflow-hidden border border-slate-700 bg-slate-950">
            <img :src="preview" class="w-full h-44 object-contain">
            <button type="button" @click="preview = null; $el.closest('[x-data]').querySelector('input[type=file]').value = ''" class="absolute top-2 right-2 bg-slate-900/80 hover:bg-red-900/80 border border-slate-600 hover:border-red-500 rounded-lg p-1 text-slate-400 hover:text-red-400 transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <input type="file" name="image" accept="image/*" class="w-full bg-slate-950 border border-slate-700 text-slate-400 rounded-xl px-4 py-2.5 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-cyan-600 file:text-white file:text-xs file:cursor-pointer" @change="const f=$event.target.files[0]; if(f){ if(f.size > 3 * 1024 * 1024) { alert('Ukuran file terlalu besar! Maksimal 3MB.'); $event.target.value=''; preview='{{ $t?->image_url ?? '' }}'; return; } const r=new FileReader();r.onload=e=>preview=e.target.result;r.readAsDataURL(f)} else { preview='{{ $t?->image_url ?? '' }}'; }">
        @if($t?->image_path)
        <p class="text-xs text-slate-500">Upload baru untuk mengganti foto saat ini.</p>
        @endif
    </div>
</div>

<div class="flex items-center gap-6 flex-wrap">
    <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-300">
        <input type="checkbox" name="is_current" value="1" {{ old('is_current', $t?->is_current) ? 'checked' : '' }} class="w-4 h-4 accent-cyan-500">
        <span>Tahun Berjalan <span class="text-slate-500 text-xs">(akan disorot di halaman utama)</span></span>
    </label>
    <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-300">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $t?->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-cyan-500">
        Tampilkan di halaman utama
    </label>
    <div>
        <label class="block text-xs text-slate-400 font-semibold mb-1">Urutan</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $t?->sort_order ?? 0) }}" class="w-20 bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-3 py-2 text-sm outline-none" min="0">
    </div>
</div>
