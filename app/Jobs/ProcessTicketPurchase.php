<?php

namespace App\Jobs;

use App\Mail\ETicketMail;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ProcessTicketPurchase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Transaction $transaction;

    /**
     * Create a new job instance.
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('ProcessTicketPurchase started for transaction: ' . $this->transaction->invoice_number);

        try {
            // Ensure relationships are loaded
            $this->transaction->loadMissing(['ticket', 'ticketCodes']);

            // Render PDF from view
            // We pass the QrCode facade explicitly if needed, but it's globally available in views usually
            $pdf = Pdf::loadView('pdf.eticket', [
                'transaction' => $this->transaction
            ]);
            
            // Output the generated PDF to a variable
            $pdfContent = $pdf->output();

            // Send Email with the attached PDF
            Mail::to($this->transaction->buyer_email)->send(new ETicketMail($this->transaction, $pdfContent));

            Log::info('ProcessTicketPurchase completed successfully for transaction: ' . $this->transaction->invoice_number);
        } catch (\Exception $e) {
            Log::error('ProcessTicketPurchase failed: ' . $e->getMessage(), [
                'transaction_id' => $this->transaction->id,
                'trace' => $e->getTraceAsString()
            ]);
            
            throw $e; // Rethrow to let the queue system handle retries/failures
        }
    }
}
