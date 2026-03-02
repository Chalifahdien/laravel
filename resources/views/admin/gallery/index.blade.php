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
                <form action="{{ route('admin.gallery.index') }}" method="GET" class="d-flex justify-content-center mb-4"
                    id="filterForm">
                    <div class="col-md-5 d-flex gap-2">
                        <input type="text" name="search" id="tableSearch" class="form-control"
                            placeholder="Search by session..." value="{{ request('search') }}"
                            oninput="clearTimeout(this.delay); this.delay = setTimeout(() => document.getElementById('filterForm').submit(), 500);">

                        <select name="per_page" class="form-select w-auto"
                            onchange="document.getElementById('filterForm').submit()">
                            <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12</option>
                            <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                            <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                        </select>
                    </div>
                </form>

                <!-- GALLERY -->
                <div class="row row-cards" id="galleryContainer">
                    @foreach ($finalImages as $image)
                        <div class="col-sm-6 col-md-4 col-lg-3 gallery-item"
                            data-search="session {{ $image->session_id }} {{ $image->created_at->format('d M Y H:i') }}">
                            <div class="card">


                                <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top"
                                        style="aspect-ratio: 3/4; object-fit: cover;">
                                </a>

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

                                <div class="card-footer d-flex gap-3">

                                    <div class="col">
                                        <a href="{{ route('transactions.show', $image->session_id) }}"
                                            class="btn btn-outline-primary w-100">
                                            Detail
                                        </a>
                                    </div>
                                    <div class="col">
                                        <button class="btn btn-outline-danger w-100" data-bs-toggle="modal"
                                            data-bs-target="#delete{{ $image->id }}">
                                            Delete
                                        </button>
                                    </div>
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
                <div class="mt-4 mb-4 d-flex justify-content-between align-items-center" id="galleryFooter">

                    <div class="text-muted" id="galleryInfo">
                        Showing <strong>{{ $finalImages->firstItem() ?? 0 }}</strong>
                        to <strong>{{ $finalImages->lastItem() ?? 0 }}</strong>
                        of <strong>{{ $finalImages->total() }}</strong> images
                    </div>

                    <nav>
                        {{ $finalImages->links('vendor.pagination.custom') }}
                    </nav>
                </div>
            @endif
        </div>
    </div>
@endsection
