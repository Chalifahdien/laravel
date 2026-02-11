<?php

namespace App\Http\Controllers\Public;

use App\Models\PhotoSession;
use App\Models\SessionPhoto;
use App\Models\Download;
use App\Http\Controllers\Controller;

class GaleriController extends Controller
{
    /**
     * HALAMAN GALERI FOTO
     */
    public function show($token)
    {
        $download = Download::where('token', $token)->firstOrFail();
        $session = PhotoSession::with(['photos', 'finalImage'])
            ->findOrFail($download->session_id);

        return view('public.gallery', compact('session', 'token'));
    }

    /**
     * DOWNLOAD FOTO FRAME
     */
    public function downloadFrame($token, $photo_id)
    {
        $download = Download::where('token', $token)->firstOrFail();

        $photo = SessionPhoto::where('session_id', $download->session_id)
            ->findOrFail($photo_id);

        $path = storage_path('app/public/' . $photo->photo_path);

        abort_if(!file_exists($path), 404);

        return response()->download($path);
    }

    /**
     * DOWNLOAD FOTO FINAL
     */
    public function downloadFinal($token)
    {
        $download = Download::where('token', $token)->firstOrFail();

        $session = PhotoSession::with('finalImage')
            ->findOrFail($download->session_id);

        abort_if(!$session->finalImage, 404);

        $path = storage_path('app/public/' . $session->finalImage->image_path);

        abort_if(!file_exists($path), 404);

        return response()->download($path);
    }

    /**
     * DOWNLOAD VIDEO / LIVE PHOTO
     */
    public function downloadLivePhoto($token)
    {
        $download = Download::where('token', $token)->firstOrFail();

        $session = PhotoSession::with('finalImage')
            ->findOrFail($download->session_id);

        abort_if(!$session->finalImage || !$session->finalImage->video_path, 404);

        $path = storage_path('app/public/' . $session->finalImage->video_path);

        abort_if(!file_exists($path), 404);

        return response()->download($path);
    }
}
