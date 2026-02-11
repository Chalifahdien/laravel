<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{


    protected $fillable = [
        'name',
        'slug',
        'paper_size_id',
        'price',
        'additional_print_cost',
        'is_active',
        'payment_required',
        'api_token'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'payment_required' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($machine) {
            if (empty($machine->api_token)) {
                $machine->api_token = \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function paperSize()
    {
        return $this->belongsTo(PaperSize::class);
    }

    public function sessions()
    {
        return $this->hasMany(PhotoSession::class);
    }

    public function bannerPromos()
    {
        return $this->belongsToMany(BannerPromo::class, 'banner_promo_machine')
            ->withTimestamps();
    }
}
