<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Tiket SPECTA XXI</title>
    <style>
        @page {
            margin: 0px;
            size: 450px 800px;
        }
        body {
            margin: 0px;
            padding: 0px;
            color: #ffffff;
            font-family: 'Helvetica', sans-serif;
        }
        .page {
            position: relative;
            width: 450px;
            height: 800px;
            overflow: hidden;
            page-break-after: always;
            background-color: #0c0a1d;
        }
        .page:last-child {
            page-break-after: avoid;
        }
    </style>
</head>
<body>

@foreach($transaction->ticketCodes as $index => $ticketCode)

@php
    // -----------------------------------------------------------------------
    // Assets — logo & QR pre-encoded to base64 in Controller.
    // Fallback: encode inline if controller did not supply the variable.
    // -----------------------------------------------------------------------
    $resolvedLogo = $smansaLogoBase64
        ?? (file_exists(public_path('images/smansa-logo.png'))
            ? 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/smansa-logo.png')))
            : '');

    $resolvedSpectaLogo = $spectaLogoBase64
        ?? (file_exists(public_path('images/logo_specta.png'))
            ? 'data:image/png;base64,' . base64_encode(file_get_contents(public_path('images/logo_specta.png')))
            : '');

    $resolvedQr = $qrCodeMap[$ticketCode->id]
        ?? 'data:image/svg+xml;base64,' . base64_encode(
            QrCode::format('svg')->size(110)->margin(0)->generate($ticketCode->unique_ticket_code)
        );
@endphp

