<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>E-Tiket SPECTA XXI – {{ $transaction->invoice_number }}</title>
    <style>
        /* ── Reset & Baseline ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            margin: 0mm;
            size: A4 portrait;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            background-color: #020617;
            color: #f8fafc;
            font-size: 11px;
            width: 210mm;
            height: 297mm;
        }

        /* ── Page Wrapper ── */
        .page {
            width: 100%;
            height: 100%;
            background-color: #020617;
        }

        /* ── Ticket Card (One ticket per A4 page) ── */
        .ticket-page {
            width: 210mm;
            height: 297mm;
            position: relative;
            padding: 30px 40px;
            page-break-after: always;
        }
        .ticket-page:last-child {
            page-break-after: avoid;
        }

        /* ── Header Banner ── */
        .header-banner {
            width: 100%;
            background-color: #0f172a;
            border: 2px solid #7c3aed;
            border-bottom: 4px solid #06b6d4;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            margin-bottom: 20px;
        }
        .header-banner .event-name {
            font-size: 28px;
            font-weight: bold;
            color: #ffffff;
            letter-spacing: 6px;
            text-transform: uppercase;
        }
        .header-banner .event-sub {
            font-size: 13px;
            color: #22d3ee;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-top: 6px;
            font-weight: bold;
        }
        .header-banner .event-org {
            font-size: 9px;
            color: #64748b;
            margin-top: 6px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* ── Seat / Index Indicator ── */
        .seat-indicator {
            text-align: center;
            margin-bottom: 20px;
        }
        .seat-indicator-badge {
            display: inline-block;
            background-color: #1e1b4b;
            border: 1px solid #7c3aed;
            color: #c084fc;
            font-size: 11px;
            font-weight: bold;
            padding: 6px 16px;
            border-radius: 30px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* ── Main Layout Table ── */
        .ticket-main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        /* ── Left Column: Ticket Details ── */
        .col-details {
            width: 58%;
            vertical-align: top;
            padding-right: 16px;
        }

        .detail-card {
            background-color: #0f172a;
            border: 2px solid #3b0764;
            border-radius: 12px;
            padding: 20px;
        }

        .detail-row {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        .detail-row td {
            vertical-align: top;
            padding: 0;
        }
        .detail-label {
            font-size: 8.5px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
            margin-bottom: 4px;
        }
        .detail-value {
            font-size: 15px;
            font-weight: bold;
            color: #f8fafc;
        }
        .detail-value.cyan { color: #22d3ee; }
        .detail-value.purple { color: #a855f7; font-size: 12px; letter-spacing: 1.5px; font-family: monospace; }

        /* Custom subtle divider line */
        .divider {
            height: 1px;
            background-color: #1e293b;
            margin: 10px 0;
        }

        /* Unique Code Display Box */
        .code-box {
            background-color: #120626;
            border: 1px dashed #7c3aed;
            border-radius: 8px;
            padding: 12px;
            text-align: center;
            margin-top: 14px;
        }
        .code-box .code-label {
            font-size: 8.5px;
            color: #a78bfa;
            letter-spacing: 2px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 4px;
        }
        .code-box .code-value {
            font-size: 15px;
            font-weight: bold;
            color: #06b6d4;
            letter-spacing: 4px;
            font-family: 'Courier New', monospace;
        }

        /* ── Right Column: QR Code ── */
        .col-qr {
            width: 42%;
            vertical-align: top;
        }
        .qr-card {
            background-color: #0f172a;
            border: 2px solid #3b0764;
            border-radius: 12px;
            padding: 24px 16px;
            text-align: center;
            height: 100%;
        }
        .qr-image-wrapper {
            margin-bottom: 16px;
        }
        .qr-image-wrapper img {
            width: 150px;
            height: 150px;
            background-color: #ffffff;
            padding: 8px;
            border-radius: 8px;
            border: 3px solid #22d3ee;
        }
        .qr-scan-label {
            font-size: 10px;
            color: #38bdf8;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 8px;
        }
        .qr-note {
            font-size: 9px;
            color: #64748b;
            line-height: 1.4;
        }

        /* ── Valid Badge ── */
        .valid-badge {
            display: inline-block;
            background-color: #064e3b;
            border: 1px solid #059669;
            color: #34d399;
            font-size: 9px;
            font-weight: bold;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-top: 10px;
        }

        /* ── Footer Strip (Anchored at page bottom safely) ── */
        .footer-strip {
            position: absolute;
            bottom: 30px;
            left: 40px;
            right: 40px;
            border-top: 1px solid #1e293b;
            padding-top: 14px;
        }
        .footer-strip-table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-strip-table td {
            vertical-align: middle;
        }
        .footer-warn-text {
            font-size: 9px;
            color: #64748b;
            line-height: 1.4;
            width: 70%;
        }
        .footer-warn-text strong {
            color: #f87171;
        }
        .footer-copyright {
            font-size: 9px;
            color: #475569;
            text-align: right;
            width: 30%;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>
<div class="page">

    @foreach($transaction->ticketCodes as $index => $ticketCode)

    @php
        // Generate QR as base64 PNG/SVG for DomPDF compatibility
        $qrSvg = QrCode::format('svg')->size(200)->margin(1)->generate($ticketCode->unique_ticket_code);
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
    @endphp

    {{-- Per-ticket A4 Page --}}
    <div class="ticket-page">

        {{-- Header Banner --}}
        <div class="header-banner">
            <div class="event-name">SPECTA XXI</div>
            <div class="event-sub">REVELIORA – Celestial Treasure</div>
            <div class="event-org">SMAN 1 Cianjur • E-Tiket Resmi</div>
        </div>

        {{-- Seat/Index Badge --}}
        <div class="seat-indicator">
            <span class="seat-indicator-badge">
                Tiket #{{ $index + 1 }} dari {{ $transaction->quantity }}
            </span>
        </div>

        {{-- Main Content --}}
        <table class="ticket-main-table">
            <tr>
                {{-- Left Side: Ticket details --}}
                <td class="col-details">
                    <div class="detail-card">

                        {{-- Buyer Name --}}
                        <table class="detail-row">
                            <tr>
                                <td>
                                    <span class="detail-label">Nama Veloran</span>
                                    <span class="detail-value">{{ strtoupper($transaction->buyer_name) }}</span>
                                </td>
                            </tr>
                        </table>
                        <div class="divider"></div>

                        {{-- Buyer Class --}}
                        <table class="detail-row">
                            <tr>
                                <td>
                                    <span class="detail-label">Kelas</span>
                                    <span class="detail-value">{{ strtoupper($transaction->buyer_class) }}</span>
                                </td>
                            </tr>
                        </table>
                        <div class="divider"></div>

                        {{-- Ticket Category --}}
                        <table class="detail-row">
                            <tr>
                                <td>
                                    <span class="detail-label">Kategori Tiket</span>
                                    <span class="detail-value cyan">{{ strtoupper($transaction->ticket->ticket_name) }}</span>
                                </td>
                            </tr>
                        </table>
                        <div class="divider"></div>

                        {{-- Invoice Number --}}
                        <table class="detail-row">
                            <tr>
                                <td>
                                    <span class="detail-label">Nomor Invoice</span>
                                    <span class="detail-value purple">{{ $transaction->invoice_number }}</span>
                                </td>
                            </tr>
                        </table>

                        {{-- Unique Ticket Code Box --}}
                        <div class="code-box">
                            <span class="code-label">Kode Tiket Unik</span>
                            <span class="code-value">{{ $ticketCode->unique_ticket_code }}</span>
                        </div>

                        {{-- Validation Status --}}
                        <div style="text-align: center;">
                            <span class="valid-badge">✓ Lunas & Valid</span>
                        </div>

                    </div>
                </td>

                {{-- Right Side: QR Code Verification --}}
                <td class="col-qr">
                    <div class="qr-card">
                        <div class="qr-image-wrapper">
                            <img src="{{ $qrBase64 }}" alt="QR Code Tiket">
                        </div>
                        <div class="qr-scan-label">Scan Pintu Masuk</div>
                        <div class="qr-note">
                            Tunjukkan QR code ini kepada panitia di gerbang masuk.<br><br>
                            Satu QR code hanya dapat dipindai <strong>satu kali</strong> untuk akses masuk satu orang.
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        {{-- Footer Strip (Safely nested inside the ticket-page container) --}}
        <div class="footer-strip">
            <table class="footer-strip-table">
                <tr>
                    <td class="footer-warn-text">
                        <strong>PENTING:</strong> Dilarang keras menggandakan, menyebarluaskan, atau membagikan e-tiket ini. Panitia tidak bertanggung jawab atas segala bentuk penyalahgunaan tiket.
                    </td>
                    <td class="footer-copyright">
                        © {{ date('Y') }} SPECTA XXI
                    </td>
                </tr>
            </table>
        </div>

    </div>

    @endforeach

</div>
</body>
</html>
