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
                        Data Mesin Photobooth
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
                            Tambah Mesin
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Mesin</h3>
                </div>

                <div class="table-responsive">
                    <table class="table card-table table-vcenter text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Mesin</th>
                                <th>Slug</th>
                                <th>Ukuran Kertas</th>
                                <th>Harga</th>
                                <th>Status</th>
                                <th class="w-1">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($machines as $machine)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $machine->name }}</td>
                                    <td class="text-muted">{{ $machine->slug }}</td>
                                    <td>
                                        {{ $machine->paperSize->name ?? '-' }}
                                    </td>
                                    <td>
                                        Rp {{ number_format($machine->price, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        @if ($machine->is_active)
                                            <span class="badge bg-green">Aktif</span>
                                        @else
                                            <span class="badge bg-red">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <a href="{{ route('machines.edit', $machine) }}" class="btn btn-sm btn-warning">
                                                Edit
                                            </a>

                                            <form action="{{ route('machines.destroy', $machine) }}" method="POST"
                                                onsubmit="return confirm('Yakin hapus mesin ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted">
                                        Data mesin belum ada
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
