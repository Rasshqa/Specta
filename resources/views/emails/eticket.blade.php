<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket SPECTA XXI – {{ $transaction->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #020617;
            color: #f8fafc;
            padding: 0;
        }
        .wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #0f172a;
        }
        /* ── Header ── */
        .header {
            background: linear-gradient(135deg, #4c1d95, #1e1b4b, #020617);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 2px solid #7c3aed;
        }
        .header h1 {
            font-size: 32px;
            font-weight: 900;
            letter-spacing: 6px;
            color: #22d3ee;
            text-transform: uppercase;
        }
        .header p {
            font-size: 14px;
            color: #c026d3;
            letter-spacing: 4px;
            margin-top: 4px;
            text-transform: uppercase;
        }
        /* ── Body ── */
        .body {
            padding: 36px 30px;
        }
        .greeting {
            font-size: 16px;
            color: #cbd5e1;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        .greeting strong {
            color: #a78bfa;
        }
        .success-banner {
            background: linear-gradient(135deg, rgba(21,128,61,0.2), rgba(5,46,22,0.4));
            border: 1px solid #16a34a;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin-bottom: 28px;
        }
        .success-banner .icon { font-size: 40px; }
        .success-banner h2 {
            font-size: 20px;
            color: #4ade80;
            margin-top: 10px;
        }
        .success-banner p {
            font-size: 13px;
            color: #86efac;
            margin-top: 6px;
        }
        /* ── Info Table ── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 28px;
        }
        .info-table td {
            padding: 12px 16px;
            font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }
        .info-table td:first-child {
            color: #64748b;
            width: 45%;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 1px;
        }
        .info-table td:last-child {
            color: #e2e8f0;
            font-weight: bold;
        }
        .invoice-val { color: #a78bfa !important; font-family: monospace; font-size: 15px !important; }
        .total-val { color: #fbbf24 !important; font-size: 16px !important; }
        /* ── Steps ── */
        .steps-title {
            font-size: 12px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 14px;
        }
        .step {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 12px;
        }
        .step-num {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            background: rgba(124,58,237,0.3);
            border: 1px solid #7c3aed;
            border-radius: 50%;
            color: #a78bfa;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            line-height: 24px;
        }
        .step p {
            color: #94a3b8;
            font-size: 13px;
            line-height: 1.6;
            margin: 0;
        }
        .step p strong { color: #c4b5fd; }
        /* ── Footer ── */
        .footer {
            background-color: #020617;
            padding: 24px 30px;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .footer p {
            font-size: 12px;
            color: #334155;
            line-height: 1.8;
        }
        .footer a { color: #7c3aed; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <div class="header">
        <h1>SPECTA XXI</h1>
        <p>REVELIORA · Celestial Treasure</p>
    </div>

    <!-- Body -->
    <div class="body">

        <!-- Greeting -->
        <p class="greeting">
            Halo, <strong>{{ $transaction->buyer_name }}</strong>! 🎉<br><br>
            Selamat! Pembayaran kamu telah <strong>berhasil dikonfirmasi</strong>.
            E-Tiket resmi kamu terlampir dalam email ini dalam format PDF.
            Tunjukkan QR Code yang ada di PDF tersebut kepada petugas di pintu masuk saat hari acara.
        </p>

        <!-- Success Banner -->
        <div class="success-banner">
            <div class="icon">🎟️</div>
            <h2>Pembayaran Dikonfirmasi!</h2>
            <p>E-Tiket kamu sudah siap. Simpan PDF ini baik-baik.</p>
        </div>

        <!-- Ticket Info -->
        <table class="info-table">
            <tr>
                <td>No. Invoice</td>
                <td class="invoice-val">{{ $transaction->invoice_number }}</td>
            </tr>
            <tr>
                <td>Nama Pembeli</td>
                <td>{{ $transaction->buyer_name }}</td>
            </tr>
            <tr>
                <td>Jenis Tiket</td>
                <td>{{ $transaction->ticket->ticket_name }}</td>
            </tr>
            <tr>
                <td>Jumlah Tiket</td>
                <td>{{ $transaction->quantity }} tiket</td>
            </tr>
            <tr>
                <td>Total Bayar</td>
                <td class="total-val">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
            </tr>
        </table>

        <!-- How to use -->
        <p class="steps-title">Cara Menggunakan E-Tiket</p>

        <div class="step">
            <div class="step-num">1</div>
            <p>Download dan simpan file <strong>PDF terlampir</strong> di HP atau device kamu.</p>
        </div>
        <div class="step">
            <div class="step-num">2</div>
            <p>Datang ke lokasi acara sesuai jadwal dan antri di <strong>pintu masuk</strong>.</p>
        </div>
        <div class="step">
            <div class="step-num">3</div>
            <p>Tunjukkan <strong>QR Code</strong> yang ada di PDF kepada petugas untuk di-scan.</p>
        </div>
        <div class="step">
            <div class="step-num">4</div>
            <p><strong>Jangan bagikan</strong> kode tiket ini kepada siapapun. Setiap kode hanya berlaku untuk <strong>1 kali masuk</strong>.</p>
        </div>

    </div>

    <!-- Footer -->
    <div class="footer">
        <p>
            Email ini dikirim secara otomatis oleh sistem SPECTA XXI.<br>
            Jika ada pertanyaan, hubungi panitia via WhatsApp.<br><br>
            &copy; {{ date('Y') }} SPECTA XXI · SMAN 1 Cianjur · Sampai jumpa di acara, Veloran! ✨
        </p>
    </div>

</div>
</body>
</html>
