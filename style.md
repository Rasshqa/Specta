# 🌌 STYLE GUIDE: SPECTA XXI — CELESTIAL TREASURE

Panduan visual ini mendefinisikan standar desain, warna, tipografi, komponen, dan animasi untuk website **SPECTA XXI: REVELIORA (SMAN 1 Cianjur)**. Tema utama yang diangkat adalah **Celestial Treasure**: sebuah perpaduan antara kemewahan digital, estetika *cyberpunk neon*, dan efek kaca transparan (*glassmorphism*).

---

## 🎨 1. COLOR PALETTE (Kombinasi Warna)

Gunakan class Tailwind berikut untuk menjaga konsistensi warna di seluruh halaman web.

### A. Warna Dasar (Background & Surface)
* **Main Background:** `bg-slate-950` (`#020617`) — Warna ruang angkasa gelap yang mendalam.
* **Card/Surface Background:** `bg-slate-900/50` atau `bg-white/5` — Basis untuk efek kaca transparan.

### B. Warna Aksen & Neon (Celestial Glow)
* **Primary Purple Neon:** `text-purple-500` / `bg-purple-600` (`#a855f7`) — Merepresentasikan harta karun surgawi.
* **Secondary Cyber Blue:** `text-cyan-400` / `bg-cyan-500` (`#22d3ee`) — Merepresentasikan teknologi digital futuristik.
* **Muted Text:** `text-slate-400` — Untuk deskripsi teks pendukung agar mata tidak lelah.

---

## ✍️ 2. TYPOGRAPHY (Tipografi)

Gunakan hirarki ukuran teks ini agar informasi tiket dan event mudah dibaca oleh Velorans di layar HP:

* **Hero Title / Judul Utama:** `text-4xl md:text-6xl font-extrabold tracking-tight`
* **Section Title / Judul Bagian:** `text-2xl md:text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-400 to-cyan-400`
* **Card Title / Judul Komponen:** `text-xl font-semibold text-slate-100`
* **Body Text / Deskripsi:** `text-sm md:text-base text-slate-300 leading-relaxed`

---

## 💎 3. THE UTMOST COMPONENT: GLASSMORPHISM

Kunci utama estetika "kemewahan digital" di web ini adalah efek kaca transparan. Gunakan kombinasi class Tailwind berikut untuk semua kotak/card (Kartu Tiket, Grid Merchandise, Detail Ekskul):

```html
<div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-6 shadow-[0_8px_32px_0_rgba(0,0,0,0.37)]">
    </div>