@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Management</div>
                    <h2 class="page-title">Edit Sticker</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="{{ route('stickers.update', $sticker->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Sticker Details</h3>
                            </div>
                            <div class="card-body">
                                <!-- Current Image Preview -->
                                <div class="mb-3">
                                    <label class="form-label">Current Image</label>
                                    <div class="p-3 border rounded" style="background: #f5f5f5; text-align: center;">
                                        <img src="{{ asset('storage/' . $sticker->image_path) }}" alt="{{ $sticker->name }}"
                                            style="max-width: 200px; max-height: 200px;">
                                    </div>
                                </div>

                                <!-- Name -->
                                <div class="mb-3">
                                    <label class="form-label required">Sticker Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name', $sticker->name) }}"
                                        placeholder="Enter sticker name" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Image Upload (Optional for edit) -->
                                <div class="mb-3">
                                    <label class="form-label">Change Image (Optional)</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        name="image" accept="image/png,image/svg+xml,image/jpeg,image/jpg">
                                    <small class="form-hint">Leave empty to keep current image. Supported formats: PNG,
                                        SVG, JPG. Max size: 2MB</small>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div class="mb-3">
                                    <label class="form-label">Category (Optional)</label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror"
                                        name="category" value="{{ old('category', $sticker->category) }}"
                                        placeholder="e.g., Emoji, Decorations">
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Active Status -->
                                <div class="mb-3">
                                    <label class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active"
                                            {{ old('is_active', $sticker->is_active) ? 'checked' : '' }}>
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
                                            <path d="M5 12l5 5l10 -10" />
                                        </svg>
                                        Update Sticker
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
