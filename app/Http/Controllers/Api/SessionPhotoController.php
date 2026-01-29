<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhotoSession;
use App\Models\SessionPhoto;
use App\Models\TemplateFrame;
use Illuminate\Support\Facades\Storage;

class SessionPhotoController extends Controller
{
    /**
     * ==============================
     * UPLOAD FOTO PER FRAME
     * ==============================
     */
    public function store(Request $request, $sessionId)
    {
        $request->validate([
            'frame_id' => 'required|exists:template_frames,id',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:10240'
        ]);

        $session = PhotoSession::with('template')->find($sessionId);

        if (!$session) {
            return response()->json([
                'status' => 'NOT_FOUND',
                'message' => 'Photo session tidak ditemukan'
            ], 404);
        }

        if ($session->status !== 'ACTIVE') {
            return response()->json([
                'status' => 'INVALID_SESSION',
                'message' => 'Session belum aktif atau sudah selesai'
            ], 422);
        }

        $frame = TemplateFrame::where('id', $request->frame_id)
            ->where('template_id', $session->template_id)
            ->first();

        if (!$frame) {
            return response()->json([
                'status' => 'INVALID_FRAME',
                'message' => 'Frame tidak sesuai template'
            ], 422);
        }

        // Cegah double foto di frame yang sama
        if (
            SessionPhoto::where('session_id', $sessionId)
                ->where('frame_id', $frame->id)
                ->exists()
        ) {
            return response()->json([
                'status' => 'ALREADY_TAKEN',
                'message' => 'Frame sudah memiliki foto'
            ], 409);
        }

        // ==============================
        // SIMPAN FOTO
        // ==============================
        $path = $request->file('photo')->store(
            "sessions/{$sessionId}",
            'public'
        );

        $photo = SessionPhoto::create([
            'session_id' => $sessionId,
            'frame_id' => $frame->id,
            'photo_path' => $path,
            'taken_at' => now()
        ]);

        return response()->json([
            'status' => 'SUCCESS',
            'photo_id' => $photo->id,
            'frame_id' => $frame->id,
            'photo_url' => asset('storage/' . $path)
        ]);
    }
}
