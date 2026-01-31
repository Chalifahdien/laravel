@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Management</div>
                    <h2 class="page-title">Invoice</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-2">
                    <input type="text" id="tableSearch" class="form-control w-50" placeholder="Search invoice...">

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
                                <th>Invoice #</th>
                                <th>Order ID</th>
                                <th>Machine</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($invoices as $i => $inv)
                                <tr>
                                    <td class="text-muted">{{ $i + 1 }}</td>
                                    <td class="fw-semibold">#{{ optional($inv->payment)->order_id ?? $inv->id }}</td>
                                    <td>{{ optional($inv->payment)->order_id ?? '-' }}</td>
                                    <td>{{ $inv->machine->name ?? '-' }}</td>
                                    <td>Rp {{ number_format(optional($inv->payment)->amount ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ optional($inv->payment)->transaction_status === 'success' ? 'green' : 'yellow' }}-lt">
                                            {{ strtoupper(optional($inv->payment)->transaction_status ?? 'N/A') }}
                                        </span>
                                    </td>
                                    <td class="text-muted">{{ $inv->created_at->format('d M Y H:i') }}</td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <a href="{{ route('transactions.invoice', $inv) }}"
                                                class="btn btn-icon btn-outline-primary" title="View Invoice">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" class="icon m-0">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                    <path
                                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('invoices.download', $inv) }}"
                                                class="btn btn-icon btn-outline-success" title="Download PDF">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" class="icon m-0">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                    <path d="M7 11l5 5l5 -5" />
                                                    <path d="M12 4l0 12" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('transactions.show', $inv) }}"
                                                class="btn btn-icon btn-outline-secondary" title="Transaction Detail">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon m-0">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path
                                                        d="M12 6h-6a2 2 0 0 0 -2 2v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-6" />
                                                    <path d="M11 13l9 -9" />
                                                    <path d="M15 4h5v5" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="border-bottom-0 text-center text-muted py-4">No invoices
                                        found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="card-footer d-flex justify-content-between align-items-center d-none" id="tableFooter">
                    <div class="text-muted" id="tableInfo"></div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('tableSearch');
            const pageSizeSelect = document.getElementById('pageSize');
            const table = document.querySelector('table tbody');
            const rows = Array.from(table.querySelectorAll('tr'));
            const footer = document.getElementById('tableFooter');
            const info = document.getElementById('tableInfo');
            const pagination = document.getElementById('pagination');

            const totalRows = rows.length;
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

                // Prev
                pagination.appendChild(
                    createItem('Prev', currentPage - 1, currentPage === 1)
                );

                // First
                pagination.appendChild(
                    createItem('First', 1, currentPage === 1)
                );

                // Hitung sliding window
                let startPage = currentPage - half;
                let endPage = currentPage + half;

                // Adjust jika kena batas kiri
                if (startPage < 1) {
                    startPage = 1;
                    endPage = Math.min(windowSize, totalPages);
                }

                // Adjust jika kena batas kanan
                if (endPage > totalPages) {
                    endPage = totalPages;
                    startPage = Math.max(1, totalPages - windowSize + 1);
                }

                for (let i = startPage; i <= endPage; i++) {
                    pagination.appendChild(
                        createItem(i, i, false, i === currentPage)
                    );
                }

                // Last
                pagination.appendChild(
                    createItem('Last', totalPages, currentPage === totalPages)
                );

                // Next
                pagination.appendChild(
                    createItem('Next', currentPage + 1, currentPage === totalPages)
                );
            }

            function render() {
                const filteredRows = getFilteredRows();
                const total = filteredRows.length;
                const isSearching = searchInput.value.trim() !== '';

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

                // Footer visibility
                footer.classList.remove('d-none');

                // Info
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
@endsection
