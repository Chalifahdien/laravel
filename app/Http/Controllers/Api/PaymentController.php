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
            'amount' => 'required|integer',
        ]);

        // Config::$serverKey = config('midtrans.server_key');
        // Config::$isProduction = false;
        // Config::$isSanitized = true;
        // Config::$is3ds = true;

        // $params = [
        //     'payment_type' => 'qris',
        //     'transaction_details' => [
        //         'order_id' => $request->order_id,
        //         'gross_amount' => $request->amount,
        //     ],
        // ];

        // $charge = \Midtrans\CoreApi::charge($params);

        Payment::create([
            'order_id' => $request->order_id,
            'payment_type' => 'qris',
            'amount' => $request->amount,
            'transaction_status' => 'PAID',
            'raw_response' => null,
        ]);

        return response()->json([
            'status' => 'SUCCESS',
            'order_id' => $request->order_id,
            // 'qr_url' => $charge->actions[0]->url ?? null,
        ]);
    }

    public function checkStatus($order_id)
    {
        $payment = Payment::where('order_id', $order_id)->first();

        if (!$payment) {
            return response()->json([
                'status' => 'NOT_FOUND'
            ], 404);
        }

        return response()->json([
            'order_id' => $order_id,
            'status' => $payment->transaction_status,
            'paid_at' => $payment->paid_at
        ]);
    }
}

