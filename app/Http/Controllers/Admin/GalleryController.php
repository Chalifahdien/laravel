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
            ->whereNotNull('image_path')
            ->orderBy('created_at', 'desc');

        // optional filter printed
        if ($request->filled('printed')) {
            $query->where('printed', $request->printed);
        }

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            // Search by session ID or formatted date
            $query->where(function ($q) use ($search) {
                $q->where('session_id', 'like', "%{$search}%")
                    // Add a way to search if needed, usually we just do session_id 
                    // or filter by created_at. Filtering by formatted date is tricky in DB
                    // so let's stick to session_id for simplicity as dataset search was just session_id and date.
                    // We'll primarily search by session_id since it's the most common search, 
                    // or raw date if it matches the DB format.
                    ->orWhere('created_at', 'like', "%{$search}%");
            });
        }

        $perPage = $request->input('per_page', 12);

        if ($perPage === 'all') {
            $total = $query->count();
            $finalImages = $query->paginate($total > 0 ? $total : 1);
        } else {
            $finalImages = $query->paginate((int) $perPage);
        }

        $finalImages->appends($request->except('page'));

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
            $finalImage->image_path = null;
        }

        // 4. Hapus video jika ada
        if ($finalImage->video_path) {
            Storage::disk('public')->delete($finalImage->video_path);
            $finalImage->video_path = null;
        }

        // 5. Simpan perubahan record final image (tidak menghapus record)
        $finalImage->save();

        return back()->with('success', 'Files deleted successfully, record preserved.');
    }
}
