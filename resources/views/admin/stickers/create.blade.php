@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Management</div>
                    <h2 class="page-title">Add New Sticker</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('stickers.index') }}" class="btn btn-link">
                        ← Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col">
                    <form action="{{ route('stickers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Sticker Details</h3>
                            </div>
                            <div class="card-body">
                                <!-- Name -->
                                <div class="mb-3">
                                    <label class="form-label required">Sticker Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" placeholder="Enter sticker name"
                                        required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Image Upload -->
                                <div class="mb-3">
                                    <label class="form-label required">Sticker Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        name="image" accept="image/png,image/svg+xml,image/jpeg,image/jpg" required>
                                    <small class="form-hint">Supported formats: PNG, SVG, JPG. Max size: 2MB</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="mb-3">
                                    <label class="form-label">Category (Optional)</label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror"
                                        name="category" value="{{ old('category') }}"
                                        placeholder="e.g., Emoji, Decorations">
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Active Status -->
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" checked>
                                        <span class="form-check-label">Active (Available for use)</span>
                                    </label>
                                </div>
                            </div>

                            <div class="card-footer text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('stickers.index') }}" class="btn">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 5l0 14" />
                                            <path d="M5 12l14 0" />
                                        </svg>
                                        Create Sticker
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
