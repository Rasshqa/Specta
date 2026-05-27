<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>E-Tiket SPECTA XXI – {{ $transaction->invoice_number }}</title>
    <style>
        /* ── Reset ── */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @page {
            margin: 0mm;
            size: A4 portrait;
        }

        body {
            font-family: 'DejaVu Sans', 'Arial', sans-serif;
            background-color: #020617;
            color: #f8fafc;
            font-size: 12px;
            width: 210mm;
        }

        /* ── Page Wrapper ── */
        .page {
            width: 100%;
            min-height: 297mm;
            background-color: #020617;
            padding: 0;
        }

        /* ── Header Banner ── */
        .header-banner {
            width: 100%;
            background-color: #1e1b4b;
            border-bottom: 3px solid #7c3aed;
            padding: 24px 40px;
            text-align: center;
        }
        .header-banner .event-name {
            font-size: 30px;
            font-weight: bold;
            color: #22d3ee;
            letter-spacing: 6px;
            text-transform: uppercase;
        }
        .header-banner .event-sub {
            font-size: 13px;
            color: #c026d3;
            letter-spacing: 4px;
            text-transform: uppercase;
            margin-top: 4px;
        }
        .header-banner .event-org {
            font-size: 10px;
            color: #475569;
            margin-top: 6px;
            letter-spacing: 1px;
        }

        /* ── Ticket Card (Per Page) ── */
        .ticket-wrapper {
            padding: 20px 40px 30px 40px;
            page-break-after: always;
        }
        .ticket-wrapper:last-child {
            page-break-after: avoid;
        }

        /* Ticket seat label */
        .seat-label {
            text-align: center;
            padding: 8px 0 14px 0;
            font-size: 10px;
            color: #64748b;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Main layout table */
        .ticket-main {
            width: 100%;
            border-collapse: collapse;
        }

        /* ── Left Column: Details ── */
        .col-details {
            width: 58%;
            vertical-align: top;
            padding-right: 20px;
        }

        .detail-card {
            background-color: #0f172a;
            border: 1px solid #3b0764;
            border-radius: 8px;
            padding: 20px;
        }

        .detail-row {
            width: 100%;
            margin-bottom: 14px;
            border-collapse: collapse;
        }
        .detail-row td {
            vertical-align: top;
            padding: 0;
        }
        .detail-label {
            font-size: 9px;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            display: block;
            margin-bottom: 3px;
        }
        .detail-value {
            font-size: 16px;
            font-weight: bold;
            color: #f8fafc;
            word-break: break-word;
        }
        .detail-value.cyan { color: #22d3ee; }
        .detail-value.purple { color: #a855f7; font-size: 13px; letter-spacing: 1px; }
        .detail-value.yellow { color: #fbbf24; font-size: 18px; }

        /* Divider */
        .divider {
            border: none;
            border-top: 1px solid #1e293b;
            margin: 12px 0;
        }

        /* Unique Code Box */
        .code-box {
            background-color: #1e1b4b;
            border: 1px dashed #7c3aed;
            border-radius: 6px;
            padding: 10px 14px;
            text-align: center;
            margin-top: 14px;
        }
        .code-box .code-label {
            font-size: 9px;
            color: #64748b;
            letter-spacing: 2px;
            text-transform: uppercase;
            display: block;
            margin-bottom: 5px;
        }
        .code-box .code-value {
            font-size: 14px;
            font-weight: bold;
            color: #a855f7;
            letter-spacing: 3px;
            font-family: 'Courier New', monospace;
        }

        /* ── Right Column: QR Code ── */
        .col-qr {
            width: 42%;
            vertical-align: top;
            text-align: center;
        }
        .qr-container {
            background-color: #0f172a;
            border: 1px solid #3b0764;
            border-radius: 8px;
            padding: 16px;
            text-align: center;
        }
        .qr-container img {
            width: 160px;
            height: 160px;
            background-color: #ffffff;
            padding: 8px;
            border-radius: 6px;
            border: 3px solid #22d3ee;
        }
        .qr-note {
            font-size: 9px;
            color: #64748b;
            margin-top: 10px;
            letter-spacing: 0.5px;
        }
        .qr-scan-label {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 6px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* ── Footer Warning Strip ── */
        .footer-strip {
            width: 100%;
            background-color: #0f172a;
            border-top: 1px solid #1e293b;
            padding: 12px 40px;
            margin-top: 0;
        }
        .footer-strip table {
            width: 100%;
            border-collapse: collapse;
        }
        .footer-strip .warn-text {
            font-size: 9px;
            color: #475569;
            letter-spacing: 0.5px;
        }
        .footer-strip .warn-text strong {
            color: #ef4444;
        }
        .footer-copy {
            font-size: 9px;
            color: #334155;
            text-align: right;
        }

        /* ── Valid Badge ── */
        .valid-badge {
            display: inline-block;
            background-color: #14532d;
            border: 1px solid #16a34a;
            color: #4ade80;
            font-size: 9px;
            font-weight: bold;
            padding: 3px 10px;
            border-radius: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 8px;
        }
    </style>
</head>
<body>
<div class="page">

    @foreach($transaction->ticketCodes as $index => $ticketCode)

    @php
        // Generate QR as base64 PNG for DomPDF compatibility
        $qrSvg = QrCode::format('svg')->size(200)->margin(1)->generate($ticketCode->unique_ticket_code);
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
    @endphp

    {{-- Per-ticket page --}}
    <div class="ticket-wrapper">

        {{-- Header --}}
        <div class="header-banner">
            <div class="event-name">SPECTA XXI</div>
            <div class="event-sub">REVELIORA · Celestial Treasure</div>
            <div class="event-org">SMAN 1 Cianjur · E-TIKET RESMI</div>
        </div>

        {{-- Seat Label --}}
        <div class="seat-label">
            TIKET #{{ $index + 1 }} dari {{ $transaction->quantity }}
        </div>

        {{-- Main Content --}}
        <table class="ticket-main">
            <tr>
                {{-- Left: Details --}}
                <td class="col-details">
                    <div class="detail-card">

                        {{-- Name --}}
                        <table class="detail-row">
                            <tr><td>
                                <span class="detail-label">Nama Veloran</span>
                                <span class="detail-value">{{ strtoupper($transaction->buyer_name) }}</span>
                            </td></tr>
                        </table>
                        <hr class="divider">

                        {{-- Class --}}
                        <table class="detail-row">
                            <tr><td>
                                <span class="detail-label">Kelas</span>
                                <span class="detail-value">{{ strtoupper($transaction->buyer_class) }}</span>
                            </td></tr>
                        </table>
                        <hr class="divider">

                        {{-- Ticket Type --}}
                        <table class="detail-row">
                            <tr><td>
                                <span class="detail-label">Jenis Tiket</span>
                                <span class="detail-value cyan">{{ strtoupper($transaction->ticket->ticket_name) }}</span>
                            </td></tr>
                        </table>
                        <hr class="divider">

                        {{-- Invoice --}}
                        <table class="detail-row">
                            <tr><td>
                                <span class="detail-label">No. Invoice</span>
                                <span class="detail-value purple">{{ $transaction->invoice_number }}</span>
                            </td></tr>
                        </table>

                        {{-- Unique Code Box --}}
                        <div class="code-box">
                            <span class="code-label">Kode Tiket Unik</span>
                            <span class="code-value">{{ $ticketCode->unique_ticket_code }}</span>
                        </div>

                        <div style="text-align:center; margin-top:10px;">
                            <span class="valid-badge">✓ Tiket Valid</span>
                        </div>

                    </div>
                </td>

                {{-- Right: QR Code --}}
                <td class="col-qr">
                    <div class="qr-container">
                        <img src="{{ $qrBase64 }}" alt="QR Code Tiket">
                        <div class="qr-scan-label">Scan di Pintu Masuk</div>
                        <div class="qr-note">
                            Tunjukkan QR ini kepada petugas.<br>
                            Berlaku untuk 1 orang, 1 kali masuk.
                        </div>
                    </div>
                </td>
            </tr>
        </table>

    </div>

    {{-- Footer Strip --}}
    <div class="footer-strip">
        <table>
            <tr>
                <td class="warn-text">
                    <strong>⚠ PENTING:</strong> Jangan bagikan kode tiket ini kepada siapapun.
                    Kode ini berlaku untuk 1 kali masuk saja.
                </td>
                <td class="footer-copy">
                    &copy; {{ date('Y') }} SPECTA XXI · SMAN 1 Cianjur
                </td>
            </tr>
        </table>
    </div>

    @endforeach

</div>
</body>
</html>
