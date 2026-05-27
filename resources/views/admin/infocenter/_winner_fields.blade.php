{{-- Reusable fields for Winner form --}}
@php 
    $w = $winner ?? null; 
    $prefix = isset($winner) ? 'edit-' . $winner->id . '-' : 'add-';
@endphp

<div>
    <label for="{{ $prefix }}rank" class="block text-xs text-slate-400 font-semibold mb-1">Prestasi *</label>
    <input type="text" id="{{ $prefix }}rank" name="rank" value="{{ old('rank', $w?->rank) }}" required class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-yellow-500 transition-all" placeholder="🏆 Juara 1" autocomplete="off">
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

<div>
    <label for="{{ $prefix }}image" class="block text-xs text-slate-400 font-semibold mb-1">Foto <span class="text-slate-600">(opsional, maks. 2MB)</span></label>
    @if($w?->image_path)
    <div class="mb-2 flex items-center gap-3">
        <img src="{{ $w->image_url }}" class="h-10 w-10 object-cover rounded-full border border-slate-700">
        <span class="text-xs text-slate-500">Upload baru untuk mengganti.</span>
    </div>
    @endif
    <input type="file" id="{{ $prefix }}image" name="image" accept="image/*" class="w-full bg-slate-950 border border-slate-700 text-slate-400 rounded-xl px-4 py-2.5 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-yellow-600 file:text-white file:text-xs file:cursor-pointer">
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
