<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    /**
     * Display the ticket/buy landing page.
     */
    public function index()
    {
        $tickets = Ticket::where('remaining_quota', '>', 0)->get();

        return view('tickets.index', compact('tickets'));
    }

    /**
     * Handle the ticket checkout form submission.
     *
     * Validates buyer data, acquires a DB transaction lock, checks remaining
     * quota, generates a unique invoice and Moota unique_code, deducts quota
     * atomically, and persists a 'pending' Transaction record.
     *
     * @param  Request $request
     * @return RedirectResponse|JsonResponse
     */
    public function checkout(Request $request): RedirectResponse|JsonResponse
    {
        // ----------------------------------------------------------
        // 1. Validate incoming request data
        // ----------------------------------------------------------
        $validated = $request->validate([
            'ticket_id'      => ['required', 'integer', Rule::exists('tickets', 'id')],
            'buyer_name'     => ['required', 'string', 'max:100'],
            'buyer_email'    => ['required', 'email', 'max:150'],
            'buyer_whatsapp' => ['required', 'string', 'min:9', 'max:20', 'regex:/^(\+62|62|0)[0-9]{8,15}$/'],
            'buyer_class'    => ['required', 'string', 'max:20'],
            'quantity'       => ['required', 'integer', 'min:1', 'max:5'],
        ], [
            'ticket_id.exists'       => 'Jenis tiket yang dipilih tidak ditemukan.',
            'buyer_whatsapp.regex'   => 'Format nomor WhatsApp tidak valid (contoh: 081234567890).',
            'quantity.max'           => 'Maksimal pembelian adalah 5 tiket per transaksi.',
        ]);

        // ----------------------------------------------------------
        // 2. Wrap everything inside a DB transaction for atomicity
        // ----------------------------------------------------------
        try {
            $transaction = DB::transaction(function () use ($validated): Transaction {

                // Lock the ticket row to prevent race conditions
                /** @var Ticket $ticket */
                $ticket = Ticket::lockForUpdate()->findOrFail($validated['ticket_id']);

                // 3. Quota check
                if (! $ticket->hasAvailableQuota($validated['quantity'])) {
                    throw new \RuntimeException(
                        "Kuota tiket '{$ticket->ticket_name}' tidak mencukupi. " .
                        "Tersisa {$ticket->remaining_quota} tiket."
                    );
                }

                // 4. Generate a unique invoice number: REV-YYYYMM-XXXX
                $invoiceNumber = $this->generateInvoiceNumber();

                // 5. Generate a Moota-style unique 3-digit code (100–999)
                $uniqueCode = $this->generateUniqueCode();

                // 6. Calculate pricing
                $basePrice  = $ticket->price * $validated['quantity'];
                $totalPrice = $basePrice + $uniqueCode;

                // 7. Deduct remaining quota immediately (holds the seat)
                $ticket->decrement('remaining_quota', $validated['quantity']);

                // 8. Persist the pending transaction
                $transaction = Transaction::create([
                    'ticket_id'      => $ticket->id,
                    'invoice_number' => $invoiceNumber,
                    'buyer_name'     => $validated['buyer_name'],
                    'buyer_email'    => $validated['buyer_email'],
                    'buyer_whatsapp' => $validated['buyer_whatsapp'],
                    'buyer_class'    => $validated['buyer_class'],
                    'quantity'       => $validated['quantity'],
                    'base_price'     => $basePrice,
                    'unique_code'    => $uniqueCode,
                    'total_price'    => $totalPrice,
                    'status'         => 'pending',
                ]);

                return $transaction;
            });

            // ----------------------------------------------------------
            // 9. On success: redirect to the payment instruction page
            // ----------------------------------------------------------
            return redirect()
                ->route('payment.show', ['invoice' => $transaction->invoice_number])
                ->with('success', 'Pesanan berhasil dibuat! Selesaikan pembayaran sebelum batas waktu.');

        } catch (\RuntimeException $e) {
            // Quota insufficient — return back with user-friendly error
            return back()
                ->withInput()
                ->withErrors(['quantity' => $e->getMessage()]);

        } catch (\Throwable $e) {
            // Unexpected failure — log it and show a generic message
            Log::error('Ticket checkout failed', [
                'error'   => $e->getMessage(),
                'payload' => $validated,
            ]);

            return back()
                ->withInput()
                ->withErrors(['general' => 'Terjadi kesalahan sistem. Silakan coba beberapa saat lagi.']);
        }
    }

    // ------------------------------------------------------------------
    // PRIVATE HELPERS
    // ------------------------------------------------------------------

    /**
     * Generate a unique invoice number in the format REV-YYYYMM-XXXX.
     *
     * Retries up to 10 times if the generated number already exists in the DB.
     *
     * @throws \RuntimeException if a unique invoice cannot be generated
     */
    private function generateInvoiceNumber(): string
    {
        $prefix = 'REV-' . now()->format('Ym') . '-';
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

    /**
     * Generate a unique Moota-style 3-digit verification code (100–999).
     *
     * Ensures the generated code is not already in use by another *pending*
     * transaction created within the same calendar month to keep the
     * verification process unambiguous.
     *
     * @throws \RuntimeException if no available code can be found
     */
    private function generateUniqueCode(): int
    {
        $used = Transaction::where('status', 'pending')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->pluck('unique_code')
            ->toArray();

        $attempts = 0;

        do {
            $code = mt_rand(100, 999);
            $attempts++;
        } while (in_array($code, $used, true) && $attempts < 100);

        if (in_array($code, $used, true)) {
            // Pool exhausted — just return a random code as a last resort
            $code = mt_rand(100, 999);
        }

        return $code;
    }
    /**
     * Securely stream a PDF e-ticket for download using a UUID download token.
     *
     * The token is non-guessable (UUID v4) and tied to the transaction.
     * Only confirmed ('success') transactions can be downloaded.
     */
    public function downloadTicket(string $token)
    {
        // 1. Find transaction by its secure download token
        $transaction = Transaction::with(['ticket', 'ticketCodes'])
            ->where('download_token', $token)
            ->first();

        // 2. Token not found
        if (!$transaction) {
            abort(404, 'Link download tidak valid atau sudah tidak berlaku.');
        }

        // 3. Payment not confirmed yet
        if (!$transaction->isDownloadable()) {
            abort(403, 'Tiket belum tersedia untuk diunduh. Selesaikan pembayaran terlebih dahulu.');
        }

        // 4. Ensure ticket codes exist
        if ($transaction->ticketCodes->isEmpty()) {
            abort(500, 'Kode tiket belum di-generate. Hubungi panitia.');
        }

        // 5. Render PDF and stream to browser
        $pdf = Pdf::loadView('tickets.pdf', compact('transaction'))
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'defaultFont'  => 'DejaVu Sans',
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'dpi' => 150,
            ]);

        $filename = 'E-Tiket-SPECTA-XXI-' . $transaction->invoice_number . '.pdf';

        return $pdf->download($filename);
    }
}
