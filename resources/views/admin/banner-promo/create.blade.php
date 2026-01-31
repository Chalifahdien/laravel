@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Management</div>
                    <h2 class="page-title">Add Banner Promo</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('admin.banner-promo.index') }}" class="btn btn-link">← Back</a>
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

            <form action="{{ route('admin.banner-promo.store') }}" method="POST" enctype="multipart/form-data" class="card">
                @csrf

                <div class="card-header">
                    <h3 class="card-title">Banner Promo Form</h3>
                </div>

                <div class="card-body">
                    <div class="row row-cards">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Title</label>
                                <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="Promo title" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Link (optional)</label>
                                <input type="url" name="link" class="form-control" value="{{ old('link') }}" placeholder="https://...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label required">Image</label>
                                <input type="file" name="image" class="form-control" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp" required>
                                <small class="form-hint">JPG, PNG, GIF, WebP. Max 5MB.</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', 0) }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Start Date (optional)</label>
                                <input type="datetime-local" name="start_at" class="form-control" value="{{ old('start_at') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">End Date (optional)</label>
                                <input type="datetime-local" name="end_at" class="form-control" value="{{ old('end_at') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Show on Machines</label>
                                <small class="form-hint d-block mb-2">Select which machines will display this banner. Leave empty to show on all machines.</small>
                                <div class="row g-2">
                                    @forelse ($machines as $machine)
                                        <div class="col-md-6 col-lg-4">
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" name="machine_ids[]" value="{{ $machine->id }}" {{ in_array($machine->id, old('machine_ids', [])) ? 'checked' : '' }}>
                                                <span class="form-check-label">{{ $machine->name }}</span>
                                            </label>
                                        </div>
                                    @empty
                                        <div class="col-12 text-muted">No machines available.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <span class="form-check-label">Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Save Banner</button>
                    <a href="{{ route('admin.banner-promo.index') }}" class="btn btn-link">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
