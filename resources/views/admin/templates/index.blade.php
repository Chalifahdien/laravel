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
                        <a href="{{ url('/templates/create') }}" class="btn btn-primary">
                            <i class="ti ti-plus"></i>
                            Tambah Template
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
                <div class="table-responsive">
                    <table class="table table-vcenter table-mobile-md card-table">
                        <thead>
                            <tr>
                                <th class="bg-transparent">#</th>
                                <th class="bg-transparent">Preview</th>
                                <th class="bg-transparent">Nama</th>
                                <th class="bg-transparent">Ukuran Kertas</th>
                                <th class="bg-transparent">Frame</th>
                                <th class="bg-transparent">Status</th>
                                <th class="bg-transparent">Dibuat</th>
                                <th class="bg-transparent w-1"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($templates as $i => $t)
                                <tr>
                                    <td class="{{ $loop->last ? 'border-bottom-0' : '' }} text-muted">{{ $i + 1 }}
                                    </td>

                                    <td class="{{ $loop->last ? 'border-bottom-0' : '' }}">
                                        <span class="avatar avatar-md"
                                            style="background-image: url('{{ asset('storage/' . $t->template_image) }}')">
                                        </span>
                                    </td>
                                    <td class="{{ $loop->last ? 'border-bottom-0' : '' }}" class="fw-semibold">
                                        {{ $t->name }}
                                    </td>

                                    <td class="{{ $loop->last ? 'border-bottom-0' : '' }}">
                                        <div>{{ $t->paperSize->name ?? '-' }}</div>
                                        <div class="text-muted small">
                                            {{ $t->paperSize->width_mm ?? '' }} ×
                                            {{ $t->paperSize->height_mm ?? '' }} mm
                                        </div>
                                    </td>

                                    <td class="{{ $loop->last ? 'border-bottom-0' : '' }}">
                                        <span class="badge bg-blue-lt">
                                            {{ $t->frame_count }} frame
                                        </span>
                                    </td>

                                    <td class="{{ $loop->last ? 'border-bottom-0' : '' }}">
                                        @if ($t->is_active)
                                            <span class="badge bg-green-lt">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary-lt">Nonaktif</span>
                                        @endif
                                    </td>

                                    <td class="{{ $loop->last ? 'border-bottom-0' : '' }}" class="text-muted">
                                        {{ $t->created_at->format('d M Y') }}
                                    </td>
                                    <td class="{{ $loop->last ? 'border-bottom-0' : '' }}">
                                        <div class="btn-list flex-nowrap justify-content-end">
                                            <form method="POST" action="{{ route('admin.templates.toggle', $t->id) }}"
                                                class="d-inline">
                                                @csrf
                                                @method('PATCH')

                                                <button class="btn btn-outline-primary">
                                                    {{ $t->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                </button>
                                            </form>
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
                                            <form method="POST" action="{{ route('admin.templates.destroy', $t->id) }}"
                                                class="d-inline" onsubmit="return confirm('Hapus template ini?')">
                                                @csrf
                                                @method('DELETE')

                                                <button class="btn btn-icon btn-outline-danger" title="Hapus">
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
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        Belum ada template
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
