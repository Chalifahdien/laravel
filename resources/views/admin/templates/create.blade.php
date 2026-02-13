@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">

                <div class="col">
                    <div class="page-pretitle">
                        Photobooth
                    </div>
                    <h2 class="page-title">
                        Upload Template Photobooth
                    </h2>
                </div>

                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ url('/templates') }}" class="btn btn-link">← Back</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">

            {{-- ERROR --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <div class="d-flex">
                        <i class="ti ti-alert-circle"></i>
                        <div class="ms-2">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.templates.upload') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">

                    {{-- FORM INFO --}}
                    <div class="col-lg-4">
                        <div class="card mb-3">
                            <div class="card-body">

                                <div class="mb-3">
                                    <label class="form-label">Nama Template</label>
                                    <input class="form-control" type="text" name="name"
                                        placeholder="Example: New Year 2026" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <div class="position-relative">
                                        <input class="form-control" type="text" name="category" id="categoryInput"
                                            placeholder="Pilih kategori atau ketik baru" autocomplete="off">
                                        <div id="categoryDropdown" class="dropdown-menu w-100"
                                            style="max-height: 200px; overflow-y: auto; display: none;">
                                            @foreach ($existingCategories as $cat)
                                                <a class="dropdown-item" href="#"
                                                    data-value="{{ $cat }}">{{ $cat }}</a>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="form-hint">
                                        Pilih dari kategori yang ada atau masukkan kategori baru
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const input = document.getElementById('categoryInput');
                                        const dropdown = document.getElementById('categoryDropdown');
                                        const items = dropdown.querySelectorAll('.dropdown-item');

                                        // Show dropdown on focus/click
                                        input.addEventListener('focus', function() {
                                            filterItems('');
                                            dropdown.style.display = 'block';
                                        });

                                        // Filter items as user types
                                        input.addEventListener('input', function() {
                                            filterItems(this.value.toLowerCase());
                                            dropdown.style.display = 'block';
                                        });

                                        // Handle item selection
                                        items.forEach(item => {
                                            item.addEventListener('click', function(e) {
                                                e.preventDefault();
                                                input.value = this.dataset.value;
                                                dropdown.style.display = 'none';
                                            });
                                        });

                                        // Close dropdown when clicking outside
                                        document.addEventListener('click', function(e) {
                                            if (!input.contains(e.target) && !dropdown.contains(e.target)) {
                                                dropdown.style.display = 'none';
                                            }
                                        });

                                        // Filter function
                                        function filterItems(query) {
                                            let hasVisible = false;
                                            items.forEach(item => {
                                                const text = item.textContent.toLowerCase();
                                                if (text.includes(query)) {
                                                    item.style.display = 'block';
                                                    hasVisible = true;
                                                } else {
                                                    item.style.display = 'none';
                                                }
                                            });
                                            // Hide dropdown if no matches
                                            if (!hasVisible && query !== '') {
                                                dropdown.style.display = 'none';
                                            }
                                        }
                                    });
                                </script>

                                <div class="mb-3">
                                    <label class="form-label">Ukuran Kertas</label>
                                    <select class="form-select" name="paper_size_id" required>
                                        @foreach ($paperSizes as $p)
                                            <option value="{{ $p->id }}">
                                                {{ $p->name }}
                                                ({{ $p->width_mm }} × {{ $p->height_mm }} mm)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Template PNG</label>
                                    <input class="form-control" type="file" name="template" id="templateInput"
                                        accept="image/*" required>
                                    <div class="form-hint">
                                        Gunakan resolusi besar untuk kualitas cetak. Anda akan mengatur frame di halaman
                                        berikutnya.
                                    </div>
                                </div>



                            </div>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ url('/templates') }}" class="btn btn-outline-azure w-50">Cancel</a>
                            <button class="btn btn-primary w-50">Upload & Continue</button>
                        </div>
                    </div>

                    {{-- IMAGE PREVIEW --}}
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Preview</h3>
                            </div>
                            <div class="card-body">
                                <div id="previewContainer" class="text-center text-muted"
                                    style="min-height: 400px; display: flex; align-items: center; justify-content: center;">
                                    <div>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon mb-3">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M15 8h.01" />
                                            <path
                                                d="M3 6a3 3 0 0 1 3 -3h12a3 3 0 0 1 3 3v12a3 3 0 0 1 -3 3h-12a3 3 0 0 1 -3 -3v-12z" />
                                            <path d="M3 16l5 -5c.928 -.893 2.072 -.893 3 0l5 5" />
                                            <path d="M14 14l1 -1c.928 -.893 2.072 -.893 3 0l3 3" />
                                        </svg>
                                        <p>Pilih gambar untuk melihat preview</p>
                                    </div>
                                </div>
                                <img id="previewImage" style="display: none; max-width: 100%; height: auto;" />
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <script>
                document.getElementById('templateInput').addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    const previewContainer = document.getElementById('previewContainer');
                    const previewImage = document.getElementById('previewImage');

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            previewImage.src = event.target.result;
                            previewContainer.style.display = 'none';
                            previewImage.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    }
                });
            </script>

        </div>
    </div>

@endsection
