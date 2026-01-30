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
                <div class="card-header d-flex justify-content-between align-items-center gap-2">
                    <input type="text" id="tableSearch" class="form-control w-50" placeholder="Search transaction...">

                    <select id="pageSize" class="form-select w-auto">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                        <option value="all">All</option>
                    </select>
                </div>

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
                                    <td>{{ $loop->iteration }}</td>

                                    <td class="fw-semibold">
                                        #{{ $transaction->payment->order_id }}
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
                                            <span class="badge bg-success-lt">YES</span>
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
                                    <td colspan="8" class="text-center text-muted">
                                        No transactions found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Footer Pagination -->
                <div class="card-footer d-flex justify-content-between align-items-center d-none" id="tableFooter">
                    <div class="text-muted" id="tableInfo"></div>

                    <nav>
                        <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
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
            {{-- JS SEARCH + PAGINATION --}}
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const searchInput = document.getElementById('tableSearch');
                    const pageSizeSelect = document.getElementById('pageSize');
                    const table = document.querySelector('table tbody');
                    const rows = Array.from(table.querySelectorAll('tr'));
                    const footer = document.getElementById('tableFooter');
                    const info = document.getElementById('tableInfo');
                    const pagination = document.getElementById('pagination');

                    let currentPage = 1;

                    function getFilteredRows() {
                        const keyword = searchInput.value.toLowerCase();
                        return rows.filter(row =>
                            row.innerText.toLowerCase().includes(keyword)
                        );
                    }

                    function renderPagination(totalPages) {
                        pagination.innerHTML = '';

                        const windowSize = 5;
                        const half = Math.floor(windowSize / 2);

                        const createItem = (label, page = null, disabled = false, active = false) => {
                            const li = document.createElement('li');
                            li.className = `page-item ${disabled ? 'disabled' : ''} ${active ? 'active' : ''}`;

                            const a = document.createElement('a');
                            a.className = 'page-link';
                            a.href = '#';
                            a.innerText = label;

                            if (!disabled && page !== null) {
                                a.addEventListener('click', e => {
                                    e.preventDefault();
                                    currentPage = page;
                                    render();
                                });
                            }

                            li.appendChild(a);
                            return li;
                        };

                        pagination.appendChild(
                            createItem('Prev', currentPage - 1, currentPage === 1)
                        );

                        pagination.appendChild(
                            createItem('First', 1, currentPage === 1)
                        );

                        let startPage = currentPage - half;
                        let endPage = currentPage + half;

                        if (startPage < 1) {
                            startPage = 1;
                            endPage = Math.min(windowSize, totalPages);
                        }

                        if (endPage > totalPages) {
                            endPage = totalPages;
                            startPage = Math.max(1, totalPages - windowSize + 1);
                        }

                        for (let i = startPage; i <= endPage; i++) {
                            pagination.appendChild(
                                createItem(i, i, false, i === currentPage)
                            );
                        }

                        pagination.appendChild(
                            createItem('Last', totalPages, currentPage === totalPages)
                        );

                        pagination.appendChild(
                            createItem('Next', currentPage + 1, currentPage === totalPages)
                        );
                    }

                    function render() {
                        const filteredRows = getFilteredRows();
                        const total = filteredRows.length;

                        const pageSize = pageSizeSelect.value === 'all' ?
                            total :
                            parseInt(pageSizeSelect.value);

                        const totalPages = Math.ceil(total / pageSize) || 1;
                        const start = (currentPage - 1) * pageSize;
                        const end = start + pageSize;

                        rows.forEach(row => row.style.display = 'none');

                        filteredRows.slice(start, end).forEach(row => {
                            row.style.display = '';
                        });

                        footer.classList.remove('d-none');

                        info.innerHTML = `
                            Showing <strong>${total === 0 ? 0 : start + 1}</strong>
                            to <strong>${Math.min(end, total)}</strong>
                            of <strong>${total}</strong> entries
                        `;

                        renderPagination(totalPages);
                    }

                    searchInput.addEventListener('input', () => {
                        currentPage = 1;
                        render();
                    });

                    pageSizeSelect.addEventListener('change', () => {
                        currentPage = 1;
                        render();
                    });

                    render();
                });
            </script>

        </div>
    </div>
@endsection
