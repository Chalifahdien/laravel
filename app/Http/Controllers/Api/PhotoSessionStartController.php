<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\PhotoSession;
use App\Models\Template;

class PhotoSessionController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string',
            'machine_id' => 'required|exists:machines,id',
        ]);

        // 1️⃣ Ambil payment
        $payment = Payment::where('order_id', $request->order_id)->first();

        if (!$payment) {
            return response()->json([
                'status' => 'NOT_FOUND',
                'message' => 'Payment tidak ditemukan'
            ], 404);
        }

        if ($payment->transaction_status !== 'PAID') {
            return response()->json([
                'status' => 'WAITING_PAYMENT'
            ], 402);
        }

        // 2️⃣ Cegah double session
        $existing = PhotoSession::where('payment_id', $payment->id)->first();
        if ($existing) {
            return response()->json([
                'status' => 'READY',
                'session_id' => $existing->id,
            ]);
        }

        // 3️⃣ Ambil template aktif
        $template = Template::where('is_active', 1)->first();

        if (!$template) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Template aktif tidak ditemukan'
            ], 500);
        }

        // 4️⃣ Buat session
        $session = PhotoSession::create([
            'machine_id' => $request->machine_id,
            'payment_id' => $payment->id,
            'template_id' => $template->id,
            'status' => 'PAID',
            'started_at' => now(),
            'expires_at' => now()->addMinutes(5),
        ]);

        // 5️⃣ Kirim frame ke mesin
        $frames = $template->frames()->orderBy('frame_order')->get()->map(fn($f) => [
            'frame_id' => $f->id,
            'x' => $f->x,
            'y' => $f->y,
            'width' => $f->width,
            'height' => $f->height,
            'shape' => $f->shape,
        ]);

        return response()->json([
            'status' => 'READY',
            'session_id' => $session->id,
            'template_id' => $template->id,
            'expires_at' => $session->expires_at,
            'frames' => $frames
        ]);
    }

    public function show($id)
    {
        $session = PhotoSession::with('photos')->find($id);

        if (!$session) {
            return response()->json(['status' => 'NOT_FOUND'], 404);
        }

        return response()->json($session);
    }

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


