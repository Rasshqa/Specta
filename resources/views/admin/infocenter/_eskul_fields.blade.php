{{-- Reusable fields for EskulProfile form (add & edit) --}}
@php $e = $eskul ?? null; @endphp

<div>
    <label class="block text-xs text-slate-400 font-semibold mb-1">Nama Eskul *</label>
    <input type="text" name="name" value="{{ old('name', $e?->name) }}" required class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-purple-500 transition-all" placeholder="Paduan Suara (Choir)">
</div>

<div>
    <label class="block text-xs text-slate-400 font-semibold mb-1">Icon/Emoji</label>
    <input type="text" name="icon" value="{{ old('icon', $e?->icon ?? '<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M11 2v2.07A8 8 0 0 0 4.07 11H2v2h2.07A8 8 0 0 0 11 19.93V22h2v-2.07A8 8 0 0 0 19.93 13H22v-2h-2.07A8 8 0 0 0 13 4.07V2m-2 4.08V8h2V6.09c2.5.41 4.5 2.41 4.92 4.91H16v2h1.91c-.41 2.5-2.41 4.5-4.91 4.92V16h-2v1.91C8.5 17.5 6.5 15.5 6.08 13H8v-2H6.09C6.5 8.5 8.5 6.5 11 6.08M12 11a1 1 0 0 0-1 1a1 1 0 0 0 1 1a1 1 0 0 0 1-1a1 1 0 0 0-1-1"/></svg>') }}" class="w-full bg-slate-950 border border-slate-700 text-slate-200 rounded-xl px-4 py-2.5 text-sm outline-none focus:border-purple-500 transition-all" placeholder="<svg class="inline align-middle w-[1em] h-[1em]" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M12 3v10.55c-.59-.34-1.27-.55-2-.55c-2.21 0-4 1.79-4 4s1.79 4 4 4s4-1.79 4-4V7h4V3z"/></svg>" maxlength="10">
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
