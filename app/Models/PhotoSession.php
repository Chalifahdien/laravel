<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoSession extends Model
{
    protected $fillable = [
        'machine_id',
        'payment_id',
        'template_id',
        'status',
        'started_at',
        'expires_at',
        'finished_at',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function photos()
    {
        return $this->hasMany(SessionPhoto::class, 'session_id');
    }

    public function finalImage()
    {
        return $this->hasOne(FinalImage::class, 'session_id');
    }


    public function download()
    {
        return $this->hasOne(Download::class, 'session_id');
    }
}
