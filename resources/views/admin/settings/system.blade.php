@extends('layouts.app')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Settings
                    </div>
                    <h2 class="page-title">
                        System Settings
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>An error occurred:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.settings.system.update') }}" method="POST" enctype="multipart/form-data"
                class="card">
                @csrf
                @method('PUT')

                <div class="card-header">
                    <h3 class="card-title">System Configuration</h3>
                </div>

                <div class="card-body">
                    <div class="row row-cards">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">System Name</label>
                                <input type="text" name="system_name" class="form-control"
                                    value="{{ old('system_name', $settings->system_name) }}" required>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Logo (PNG/JPG/WEBP)</label>
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                @if ($settings->logo_path)
                                    <div class="mt-2">
                                        <div class="text-secondary mb-1">Current Logo:</div>
                                        <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo"
                                            style="max-height: 60px;">
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Favicon (PNG/ICO/JPG/WEBP)</label>
                                <input type="file" name="favicon" class="form-control" accept="image/*">
                                @if ($settings->favicon_path)
                                    <div class="mt-2">
                                        <div class="text-secondary mb-1">Current Favicon:</div>
                                        <img src="{{ asset('storage/' . $settings->favicon_path) }}" alt="Favicon"
                                            style="max-height: 32px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
