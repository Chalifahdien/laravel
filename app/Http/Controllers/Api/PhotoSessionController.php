<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhotoSession;
use App\Models\SessionPhoto;
use App\Models\FinalImage;
use Illuminate\Support\Facades\DB;

class PhotoSessionController extends Controller
{
    public function completeSession(Request $request, PhotoSession $session)
    {
        $request->validate([
            'frames' => 'required|array',
            'frames.*' => 'required',
            'final_image' => 'required',
            'live_photo' => 'nullable|file',
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
               SAVE FINAL IMAGE & VIDEO
            ============================ */
            $finalPath = $request->file('final_image')
                ->store("sessions/{$session->id}/final", 'public');

            $videoPath = null;
            if ($request->hasFile('live_photo')) {
                $videoPath = $request->file('live_photo')
                    ->store("sessions/{$session->id}/final", 'public');
            }

            FinalImage::create([
                'session_id' => $session->id,
                'image_path' => $finalPath,
                'video_path' => $videoPath,
                'print_quantity' => 1,
            ]);

            /* ============================
               UPDATE SESSION
            ============================ */
            $session->update([
                'status' => 'DONE',
                'finished_at' => now()
            ]);

            /* ============================
               CREATE DOWNLOAD TOKEN
            ============================ */
            $download = \App\Models\Download::create([
                'session_id' => $session->id,
                'expired_at' => now()->addDays(7),
            ]);

            DB::commit();

            return response()->json([
                'status' => 'SUCCESS',
                'session_id' => $session->id,
                'download_token' => $download->token, // Send token to frontend
                'final_image' => $finalPath,
                'live_photo' => $videoPath
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set print quantity for a session
     */
    public function setPrintQuantity(Request $request, PhotoSession $session)
    {
        $request->validate([
            'print_quantity' => 'required|integer|min:1',
        ]);

        if (!$session->finalImage) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Final image not found for this session'
            ], 404);
        }

        $session->finalImage->update([
            'print_quantity' => $request->print_quantity
        ]);

        // Calculate total cost
        $basePrice = $session->machine->price ?? 0;
        $additionalPrintCost = $session->machine->additional_print_cost ?? 0;
        $additionalPrints = max(0, $request->print_quantity - 1);
        $totalCost = $basePrice + ($additionalPrints * $additionalPrintCost);

        return response()->json([
            'status' => 'SUCCESS',
            'data' => [
                'print_quantity' => $request->print_quantity,
                'base_price' => $basePrice,
                'additional_print_cost' => $additionalPrintCost,
                'additional_prints' => $additionalPrints,
                'total_cost' => $totalCost,
            ]
        ]);
    }
}
