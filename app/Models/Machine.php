<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'paper_size_id',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function paperSize()
    {
        return $this->belongsTo(PaperSize::class);
    }

    public function sessions()
    {
        return $this->hasMany(PhotoSession::class);
    }
}
