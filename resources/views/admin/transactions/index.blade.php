@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Management
                    </div>
                    <h2 class="page-title">
                        Transactions
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">

            <div class="card">
                <form action="{{ route('transactions.index') }}" method="GET"
                    class="card-header d-flex justify-content-between align-items-center gap-2" id="filterForm">
                    <input type="text" name="search" id="tableSearch" class="form-control w-50"
                        placeholder="Search transaction..." value="{{ request('search') }}"
                        oninput="clearTimeout(this.delay); this.delay = setTimeout(() => document.getElementById('filterForm').submit(), 500);">

                    <select name="per_page" id="pageSize" class="form-select w-auto"
                        onchange="document.getElementById('filterForm').submit()">
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                    </select>
                </form>

                <div class="table-responsive">
                    <table class="table table-vcenter table-bordered card-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Machine</th>
                                <th>Template</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Final Image</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                                <tr class="table-row" data-url="{{ route('transactions.show', $transaction->id) }}"
                                    style="cursor: pointer;">
                                    <td>{{ ($transactions->currentPage() - 1) * $transactions->perPage() + $loop->iteration }}
                                    </td>

                                    <td class="fw-semibold">
                                        #{{ $transaction->payment->order_id ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $transaction->machine->name ?? '-' }}
                                        <div class="text-muted small">
                                            {{ $transaction->machine->paperSize->name ?? '-' }} |
                                            {{ $transaction->machine->paperSize->width_mm ?? '' }} ×
                                            {{ $transaction->machine->paperSize->height_mm ?? '' }} mm
                                        </div>

                                    </td>

                                    <td>
                                        {{ $transaction->template->name ?? '-' }}
                                    </td>

                                    <td>
                                        @if ($transaction->payment)
                                            <div class="fw-semibold">
                                                Rp {{ number_format($transaction->payment->amount ?? 0, 0, ',', '.') }}
                                            </div>
                                            <span class="badge bg-blue-lt mt-2">
                                                {{ strtoupper($transaction->payment->transaction_status) }}
                                            </span>
                                        @else
                                            -
                                        @endif
                                    </td>

                                    <td>
                                        <span class="badge bg-green-lt">
                                            {{ strtoupper($transaction->status) }}
                                        </span>
                                    </td>

                                    <td>
                                        @if ($transaction->finalImage)
                                            @if ($transaction->finalImage->image_path)
                                                <span class="badge bg-success-lt">YES</span>
                                            @else
                                                <span class="badge bg-danger-lt">EXPIRED</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary-lt">NO</span>
                                        @endif
                                    </td>

                                    <td class="text-muted">
                                        {{ $transaction->created_at->format('d M Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        No transactions found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <div class="card-footer d-flex justify-content-between align-items-center" id="tableFooter">
                    <div class="text-muted" id="tableInfo">
                        Showing <strong>{{ $transactions->firstItem() ?? 0 }}</strong>
                        to <strong>{{ $transactions->lastItem() ?? 0 }}</strong>
                        of <strong>{{ $transactions->total() }}</strong> entries
                    </div>

                    <nav>
                        {{ $transactions->links('vendor.pagination.custom') }}
                    </nav>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('.table-row').forEach(row => {
                        row.addEventListener('click', () => {
                            window.location.href = row.dataset.url;
                        });
                    });
                });
            </script>

        </div>
    </div>
@endsection
