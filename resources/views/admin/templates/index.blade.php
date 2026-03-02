@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">

                <div class="col">
                    <div class="page-pretitle">
                        Photobooth
                    </div>
                    <h2 class="page-title">
                        Template Photobooth
                    </h2>
                </div>

                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ url('/templates/create') }}" class="btn btn-indigo">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path d="M12 5l0 14" />
                                <path d="M5 12l14 0" />
                            </svg>
                            Add Template
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
                <form action="{{ route('admin.templates.index') }}" method="GET"
                    class="card-header d-flex justify-content-between align-items-center gap-2" id="filterForm">
                    <input type="text" name="search" id="tableSearch" class="form-control w-50"
                        placeholder="Search template..." value="{{ request('search') }}"
                        oninput="clearTimeout(this.delay); this.delay = setTimeout(() => document.getElementById('filterForm').submit(), 500);">

                    <select name="per_page" id="pageSize" class="form-select w-auto"
                        onchange="document.getElementById('filterForm').submit()">
                        <option value="12" {{ request('per_page') == 12 ? 'selected' : '' }}>12</option>
                        <option value="24" {{ request('per_page') == 24 ? 'selected' : '' }}>24</option>
                        <option value="48" {{ request('per_page') == 48 ? 'selected' : '' }}>48</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All</option>
                    </select>
                </form>
                <div class="table-responsive">
                    <table class="table table-vcenter table-bordered card-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Preview</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Paper Size</th>
                                <th>Frame</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th class="w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($templates as $t)
                                <tr>
                                    <td class="text-muted">
                                        {{ ($templates->currentPage() - 1) * $templates->perPage() + $loop->iteration }}
                                    </td>

                                    <td>
                                        <span class="avatar avatar-md"
                                            style="background-image: url('{{ asset('storage/' . $t->template_image) }}')">
                                        </span>
                                    </td>
                                    <td class="fw-semibold">
                                        {{ $t->name }}
                                    </td>

                                    <td>
                                        @if ($t->category)
                                            <span class="badge bg-indigo-lt">{{ $t->category }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div>{{ $t->paperSize->name ?? '-' }}</div>
                                        <div class="text-muted small">
                                            {{ $t->paperSize->width_mm ?? '' }} ×
                                            {{ $t->paperSize->height_mm ?? '' }} mm
                                        </div>
                                    </td>

                                    <td>
                                        <span class="badge bg-blue-lt">
                                            {{ $t->frame_count }} frame
                                        </span>
                                    </td>

                                    <td>

                                        <form method="POST" action="{{ route('admin.templates.toggle', $t->id) }}">
                                            @csrf
                                            @method('PATCH')

                                            <label class="form-check form-switch d-flex align-items-center gap-2 mb-2 m-0">
                                                <input class="form-check-input" type="checkbox"
                                                    {{ $t->is_active ? 'checked' : '' }} onchange="this.form.submit()">
                                            </label>
                                        </form>
                                        @if ($t->is_active)
                                            <span class="badge bg-green-lt">Active</span>
                                        @else
                                            <span class="badge bg-secondary-lt">Inactive</span>
                                        @endif
                                    </td>

                                    <td class="text-muted">
                                        {{ $t->created_at->format('d M Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap justify-content-end">
                                            <a href="/templates/{{ $t->id }}/edit"
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
                                                data-bs-target="#hapus{{ $t->id }}">
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
                                    <td colspan="9" class="border-bottom-0 text-center text-muted py-4">
                                        No templates found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center" id="tableFooter">
                    <div class="text-muted" id="tableInfo">
                        Showing <strong>{{ $templates->firstItem() ?? 0 }}</strong>
                        to <strong>{{ $templates->lastItem() ?? 0 }}</strong>
                        of <strong>{{ $templates->total() }}</strong> entries
                    </div>

                    <nav>
                        {{ $templates->links('vendor.pagination.custom') }}
                    </nav>
                </div>

                {{-- Model Hapus --}}
                @forelse ($templates as $i => $t)
                    <div class="modal modal-blur fade" id="hapus{{ $t->id }}" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <div class="modal-body text-center py-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="icon mb-2 text-danger icon-lg">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 7l16 0" />
                                        <path d="M10 11l0 6" />
                                        <path d="M14 11l0 6" />
                                        <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                        <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                    </svg>
                                    <h3>Are you sure?</h3>
                                    <div class="text-secondary">
                                        Are you sure you want to delete the template {{ $t->name }}?
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
                                                <form method="POST"
                                                    action="{{ route('admin.templates.destroy', $t->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-4 w-100" type="submit">
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
            @endsection
