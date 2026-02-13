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
        // 1. Ambil session terkait
        $session = $finalImage->session;

        // 2. Jika session ada, hapus foto-fotonya (file & record) tapi JANGAN hapus session-nya
        if ($session) {
            foreach ($session->photos as $photo) {
                if ($photo->photo_path) {
                    Storage::disk('public')->delete($photo->photo_path);
                }
                $photo->delete();
            }
        }

        // 3. Hapus file final image
        if ($finalImage->image_path) {
            Storage::disk('public')->delete($finalImage->image_path);
        }

        // 4. Hapus video jika ada
        if ($finalImage->video_path) {
            Storage::disk('public')->delete($finalImage->video_path);
        }

        // 5. Hapus record final image
        $finalImage->delete();

        return back()->with('success', 'Image and Session Photos deleted successfully');
    }
}
