<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaperSize;
use App\Models\Template;
use App\Models\TemplateFrame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
            ->with('success', 'Template berhasil diperbarui');
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

        $path = $request->file('template')->store('templates', 'public');

        $template = Template::create([
            'paper_size_id' => $request->paper_size_id,
            'name' => $request->name,
            'template_image' => $path,
            'frame_count' => count($frames),
            'is_active' => 1,
        ]);

        foreach ($frames as $i => $f) {
            TemplateFrame::create([
                'template_id' => $template->id,
                'frame_order' => $i + 1,
                'x' => intval($f['x']),
                'y' => intval($f['y']),
                'width' => intval($f['width']),
                'height' => intval($f['height']),
                'shape' => $f['shape'],
            ]);
        }

        return redirect()
            ->route('admin.templates.create')
            ->with('success', 'Template berhasil disimpan');
    }

    public function toggle(Template $template)
    {
        $template->update([
            'is_active' => !$template->is_active
        ]);

        return back()->with(
            'success',
            $template->is_active
            ? 'Template berhasil diaktifkan'
            : 'Template berhasil dinonaktifkan'
        );
    }

    public function destroy(Template $template)
    {
        // hapus frame dulu
        $template->frames()->delete();

        // hapus file gambar
        if ($template->template_image) {
            \Storage::disk('public')->delete($template->template_image);
        }

        // hapus template
        $template->delete();

        return redirect()
            ->route('admin.templates.index')
            ->with('success', 'Template berhasil dihapus');
    }

}
