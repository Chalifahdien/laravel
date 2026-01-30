<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinalImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * Display gallery of final images
     */
    public function index(Request $request)
    {
        $query = FinalImage::with('session')
            ->orderBy('created_at', 'desc');

        // optional filter printed
        if ($request->filled('printed')) {
            $query->where('printed', $request->printed);
        }

        $finalImages = $query->get();

        return view('admin.gallery.index', compact('finalImages'));
    }

    /**
     * Show images by photo session
     */
    public function bySession($sessionId)
    {
        $finalImages = FinalImage::with('session')
            ->where('session_id', $sessionId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.gallery.session', compact('finalImages'));
    }

    /**
     * Delete final image
     */
    public function destroy(FinalImage $finalImage)
    {
        if ($finalImage->image_path) {
            Storage::disk('public')->delete($finalImage->image_path);
        }

        $finalImage->delete();

        return back()->with('success', 'Image deleted successfully');
    }
}
