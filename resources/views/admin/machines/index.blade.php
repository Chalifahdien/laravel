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
                        Photobooth Machine Data
                    </h2>
                </div>

                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('machines.create') }}" class="btn btn-primary">
                            <!-- plus icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Add Machine
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-2">
                    <input type="text" id="tableSearch" class="form-control w-50" placeholder="Search machine...">

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
                                <th>Machine Name</th>
                                <th>Slug</th>
                                <th>Paper Size</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($machines as $machine)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $machine->name }}</td>
                                    <td class="text-muted">{{ $machine->slug }}</td>
                                    <td>
                                        <div>{{ $machine->paperSize->name ?? '-' }}</div>
                                        <div class="text-muted small">
                                            {{ $machine->paperSize->width_mm ?? '' }} ×
                                            {{ $machine->paperSize->height_mm ?? '' }} mm
                                        </div>
                                    </td>
                                    <td>
                                        Rp {{ number_format($machine->price, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($machine->is_active)
                                            <span class="badge bg-green-lt">Active</span>
                                        @else
                                            <span class="badge bg-orange-lt">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap justify-content-end">
                                            <a href="{{ route('machines.edit', $machine) }}"
                                                class="btn btn-icon btn-outline-warning" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon m-0">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                    <path
                                                        d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415" />
                                                    <path d="M16 5l3 3" />
                                                </svg>
                                            </a>
                                            <a class="btn btn-icon btn-outline-danger" href="#" data-bs-toggle="modal"
                                                data-bs-target="#hapus{{ $machine->id }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon m-0">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 7l16 0" />
                                                    <path d="M10 11l0 6" />
                                                    <path d="M14 11l0 6" />
                                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="border-bottom-0 text-center text-muted">
                                        Data mesin belum ada
                                    </td>
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

            {{-- Model Hapus --}}
            @forelse ($machines as $machine)
                <div class="modal modal-blur fade" id="hapus{{ $machine->id }}" tabindex="-1" role="dialog"
                    aria-hidden="true">
                    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="modal-body text-center py-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon mb-2 text-danger icon-lg">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                                <h3>Are you sure?</h3>
                                <div class="text-secondary">
                                    Are you sure you want to delete the machine {{ $machine->name }}?
                                </div>
                            </div>
                            <div class="modal-footer">
                                <div class="w-100">
                                    <div class="row">
                                        <div class="col">
                                            <a href="#" class="btn btn-3 w-100" data-bs-dismiss="modal">
                                                Cancel
                                            </a>
                                        </div>
                                        <div class="col">
                                            <form action="{{ route('machines.destroy', $machine) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-4 w-100">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

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
        </div>
    </div>
@endsection
