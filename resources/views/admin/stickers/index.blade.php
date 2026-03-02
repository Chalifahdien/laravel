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
                <form action="{{ route('stickers.index') }}" method="GET"
                    class="card-header d-flex justify-content-between align-items-center gap-2" id="filterForm">
                    <input type="text" name="search" id="tableSearch" class="form-control w-50"
                        placeholder="Search sticker..." value="{{ request('search') }}"
                        oninput="clearTimeout(this.delay); this.delay = setTimeout(() => document.getElementById('filterForm').submit(), 500);">

                    <select name="per_page" id="pageSize" class="form-select w-auto"
                        onchange="document.getElementById('filterForm').submit()">
                        <option value="18" {{ request('per_page') == 18 ? 'selected' : '' }}>18</option>
                        <option value="36" {{ request('per_page') == 36 ? 'selected' : '' }}>36</option>
                        <option value="60" {{ request('per_page') == 60 ? 'selected' : '' }}>60</option>
                        <option value="120" {{ request('per_page') == 120 ? 'selected' : '' }}>120</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                    </select>
                </form>
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
                <div class="card-footer d-flex justify-content-between align-items-center" id="tableFooter">
                    <div class="text-muted" id="tableInfo">
                        Showing <strong>{{ $stickers->firstItem() ?? 0 }}</strong>
                        to <strong>{{ $stickers->lastItem() ?? 0 }}</strong>
                        of <strong>{{ $stickers->total() }}</strong> entries
                    </div>

                    <nav>
                        {{ $stickers->links('vendor.pagination.custom') }}
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection
