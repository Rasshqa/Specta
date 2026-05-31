<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Tiket SPECTA XXI</title>
</head>
<body style="margin:0;padding:0;background-color:#0f0e2a;font-family:Arial,Helvetica,sans-serif;">

    <!-- Outer Wrapper -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#0f0e2a" style="background-color:#0f0e2a;padding:32px 16px;">
        <tr>
            <td align="center">

                <!-- Card -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:560px;width:560px;background-color:#1a1836;border-radius:12px;border:1px solid #3b3676;overflow:hidden;">

                    <!-- ══ HEADER ══ -->
                    <tr>
                        <td align="center" bgcolor="#2d1b6e" style="background:linear-gradient(135deg,#4c1d95 0%,#1e1b4b 100%);padding:36px 24px 32px 24px;">
                            <h1 style="margin:0;color:#c4b5fd;font-size:28px;font-weight:800;letter-spacing:4px;font-family:Arial,sans-serif;">SPECTA XXI</h1>
                            <p style="margin:10px 0 0 0;color:#a78bfa;font-size:12px;letter-spacing:2px;font-family:Arial,sans-serif;">REVELIORA &nbsp;&middot;&nbsp; CELESTIAL TREASURE</p>
                        </td>
                    </tr>

                    <!-- ══ GREETING ══ -->
                    <tr>
                        <td style="padding:28px 28px 0 28px;">
                            <p style="margin:0 0 8px 0;color:#f8fafc;font-size:16px;font-family:Arial,sans-serif;">Halo, <strong style="color:#a78bfa;">{{ $buyer_name }}</strong>! 🎉</p>
                            <p style="margin:0 0 24px 0;color:#cbd5e1;font-size:14px;line-height:1.7;font-family:Arial,sans-serif;">
                                Selamat! Pembayaran kamu telah <strong style="color:#ffffff;">berhasil dikonfirmasi</strong>.
                                E-Tiket resmi kamu sudah siap untuk didownload. Tunjukkan QR Code yang ada di PDF tersebut kepada petugas di pintu masuk saat hari acara.
                            </p>
                        </td>
                    </tr>

                    <!-- ══ STATUS BOX ══ -->
                    <tr>
                        <td style="padding:0 28px 20px 28px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#052e16" style="background-color:#052e16;border:2px solid #16a34a;border-radius:10px;">
                                <tr>
                                    <td align="center" style="padding:24px 20px;">
                                        <p style="margin:0 0 6px 0;font-size:26px;">🎟️</p>
                                        <p style="margin:0 0 8px 0;color:#4ade80;font-size:18px;font-weight:700;font-family:Arial,sans-serif;">Pembayaran Dikonfirmasi!</p>
                                        <p style="margin:0 0 18px 0;color:#86efac;font-size:13px;font-family:Arial,sans-serif;">E-Tiket kamu sudah siap. Simpan PDF ini baik-baik.</p>
                                        <a href="{{ $download_url }}" style="display:inline-block;background-color:#7c3aed;color:#ffffff;text-decoration:none;padding:12px 30px;border-radius:6px;font-weight:700;font-size:14px;font-family:Arial,sans-serif;">&#11015; Download E-Tiket (PDF)</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ══ INVOICE NUMBER ══ -->
                    <tr>
                        <td align="center" style="padding:0 28px 20px 28px;">
                            <p style="margin:0;color:#60a5fa;font-size:14px;font-weight:700;letter-spacing:1px;font-family:Arial,sans-serif;">{{ $invoice_number }}</p>
                        </td>
                    </tr>

                    <!-- ══ DIVIDER ══ -->
                    <tr>
                        <td style="padding:0 28px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="border-top:1px solid #3b3676;font-size:0;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ══ DETAIL ROWS ══ -->
                    <!-- Row: Nama Pembeli -->
                    <tr>
                        <td style="padding:14px 28px 0 28px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="color:#94a3b8;font-size:13px;font-family:Arial,sans-serif;">Nama Pembeli</td>
                                    <td align="right" style="color:#ffffff;font-size:13px;font-weight:700;font-family:Arial,sans-serif;">{{ $buyer_name }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Divider -->
                    <tr><td style="padding:10px 28px 0 28px;"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="border-top:1px solid #2d2b5e;font-size:0;">&nbsp;</td></tr></table></td></tr>

                    <!-- Row: Jenis Tiket -->
                    <tr>
                        <td style="padding:14px 28px 0 28px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="color:#94a3b8;font-size:13px;font-family:Arial,sans-serif;">Jenis Tiket</td>
                                    <td align="right" style="color:#ffffff;font-size:13px;font-weight:700;font-family:Arial,sans-serif;">{{ $ticket_name }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Divider -->
                    <tr><td style="padding:10px 28px 0 28px;"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="border-top:1px solid #2d2b5e;font-size:0;">&nbsp;</td></tr></table></td></tr>

                    <!-- Row: Jumlah Tiket -->
                    <tr>
                        <td style="padding:14px 28px 0 28px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="color:#94a3b8;font-size:13px;font-family:Arial,sans-serif;">Jumlah Tiket</td>
                                    <td align="right" style="color:#ffffff;font-size:13px;font-weight:700;font-family:Arial,sans-serif;">{{ $quantity }} Tiket</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- Divider -->
                    <tr><td style="padding:10px 28px 0 28px;"><table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td style="border-top:1px solid #2d2b5e;font-size:0;">&nbsp;</td></tr></table></td></tr>

                    <!-- Row: Total Bayar -->
                    <tr>
                        <td style="padding:14px 28px 28px 28px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="color:#94a3b8;font-size:14px;font-weight:700;font-family:Arial,sans-serif;">Total Bayar</td>
                                    <td align="right" style="color:#fbbf24;font-size:18px;font-weight:800;font-family:Arial,sans-serif;">Rp {{ number_format($total_price, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ══ STEPS ══ -->
                    <tr>
                        <td style="padding:0 28px 28px 28px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#0f0e2a" style="background-color:#0f0e2a;border-radius:8px;border:1px solid #2d2b5e;">
                                <!-- Title -->
                                <tr>
                                    <td style="padding:18px 20px 12px 20px;">
                                        <p style="margin:0;color:#6b7280;font-size:11px;text-transform:uppercase;letter-spacing:2px;font-weight:700;font-family:Arial,sans-serif;">CARA MENGGUNAKAN E-TIKET</p>
                                    </td>
                                </tr>
                                <!-- Step 1 -->
                                <tr>
                                    <td style="padding:0 20px 12px 20px;">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td valign="top" width="32" style="padding-top:1px;">
                                                    <table cellpadding="0" cellspacing="0" border="0" width="22" height="22" bgcolor="#2563eb" style="background-color:#2563eb;border-radius:11px;">
                                                        <tr><td align="center" style="color:#ffffff;font-size:11px;font-weight:700;font-family:Arial,sans-serif;line-height:22px;">1</td></tr>
                                                    </table>
                                                </td>
                                                <td style="color:#cbd5e1;font-size:13px;line-height:1.6;font-family:Arial,sans-serif;">
                                                    <strong style="color:#f8fafc;">Download dan simpan</strong> file PDF tiket melalui tombol di atas ke HP atau device kamu.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Step 2 -->
                                <tr>
                                    <td style="padding:0 20px 12px 20px;">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td valign="top" width="32" style="padding-top:1px;">
                                                    <table cellpadding="0" cellspacing="0" border="0" width="22" height="22" bgcolor="#2563eb" style="background-color:#2563eb;border-radius:11px;">
                                                        <tr><td align="center" style="color:#ffffff;font-size:11px;font-weight:700;font-family:Arial,sans-serif;line-height:22px;">2</td></tr>
                                                    </table>
                                                </td>
                                                <td style="color:#cbd5e1;font-size:13px;line-height:1.6;font-family:Arial,sans-serif;">
                                                    Datang ke lokasi acara sesuai jadwal dan antri di <strong style="color:#f8fafc;">pintu masuk</strong>.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Step 3 -->
                                <tr>
                                    <td style="padding:0 20px 12px 20px;">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td valign="top" width="32" style="padding-top:1px;">
                                                    <table cellpadding="0" cellspacing="0" border="0" width="22" height="22" bgcolor="#2563eb" style="background-color:#2563eb;border-radius:11px;">
                                                        <tr><td align="center" style="color:#ffffff;font-size:11px;font-weight:700;font-family:Arial,sans-serif;line-height:22px;">3</td></tr>
                                                    </table>
                                                </td>
                                                <td style="color:#cbd5e1;font-size:13px;line-height:1.6;font-family:Arial,sans-serif;">
                                                    Tunjukkan <strong style="color:#f8fafc;">QR Code</strong> yang ada di PDF kepada petugas untuk di-scan.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <!-- Step 4 -->
                                <tr>
                                    <td style="padding:0 20px 20px 20px;">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td valign="top" width="32" style="padding-top:1px;">
                                                    <table cellpadding="0" cellspacing="0" border="0" width="22" height="22" bgcolor="#2563eb" style="background-color:#2563eb;border-radius:11px;">
                                                        <tr><td align="center" style="color:#ffffff;font-size:11px;font-weight:700;font-family:Arial,sans-serif;line-height:22px;">4</td></tr>
                                                    </table>
                                                </td>
                                                <td style="color:#cbd5e1;font-size:13px;line-height:1.6;font-family:Arial,sans-serif;">
                                                    Jangan bagikan kode tiket ini kepada siapapun. Setiap kode hanya berlaku untuk <strong style="color:#f8fafc;">1 kali masuk</strong>.
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ══ FOOTER ══ -->
                    <tr>
                        <td align="center" bgcolor="#0d0c26" style="background-color:#0d0c26;padding:16px 28px;border-top:1px solid #2d2b5e;">
                            <p style="margin:0;color:#6b7280;font-size:11px;line-height:1.7;font-family:Arial,sans-serif;">
                                Email ini dikirim secara otomatis oleh sistem SPECTA XXI.<br>
                                Mohon tidak membalas email ini.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Card -->

            </td>
        </tr>
    </table>

</body>
</html>
