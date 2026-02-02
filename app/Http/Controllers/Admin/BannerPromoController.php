<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerPromo;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerPromoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bannerPromos = BannerPromo::with('machines')
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.banner-promo.index', compact('bannerPromos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $machines = Machine::orderBy('name')->get();

        return view('admin.banner-promo.create', compact('machines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'link' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'machine_ids' => 'nullable|array',
            'machine_ids.*' => 'integer|exists:machines,id',
        ]);

        $path = $request->file('image')->store('banner-promos', 'public');

        $bannerPromo = BannerPromo::create([
            'title' => $validated['title'],
            'image' => $path,
            'link' => $validated['link'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
            'start_at' => $validated['start_at'] ?? null,
            'end_at' => $validated['end_at'] ?? null,
        ]);

        // Create: tidak ada yang dicentang = tidak ada mesin yang dipilih
        $machineIds = $request->input('machine_ids', []);
        $machineIds = is_array($machineIds)
            ? array_values(array_filter($machineIds, function ($id) {
                return $id !== '' && $id !== null;
            }))
            : array_filter([$machineIds]);
        $bannerPromo->machines()->sync($machineIds);

        return redirect()
            ->back()
            ->with('success', 'Banner promo created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BannerPromo $bannerPromo)
    {
        $machines = Machine::orderBy('name')->get();
        $bannerPromo->load('machines');

        return view('admin.banner-promo.edit', compact('bannerPromo', 'machines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BannerPromo $bannerPromo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:5120',
            'link' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'machine_ids' => 'nullable|array',
            'machine_ids.*' => 'integer|exists:machines,id',
        ]);

        $data = [
            'title' => $validated['title'],
            'link' => $validated['link'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
            'start_at' => $validated['start_at'] ?? null,
            'end_at' => $validated['end_at'] ?? null,
        ];

        if ($request->hasFile('image')) {
            if ($bannerPromo->image) {
                Storage::disk('public')->delete($bannerPromo->image);
            }
            $data['image'] = $request->file('image')->store('banner-promos', 'public');
        }

        $bannerPromo->update($data);

        // Edit: hanya mesin yang dicentang yang punya banner; yang tidak dicentang dihapus dari pivot
        $machineIds = $request->input('machine_ids', []);
        $machineIds = is_array($machineIds)
            ? array_values(array_filter($machineIds, function ($id) {
                return $id !== '' && $id !== null; }))
            : array_filter([$machineIds]);
        $bannerPromo->machines()->sync($machineIds);

        return redirect()
            ->back()
            ->with('success', 'Banner promo updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BannerPromo $bannerPromo)
    {
        if ($bannerPromo->image) {
            Storage::disk('public')->delete($bannerPromo->image);
        }

        $bannerPromo->delete();

        return redirect()
            ->route('admin.banner-promo.index')
            ->with('success', 'Banner promo deleted successfully.');
    }

    /**
     * Toggle active status.
     */
    public function toggle(BannerPromo $bannerPromo)
    {
        $bannerPromo->update(['is_active' => !$bannerPromo->is_active]);

        return back()->with(
            'success',
            $bannerPromo->is_active ? 'Banner promo activated.' : 'Banner promo deactivated.'
        );
    }
}
