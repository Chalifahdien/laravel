<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaperSize;
use App\Models\Template;
use App\Models\TemplateFrame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateUploadController extends Controller
{
    /**
     * LIST TEMPLATES
     */
    public function index()
    {
        $templates = Template::with('paperSize')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.templates.index', compact('templates'));
    }

    /**
     * CREATE FORM
     */
    public function create()
    {
        $existingCategories = Template::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->toArray();

        return view('admin.templates.create', [
            'paperSizes' => PaperSize::where('is_active', 1)->get(),
            'existingCategories' => $existingCategories
        ]);
    }

    /**
     * EDIT FORM
     */
    public function edit(Template $template)
    {
        $template->load('frames', 'paperSize');

        $existingCategories = Template::whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->toArray();

        return view('admin.templates.edit', [
            'template' => $template,
            'frames' => $template->frames,
            'paperSizes' => PaperSize::where('is_active', 1)->get(),
            'existingCategories' => $existingCategories
        ]);
    }

    /**
     * UPDATE TEMPLATE
     */
    public function update(Request $request, Template $template)
    {
        $request->validate([
            'frames' => 'required|json',
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:100',
            'paper_size_id' => 'required|exists:paper_sizes,id',
        ]);

        $frames = json_decode($request->frames, true);

        // delete existing frames
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
            'frame_count' => count($frames),
            'paper_size_id' => $request->paper_size_id,
            'name' => $request->name,
            'category' => $request->category,
        ]);

        return redirect()
            ->back()
            ->with('success', 'Template successfully updated');
    }

    /**
     * UPLOAD TEMPLATE
     */
    public function upload(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:100',
            'paper_size_id' => 'required|exists:paper_sizes,id',
            'template' => 'required|image|max:10240',
            'frames' => 'required|json',
        ]);

        $frames = json_decode($request->frames, true);

        if (!is_array($frames) || count($frames) === 0) {
            return back()->withErrors([
                'frames' => 'At least 1 frame must be created'
            ]);
        }

        $path = $request->file('template')->store('templates', 'public');

        $template = Template::create([
            'paper_size_id' => $request->paper_size_id,
            'name' => $request->name,
            'category' => $request->category,
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
            ->with('success', 'Template successfully saved');
    }

    /**
     * TOGGLE ACTIVE / INACTIVE
     */
    public function toggle(Template $template)
    {
        $template->update([
            'is_active' => !$template->is_active
        ]);

        return back()->with(
            'success',
            $template->is_active
            ? 'Template successfully activated'
            : 'Template successfully deactivated'
        );
    }

    /**
     * DELETE TEMPLATE
     */
    public function destroy(Template $template)
    {
        // delete frames first
        $template->frames()->delete();

        // delete image file
        if ($template->template_image) {
            Storage::disk('public')->delete($template->template_image);
        }

        // delete template
        $template->delete();

        return redirect()
            ->route('admin.templates.index')
            ->with('success', 'Template successfully deleted');
    }
}
