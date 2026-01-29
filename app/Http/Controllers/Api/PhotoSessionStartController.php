<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhotoSession;
use App\Models\SessionPhoto;
use App\Models\FinalImage;
use Illuminate\Support\Facades\DB;

class PhotoSessionStartController extends Controller
{
    public function completeSession(Request $request, PhotoSession $session)
    {
        $request->validate([
            'frames' => 'required|array',
            'frames.*' => 'required|image',
            'final_image' => 'required|image'
        ]);

        DB::beginTransaction();

        try {
            /* ============================
               SAVE FRAME PHOTOS
            ============================ */
            foreach ($request->file('frames') as $frameId => $file) {

                $path = $file->store(
                    "sessions/{$session->id}/frames",
                    'public'
                );

                SessionPhoto::updateOrCreate(
                    [
                        'session_id' => $session->id,
                        'frame_id' => $frameId
                    ],
                    [
                        'photo_path' => $path
                    ]
                );
            }

            /* ============================
               SAVE FINAL IMAGE
            ============================ */
            $finalPath = $request->file('final_image')
                ->store("sessions/{$session->id}/final", 'public');

            FinalImage::create([
                'session_id' => $session->id,
                'image_path' => $finalPath
            ]);

            /* ============================
               UPDATE SESSION
            ============================ */
            $session->update([
                'status' => 'DONE',
                'finished_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'status' => 'SUCCESS',
                'session_id' => $session->id,
                'final_image' => $finalPath
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
