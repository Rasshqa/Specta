# 📋 Rencana Implementasi Frontend — SPECTA XXI: REVELIORA

> **Dibuat:** 27 Mei 2026  
> **Branch Kerja:** `frontend`  
> **Tema Desain:** Celestial Treasure (Dark Futuristic Glassmorphism)  
> **Stack:** Laravel Blade + Tailwind CSS v4 + Alpine.js + AOS + Splide.js + Vanilla-Tilt.js

---

## 📑 Daftar Isi

1. [Persiapan Branch & Environment](#-1-persiapan-branch--environment)
2. [Tugas 1: Responsivitas Mobile Admin Dashboard](#-tugas-1-responsivitas-mobile-admin-dashboard)
3. [Tugas 2: Modal Detail Merchandise](#-tugas-2-modal-detail-merchandise)
4. [Tugas 3: Penyempurnaan E-Ticket PDF](#-tugas-3-penyempurnaan-e-ticket-pdf)
5. [Tugas 4: Efek Glitch / Sci-Fi Hero Text](#-tugas-4-efek-glitch--sci-fi-hero-text)
6. [Checklist Final & Catatan Penting](#-checklist-final--catatan-penting)

---

## 🔧 1. Persiapan Branch & Environment

### A. Buat Branch `frontend`
```bash
git checkout -b frontend
git push -u origin frontend
```

### B. Pastikan Environment Berjalan
```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: Vite Dev Server (Tailwind compiler)
npm run dev

# Terminal 3: Queue Worker (untuk email e-ticket)
php artisan queue:work
```

### C. Verifikasi Akses
- **Landing Page:** `http://localhost:8000`
- **Admin Login:** `http://localhost:8000/login`
  - Email: `admin@spectaxxi.sch.id`
  - Password: `specta2025admin`

---

## 📱 Tugas 1: Responsivitas Mobile Admin Dashboard

### Status Saat Ini
| File | Masalah |
|---|---|
| `admin/dashboard.blade.php` | Sidebar sudah ada Alpine.js toggle, tapi **tersembunyi via `-translate-x-full` pada mobile** tanpa tombol hamburger yang jelas. Tabel transaksi terbaru belum optimal di layar kecil. |
| `admin/transactions.blade.php` | **Tidak memiliki sidebar** sama sekali — hanya tombol panah kembali. Filter form menumpuk di mobile. Tabel 5 kolom terlalu lebar di layar kecil. |
| `admin/merchandises.blade.php` | Perlu diperiksa responsivitasnya juga agar konsisten. |

### Rencana Perubahan

#### 1.1 — Ekstrak Sidebar Menjadi Komponen Reusable
**File:** `resources/views/admin/partials/sidebar.blade.php` *(baru)*

- Pindahkan kode sidebar dari `dashboard.blade.php` ke partial yang dapat di-`@include` di semua halaman admin.
- Pastikan sidebar menggunakan `x-data` dari parent layout agar state `sidebarOpen` tersinkronisasi.
- Navigasi items: Dashboard, Transaksi, Merchandise, + tombol Logout.

```
Struktur baru:
resources/views/admin/
├── partials/
│   ├── sidebar.blade.php    ← BARU (ekstrak dari dashboard)
│   └── topbar.blade.php     ← BARU (header bar + hamburger button)
├── dashboard.blade.php      ← Diperbarui: @include partial
├── transactions.blade.php   ← Diperbarui: @include partial + sidebar
└── merchandises.blade.php   ← Diperbarui: @include partial + sidebar
```

#### 1.2 — Perbaiki Layout Sidebar pada Mobile
**Perubahan di `sidebar.blade.php`:**
- Desktop (`lg:`): Sidebar tetap fixed di kiri, selalu terlihat.
- Mobile/Tablet: Sidebar tersembunyi, muncul sebagai drawer overlay saat tombol hamburger ditekan.
- Overlay hitam semi-transparan di belakang sidebar (`bg-black/60 backdrop-blur-sm`) — sudah ada di dashboard, perlu ditambahkan ke transactions & merchandises.

#### 1.3 — Responsivitas Tabel Transaksi
**File:** `admin/transactions.blade.php`

**Pendekatan:** Ubah tabel menjadi "card stack" pada layar kecil.

| Breakpoint | Tampilan |
|---|---|
| `lg:` ke atas | Tabel horizontal standar 5 kolom |
| `md:` ke bawah | Setiap baris transaksi berubah menjadi card vertikal glassmorphism |

**Detail implementasi:**
- Tambahkan class `hidden md:table-cell` pada kolom yang kurang penting di mobile (misalnya kolom "Pesanan" detail).
- Alternatif: gunakan pendekatan **responsive card** dengan `<div>` yang tersembunyi di desktop dan muncul di mobile.
- Filter form: ubah dari `flex-row` menjadi `flex-col` di mobile (sudah partial — perlu disempurnakan gap dan padding-nya).

#### 1.4 — Topbar Konsisten
**File:** `admin/partials/topbar.blade.php` *(baru)*

- Tombol hamburger (`btn-toggle-sidebar`) yang hanya muncul di `lg:hidden`.
- Info user (nama + avatar inisial) di sisi kanan.
- Sticky top dengan backdrop blur.

### File yang Dimodifikasi
- [ ] `resources/views/admin/partials/sidebar.blade.php` — **BARU**
- [ ] `resources/views/admin/partials/topbar.blade.php` — **BARU**
- [ ] `resources/views/admin/dashboard.blade.php` — Refactor: ganti inline sidebar dengan `@include`
- [ ] `resources/views/admin/transactions.blade.php` — Tambah sidebar + responsive card
- [ ] `resources/views/admin/merchandises.blade.php` — Tambah sidebar + responsive

---

## 🛍️ Tugas 2: Modal Detail Merchandise

### Status Saat Ini
**File:** `resources/views/welcome.blade.php` (baris 130-165)

Saat ini, setiap kartu merchandise langsung memiliki tombol "Beli via WhatsApp" yang membuka `wa.me` link. Tidak ada detail tambahan atau preview sebelum user diarahkan ke WhatsApp.

### Rencana Perubahan

#### 2.1 — Buat Komponen Modal dengan Alpine.js
**File:** `resources/views/welcome.blade.php` (modifikasi bagian merchandise)

**Struktur Alpine.js:**
```
x-data="{ 
    showModal: false, 
    activeMerch: null 
}"
```

**Komponen modal berisi:**
1. **Gambar besar** merchandise (full-width di atas modal)
2. **Nama** + **harga** dengan gradient text
3. **Deskripsi lengkap** produk
4. **Ukuran / Varian** (jika data tersedia di masa depan)
5. **Tombol "Beli via WhatsApp"** — mempertahankan link `wa.me` yang sudah ada
6. **Tombol "Tutup"** modal

#### 2.2 — Desain Modal (Celestial Glassmorphism)
```
Spesifikasi visual:
- Overlay:        bg-black/70 backdrop-blur-sm
- Modal container: bg-slate-900/95 backdrop-blur-xl border border-white/10 rounded-3xl
- Max width:      max-w-lg mx-auto
- Animasi masuk:  x-transition:enter scale-95 → scale-100, opacity-0 → opacity-100
- Animasi keluar: x-transition:leave scale-100 → scale-95, opacity-100 → opacity-0
- Shadow:         shadow-[0_0_60px_rgba(168,85,247,0.3)]
```

#### 2.3 — Trigger Modal dari Kartu
- Ubah tombol "Beli via WhatsApp" di setiap kartu menjadi **"Lihat Detail"**.
- `@click` pada tombol set `activeMerch` ke data merchandise yang dipilih dan `showModal = true`.
- Tombol WhatsApp dipindahkan ke dalam modal sebagai CTA utama.

#### 2.4 — Data Binding
Merchandise data yang perlu di-bind ke modal melalui Alpine.js:
```javascript
{
    name: '{{ $merch->name }}',
    price: 'Rp {{ number_format($merch->price, 0, ",", ".") }}',
    description: '{{ $merch->description }}',
    image_url: '{{ $merch->image_url }}',
    wa_link: 'https://wa.me/...'
}
```

### File yang Dimodifikasi
- [ ] `resources/views/welcome.blade.php` — Tambah modal + ubah tombol kartu

---

## 🎫 Tugas 3: Penyempurnaan E-Ticket PDF

### Status Saat Ini
**File:** `resources/views/tickets/pdf.blade.php` (354 baris)

E-Ticket sudah memiliki desain yang solid dengan:
- Header banner biru gelap + border ungu
- Layout 2 kolom (58% detail, 42% QR) menggunakan `<table>`
- Kode warna Celestial: `#020617`, `#1e1b4b`, `#22d3ee`, `#a855f7`
- Valid badge hijau, code box dengan border dashed

### ⚠️ BATASAN KRITIS — DomPDF
> **WAJIB DIPATUHI:**
> - ❌ TIDAK boleh menggunakan Flexbox, CSS Grid, atau Tailwind CSS
> - ❌ TIDAK boleh menggunakan `position: absolute/fixed` yang kompleks
> - ✅ HANYA gunakan `<table>`, `float`, dan CSS 2.1 inline/embedded styles
> - ✅ Font yang didukung: `DejaVu Sans`, `Arial`, `Courier New`

### Rencana Perubahan

#### 3.1 — Tambah Ornamen Visual Header
**Area:** `.header-banner`
- Tambahkan dekorasi bintang/dot menggunakan karakter Unicode: `✦`, `·`, `★`
- Tambahkan garis tipis dekoratif di bawah subtitle menggunakan `<hr>` dengan styling gradient simulasi (warna solid fallback)
- Perbesar sedikit padding dan tambahkan warna latar yang lebih dramatis

#### 3.2 — Perkaya Detail Card
**Area:** `.detail-card`
- Tambahkan informasi tambahan:
  - **Tanggal Acara** (jika data tersedia)
  - **Lokasi Acara** (teks statis: "SMAN 1 Cianjur")
  - **Waktu** (teks statis: "Coming Soon" atau diisi dari config)
- Perbesar spacing antar field agar lebih lapang
- Tambahkan ikon emoji di samping setiap label: 👤 Nama, 🏫 Kelas, 🎟️ Jenis Tiket, 📄 Invoice

#### 3.3 — Perkaya QR Section
**Area:** `.qr-container`
- Tambahkan border lebih tebal dan warna cyan yang lebih mencolok
- Tambahkan teks "SCAN ME" di atas QR code
- Tambahkan dekorasi titik-titik di sekitar container

#### 3.4 — Footer yang Lebih Informatif
**Area:** `.footer-strip`
- Tambahkan baris informasi kontak: "Hubungi panitia: 08xx-xxxx-xxxx"
- Tambahkan text "Dicetak otomatis oleh sistem SPECTA XXI"
- Pertahankan peringatan keamanan yang sudah ada

#### 3.5 — Halaman Terpisah yang Lebih Rapi
- Pastikan `page-break-after: always` bekerja sempurna untuk tiket multipel
- Header banner harus muncul di setiap halaman tiket (sudah benar saat ini)
- Tambahkan nomor halaman simulasi: "Halaman 1 dari X"

### File yang Dimodifikasi
- [ ] `resources/views/tickets/pdf.blade.php` — Enhasi visual dengan CSS 2.1

---

## ✨ Tugas 4: Efek Glitch / Sci-Fi Hero Text

### Status Saat Ini
**File yang terlibat:**
- `resources/css/app.css` — Hanya berisi import Tailwind, **belum ada custom CSS sama sekali**
- `resources/views/welcome.blade.php` (baris 17-22) — Hero title "SPECTA XXI" dan "Reveliora" menggunakan Tailwind statis

### Rencana Perubahan

#### 4.1 — Animasi Neon Glow Berkedip
**File:** `resources/css/app.css`

Buat `@keyframes` animasi untuk efek neon pulse pada judul utama:
```
Efek: text-shadow berpendar ungu/cyan yang membesar-mengecil secara berkala
Target: Judul "SPECTA XXI" dan "Reveliora"
Durasi: 3-4 detik per cycle, infinite loop
Intensitas: Halus — tidak mengganggu, tapi terasa hidup
```

#### 4.2 — Animasi Glitch Halus
**File:** `resources/css/app.css`

Buat efek glitch menggunakan pseudo-element `::before` dan `::after`:
```
Teknik:
1. Duplikasi teks dengan ::before dan ::after
2. Masing-masing pseudo-element diberi clip-path random
3. Animasi translateX kecil (2-3px) secara intermittent
4. Warna pseudo: satu cyan, satu magenta (chromatic aberration)
5. Glitch hanya muncul sesekali (keyframe 95% normal, 5% glitch)
```

#### 4.3 — Efek Typing / Reveal pada Subtitle
**Opsional** — Animasi CSS murni untuk teks "Reveliora" muncul perlahan:
```
Teknik: overflow: hidden + width animation dari 0 ke 100%
Border-right animasi untuk efek kursor ketik
```

#### 4.4 — Tambah Class ke Hero Section
**File:** `resources/views/welcome.blade.php`

- Tambahkan class `.hero-glitch` pada `<h1>` "SPECTA XXI"
- Tambahkan class `.hero-neon` pada `<h2>` "Reveliora"
- Tambahkan class `.hero-subtitle-type` jika efek typing diimplementasi

#### 4.5 — Animasi Partikel Bintang (CSS Murni)
**File:** `resources/css/app.css`

Buat beberapa elemen bintang kecil menggunakan pseudo-element atau `<div>` tambahan:
```
Teknik:
- Titik-titik kecil (2-4px) putih dengan opacity rendah
- Animasi translateY perlahan dari atas ke bawah (efek jatuh)
- Beberapa titik dengan animasi scale pulse (efek berkelip)
- z-index di bawah konten utama
```

### File yang Dimodifikasi
- [ ] `resources/css/app.css` — Tambah @keyframes dan class animasi
- [ ] `resources/views/welcome.blade.php` — Tambah class animasi pada hero section

---

## ✅ Checklist Final & Catatan Penting

### Urutan Eksekusi yang Disarankan

| Prioritas | Tugas | Alasan |
|---|---|---|
| 🥇 **1** | Tugas 4: Efek Glitch Hero | Paling cepat selesai, dampak visual langsung terasa di halaman utama |
| 🥈 **2** | Tugas 2: Modal Merchandise | Perbaikan UX signifikan, hanya 1 file yang diubah |
| 🥉 **3** | Tugas 1: Responsive Admin | Cakupan luas (3+ file), perlu refactoring partial |
| 4️⃣ **4** | Tugas 3: E-Ticket PDF | Paling rumit karena batasan DomPDF, perlu testing cetak berulang |

### Aturan Wajib Selama Pengerjaan

> [!CAUTION]
> **Jangan pernah** menggunakan Flexbox atau Tailwind CSS di `pdf.blade.php`!

> [!IMPORTANT]
> Selalu test PDF output dengan `php artisan serve` lalu akses route download tiket setelah mengubah `pdf.blade.php`.

> [!NOTE]
> Semua perubahan dilakukan di branch `frontend`. Jangan merge ke `main` tanpa review dari tim backend.

### Konvensi Commit
```
feat(frontend): tambah animasi glitch pada hero section
feat(frontend): tambah modal detail merchandise
refactor(admin): ekstrak sidebar menjadi partial component
fix(admin): perbaiki responsivitas tabel transaksi mobile
enhance(pdf): perkaya desain e-ticket PDF
```

### Testing Checklist Per Tugas
- [ ] Tampilan responsive di Chrome DevTools (320px, 768px, 1024px, 1440px)
- [ ] Animasi berjalan mulus tanpa jank (60fps)
- [ ] Alpine.js interaksi modal bekerja (buka, tutup, data binding)
- [ ] Sidebar admin berfungsi di semua halaman admin
- [ ] PDF ter-render dengan benar (download dan buka di PDF reader)
- [ ] Tidak ada error di console browser
- [ ] AOS animations tetap bekerja setelah perubahan

---

> 📌 **Catatan:** Dokumen ini adalah panduan kerja, bukan spesifikasi final.
> Perubahan desain boleh dilakukan selama masih mengikuti style guide Celestial Treasure di `style.md`.

