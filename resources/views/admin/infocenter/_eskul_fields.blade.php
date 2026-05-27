{{-- Reusable fields for EskulProfile form (add & edit) --}}
@php $e = $eskul ?? null; @endphp

<div>
    <label class="block text-xs text-slate-400 font-semibold mb-1">Nama Eskul *</label>
    <input type="text" name="name" value="{{ old('name', $e?->name) }}" required class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-purple-500 transition-all" placeholder="Paduan Suara (Choir)">
</div>

<div>
    <label class="block text-xs text-slate-400 font-semibold mb-1">Icon/Emoji</label>
    <input type="text" name="icon" value="{{ old('icon', $e?->icon ?? '🎯') }}" class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-purple-500 transition-all" placeholder="🎵" maxlength="10">
</div>

<div>
    <label class="block text-xs text-slate-400 font-semibold mb-1">Deskripsi Singkat *</label>
    <input type="text" name="description" value="{{ old('description', $e?->description) }}" required class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-purple-500 transition-all" placeholder="Harmoni vokal surgawi...">
</div>

<div>
    <label class="block text-xs text-slate-400 font-semibold mb-1">Jadwal Latihan *</label>
    <input type="text" name="schedule" value="{{ old('schedule', $e?->schedule) }}" required class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-purple-500 transition-all" placeholder="Senin & Kamis, 15:30 - 17:30 WIB">
</div>

<div>
    <label class="block text-xs text-slate-400 font-semibold mb-1">Narahubung WA</label>
    <input type="text" name="contact" value="{{ old('contact', $e?->contact) }}" class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-purple-500 transition-all" placeholder="0812-xxxx-xxxx">
</div>

<div>
    <label class="block text-xs text-slate-400 font-semibold mb-1">Prestasi Unggulan</label>
    <input type="text" name="achievements" value="{{ old('achievements', $e?->achievements) }}" class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-purple-500 transition-all" placeholder="Juara 1 FLS2N 2025">
</div>

<div class="md:col-span-2">
    <label class="block text-xs text-slate-400 font-semibold mb-1">Detail Lengkap *</label>
    <textarea name="detail" required rows="3" class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-purple-500 transition-all resize-none" placeholder="Deskripsi lengkap tentang ekstrakurikuler ini...">{{ old('detail', $e?->detail) }}</textarea>
</div>

<div class="md:col-span-2">
    <label class="block text-xs text-slate-400 font-semibold mb-1">Agenda & Kegiatan</label>
    <textarea name="activities" rows="2" class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-purple-500 transition-all resize-none" placeholder="Latihan rutin, persiapan konser, ...">{{ old('activities', $e?->activities) }}</textarea>
</div>

<div class="md:col-span-2">
    <label class="block text-xs text-slate-400 font-semibold mb-1">Foto Eskul (jpg/png/webp, maks. 2MB)</label>
    @if($e?->image_path)
    <div class="mb-2 flex items-center gap-3">
        <img src="{{ $e->image_url }}" class="h-16 w-24 object-cover rounded-lg border border-slate-700">
        <span class="text-xs text-slate-500">Foto saat ini. Upload baru untuk mengganti.</span>
    </div>
    @endif
    <input type="file" name="image" accept="image/*" class="w-full bg-slate-950 border border-slate-700 text-slate-400 rounded-xl px-4 py-2.5 text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-purple-600 file:text-white file:text-xs file:cursor-pointer">
</div>

<div class="flex items-center gap-6">
    <label class="flex items-center gap-2 cursor-pointer text-sm text-slate-300">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $e?->is_active ?? true) ? 'checked' : '' }} class="w-4 h-4 accent-purple-500">
        Tampilkan di halaman utama
    </label>
    <div>
        <label class="block text-xs text-slate-400 font-semibold mb-1">Urutan Tampil</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $e?->sort_order ?? 0) }}" class="w-20 bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-3 py-2 text-sm outline-none focus:border-purple-500" min="0">
    </div>
</div>
