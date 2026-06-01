<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Timeline extends Model
{
    protected $fillable = [
        'year', 'title', 'subtitle', 'description',
        'image_path', 'is_current', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'is_current' => 'boolean',
        'is_active'  => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderByDesc('year');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::url($this->image_path) : null;
    }
}
