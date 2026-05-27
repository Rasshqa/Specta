# 🌌 SPECTA REVELIORA — The Dark Fantasy Festival Design System

Selamat datang di truth engine desain SPECTA REVELIORA. Panduan visual ini mendefinisikan standar desain kelas dunia untuk antarmuka web, e-commerce, dan sistem transaksi tiket SPECTA REVELIORA. Konsep utama yang diusung adalah **Dark Fantasy Festival**: sebuah perpaduan magis antara kemewahan digital gelap, elemen okultis mistis modern, dan antarmuka transparan futuristik.

---

## 🎨 1. CORE DESIGN TOKENS

Semua implementasi visual wajib menggunakan sistem token tersentralisasi berikut untuk menjamin visual yang konsisten dan tampak sangat premium.

### A. Tipografi & Skala Font
* **Font Utama:** `Plus Jakarta Sans`, `sans-serif`
* **Font Stack:** `'Plus Jakarta Sans', sans-serif`
* **Base Size:** `16px`
* **Base Line Height:** `24px`
* **Base Weight:** `400`
* **Skala Tipografi:**
  * `font.size.xs` = `10px`
  * `font.size.sm` = `12px`
  * `font.size.md` = `14px`
  * `font.size.lg` = `16px`
  * `font.size.xl` = `18px`
  * `font.size.2xl` = `19.2px`
  * `font.size.3xl` = `20px`
  * `font.size.4xl` = `24px`
  * `font.size.hero` = `48px` s.d. `72px`

### B. Palet Warna (The Ethereal Dark Palette)
* **`color.surface.base`** = `#000000` — Hitam pekat tak berujung.
* **`color.surface.strong`** = `#120d1a` — Ungu gelap kastil gotik.
* **`color.surface.muted`** = `#341f52` — Ungu kelam berkabut.
* **`color.surface.raised`** = `oklab(0.627 0.147802 -0.219953 / 0.2)` — Transparansi kristal magis.
* **`color.text.primary`** = `#f0e6ff` — Ungu putih surgawi (kontras tinggi).
* **`color.text.secondary`** = `oklch(0.946 0.033 307.174)` — Emas pucat cahaya rembulan.
* **`color.text.tertiary`** = `oklab(0.714 0.117894 -0.165257 / 0.6)` — Aksen nebula ungu transparan.
* **`color.text.inverse`** = `oklab(0.827 0.0705884 -0.0958033 / 0.6)` — Teks kontras dalam keadaan dibalik.
* **`color.border.default`** = `#ba83ff` — Neon ungu pembatas dimensi.

### C. Spacing Scale
* `space.1` = `2px`
* `space.2` = `4px`
* `space.3` = `6px`
* `space.4` = `8px`
* `space.5` = `12px`
* `space.6` = `16px`
* `space.7` = `20px`
* `space.8` = `24px`

### D. Sudut (Radius)
* `radius.xs` = `6px`
* `radius.sm` = `8px`
* `radius.md` = `12px`
* `radius.lg` = `16px`
* `radius.xl` = `24px`

### E. Bayangan & Cahaya (Shadow & Glow Tokens)
* **`shadow.1` (Celestial Glow):**
  `rgba(186, 131, 255, 0.3) 0px 0px 20px 0px, rgba(186, 131, 255, 0.1) 0px 0px 60px 0px`
* **`shadow.2` (Subtle Neon Edge):**
  `rgba(186, 131, 255, 0.25) 0px 0px 10px 0px`
* **`shadow.3` (Chamber Glassmorphism):**
  `oklab(0.627 0.147802 -0.219953 / 0.4) 0px 0px 0px 1px, rgba(186, 131, 255, 0.15) 0px 0px 30px 0px`

### F. Transisi & Animasi (Motion Tokens)
* `motion.duration.instant` = `150ms` (Umpan balik instan klik/hover)
* `motion.duration.fast` = `300ms` (Transisi modal / dropdown)
* `motion.duration.normal` = `500ms` (Animasi ambient / starfields)

---

## 💎 2. COMPONENT DESIGN SYSTEM & STATES

Setiap komponen interaktif wajib mengimplementasikan state transisi berikut:

### A. Tombol Utama (Primary Celestial Button)
* **Default:** Latar belakang gradasi dari `color.surface.muted` ke ungu gelap gotik, teks `color.text.primary`, border `color.border.default` tipis, shadow `shadow.2`.
* **Hover:** Transisi scale `1.03` dengan durasi `motion.duration.instant`, warna bergeser ke ungu menyala lebih terang, shadow meningkat ke `shadow.1`.
* **Focus-Visible:** Outline cincin neon 2px solid `color.border.default`, offset 2px hitam pekat.
* **Active:** Penurunan scale ke `0.98`, cahaya redup sejenak memberikan sensasi umpan balik haptik.
* **Disabled:** Keburaman `0.4`, kursor tidak diizinkan (`not-allowed`), cahaya neon mati sepenuhnya.

### B. Kartu Glassmorphism (Dark Chamber Cards)
* **Struktur HTML:**
  ```html
  <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-[0_8px_32px_0_rgba(186,131,255,0.08)] hover:border-[#ba83ff]/40 transition-all duration-300">
      <!-- Isi Konten Kartu -->
  </div>
  ```

---

## ♿ 3. ACCESSIBILITY (WCAG 2.2 AA)

* **Kontras Teks:** Semua teks utama wajib memiliki rasio kontras minimal **4.5:1** terhadap latar belakang hitam/ungu tua.
* **Fokus Keyboard:** Pengguna yang menggunakan navigasi keyboard (`TAB`) wajib melihat indikator fokus neon ungu menyala yang sangat jelas di setiap elemen interaktif.
* **Aria Attributes:** Semua tombol berupa ikon wajib menggunakan `aria-label` deskriptif.

---

## 🚫 4. PROHIBITED ANTI-PATTERNS
* **Jangan** menggunakan warna abu-abu polos standard atau warna primer murni (seperti merah/biru murni tanpa gradasi).
* **Jangan** menghilangkan efek `focus-visible` untuk elemen input dan tombol.
* **Jangan** menumpuk teks tipis di atas gambar tanpa lapisan overlay gelap (`bg-slate-950/80` atau gradien gelap ke transparan).