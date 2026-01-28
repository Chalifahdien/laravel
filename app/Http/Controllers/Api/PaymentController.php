<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use Midtrans\Config;
use Midtrans\Transaction;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'order_id' => 'required|unique:payments,order_id',
            'amount' => 'required|integer|min:1000',
        ]);

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'payment_type' => 'qris',
            'transaction_details' => [
                'order_id' => $request->order_id,
                'gross_amount' => $request->amount,
            ],
        ];

        $charge = \Midtrans\CoreApi::charge($params);

        Payment::create([
            'order_id' => $request->order_id,
            'payment_type' => 'qris',
            'amount' => $request->amount,
            'transaction_status' => 'PENDING',
            'raw_response' => $charge,
        ]);

        return response()->json([
            'status' => 'SUCCESS',
            'order_id' => $request->order_id,
            'qr_url' => $charge->actions[0]->url ?? null,
        ]);
    }

    public function checkStatus($order_id)
    {
        // SET CONFIG MIDTRANS
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        if (!Config::$serverKey) {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'MIDTRANS SERVER KEY NOT SET'
            ], 500);
        }

        $payment = Payment::where('order_id', $order_id)->first();

        if (!$payment) {
            return response()->json([
                'status' => 'NOT_FOUND'
            ], 404);
        }

        try {
            $midtrans = Transaction::status($order_id);

            if (in_array($midtrans->transaction_status, ['settlement', 'capture'])) {
                $payment->update([
                    'transaction_status' => 'PAID',
                    'paid_at' => now(),
                    'raw_response' => json_encode($midtrans)
                ]);
            } else {
                $payment->update([
                    'transaction_status' => strtoupper($midtrans->transaction_status),
                    'raw_response' => json_encode($midtrans)
                ]);
            }

            return response()->json([
                'order_id' => $order_id,
                'status' => $payment->transaction_status,
                'paid_at' => $payment->paid_at
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'ERROR',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

