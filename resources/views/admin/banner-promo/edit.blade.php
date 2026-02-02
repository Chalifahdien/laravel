@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Management</div>
                    <h2 class="page-title">Edit Banner Promo</h2>
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

            <form action="{{ route('admin.banner-promo.update', $bannerPromo) }}" method="POST"
                enctype="multipart/form-data" class="card">
                @csrf
                @method('PUT')

                <div class="card-header">
                    <h3 class="card-title">Edit Banner Promo</h3>
                </div>

                <div class="card-body">
                    <div class="row row-cards">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label required">Title</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $bannerPromo->title) }}" placeholder="Promo title" required>
                            </div>
                        </div>
                        <div class="col-md-6 d-none">
                            <div class="mb-3">
                                <label class="form-label">Link (optional)</label>
                                <input type="url" name="link" class="form-control"
                                    value="{{ old('link', $bannerPromo->link) }}" placeholder="https://...">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Current Image</label>
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $bannerPromo->image) }}" alt="{{ $bannerPromo->title }}"
                                        class="img-fluid rounded" style="max-height: 120px;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Replace Image (optional)</label>
                                <input type="file" name="image" class="form-control"
                                    accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                                <small class="form-hint">Leave empty to keep current. JPG, PNG, GIF, WebP. Max
                                    5MB.</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Sort Order</label>
                                <input type="number" name="sort_order" class="form-control"
                                    value="{{ old('sort_order', $bannerPromo->sort_order) }}" min="0">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Start Date (optional)</label>
                                    <input type="datetime-local" name="start_at" class="form-control"
                                        value="{{ old('start_at', $bannerPromo->start_at?->format('Y-m-d\TH:i')) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">End Date (optional)</label>
                                    <input type="datetime-local" name="end_at" class="form-control"
                                        value="{{ old('end_at', $bannerPromo->end_at?->format('Y-m-d\TH:i')) }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Show on Machines</label>
                                <small class="form-hint d-block mb-2">
                                    Only the selected machines will display this banner. Unchecked machines will be removed
                                    from the banner.
                                </small>
                                <div class="row g-2">
                                    @forelse ($machines as $machine)
                                        <div class="col-md-6 col-lg-4">
                                            <label class="form-check">
                                                <input class="form-check-input" type="checkbox" name="machine_ids[]"
                                                    value="{{ $machine->id }}"
                                                    {{ in_array($machine->id, old('machine_ids', $bannerPromo->machines->pluck('id')->toArray())) ? 'checked' : '' }}>
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
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    {{ old('is_active', $bannerPromo->is_active) ? 'checked' : '' }}>
                                <span class="form-check-label">Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update Banner</button>
                    <a href="{{ route('admin.banner-promo.index') }}" class="btn btn-link">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
