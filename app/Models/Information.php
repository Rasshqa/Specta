<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Information extends Model
{
    use HasFactory;

    protected $table = 'informations';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'content',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($info) {
            if (empty($info->slug) || $info->isDirty('title')) {
                $info->slug = Str::slug($info->title) . '-' . substr(uniqid(), -5);
            }
        });
    }
}
