<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperSize extends Model
{
    protected $fillable = [
        'name',
        'width_mm',
        'height_mm',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function machines()
    {
        return $this->hasMany(Machine::class);
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }
}