<div class="page">

    {{-- ================================================================
         DECORATIVE LAYER — accent strips, watermark, card backgrounds
         All positioned at z-index 0-1 to sit behind content
    ================================================================ --}}

    {{-- Top accent strip — cyan neon --}}
    <div style="position: absolute; top: 0; left: 0; width: 450px; height: 4px;
                background-color: #22d3ee; z-index: 1;"></div>

    {{-- Secondary thin purple line below cyan --}}
    <div style="position: absolute; top: 4px; left: 0; width: 450px; height: 1px;
                background-color: #a855f7; z-index: 1;"></div>

    {{-- Bottom accent strip — purple neon --}}
    <div style="position: absolute; top: 796px; left: 0; width: 450px; height: 4px;
                background-color: #a855f7; z-index: 1;"></div>

    {{-- Secondary thin cyan line above purple --}}
    <div style="position: absolute; top: 795px; left: 0; width: 450px; height: 1px;
                background-color: #22d3ee; z-index: 1;"></div>

    {{-- Subtle watermark "XXI" in background --}}
    <div style="position: absolute; top: 620px; left: 240px;
                font-size: 130px; color: #100e26; font-weight: bold;
                letter-spacing: 8px; z-index: 0;">
        XXI
    </div>

    {{-- Left edge accent — thin vertical cyan glow --}}
    <div style="position: absolute; top: 100px; left: 0; width: 2px; height: 600px;
                background-color: #13112a; z-index: 0;"></div>

    {{-- Right edge accent — thin vertical purple glow --}}
    <div style="position: absolute; top: 100px; left: 448px; width: 2px; height: 600px;
                background-color: #13112a; z-index: 0;"></div>




    {{-- ================================================================
         HEADER — Logo SMANSA + Event Title + Ticket Index
    ================================================================ --}}

    {{-- Logo SMANSA --}}
    <div style="position: absolute; top: 18px; left: 0; width: 450px;
                text-align: center; z-index: 2;">
        @if($resolvedLogo)
        <img src="{{ $resolvedLogo }}"
             style="width: 52px; height: 52px;"
             alt="Logo SMANSA" />
        @endif
    </div>

    {{-- Event Title: SPECTA XXI REVELIORA Logo --}}
    <div style="position: absolute; top: 76px; left: 0; width: 450px;
                text-align: center; z-index: 2;">
        @if($resolvedSpectaLogo)
        <img src="{{ $resolvedSpectaLogo }}"
             style="height: 46px; width: auto;"
             alt="Logo SPECTA" />
        @endif
    </div>

    {{-- Ticket index --}}
    <div style="position: absolute; top: 130px; left: 0; width: 450px;
                text-align: center; z-index: 2;">
        <span style="font-size: 9px; color: #22d3ee; letter-spacing: 2px;">
            TIKET #{{ $index + 1 }} DARI {{ $transaction->quantity }}
        </span>
    </div>

    {{-- Decorative divider line --}}
    <div style="position: absolute; top: 150px; left: 100px; width: 250px; height: 1px;
                background-color: #1e1b38; z-index: 2;"></div>
    <div style="position: absolute; top: 148px; left: 220px; width: 10px; height: 5px;
                background-color: #0c0a1d; z-index: 3;"></div>
    <div style="position: absolute; top: 148px; left: 222px;
                font-size: 7px; color: #a855f7; z-index: 4;">
        &#9670;
    </div>


    {{-- ================================================================
         UPPER CARD — QR Code Area
         Card with dark bg, border, and purple left accent bar
    ================================================================ --}}

    {{-- Card background --}}
    <div style="position: absolute; top: 162px; left: 35px; width: 376px; height: 195px;
                background-color: #11102a; border: 1px solid #252244; z-index: 1;"></div>

    {{-- Purple left accent bar --}}
    <div style="position: absolute; top: 162px; left: 35px; width: 3px; height: 197px;
                background-color: #a855f7; z-index: 2;"></div>

    {{-- Small purple top accent on card --}}
    <div style="position: absolute; top: 162px; left: 35px; width: 60px; height: 2px;
                background-color: #a855f7; z-index: 2;"></div>

    {{-- QR Code white box with cyan border --}}
    <div style="position: absolute; top: 180px; left: 156px; z-index: 3;">
        <div style="background-color: #ffffff;
                    padding: 12px;
                    border: 2px solid #22d3ee;
                    width: 110px;
                    height: 110px;
                    line-height: 0;">
            <img src="{{ $resolvedQr }}"
                 style="width: 110px; height: 110px;"
                 alt="QR Code" />
        </div>
    </div>

    {{-- Status badge: LUNAS & VALID --}}
    <div style="position: absolute; top: 325px; left: 0; width: 450px;
                text-align: center; z-index: 3;">
        <span style="color: #22d3ee; font-weight: bold; font-size: 12px;
                     letter-spacing: 3px;">
            LUNAS &amp; VALID
        </span>
    </div>


    {{-- ================================================================
         LOWER CARD — Transaction Data (4 Rows)
         Card with dark bg, border, and cyan left accent bar
    ================================================================ --}}

    {{-- Card background --}}
    <div style="position: absolute; top: 378px; left: 35px; width: 376px; height: 250px;
                background-color: #11102a; border: 1px solid #252244; z-index: 1;"></div>

    {{-- Cyan left accent bar --}}
    <div style="position: absolute; top: 378px; left: 35px; width: 3px; height: 252px;
                background-color: #22d3ee; z-index: 2;"></div>

    {{-- Small cyan top accent on card --}}
    <div style="position: absolute; top: 378px; left: 35px; width: 60px; height: 2px;
                background-color: #22d3ee; z-index: 2;"></div>

    {{-- ---- Row 1: NAMA VELORAN ---- --}}
    <div style="position: absolute; top: 396px; left: 58px; z-index: 3;">
        <span style="font-size: 7px; color: #64748b; text-transform: uppercase;
                     letter-spacing: 2px; display: block; margin-bottom: 4px;">
            NAMA VELORAN
        </span>
        <span style="font-size: 14px; color: #ffffff; font-weight: bold; display: block;">
            {{ strtoupper($transaction->buyer_name) }}
        </span>
    </div>

    {{-- Divider 1 --}}
    <div style="position: absolute; top: 435px; left: 55px; width: 340px; height: 1px;
                background-color: #1e1b38; z-index: 3;"></div>

    {{-- ---- Row 2: KATEGORI TIKET ---- --}}
    <div style="position: absolute; top: 448px; left: 58px; z-index: 3;">
        <span style="font-size: 7px; color: #64748b; text-transform: uppercase;
                     letter-spacing: 2px; display: block; margin-bottom: 4px;">
            KATEGORI TIKET
        </span>
        <span style="font-size: 14px; color: #a855f7; font-weight: bold; display: block;">
            {{ strtoupper($transaction->ticket->ticket_name ?? 'TIKET REGULER') }}
        </span>
    </div>

    {{-- Divider 2 --}}
    <div style="position: absolute; top: 487px; left: 55px; width: 340px; height: 1px;
                background-color: #1e1b38; z-index: 3;"></div>

    {{-- ---- Row 3: NOMOR INVOICE ---- --}}
    <div style="position: absolute; top: 500px; left: 58px; z-index: 3;">
        <span style="font-size: 7px; color: #64748b; text-transform: uppercase;
                     letter-spacing: 2px; display: block; margin-bottom: 4px;">
            NOMOR INVOICE
        </span>
        <span style="font-family: 'Courier New', monospace; font-size: 13px;
                     color: #ffffff; font-weight: bold; display: block;">
            {{ $transaction->invoice_number }}
        </span>
    </div>

    {{-- Divider 3 --}}
    <div style="position: absolute; top: 539px; left: 55px; width: 340px; height: 1px;
                background-color: #1e1b38; z-index: 3;"></div>

    {{-- ---- Row 4: KODE TIKET UNIK ---- --}}
    <div style="position: absolute; top: 552px; left: 58px; z-index: 3;">
        <span style="font-size: 7px; color: #64748b; text-transform: uppercase;
                     letter-spacing: 2px; display: block; margin-bottom: 4px;">
            KODE TIKET UNIK
        </span>
        <span style="font-family: 'Courier New', monospace; font-size: 13px;
                     color: #22d3ee; font-weight: bold; display: block;">
            {{ $ticketCode->unique_ticket_code }}
        </span>
    </div>


    {{-- ================================================================
         DECORATIVE — small corner accents on lower card
    ================================================================ --}}

    {{-- Bottom-right corner tick on lower card --}}
    <div style="position: absolute; top: 628px; left: 391px; width: 20px; height: 2px;
                background-color: #252244; z-index: 2;"></div>
    <div style="position: absolute; top: 610px; left: 409px; width: 2px; height: 20px;
                background-color: #252244; z-index: 2;"></div>


    {{-- ================================================================
         FOOTER — Disclaimer & event branding
    ================================================================ --}}

    {{-- Small decorative divider before footer --}}
    <div style="position: absolute; top: 680px; left: 175px; width: 100px; height: 1px;
                background-color: #1e1b38; z-index: 2;"></div>

    <div style="position: absolute; top: 695px; left: 40px; width: 370px;
                text-align: center; font-size: 7px; color: #3d3a56;
                line-height: 1.6; z-index: 2;">
        Scan QR Code ini pada pintu masuk. Satu QR code hanya berlaku untuk satu kali pemindaian.
        PENTING: Dilarang keras menggandakan, memperjualbelikan kembali, atau membagikan e-ticket
        ini kepada pihak lain. Panitia SPECTA XXI REVELIORA tidak bertanggung jawab atas
        segala bentuk penyalahgunaan tiket.
    </div>

    {{-- Footer event mark --}}
    <div style="position: absolute; top: 765px; left: 0; width: 450px;
                text-align: center; z-index: 2;">
        @if($resolvedSpectaLogo)
        <img src="{{ $resolvedSpectaLogo }}"
             style="height: 14px; width: auto; opacity: 0.7;"
             alt="Logo SPECTA" />
        <span style="font-size: 8px; color: #3d3a56; letter-spacing: 2px; margin-left: 5px; vertical-align: middle;">2026</span>
        @endif
    </div>

</div>
@endforeach

</body>
</html>