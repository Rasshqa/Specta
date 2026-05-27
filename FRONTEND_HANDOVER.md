# Panduan Serah Terima Frontend (Handover)
## SPECTA XXI: REVELIORA – Celestial Treasure

Dokumen ini ditujukan untuk **Developer Frontend** yang akan melanjutkan pengembangan antarmuka (UI/UX) website SPECTA XXI: REVELIORA.

---

## 🚀 1. Persiapan Environment Kerja

Untuk memulai, silakan ikuti langkah-langkah setup repository dan environment berikut:

### A. Clone & Checkout Branch
Repository utama berada di GitHub. Pastikan Anda melakukan clone dan berpindah ke branch **`backend`** (atau `main` jika sudah di-merge) tempat fitur-fitur terbaru berada.
```bash
# Clone repository
git clone https://github.com/Rasshqa/Specta.git
cd Specta/specta-web

# Berpindah ke branch backend
git checkout backend
```

### B. Install Dependencies
Instal seluruh package PHP (Composer) dan Javascript (NPM):
```bash
# Install PHP dependencies
composer install

# Install JS & CSS dependencies
npm install
```

### C. Konfigurasi Environment (`.env`)
Salin file konfigurasi `.env.example` menjadi `.env`, lalu sesuaikan pengaturannya:
```bash
cp .env.example .env
```
Isi bagian konfigurasi database Anda di `.env`. Untuk Mailer, kami menggunakan **Brevo SMTP** (atau Mailtrap untuk testing lokal). Pastikan key mailer berikut terisi agar fitur e-ticket berjalan lancar:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=rashqaanti2@gmail.com
MAIL_PASSWORD=your_brevo_smtp_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="rashqaanti2@gmail.com"
MAIL_FROM_NAME="SPECTA XXI - REVELIORA"
```

### D. Generate Key & Migrasi Database + Seed
Lakukan generate application key, jalankan migrasi database, dan jalankan seeder untuk membuat akun Administrator bawaan:
```bash
# Generate Key
php artisan key:generate

# Migrasi & Seed Database
php artisan migrate:fresh --seed
```

> 🔑 **Akun Admin Default (untuk masuk Dashboard Admin):**
> * **Email:** `admin@spectaxxi.sch.id`
> * **Password:** `specta2025admin`

### E. Menjalankan Aplikasi
Buka 3 tab terminal baru untuk menjalankan server lokal berikut:
```bash
# Terminal 1: Laravel Local Server
php artisan serve

# Terminal 2: Vite Dev Server (Compiler Asset Tailwind)
npm run dev

