<?php

namespace App\Http\Controllers\Public;

use App\Models\PhotoSession;
use App\Models\SessionPhoto;

class GalleryController extends Controller
{
    /**
     * HALAMAN GALERI FOTO
     */
    public function show($session_id)
    {
        $session = PhotoSession::with(['photos', 'finalImage'])
            ->findOrFail($session_id);

        return view('public.gallery', compact('session'));
    }

    /**
     * DOWNLOAD FOTO FRAME
     */
    public function downloadFrame($photo_id)
    {
        $photo = SessionPhoto::findOrFail($photo_id);
        $path = storage_path('app/public/' . $photo->photo_path);

        abort_if(!file_exists($path), 404);

        return response()->download($path);
    }

    /**
     * DOWNLOAD FOTO FINAL
     */
    public function downloadFinal($session_id)
    {
        $session = PhotoSession::with('finalImage')
            ->findOrFail($session_id);

        abort_if(!$session->finalImage, 404);

        $path = storage_path('app/public/' . $session->finalImage->image_path);

        abort_if(!file_exists($path), 404);

        return response()->download($path);
    }
}
