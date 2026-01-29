<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PhotoSession;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    /**
     * HALAMAN GALERI HASIL FOTO
     */
    public function show($session_id)
    {
        $session = PhotoSession::with([
            'photos.frame',
            'finalImage'
        ])->where('id', $session_id)
            ->where('status', 'FINISHED')
            ->firstOrFail();

        return view('public.gallery', compact('session'));
    }

    /**
     * DOWNLOAD FOTO FRAME
     */
    public function downloadFrame($photo_id)
    {
        $photo = \App\Models\SessionPhoto::findOrFail($photo_id);

        $path = storage_path("app/public/{$photo->photo_path}");

        abort_if(!file_exists($path), 404);

        return response()->download($path);
    }

    /**
     * DOWNLOAD FINAL IMAGE
     */
    public function downloadFinal($session_id)
    {
        $session = PhotoSession::with('finalImage')->findOrFail($session_id);

        abort_if(!$session->finalImage, 404);

        $path = storage_path("app/public/{$session->finalImage->image_path}");

        abort_if(!file_exists($path), 404);

        return response()->download($path);
    }
}
