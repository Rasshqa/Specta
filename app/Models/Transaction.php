<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ticket_id',
        'invoice_number',
        'download_token',
        'buyer_name',
        'buyer_email',
        'buyer_whatsapp',
        'buyer_class',
        'quantity',
        'base_price',
        'unique_code',
        'total_price',
        'status',
    ];

    /**
     * Auto-generate a secure UUID download token on creation.
     */
    protected static function booted(): void
    {
        static::creating(function (Transaction $transaction) {
            if (empty($transaction->download_token)) {
                $transaction->download_token = Str::uuid()->toString();
            }
        });
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'quantity'    => 'integer',
        'base_price'  => 'integer',
        'unique_code' => 'integer',
        'total_price' => 'integer',
    ];

    /**
     * A transaction belongs to a ticket type.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    /**
     * A transaction produces many individual ticket codes.
     */
    public function ticketCodes(): HasMany
    {
        return $this->hasMany(TicketCode::class, 'transaction_id');
    }

    /**
     * Check whether this transaction is still awaiting payment.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check whether payment for this transaction was confirmed.
     */
    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Check whether this transaction has expired.
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    /**
     * Check whether the transaction is eligible for PDF download.
     */
    public function isDownloadable(): bool
    {
        return $this->status === 'success';
    }

    /**
     * Generate a unique, deterministic ticket code per seat based on the invoice.
     */
    public function generateTicketCode(int $index): string
    {
        $raw = strtoupper(hash('sha256', $this->invoice_number . '-' . $index . '-' . config('app.key')));
        return 'TKT-' . substr($raw, 0, 5) . '-' . substr($raw, 5, 5) . '-' . ($index + 1);
    }
}
