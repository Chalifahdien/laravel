@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Transaction
                    </div>
                    <h2 class="page-title">
                        Detail Transaction #{{ $photoSession->id }}
                    </h2>
                </div>

                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('transactions.invoice', $photoSession) }}" class="btn btn-primary me-2">
                        View Invoice
                    </a>
                    <a href="{{ route('transactions.index') }}" class="btn btn-link">
                        ← Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">

            <div class="row row-cards">

                {{-- LEFT : INFO --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Session Information</h3>
                        </div>

                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td class="text-muted">Session ID</td>
                                    <td class="fw-semibold">#{{ $photoSession->id }}</td>
                                </tr>

                                <tr>
                                    <td class="text-muted">Status</td>
                                    <td>
                                        <span class="badge bg-green-lt">
                                            {{ strtoupper($photoSession->status) }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="text-muted">Machine</td>
                                    <td>{{ $photoSession->machine->name ?? '-' }}</td>
                                </tr>

                                <tr>
                                    <td class="text-muted">Template</td>
                                    <td>{{ $photoSession->template->name ?? '-' }}</td>
                                </tr>

                                <tr>
                                    <td class="text-muted">Started At</td>
                                    <td>{{ optional($photoSession->started_at)->format('d M Y H:i') ?? '-' }}</td>
                                </tr>

                                <tr>
                                    <td class="text-muted">Finished At</td>
                                    <td>{{ optional($photoSession->finished_at)->format('d M Y H:i') ?? '-' }}</td>
                                </tr>

                                <tr>
                                    <td class="text-muted">Created</td>
                                    <td>{{ $photoSession->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- RIGHT : PAYMENT --}}
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Payment Information</h3>
                        </div>

                        <div class="card-body">
                            @if ($photoSession->payment)
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td class="text-muted">Order ID</td>
                                        <td class="fw-semibold">
                                            {{ $photoSession->payment->order_id ?? '-' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-muted">Amount</td>
                                        <td class="fw-semibold">
                                            Rp {{ number_format($photoSession->payment->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-muted">Status</td>
                                        <td>
                                            <span class="badge bg-blue-lt">
                                                {{ strtoupper($photoSession->payment->transaction_status) }}
                                            </span>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-muted">Paid At</td>
                                        <td>
                                            {{ optional($photoSession->payment->paid_at)->format('d M Y H:i') ?? '-' }}
                                        </td>
                                    </tr>

                                    @php
                                        $printQty = $photoSession->finalImage->print_quantity ?? 1;
                                        $basePrice = $photoSession->payment->amount ?? 0;
                                        if ($photoSession->payment->amount == 0) {
                                            $additionalPrintCost = 0;
                                        } else {
                                            $additionalPrintCost = $photoSession->machine->additional_print_cost ?? 0;
                                        }
                                        $additionalPrints = max(0, $printQty - 1);
                                        $totalCost = $basePrice + $additionalPrints * $additionalPrintCost;
                                    @endphp

                                    <tr>
                                        <td class="text-muted">Print Quantity</td>
                                        <td class="fw-semibold">
                                            {{ $printQty }} print(s)
                                        </td>
                                    </tr>

                                    <tr>
                                        <td class="text-muted">Total Cost</td>
                                        <td class="fw-semibold text-primary">
                                            Rp
                                            {{ number_format($photoSession->payment->amount + $additionalPrints * $additionalPrintCost, 0, ',', '.') }}
                                            @if ($additionalPrints > 0)
                                                <br>
                                                <small class="text-muted">
                                                    (Base: Rp {{ number_format($basePrice, 0, ',', '.') }} +
                                                    {{ $additionalPrints }} extra print(s): Rp
                                                    {{ number_format($additionalPrints * $additionalPrintCost, 0, ',', '.') }})
                                                </small>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            @else
                                <div class="text-muted text-center">
                                    No payment data
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- FINAL IMAGE --}}
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Final Image</h3>
                        </div>

                        <div class="card-body text-center">
                            @if ($photoSession->finalImage)
                                <img src="{{ asset('storage/' . $photoSession->finalImage->image_path) }}"
                                    class="img-fluid rounded shadow-sm" style="max-height: 400px">
                                <div class="mt-3">
                                    <a href="{{ route('gallery.final.download', $photoSession->download->token ?? '') }}"
                                        class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                            <path d="M7 11l5 5l5 -5" />
                                            <path d="M12 4l0 12" />
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            @else
                                <div class="text-muted">
                                    Final image not available
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- LIVE PHOTO --}}
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Live Photo</h3>
                        </div>

                        <div class="card-body text-center">
                            @if ($photoSession->finalImage && $photoSession->finalImage->video_path)
                                @php
                                    $videoPath = $photoSession->finalImage->video_path;
                                    $extension = strtolower(pathinfo($videoPath, PATHINFO_EXTENSION));
                                @endphp

                                @if ($extension === 'gif')
                                    <img src="{{ asset('storage/' . $videoPath) }}" class="img-fluid rounded shadow-sm"
                                        style="max-height: 400px;">
                                @else
                                    <video autoplay loop muted playsinline controls class="img-fluid rounded shadow-sm"
                                        style="max-height: 400px; width: 100%;">
                                        <source src="{{ asset('storage/' . $videoPath) }}"
                                            type="video/{{ $extension }}">
                                        Your browser does not support the video tag.
                                    </video>
                                @endif
                                <div class="mt-3">
                                    <a href="/gallery/{{ $photoSession->download->token }}/live/download"
                                        class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                            <path d="M7 11l5 5l5 -5" />
                                            <path d="M12 4l0 12" />
                                        </svg>
                                        Download Live Photo
                                    </a>
                                </div>
                            @else
                                <div class="text-muted">
                                    Live photo not available
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- SESSION PHOTOS --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Session Photos</h3>
                        </div>

                        <div class="card-body">
                            <div class="row g-3">
                                @forelse ($photoSession->photos as $photo)
                                    <div class="col-md-3 col-sm-4 col-6">
                                        <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                            class="img-fluid rounded mb-2">
                                        <a href="{{ route('gallery.frame.download', ['token' => $photoSession->download->token ?? '', 'photo_id' => $photo->id]) }}"
                                            class="btn btn-outline-primary w-100">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="icon icon-sm">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                <path d="M7 11l5 5l5 -5" />
                                                <path d="M12 4l0 12" />
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                @empty
                                    <div class="text-muted text-center">
                                        No photos found
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
@endsection
