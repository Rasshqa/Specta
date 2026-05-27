<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SPECTA XXI E-Ticket</title>
    <style>
        @page { margin: 0px; }
        body {
            margin: 0px;
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #020617; /* slate-950 */
            color: #ffffff;
        }
        .page-break {
            page-break-after: always;
        }
        .ticket-container {
            width: 100%;
            height: 100vh;
            padding: 40px;
            box-sizing: border-box;
            background-image: linear-gradient(135deg, #020617 0%, #1e1b4b 100%);
        }
        .ticket-card {
            background-color: rgba(15, 23, 42, 0.8);
            border: 2px solid #a855f7; /* purple-500 */
            border-radius: 16px;
            padding: 30px;
            text-align: center;
            height: 90%;
            position: relative;
        }
        .header h1 {
            color: #22d3ee; /* cyan-400 */
            font-size: 36px;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .header h2 {
            color: #c026d3; /* fuchsia-600 */
            font-size: 20px;
            margin-top: 0;
            font-weight: normal;
        }
        .qr-section {
            background-color: #ffffff;
            padding: 20px;
            display: inline-block;
            border-radius: 12px;
            margin: 30px 0;
            border: 4px solid #22d3ee;
        }
        .details-section {
            margin-top: 20px;
            text-align: left;
            padding: 0 40px;
        }
        .details-row {
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            padding-bottom: 5px;
        }
        .details-label {
            color: #94a3b8; /* slate-400 */
            font-size: 14px;
            text-transform: uppercase;
        }
        .details-value {
            color: #f8fafc;
            font-size: 22px;
            font-weight: bold;
        }
        .ticket-code-text {
            color: #a855f7;
            font-size: 24px;
            letter-spacing: 4px;
            margin-top: 10px;
            font-family: monospace;
        }
        .footer {
            position: absolute;
            bottom: 30px;
            left: 0;
            width: 100%;
            text-align: center;
            color: #64748b;
            font-size: 12px;
        }
    </style>
</head>
<body>
    @foreach($transaction->ticketCodes as $index => $ticketCode)
        <div class="ticket-container {{ !$loop->last ? 'page-break' : '' }}">
            <div class="ticket-card">
                
                <div class="header">
                    <h1>SPECTA XXI</h1>
                    <h2>REVELIORA</h2>
                </div>

                <div class="qr-section">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(QrCode::format('svg')->size(250)->generate($ticketCode->unique_ticket_code)) }}" alt="QR Code">
                </div>
                
                <div class="ticket-code-text">
                    {{ $ticketCode->unique_ticket_code }}
                </div>

                <div class="details-section">
                    <div class="details-row">
                        <div class="details-label">Nama Veloran</div>
                        <div class="details-value">{{ strtoupper($transaction->buyer_name) }}</div>
                    </div>
                    
                    <div class="details-row">
                        <div class="details-label">Jenis Tiket</div>
                        <div class="details-value">{{ strtoupper($transaction->ticket->ticket_name) }}</div>
                    </div>

                    <div class="details-row">
                        <div class="details-label">No. Invoice</div>
                        <div class="details-value">{{ $transaction->invoice_number }}</div>
                    </div>
                </div>

                <div class="footer">
                    Berlaku untuk 1 Orang • Jangan Bagikan Kode Ini<br>
                    SMAN 1 Cianjur
                </div>
                
            </div>
        </div>
    @endforeach
</body>
</html>
