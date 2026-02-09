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
                        Edit Machine
                    </h2>
                </div>

                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('machines.index') }}" class="btn btn-link">
                        ← Back
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

            <form action="{{ route('machines.update', $machine) }}" method="POST" class="card">
                @csrf
                @method('PUT')

                <div class="card-header">
                    <h3 class="card-title">Edit Machine Form</h3>
                </div>

                <div class="card-body">
                    <div class="row row-cards">

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Machine Name</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $machine->name) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6 d-none">
                            <div class="mb-3">
                                <label class="form-label">Slug</label>
                                <input disabled type="text" name="slug" class="form-control"
                                    value="{{ old('slug', $machine->slug) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Paper Size</label>
                                <select name="paper_size_id" class="form-select" required>
                                    @foreach ($paperSizes as $paper)
                                        <option value="{{ $paper->id }}"
                                            {{ $machine->paper_size_id == $paper->id ? 'selected' : '' }}>
                                            {{ $paper->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Price (IDR)</label>
                                <input type="number" name="price" class="form-control"
                                    value="{{ old('price', $machine->price) }}" min="1000" required>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <input type="hidden" name="is_active" value="0">
                                <label class="form-check form-switch d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                        {{ $machine->is_active ? 'checked' : '' }}>
                                    <span class="form-check-label">
                                        Active Machine
                                    </span>
                                </label>
                            </div>

                            <div class="mb-3">
                                <input type="hidden" name="payment_required" value="1">
                                <label class="form-check form-switch d-flex align-items-center gap-2">
                                    <input class="form-check-input" type="checkbox" name="payment_required" value="0"
                                        {{ !$machine->payment_required ? 'checked' : '' }}>
                                    <span class="form-check-label">
                                        Active Free Mode
                                    </span>
                                </label>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
