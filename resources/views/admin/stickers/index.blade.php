@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Management</div>
                    <h2 class="page-title">Stickers</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('stickers.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                            stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                        Add New Sticker
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center gap-2">
                    <input type="text" id="tableSearch" class="form-control w-50" placeholder="Search sticker...">

                    <select id="pageSize" class="form-select w-auto">
                        <option value="18">18</option>
                        <option value="36">36</option>
                        <option value="60">60</option>
                        <option value="120">120</option>
                        <option value="all">All</option>
                    </select>
                </div>
                <div class="row g-0" id="stickerGrid">
                    @forelse ($stickers as $sticker)
                        <div class="col-4 col-md-2 p-0 border sticker-item" data-name="{{ strtolower($sticker->name) }}">
                            <a href="{{ route('stickers.edit', $sticker->id) }}" class="d-block text-decoration-none p-1"
                                title="{{ $sticker->name }}">
                                <div class="img-responsive img-responsive-1x1 rounded rounded-0 w-100"
                                    style="background-image: url('{{ asset('storage/' . $sticker->image_path) }}'); background-size: contain; background-repeat: no-repeat; background-position: center;">
                                </div>
                            </a>
                        </div>
                    @empty
                        <div class="col-12 text-center p-5">
                            <p class="empty-title">No stickers found</p>
                            <p class="empty-subtitle text-secondary">Get started by adding your first sticker.</p>
                        </div>
                    @endforelse
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
            const gridContainer = document.getElementById('stickerGrid');
            const items = Array.from(gridContainer.getElementsByClassName('sticker-item'));
            const footer = document.getElementById('tableFooter');
            const info = document.getElementById('tableInfo');
            const pagination = document.getElementById('pagination');

            const totalItems = items.length;
            let currentPage = 1;

            function getFilteredItems() {
                const keyword = searchInput.value.toLowerCase();
                return items.filter(item =>
                    item.getAttribute('data-name').includes(keyword)
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

                // Calculate sliding window
                let startPage = currentPage - half;
                let endPage = currentPage + half;

                // Adjust if hitting left bound
                if (startPage < 1) {
                    startPage = 1;
                    endPage = Math.min(windowSize, totalPages);
                }

                // Adjust if hitting right bound
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
                const filteredItems = getFilteredItems();
                const total = filteredItems.length;

                const pageSize = pageSizeSelect.value === 'all' ?
                    total :
                    parseInt(pageSizeSelect.value);

                const totalPages = Math.ceil(total / pageSize) || 1;
                // Ensure current page is valid
                if (currentPage > totalPages) currentPage = 1;

                const start = (currentPage - 1) * pageSize;
                const end = start + pageSize;

                // Hide all first
                items.forEach(item => item.style.display = 'none');

                // Show only filtered and paginated items
                filteredItems.slice(start, end).forEach(item => {
                    item.style.display = 'block';
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
