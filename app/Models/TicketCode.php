<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketCode extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'transaction_id',
        'unique_ticket_code',
        'is_scanned',
        'scanned_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_scanned' => 'boolean',
        'scanned_at' => 'datetime',
    ];

    /**
     * A ticket code belongs to one transaction.
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id');
    }

    /**
     * Mark this ticket code as scanned.
     */
    public function markAsScanned(): void
    {
        $this->update([
            'is_scanned' => true,
            'scanned_at' => now(),
        ]);
    }
}
