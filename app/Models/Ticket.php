<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'ticket_name',
        'price',
        'quota',
        'remaining_quota',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price'           => 'integer',
        'quota'           => 'integer',
        'remaining_quota' => 'integer',
    ];

    /**
     * A ticket can have many transactions.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'ticket_id');
    }

    /**
     * Check whether a given quantity is still available.
     */
    public function hasAvailableQuota(int $quantity): bool
    {
        return $this->remaining_quota >= $quantity;
    }
}
