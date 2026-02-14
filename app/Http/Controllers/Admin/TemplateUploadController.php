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
                'path_data' => $f['path_data'] ?? null,
            ]);
        }

        $template->update([
            'frame_count' => count($frames),
            'paper_size_id' => $request->paper_size_id,
            'name' => $request->name,
            'category' => $request->category,
            'is_active' => 1, // Activate template when frames are set
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
        ]);

        $path = $request->file('template')->store('templates', 'public');

        $template = Template::create([
            'paper_size_id' => $request->paper_size_id,
            'name' => $request->name,
            'category' => $request->category,
            'template_image' => $path,
            'frame_count' => 0, // Will be updated when frames are added
            'is_active' => 0, // Inactive until frames are set
        ]);

        return redirect()
            ->route('admin.templates.edit', $template->id)
            ->with('success', 'Template image uploaded. Please set up the frames.');
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
