<?php

namespace App\Http\Controllers\Admin;

use App\Models\PaperSize;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaperSizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paperSizes = PaperSize::latest()->get();

        return view('admin.paper-sizes.index', compact('paperSizes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'width_mm' => 'required|integer|min:1',
            'height_mm' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        PaperSize::create([
            'name' => $validated['name'],
            'width_mm' => $validated['width_mm'],
            'height_mm' => $validated['height_mm'],
            'is_active' => 1,
        ]);

        return redirect()
            ->route('admin.paper-sizes.index')
            ->with('success', 'Paper size created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaperSize $paperSize)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'width_mm' => 'required|integer|min:1',
            'height_mm' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $paperSize->update([
            'name' => $validated['name'],
            'width_mm' => $validated['width_mm'],
            'height_mm' => $validated['height_mm'],
            'is_active' => 1,
        ]);

        return redirect()
            ->route('admin.paper-sizes.index')
            ->with('success', 'Paper size updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaperSize $paperSize)
    {
        $paperSize->delete();

        return redirect()
            ->route('admin.paper-sizes.index')
            ->with('success', 'Paper size deleted successfully.');
    }

}
