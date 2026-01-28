<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $fillable = [
        'session_id',
        'token',
        'expired_at',
        'downloaded_at'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'downloaded_at' => 'datetime'
    ];

    public function session()
    {
        return $this->belongsTo(PhotoSession::class, 'session_id');
    }
}
