<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinalImage extends Model
{
    protected $fillable = [
        'session_id',
        'image_path',
        'video_path',
        'print_quantity',
        'printed',
        'gift'
    ];

    protected $casts = [
        'printed' => 'boolean'
    ];

    public function session()
    {
        return $this->belongsTo(PhotoSession::class, 'session_id');
    }
}
