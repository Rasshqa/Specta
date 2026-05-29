<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketOrderController extends Controller
{
    /**
     * The rigid ticket price — enforced globally. No bypass allowed.
     */
    private const TICKET_PRICE = 100000;

    /**
     * Display the ticket/buy landing page.
     */
    public function index()
    {
        // Cache available tickets — rarely change, expensive to re-query
        $tickets = cache()->remember('tickets_available', 300, function () {
            return Ticket::select(['id', 'ticket_name', 'price', 'remaining_quota'])
                ->where('remaining_quota', '>', 0)
                ->get();
        });

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Handle the ticket checkout form submission.
     * Manual QRIS/Transfer Order Flow.
     */
    public function checkout(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'buyer_name'     => ['required', 'string', 'max:100'],
            'buyer_email'    => ['required', 'email', 'max:150'],
            'buyer_whatsapp' => ['required', 'string', 'min:9', 'max:20', 'regex:/^(\+62|62|0)[0-9]{8,15}$/'],
            'quantity'       => ['required', 'integer', 'min:1', 'max:5'],
        ], [
            'buyer_whatsapp.regex'   => 'Format nomor WhatsApp tidak valid (contoh: 081234567890).',
            'quantity.max'           => 'Maksimal pembelian adalah 5 tiket per transaksi.',
        ]);

        try {
            $transaction = DB::transaction(function () use ($validated): Transaction {
                /** @var Ticket $ticket */
                $ticket = Ticket::where('ticket_name', 'Tiket Reguler')->lockForUpdate()->first();
                if (!$ticket) {
                    $ticket = Ticket::lockForUpdate()->first();
                }

                if (!$ticket) {
                    throw new \RuntimeException("Tidak ada tiket yang aktif saat ini.");
                }

                if (! $ticket->hasAvailableQuota($validated['quantity'])) {
                    throw new \RuntimeException(
                        "Kuota tiket '{$ticket->ticket_name}' tidak mencukupi. " .
                        "Tersisa {$ticket->remaining_quota} tiket."
                    );
                }

                $invoiceNumber = $this->generateInvoiceNumber();

                // Rigid price — IDR 100,000 per ticket, no exceptions
                $unitPrice  = self::TICKET_PRICE;
                $basePrice  = $unitPrice * $validated['quantity'];
                $totalPrice = $basePrice;

                $ticket->decrement('remaining_quota', $validated['quantity']);

                return Transaction::create([
                    'ticket_id'      => $ticket->id,
                    'invoice_number' => $invoiceNumber,
                    'buyer_name'     => $validated['buyer_name'],
                    'buyer_email'    => $validated['buyer_email'],
                    'buyer_whatsapp' => $validated['buyer_whatsapp'],
                    'quantity'       => $validated['quantity'],
                    'base_price'     => $basePrice,
                    'unique_code'    => 0,
                    'total_price'    => $totalPrice,
                    'status'         => 'PENDING_PROOF',
                ]);
            });

            return redirect()
                ->route('payment.show', ['invoice' => $transaction->invoice_number])
                ->with('success', 'Pesanan berhasil dibuat! Silakan unggah bukti pembayaran Anda.');

            // Invalidate caches that depend on quota/stats
            cache()->forget('tickets_available');
            cache()->forget('admin_dashboard_stats');
            cache()->forget('admin_tickets_list');

        } catch (\RuntimeException $e) {
            return back()->withInput()->withErrors(['quantity' => $e->getMessage()]);
        } catch (\Throwable $e) {
            Log::error('Ticket checkout failed', ['error' => $e->getMessage(), 'payload' => $validated]);
            return back()->withInput()->withErrors(['general' => 'Terjadi kesalahan sistem. Silakan coba beberapa saat lagi.']);
        }
    }

    /**
     * Handle payment proof upload.
     * Compresses image to 800px wide JPEG saved with .jfif extension at 60% quality.
     */
    public function uploadProof(Request $request, string $invoice): RedirectResponse
    {
        $request->validate([
            'payment_proof' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ], [
            'payment_proof.required' => 'Harap unggah bukti pembayaran.',
            'payment_proof.mimes'    => 'Format file harus JPG, PNG, atau WEBP.',
            'payment_proof.max'      => 'Ukuran file maksimal 10MB.',
        ]);

        $transaction = Transaction::where('invoice_number', $invoice)
            ->where('status', 'PENDING_PROOF')
            ->firstOrFail();

        try {
            $file     = $request->file('payment_proof');
            $filename = 'proof_' . $invoice . '.jfif';
            $destPath = storage_path('app/public/proofs/' . $filename);

            // Ensure directory exists
            if (!file_exists(storage_path('app/public/proofs'))) {
                mkdir(storage_path('app/public/proofs'), 0755, true);
            }

            // Delete old proof if exists
            if ($transaction->payment_proof) {
                $oldPath = storage_path('app/public/proofs/' . $transaction->payment_proof);
                if (file_exists($oldPath)) {
                    @unlink($oldPath);
                }
            }

            // Use InterventionImage v3 if available, otherwise GD fallback
            if (class_exists(\Intervention\Image\ImageManager::class)) {
                $this->compressWithIntervention($file->getRealPath(), $destPath);
            } else {
                $this->compressWithGd($file->getRealPath(), $destPath, $file->getMimeType());
            }

            $transaction->update(['payment_proof' => $filename]);

            return redirect()
                ->route('payment.show', $invoice)
                ->with('success', 'Bukti pembayaran berhasil diunggah! Tim kami akan memverifikasi dalam 1×24 jam.');

        } catch (\Throwable $e) {
            Log::error('Proof upload failed', ['invoice' => $invoice, 'error' => $e->getMessage()]);
            return back()->withErrors(['payment_proof' => 'Gagal mengunggah file. Silakan coba lagi.']);
        }
    }

    /**
     * Compress using InterventionImage v3 (preferred).
     */
    private function compressWithIntervention(string $sourcePath, string $destPath): void
    {
        $manager = new \Intervention\Image\ImageManager(
            new \Intervention\Image\Drivers\Gd\Driver()
        );

        $image = $manager->read($sourcePath);

        // Scale down to max 800px width, maintain aspect ratio
        if ($image->width() > 800) {
            $image->scale(width: 800);
        }

        // Encode as JPEG (JFIF is JPEG format) at 60% quality and save
        $image->toJpeg(60)->save($destPath);
    }

    /**
     * Compress using native PHP GD (fallback if Intervention not installed).
     */
    private function compressWithGd(string $sourcePath, string $destPath, string $mimeType): void
    {
        [$origWidth, $origHeight] = getimagesize($sourcePath);

        $srcImage = match ($mimeType) {
            'image/png'  => imagecreatefrompng($sourcePath),
            'image/webp' => imagecreatefromwebp($sourcePath),
            default      => imagecreatefromjpeg($sourcePath),
        };

        if (!$srcImage) {
            throw new \RuntimeException('Gagal memproses file gambar.');
        }

        // Scale to max 800px width
        if ($origWidth > 800) {
            $newWidth  = 800;
            $newHeight = (int) round(($origHeight / $origWidth) * 800);
        } else {
            $newWidth  = $origWidth;
            $newHeight = $origHeight;
        }

        $dstImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG
        if ($mimeType === 'image/png') {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
            $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
            imagefilledrectangle($dstImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        // Save as JPEG (JFIF) at 60% quality
        imagejpeg($dstImage, $destPath, 60);

        imagedestroy($srcImage);
        imagedestroy($dstImage);
    }

    /**
     * Download the purchased E-Ticket as PDF.
     * Only accessible via the secure download_token. Transaction must be SUCCESS.
     */
    public function downloadTicket(string $token)
    {
        $transaction = Transaction::select([
                'id', 'invoice_number', 'ticket_id', 'buyer_name', 'buyer_email',
                'buyer_whatsapp', 'quantity', 'total_price', 'status', 'download_token', 'created_at'
            ])
            ->with([
                'ticket:id,ticket_name,price',
                'ticketCodes:id,transaction_id,unique_ticket_code,is_scanned'
            ])
            ->where('download_token', $token)
            ->where('status', 'SUCCESS')
            ->firstOrFail();

        $pdf = Pdf::loadView('tickets.pdf', compact('transaction'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("SPECTA-XXI-Ticket-{$transaction->invoice_number}.pdf");
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    private function generateInvoiceNumber(): string
    {
        $prefix   = 'REV-' . now()->format('Ym') . '-';
        $attempts = 0;

        do {
            $suffix  = strtoupper(substr(md5(uniqid((string) mt_rand(), true)), 0, 6));
            $invoice = $prefix . $suffix;
            $exists  = Transaction::where('invoice_number', $invoice)->exists();
            $attempts++;
        } while ($exists && $attempts < 10);

        if ($exists) {
            throw new \RuntimeException('Gagal membuat nomor invoice unik. Coba lagi.');
        }

        return $invoice;
    }
}
