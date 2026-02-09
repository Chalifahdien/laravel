<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sticker extends Model
{
    protected $fillable = [
        'name',
        'image_path',
        'is_active',
        'category',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
