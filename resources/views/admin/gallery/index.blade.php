@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Overview</div>
                    <h2 class="page-title">Final Image Gallery</h2>
                </div>

                <div class="col-auto ms-auto d-print-none">
                    <div class="btn btn-indigo">
                        Total: {{ $finalImages->count() }} images
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">

            @if ($finalImages->isEmpty())
                <div class="empty">
                    {{-- <div class="empty-img">
                        <img src="{{ asset('static/illustrations/undraw_photograph_re_up3b.svg') }}" height="128">
                    </div> --}}
                    <p class="empty-title">No images found</p>
                    <p class="empty-subtitle text-secondary">
                        Final images will appear here after photo sessions.
                    </p>
                </div>
            @else
                <!-- SEARCH -->
                <div class="d-flex justify-content-center mb-4">
                    <div class="col-md-5">
                        <input type="text" id="tableSearch" class="form-control"
                            placeholder="Search by session or date...">
                    </div>
                </div>

                <!-- GALLERY -->
                <div class="row row-cards" id="galleryContainer">
                    @foreach ($finalImages as $image)
                        <div class="col-sm-6 col-md-4 col-lg-3 gallery-item"
                            data-search="session {{ $image->session_id }} {{ $image->created_at->format('d M Y H:i') }}">
                            <div class="card">

                                @if ($image->video_path)
                                    <video controls class="card-img-top" style="aspect-ratio: 3/4; object-fit: cover;">
                                        <source src="{{ asset('storage/' . $image->video_path) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top"
                                            style="aspect-ratio: 3/4; object-fit: cover;">
                                    </a>
                                @endif

                                <div class="card-body">
                                    <div class="fw-semibold">
                                        Session #{{ $image->session_id }}
                                        @if ($image->video_path)
                                            <span class="badge bg-blue-lt ms-1">LIVE</span>
                                        @endif
                                    </div>
                                    <div class="text-secondary">
                                        {{ $image->created_at->format('d M Y H:i') }}
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button class="btn btn-ghost-danger w-100" data-bs-toggle="modal"
                                        data-bs-target="#delete{{ $image->id }}">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- DELETE MODAL -->
                        <div class="modal modal-blur fade" id="delete{{ $image->id }}" tabindex="-1">
                            <div class="modal-dialog modal-sm modal-dialog-centered">
                                <div class="modal-content">

                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>

                                    <div class="modal-body text-center py-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon mb-2 text-danger icon-lg">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 7h16" />
                                            <path d="M10 11v6" />
                                            <path d="M14 11v6" />
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                        </svg>

                                        <h3>Delete Image?</h3>
                                        <div class="text-secondary">
                                            Session #{{ $image->session_id }} <br>
                                            {{ $image->created_at->format('d M Y H:i') }}
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <div class="row w-100">
                                            <div class="col">
                                                <button class="btn btn-3 w-100" data-bs-dismiss="modal">
                                                    Cancel
                                                </button>
                                            </div>
                                            <div class="col">
                                                <form action="{{ route('admin.gallery.destroy', $image) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger w-100">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-between align-items-center d-none mt-4" id="galleryFooter">

                    <div class="text-muted" id="galleryInfo"></div>

                    <nav>
                        <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
                    </nav>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const searchInput = document.getElementById('tableSearch');
            const items = Array.from(document.querySelectorAll('.gallery-item'));

            const footer = document.getElementById('galleryFooter');
            const info = document.getElementById('galleryInfo');
            const pagination = document.getElementById('pagination');

            const pageSize = 12; // jumlah card per halaman
            let currentPage = 1;

            function getFilteredItems() {
                const keyword = searchInput.value.toLowerCase();
                return items.filter(item =>
                    item.dataset.search.toLowerCase().includes(keyword)
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
                const filtered = getFilteredItems();
                const total = filtered.length;
                const totalPages = Math.ceil(total / pageSize) || 1;

                const start = (currentPage - 1) * pageSize;
                const end = start + pageSize;

                items.forEach(item => item.style.display = 'none');

                filtered.slice(start, end).forEach(item => {
                    item.style.display = '';
                });

                footer.classList.remove('d-none');

                info.innerHTML = `
            Showing <strong>${total === 0 ? 0 : start + 1}</strong>
            to <strong>${Math.min(end, total)}</strong>
            of <strong>${total}</strong> images
        `;

                renderPagination(totalPages);
            }

            searchInput.addEventListener('input', () => {
                currentPage = 1;
                render();
            });

            render();
        });
    </script>
@endsection
