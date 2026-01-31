<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotoSession;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * List all invoices (transactions).
     */
    public function index(Request $request)
    {
        $query = PhotoSession::with([
            'machine.paperSize',
            'payment',
            'template',
        ])->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%$search%")
                    ->orWhereHas('machine', fn ($m) => $m->where('name', 'like', "%$search%"))
                    ->orWhereHas('payment', fn ($p) => $p->where('order_id', 'like', "%$search%"));
            });
        }

        $invoices = $query->get();

        return view('admin.invoice.index', compact('invoices'));
    }

    /**
     * Show invoice for a transaction (photo session).
     */
    public function show(PhotoSession $photoSession)
    {
        $photoSession->load([
            'machine.paperSize',
            'payment',
            'template',
        ]);

        return view('admin.invoice.show', compact('photoSession'));
    }

    /**
     * Download invoice as PDF.
     */
    public function download(PhotoSession $photoSession)
    {
        $photoSession->load([
            'machine.paperSize',
            'payment',
            'template',
        ]);

        $pdf = Pdf::loadView('admin.invoice.pdf', compact('photoSession'))
            ->setPaper('a4');

        $filename = 'invoice-' . (optional($photoSession->payment)->order_id ?? $photoSession->id) . '.pdf';

        return $pdf->download($filename);
    }
}
