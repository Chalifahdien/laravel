<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    protected $fillable = [
        'paper_size_id',
        'orientation',
        'name',
        'category',
        'preview_image',
        'template_image',
        'frame_count',
        'is_active'
    ];

    public function frames()
    {
        return $this->hasMany(TemplateFrame::class);
    }

    public function paperSize()
    {
        return $this->belongsTo(PaperSize::class);
    }
}
