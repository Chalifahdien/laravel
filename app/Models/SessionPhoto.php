<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SessionPhoto extends Model
{
    protected $fillable = [
        'session_id',
        'frame_id',
        'photo_path',
        'taken_at'
    ];

    public function session()
    {
        return $this->belongsTo(PhotoSession::class, 'session_id');
    }

    public function frame()
    {
        return $this->belongsTo(TemplateFrame::class, 'frame_id');
    }
}
