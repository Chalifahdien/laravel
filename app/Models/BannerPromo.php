<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerPromo extends Model
{
    protected $fillable = [
        'title',
        'image',
        'link',
        'sort_order',
        'is_active',
        'start_at',
        'end_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];

    public function machines()
    {
        return $this->belongsToMany(Machine::class, 'banner_promo_machine')
            ->withTimestamps();
    }
}
