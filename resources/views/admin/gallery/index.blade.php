@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Final Image Gallery
                    </h2>
                </div>

                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <div class="btn btn-indigo">
                            Total: {{ $finalImages->count() }} images
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">

            @if ($finalImages->isEmpty())
                <div class="empty">
                    <div class="empty-img">
                        <img src="{{ asset('static/illustrations/undraw_photograph_re_up3b.svg') }}" height="128"
                            alt="">
                    </div>
                    <p class="empty-title">No images found</p>
                    <p class="empty-subtitle text-secondary">
                        Final images will appear here after photo sessions.
                    </p>
                </div>
            @else
                <div class="row row-cards">
                    @foreach ($finalImages as $image)
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <div class="card">
                                <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank" class="d-block">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="card-img-top"
                                        style="aspect-ratio: 3/4; object-fit: cover;">
                                </a>

                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <div class="fw-semibold">
                                                Session #{{ $image->session_id }}
                                            </div>
                                            <div class="text-secondary">
                                                {{ $image->created_at->format('d M Y H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <button class="btn btn-ghost-danger w-100" data-bs-toggle="modal"
                                        data-bs-target="#hapus{{ $image->id }}">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="modal modal-blur fade" id="hapus{{ $image->id }}" tabindex="-1" role="dialog"
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
                                            <path d="M4 7h16" />
                                            <path d="M10 11v6" />
                                            <path d="M14 11v6" />
                                            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                        </svg>

                                        <h3>Delete Image?</h3>
                                        <div class="text-secondary">
                                            Session #{{ $image->session_id }} <br>
                                            {{ $image->created_at->format('d M Y H:i') }}
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <div class="w-100">
                                            <div class="row">
                                                <div class="col">
                                                    <button class="btn btn-3 w-100" data-bs-dismiss="modal">
                                                        Cancel
                                                    </button>
                                                </div>
                                                <div class="col">
                                                    <form action="{{ route('admin.gallery.destroy', $image) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-danger btn-4 w-100">
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
                </div>
            @endif

        </div>
    </div>
@endsection
