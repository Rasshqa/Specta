<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Documentation extends Model
{
    protected $fillable = [
        'title',
        'description',
        'file_path',
        'file_type',
        'event_date',
        'is_active',
    ];

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
