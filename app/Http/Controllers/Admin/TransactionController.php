<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotoSession;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * List all transactions
     */
    public function index(Request $request)
    {
        $query = PhotoSession::with([
            'machine',
            'payment',
            'template',
            'finalImage',
        ])->orderBy('created_at', 'desc');

        // 🔍 Filter status session
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔍 Filter payment status
        if ($request->filled('payment_status')) {
            $query->whereHas('payment', function ($q) use ($request) {
                $q->where('status', $request->payment_status);
            });
        }

        // 🔍 Search (session id / machine / payment id)
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                    ->orWhereHas('machine', fn($m) =>
                        $m->where('name', 'like', "%$search%"))
                    ->orWhereHas('payment', fn($p) =>
                        $p->where('reference', 'like', "%$search%"));
            });
        }

        $transactions = $query->get();

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Show transaction detail
     */
    public function show(PhotoSession $photoSession)
    {
        $photoSession->load([
            'machine',
            'payment',
            'template',
            'photos',
            'finalImage',
        ]);

        return view('admin.transactions.show', compact('photoSession'));
    }
}
