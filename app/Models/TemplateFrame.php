<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplateFrame extends Model
{
    protected $fillable = [
        'template_id',
        'frame_order',
        'x',
        'y',
        'width',
        'height',
        'mask_path',
        'shape'
    ];

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function sessionPhotos()
    {
        return $this->hasMany(SessionPhoto::class, 'frame_id');
    }
}
