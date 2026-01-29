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
                        Tambah Mesin Baru
                    </h2>
                </div>

                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('machines.index') }}" class="btn btn-outline-secondary">
                        Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('machines.store') }}" method="POST" class="card">
                @csrf

                <div class="card-header">
                    <h3 class="card-title">Form Tambah Mesin</h3>
                </div>

                <div class="card-body">
                    <div class="row row-cards">

                        {{-- Nama --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nama Mesin</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    placeholder="Photobooth Mall A" required>
                            </div>
                        </div>

                        {{-- Slug --}}
                        {{-- <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Slug (unik)</label>
                                <input type="text" name="slug" class="form-control" value="{{ old('slug') }}"
                                    placeholder="mall-a-01" required>
                                <small class="text-secondary">
                                    Digunakan oleh mesin / device
                                </small>
                            </div>
                        </div> --}}

                        {{-- Paper size --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ukuran Kertas</label>
                                <select name="paper_size_id" class="form-select" required>
                                    <option value="">-- Pilih ukuran --</option>
                                    @foreach ($paperSizes as $paper)
                                        <option value="{{ $paper->id }}"
                                            {{ old('paper_size_id') == $paper->id ? 'selected' : '' }}>
                                            {{ $paper->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Price --}}
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Harga (Rp)</label>
                                <input type="number" name="price" class="form-control" value="{{ old('price') }}"
                                    min="1000" placeholder="30000" required>
                            </div>
                        </div>

                        {{-- Active --}}
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                    <span class="form-check-label">Mesin Aktif</span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        Simpan Mesin
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection
