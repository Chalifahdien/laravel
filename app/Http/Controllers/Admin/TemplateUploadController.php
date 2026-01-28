<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaperSize;
use App\Models\Template;
use App\Models\TemplateFrame;
use Illuminate\Http\Request;

class TemplateUploadController extends Controller
{
    public function index()
    {
        $templates = Template::with('paperSize')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.templates.index', compact('templates'));
    }
    public function create()
    {
        return view('admin.templates.create', [
            'paperSizes' => PaperSize::where('is_active', 1)->get()
        ]);
    }

    public function edit(Template $template)
    {
        $template->load('frames', 'paperSize');

        return view('admin.templates.edit', [
            'template' => $template,
            'frames' => $template->frames
        ]);
    }

    public function update(Request $request, Template $template)
    {
        $request->validate([
            'frames' => 'required|json'
        ]);

        $frames = json_decode($request->frames, true);

        // hapus frame lama
        $template->frames()->delete();

        foreach ($frames as $i => $f) {
            $template->frames()->create([
                'frame_order' => $i + 1,
                'x' => $f['x'],
                'y' => $f['y'],
                'width' => $f['width'],
                'height' => $f['height'],
                'shape' => $f['shape'],
            ]);
        }

        $template->update([
            'frame_count' => count($frames)
        ]);

        return redirect()
            ->route('admin.templates.index')
            ->with('success', 'Frame berhasil diperbarui');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'paper_size_id' => 'required|exists:paper_sizes,id',
            'template' => 'required|image|mimes:png|max:5120',
            'frames' => 'required|json',
        ]);

        $frames = json_decode($request->frames, true);

        if (!is_array($frames) || count($frames) === 0) {
            return back()->withErrors(['frames' => 'Minimal 1 frame harus dibuat']);
        }

        /** Simpan file */
        $path = $request->file('template')->store('templates', 'public');

        /** Simpan template */
        $template = Template::create([
            'paper_size_id' => $request->paper_size_id,
            'name' => $request->name,
            'template_image' => $path,
            'frame_count' => count($frames),
            'is_active' => 1,
        ]);

        /** Simpan frame */
        foreach ($frames as $i => $frame) {
            TemplateFrame::create([
                'template_id' => $template->id,
                'frame_order' => $i + 1,
                'x' => intval($frame['x']),
                'y' => intval($frame['y']),
                'width' => intval($frame['width']),
                'height' => intval($frame['height']),
                'shape' => $frame['shape'],
            ]);
        }

        return redirect()
            ->route('admin.templates.create')
            ->with('success', 'Template berhasil disimpan (' . count($frames) . ' frame)');
    }
}
