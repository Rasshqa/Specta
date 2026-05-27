<?php

namespace App\Mail;

use App\Models\Transaction;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ETicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public Transaction $transaction;
    public string $pdfData;

    /**
     * Create a new message instance.
     */
    public function __construct(Transaction $transaction, string $pdfData)
    {
        $this->transaction = $transaction;
        $this->pdfData = $pdfData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎟️ E-Tiket SPECTA XXI: REVELIORA – ' . $this->transaction->invoice_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.eticket',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfData, 'SPECTA-XXI-Ticket-' . $this->transaction->invoice_number . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}
