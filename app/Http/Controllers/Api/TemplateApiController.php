<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Template;

class TemplateApiController extends Controller
{
    /**
     * =========================
     * LIST TEMPLATE AKTIF
     * =========================
     */
    public function index()
    {
        $templates = Template::where('is_active', true)
            ->with('paperSize')
            ->orderBy('id', 'desc')
            ->get();
        return response()->json(
            $templates->map(fn($t) => [
                'id' => $t->id,
                'name' => $t->name,
                'category' => $t->category,
                'template_image' => $t->template_image
                    ? asset('storage/' . $t->template_image)
                    : null,
                'frame_count' => $t->frame_count,
                'paper_size' => [
                    'name' => $t->paperSize->name ?? null,
                    'width_mm' => $t->paperSize->width_mm ?? null,
                    'height_mm' => $t->paperSize->height_mm ?? null,
                ]
            ])
        );
    }

    /**
     * =========================
     * DETAIL TEMPLATE + FRAME
     * =========================
     */
    public function show($id)
    {
        $template = Template::with([
            'frames' => fn($q) => $q->orderBy('frame_order'),
            'paperSize'
        ])
            ->where('id', $id)
            ->where('is_active', true)
            ->first();

        if (!$template) {
            return response()->json([
                'status' => 'NOT_FOUND'
            ], 404);
        }

        return response()->json([
            'id' => $template->id,
            'name' => $template->name,
            'category' => $template->category,
            'template_image' => asset('storage/' . $template->template_image),
            'frame_count' => $template->frame_count,
            'paper_size' => [
                'name' => $template->paperSize->name,
                'width_mm' => $template->paperSize->width_mm,
                'height_mm' => $template->paperSize->height_mm,
            ],
            'frames' => $template->frames->map(fn($f) => [
                'id' => $f->id,
                'order' => $f->frame_order,
                'x' => $f->x,
                'y' => $f->y,
                'width' => $f->width,
                'height' => $f->height,
                'shape' => $f->shape,          // rect | circle
                'mask' => $f->mask_path
                    ? asset('storage/' . $f->mask_path)
                    : null
            ])
        ]);
    }
}
