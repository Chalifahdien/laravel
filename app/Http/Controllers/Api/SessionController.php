<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\PhotoSession;
use App\Models\Template;


class SessionController extends Controller
{
    public function start(Request $request)
    {
        $request->validate([
            'machine_id' => 'required|exists:machines,id',
        ]);

        $machine = \App\Models\Machine::findOrFail($request->machine_id);
        $paymentRequired = $machine->payment_required;

        if ($paymentRequired) {
            $request->validate([
                'order_id' => 'required|string',
            ]);
        }

        // Check for active template first
        $template = Template::where('is_active', 1)->first();

        if (!$template) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Template aktif tidak ditemukan'
            ], 500);
        }

        // // MODE GRATIS (payment_required = false)
        // if (!$paymentRequired) {
        //     // Check if there's already an active session for this machine
        //     $existingSession = PhotoSession::where('machine_id', $request->machine_id)
        //         ->where('status', 'PAID')
        //         ->where('expires_at', '>', now())
        //         ->first();

        //     if ($existingSession) {
        //         return response()->json([
        //             'status' => 'READY',
        //             'session_id' => $existingSession->id,
        //             'message' => 'Session aktif ditemukan'
        //         ]);
        //     }

        //     // Create session directly without payment
        //     $session = PhotoSession::create([
        //         'machine_id' => $request->machine_id,
        //         'payment_id' => $request->payment_id, // No payment in free mode
        //         'template_id' => $template->id,
        //         'status' => 'PAID', // Mark as PAID so it can proceed
        //         'started_at' => now(),
        //         'expires_at' => now()->addMinutes(5),
        //     ]);

        //     // Get frames for the template
        //     $frames = $template->frames()->orderBy('frame_order')->get()->map(fn($f) => [
        //         'frame_id' => $f->id,
        //         'x' => $f->x,
        //         'y' => $f->y,
        //         'width' => $f->width,
        //         'height' => $f->height,
        //         'shape' => $f->shape,
        //     ]);

        //     return response()->json([
        //         'status' => 'READY',
        //         'session_id' => $session->id,
        //         'template_id' => $template->id,
        //         'expires_at' => $session->expires_at,
        //         'frames' => $frames,
        //         'payment_mode' => 'FREE'
        //     ]);
        // }

        // MODE BERBAYAR MANUAL (payment_required = true)
        // Cek payment dengan order_id yang diberikan
        $payment = Payment::where('order_id', $request->order_id)->first();

        if (!$payment) {
            return response()->json([
                'status' => 'NOT_FOUND',
                'message' => 'Payment tidak ditemukan. Silakan lakukan pembayaran manual terlebih dahulu.'
            ], 404);
        }

        if ($payment->transaction_status !== 'PAID') {
            return response()->json([
                'status' => 'WAITING_PAYMENT',
                'message' => 'Menunggu konfirmasi pembayaran dari admin.'
            ], 402);
        }

        // Cegah double session
        $existing = PhotoSession::where('payment_id', $payment->id)->first();
        if ($existing) {
            return response()->json([
                'status' => 'READY',
                'session_id' => $existing->id,
            ]);
        }

        // Buat session
        $session = PhotoSession::create([
            'machine_id' => $request->machine_id,
            'payment_id' => $payment->id,
            'template_id' => $template->id,
            'status' => 'PAID',
            'started_at' => now(),
            'expires_at' => now()->addMinutes(5),
        ]);

        // Kirim frame ke mesin
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
            'frames' => $frames,
            'payment_mode' => 'MANUAL'
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
}