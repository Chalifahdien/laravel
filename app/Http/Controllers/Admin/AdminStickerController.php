<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Sticker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminStickerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stickers = Sticker::latest()->get();
        return view('admin.stickers.index', compact('stickers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.stickers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:png,svg,jpg,jpeg|max:2048',
            'category' => 'nullable|string|max:255',
            'is_active' => 'nullable',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('stickers', 'public');
        }

        Sticker::create([
            'name' => $validated['name'],
            'image_path' => $imagePath,
            'category' => $validated['category'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('stickers.index')
            ->with('success', 'Sticker created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sticker $sticker)
    {
        return view('admin.stickers.edit', compact('sticker'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sticker $sticker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,svg,jpg,jpeg|max:2048',
            'category' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        // Handle image upload if new image provided
        if ($request->hasFile('image')) {
            // Delete old image
            if ($sticker->image_path) {
                Storage::disk('public')->delete($sticker->image_path);
            }
            $imagePath = $request->file('image')->store('stickers', 'public');
            $sticker->image_path = $imagePath;
        }

        $sticker->update([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        if (isset($imagePath)) {
            $sticker->image_path = $imagePath;
            $sticker->save();
        }

        return redirect()->route('stickers.index')
            ->with('success', 'Sticker updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sticker $sticker)
    {
        // Delete image file
        if ($sticker->image_path) {
            Storage::disk('public')->delete($sticker->image_path);
        }

        $sticker->delete();

        return redirect()->route('stickers.index')
            ->with('success', 'Sticker deleted successfully!');
    }
}
