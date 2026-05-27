<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EskulProfile extends Model
{
    protected $fillable = [
        'name', 'icon', 'description', 'detail',
        'schedule', 'contact', 'activities', 'achievements',
        'image_path', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? asset('storage/' . $this->image_path) : null;
    }
}
