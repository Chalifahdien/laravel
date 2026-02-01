@extends('layouts.app')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Transaction</div>
                    <h2 class="page-title">Invoice #{{ optional($photoSession->payment)->order_id ?? $photoSession->id }}
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="/invoices" class="btn btn-link me-2">← Back</a>
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" class="icon me-1">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
                            <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
                            <path d="M7 13m0 2a2 2 0 0 1 2 -2h6a2 2 0 0 1 2 2v4a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
                        </svg>
                        Print Invoice
                    </button>

                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="card" id="invoice-content">
                <div class="card-body p-4 p-lg-5">
                    {{-- Header --}}
                    <div class="row mb-4">
                        <div class="col-6">
                            <h1 class="h2 mb-1">YASHA SNAP</h1>
                            <p class="text-muted mb-0">Photobooth Service</p>
                        </div>
                        <div class="col-6 text-end">
                            <h3 class="text-uppercase text-muted mb-1">Invoice</h3>
                            <p class="mb-0 fw-semibold">
                                #{{ optional($photoSession->payment)->order_id ?? 'INV-' . $photoSession->id }}</p>
                            <p class="text-muted small mb-0">{{ $photoSession->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- Session & Payment info --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p class="text-muted small text-uppercase mb-1">Session</p>
                            <p class="mb-0">Session #{{ $photoSession->id }}</p>
                            <p class="mb-0">{{ $photoSession->machine->name ?? '-' }}</p>
                            <p class="mb-0 text-muted small">{{ $photoSession->template->name ?? '-' }}</p>
                            <p class="mb-0 text-muted small">{{ $photoSession->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="text-muted small text-uppercase mb-1">Payment</p>
                            <p class="mb-0">Order ID: {{ optional($photoSession->payment)->order_id ?? '-' }}</p>
                            <p class="mb-0">Status: <span
                                    class="badge bg-{{ optional($photoSession->payment)->transaction_status === 'success' ? 'green' : 'yellow' }}-lt">{{ strtoupper(optional($photoSession->payment)->transaction_status ?? 'N/A') }}</span>
                            </p>
                            @if (optional($photoSession->payment)->paid_at)
                                <p class="mb-0 text-muted small">Paid:
                                    {{ $photoSession->payment->paid_at->format('d M Y H:i') }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Items table --}}
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-uppercase small">#</th>
                                    <th class="text-uppercase small">Description</th>
                                    <th class="text-uppercase small text-end">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <strong>Photobooth Session</strong><br>
                                        <span class="text-muted small">
                                            Machine: {{ $photoSession->machine->name ?? '-' }}<br>
                                            Template: {{ $photoSession->template->name ?? '-' }}
                                            @if ($photoSession->machine && $photoSession->machine->paperSize)
                                                · {{ $photoSession->machine->paperSize->name }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="text-end fw-semibold">
                                        Rp {{ number_format(optional($photoSession->payment)->amount ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" class="text-end fw-bold">Total</td>
                                    <td class="text-end fw-bold">Rp
                                        {{ number_format(optional($photoSession->payment)->amount ?? 0, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4 pt-4 border-top text-muted small">
                        <p class="mb-0">Thank you for your order.</p>
                        <p class="mb-0">This is a computer-generated invoice. No signature required.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style media="print">
        .navbar,
        .sidebar,
        .page-header .btn,
        .page-header .btn-link,
        .d-print-none,
        .page-header .col-auto {
            display: none !important;
        }

        .page-wrapper {
            margin: 0 !important;
            padding: 0 !important;
        }

        .page-body {
            padding: 0 !important;
        }

        #invoice-content {
            border: none !important;
            box-shadow: none !important;
        }
    </style>
@endsection