# Terminal 3: Laravel Queue Worker (Penting untuk mengirim email e-ticket di background)
php artisan queue:work
```

---

## 🎨 2. Konsep Desain & Estetika (Celestial Treasure)

Website SPECTA XXI menggunakan tema futuristik luar angkasa premium (**Celestial Treasure**). Tolong pastikan setiap halaman baru atau modifikasi yang dilakukan mengikuti standar visual ini:

1. **Warna Dasar (Dark Mode)**: Dominan warna gelap ultra-modern seperti `bg-slate-950` untuk latar belakang, dan warna `text-slate-100` atau `text-slate-400` untuk teks.
2. **Aksen Gradasi (Celestial Glow)**:
   * Utama: `from-purple-600 to-cyan-500` atau `from-purple-400 to-cyan-400`.
   * Glow shadow: Gunakan box-shadow pendaran ungu/cyan (`shadow-[0_0_30px_rgba(168,85,247,0.4)]`).
3. **Glassmorphism**: Gunakan kombinasi background transparan dengan blur di belakangnya untuk kartu-kartu informasi: `bg-white/5 backdrop-blur-md border border-white/10`.
4. **Efek Interaktif & Animasi**:
   * **AOS (Animate On Scroll)** untuk memunculkan elemen secara sinematik saat di-scroll.
   * **Vanilla-Tilt.js** untuk efek kemiringan kartu 3D interaktif pada hover.
   * **Splide.js** untuk komparator atau karusel dokumentasi yang mulus.

---

## 📁 3. Struktur Folder & File Utama Frontend

Semua halaman frontend dibuat menggunakan sistem **Laravel Blade Views** terintegrasi dengan **Tailwind CSS v4** dan **Alpine.js**.

Berikut adalah letak file-file penting yang perlu Anda kelola atau kembangkan:

```
specta-web/
├── resources/
│   ├── css/
│   │   └── app.css             # Entry point Tailwind v4
│   ├── js/
│   │   └── app.js              # Entry point script
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php   # Layout utama (Memuat Tailwind, Alpine.js, AOS, Splide.js, dsb.)
│       ├── welcome.blade.php   # Halaman Utama (Hero, Ekskul, Galeri Bintang, Catalog Merchandise)
│       ├── auth/
│       │   └── login.blade.php # Halaman Login Admin (Celestial Dark Theme)
│       ├── admin/
│       │   ├── dashboard.blade.php    # Dashboard Panel Admin (Statistik Penjualan & Scan)
│       │   ├── merchandises.blade.php # Manajemen / CRUD Merchandise
│       │   └── transactions.blade.php # Validasi & Konfirmasi Pembelian Tiket Manual oleh Admin
│       ├── tickets/
│       │   ├── index.blade.php # Form Pemesanan Tiket (Pilihan Tiket, Input Data Pemesan)
│       │   └── pdf.blade.php   # Templat Desain E-Ticket PDF (⚠️ Baca Batasan DomPDF di bawah!)
│       ├── payment/
│       │   └── show.blade.php  # Detail Invoice / Pembayaran & Tombol Download E-Ticket
│       └── gatekeeper/
│           └── scanner.blade.php # Halaman Scanner QR Code Kamera (Untuk panitia tiket masuk)
```

---

## ⚠️ 4. Batasan Sangat Penting (CRITICAL CONSTRAINTS)

### 📌 A. Pengeditan E-Ticket PDF (`resources/views/tickets/pdf.blade.php`)
Fitur cetak tiket PDF menggunakan pustaka **DomPDF** (`barryvdh/laravel-dompdf`). 
* **DomPDF memiliki dukungan CSS yang sangat terbatas** (tidak mendukung Flexbox, CSS Grid, atau class Tailwind).
* **Solusi**: Desain e-ticket di file `pdf.blade.php` **WAJIB** menggunakan layout tabel HTML klasik (`<table>`), float (`float: left/right`), dan gaya CSS inline atau tag `<style>` bertipe CSS 2.1 murni. Jangan pernah menggunakan Flexbox atau Tailwind CSS di sini, karena layout PDF akan berantakan atau gagal di-render!

### 📌 B. Pilihan Tiket & Transaksi
Proses checkout tiket mengirimkan data ke `TicketController@checkout` yang akan menghasilkan record transaksi berstatus `pending` serta membuat token download unik yang aman (`download_token`). Halaman sukses akan menampilkan detail transfer bank/e-wallet manual. Setelah admin melakukan verifikasi via Dashboard, tiket berstatus `success` dan tombol "Download E-Ticket" akan aktif secara otomatis.

---

## 🛠️ 5. Agenda Pengembangan Selanjutnya (Next Task List)

Tugas-tugas frontend yang dapat Anda lakukan selanjutnya untuk menyempurnakan website ini:

1. **Responsivitas Mobile Dashboard Admin**:
   * Sempurnakan sidebar/navbar dan tabel transaksi pada `admin/transactions.blade.php` agar lebih rapi saat dibuka melalui perangkat mobile/tablet.
2. **Desain Pop-Up Modal Detail Merchandise**:
   * Saat ini, katalog merchandise langsung mengarah ke chat WhatsApp. Anda dapat menambahkan modal detail merchandise terlebih dahulu menggunakan Alpine.js sebelum user mengklik tombol WhatsApp.
3. **Penyempurnaan Tampilan E-Ticket PDF**:
   * Percantik tampilan PDF tiket pada `tickets/pdf.blade.php` agar terasa lebih eksklusif bagi pembeli, dengan tetap mematuhi aturan tabel layout CSS 2.1.
4. **Efek Glitch atau Sci-Fi pada Teks Hero**:
   * Tambahkan micro-interaction berupa efek teks neon berkedip atau glitch halus di halaman utama menggunakan animasi custom CSS di `resources/css/app.css` agar nuansa Celestial Treasure semakin terasa imersif.

---

Jika ada pertanyaan seputar route backend atau query database, jangan ragu untuk berdiskusi dengan tim backend! Selamat berkarya! 🚀✨
