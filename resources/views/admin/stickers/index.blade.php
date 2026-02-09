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
            <div class="row row-cards">
                @forelse ($stickers as $sticker)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card">
                            <div class="card-img-top img-responsive img-responsive-16x9"
                                style="background: #f5f5f5; display: flex; align-items: center; justify-content: center; padding: 20px;">
                                <img src="{{ asset('storage/' . $sticker->image_path) }}" alt="{{ $sticker->name }}"
                                    style="max-width: 100%; max-height: 200px; object-fit: contain;">
                            </div>

                            <div class="card-body">
                                <div class="fw-semibold">
                                    {{ $sticker->name }}
                                    @if ($sticker->is_active)
                                        <span class="badge bg-green-lt ms-1">Active</span>
                                    @else
                                        <span class="badge bg-secondary-lt ms-1">Inactive</span>
                                    @endif
                                </div>
                                @if ($sticker->category)
                                    <div class="text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-tag"
                                            width="16" height="16" viewBox="0 0 24 24" stroke-width="2"
                                            stroke="currentColor" fill="none" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7.5 7.5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                            <path
                                                d="M3 6v5.172a2 2 0 0 0 .586 1.414l7.71 7.71a2.41 2.41 0 0 0 3.408 0l5.592 -5.592a2.41 2.41 0 0 0 0 -3.408l-7.71 -7.71a2 2 0 0 0 -1.414 -.586h-5.172a3 3 0 0 0 -3 3z" />
                                        </svg>
                                        {{ $sticker->category }}
                                    </div>
                                @endif
                            </div>

                            <div class="card-footer d-flex gap-2">
                                <a href="{{ route('stickers.edit', $sticker->id) }}" class="btn btn-outline-primary w-100">
                                    Edit
                                </a>
                                <form action="{{ route('stickers.destroy', $sticker->id) }}" method="POST" class="w-100"
                                    onsubmit="return confirm('Are you sure you want to delete this sticker?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger w-100">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty">
                            <p class="empty-title">No stickers found</p>
                            <p class="empty-subtitle text-secondary">
                                Get started by adding your first sticker.
                            </p>
                            <div class="empty-action">
                                <a href="{{ route('stickers.create') }}" class="btn btn-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M12 5l0 14" />
                                        <path d="M5 12l14 0" />
                                    </svg>
                                    Add New Sticker
                                </a>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
