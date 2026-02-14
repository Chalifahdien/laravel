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
                        Edit Template Photobooth
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
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $e)
                            <li>{{ $e }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- NEW TEMPLATE ALERT --}} @if ($template->frame_count == 0)
                <div class="card card bg-primary-lt mb-3">
                    <div class="card-body">
                        <h4 class="alert-title">Template Baru!</h4>
                        <div class="text-secondary">Silakan tambahkan frame ke template ini menggunakan tools di bawah.
                            Minimal 1 frame harus dibuat agar template bisa diaktifkan.</div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.templates.update', $template->id) }}" enctype="multipart/form-data"
                onsubmit="return prepareFrames()">
                @csrf
                @method('PUT')

                <input type="hidden" name="frames" id="framesInput">

                <div class="row g-3">

                    {{-- FORM INFO --}}
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-12">
                                        <label class="form-label">Nama Template</label>
                                        <input class="form-control" type="text" name="name"
                                            value="{{ old('name', $template->name) }}" required>
                                    </div>

                                    <div class="col-xl-3 col-lg-6 mt-3 mt-xl-0">
                                        <label class="form-label">Kategori</label>
                                        <div class="position-relative">
                                            <input class="form-control" type="text" name="category"
                                                id="categoryInputEdit" value="{{ old('category', $template->category) }}"
                                                placeholder="Pilih kategori atau ketik baru" autocomplete="off">
                                            <div id="categoryDropdownEdit" class="dropdown-menu w-100"
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
                                            const input = document.getElementById('categoryInputEdit');
                                            const dropdown = document.getElementById('categoryDropdownEdit');
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

                                    <div class="col-xl-3 col-lg-6 mt-3 mt-xl-0">
                                        <label class="form-label">Ukuran Kertas</label>
                                        <select class="form-select" name="paper_size_id" required>
                                            @foreach ($paperSizes as $p)
                                                <option value="{{ $p->id }}" @selected($template->paper_size_id == $p->id)>
                                                    {{ $p->name }}
                                                    ({{ $p->width_mm }} × {{ $p->height_mm }} mm)
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-xl-3 col-lg-12">
                                        <label class="form-label transparent" style="color: transparent">.</label>
                                        <div class="d-flex gap-3">
                                            <div class="col w-50">
                                                <a href="{{ url('/templates') }}"
                                                    class="btn btn-outline-azure w-100">Cancel</a>
                                            </div>
                                            <div class="col w-50">
                                                <button class="btn btn-primary w-100">Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CANVAS EDITOR --}}
                    <div class="col-lg-12">
                        <div class="card">

                            {{-- TOOLBAR --}}
                            <div class="card-header">
                                <div class="btn-list">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-indigo dropdown-toggle" type="button"
                                            id="shapeDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" class="icon m-0 p-0 me-2">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path
                                                    d="M12 3l-4 7h8zM7 14l-4 7h8zM17 14l-4 7h8zM12 3l-4 7h8zM7 14l-4 7h8zM17 14l-4 7h8z" />
                                            </svg>
                                            Shape
                                        </button>
                                        <ul class="dropdown-menu" aria-labelledby="shapeDropdown">
                                            <div class="row">
                                                <div class="col pe-0">

                                                    <a class="btn w-100 btn-ghost-dark" href="#"
                                                        onclick="addShape('rect')" data-bs-toggle="tooltip"
                                                        data-bs-placement="left" aria-label="Rectangle"
                                                        data-bs-original-title="Rectangle">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon m-0 p-0">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10" />
                                                        </svg>
                                                    </a>
                                                    <a class="btn w-100 btn-ghost-dark mt-1" href="#"
                                                        onclick="addShape('circle')" data-bs-toggle="tooltip"
                                                        data-bs-placement="left" aria-label="Circle"
                                                        data-bs-original-title="Circle">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon m-0 p-0">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                                        </svg>
                                                    </a>
                                                    <a class="btn w-100 btn-ghost-dark mt-1" href="#"
                                                        onclick="addShape('heart')" data-bs-toggle="tooltip"
                                                        data-bs-placement="left" aria-label="Heart"
                                                        data-bs-original-title="Heart">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon m-0 p-0">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 0 1 7.5 6.566" />
                                                        </svg>
                                                    </a>

                                                </div>
                                                <div class="col ps-1">
                                                    <a class="btn w-100 btn-ghost-dark" href="#"
                                                        onclick="addShape('star')" data-bs-toggle="tooltip"
                                                        data-bs-placement="right" aria-label="Star"
                                                        data-bs-original-title="Star">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon m-0 p-0">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873l-6.158 -3.245" />
                                                        </svg>
                                                    </a>
                                                    <a class="btn w-100 btn-ghost-dark mt-1" href="#"
                                                        onclick="addShape('triangle')" data-bs-toggle="tooltip"
                                                        data-bs-placement="right" aria-label="Triangle"
                                                        data-bs-original-title="Triangle">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon m-0 p-0">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0" />
                                                        </svg>
                                                    </a>
                                                    <a class="btn w-100 btn-ghost-dark mt-1" href="#"
                                                        onclick="addShape('hexagon')" data-bs-toggle="tooltip"
                                                        data-bs-placement="right" aria-label="Hexagon"
                                                        data-bs-original-title="Hexagon">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon m-0 p-0">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path
                                                                d="M19.875 6.43l-6.25 -3.577a2 2 0 0 0 -1.75 0l-6.25 3.577a2 2 0 0 0 -1 1.768v7.272a2 2 0 0 0 1 1.768l6.25 3.577a2 2 0 0 0 1.75 0l6.25 -3.577a2 2 0 0 0 1 -1.768v-7.272a2 2 0 0 0 -1 -1.768z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="col-12 mt-1">
                                                    <button type="button" class="btn btn-outline-success w-100"
                                                        onclick="toggleDrawingMode()" id="drawBtn">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                                            height="24" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round" class="icon m-0 p-0 me-2">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M4 20h4l10.5 -10.5a1.5 1.5 0 0 0 -4 -4l-10.5 10.5v4" />
                                                            <line x1="13.5" y1="6.5" x2="17.5"
                                                                y2="10.5" />
                                                        </svg>Draw Frame
                                                    </button>
                                                </div>
                                            </div>
                                        </ul>
                                    </div>

                                    <button type="button" class="btn btn-success d-none" onclick="finishDrawing()"
                                        id="finishDrawBtn">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon m-0 p-0 me-2">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M5 12l5 5l10 -10" />
                                        </svg>Finish
                                    </button>

                                    <button type="button" class="btn btn-ghost-yellow btn-icon" onclick="copyObject()"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Copy"
                                        data-bs-original-title="Copy">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon m-0 p-0">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <rect x="8" y="8" width="12" height="12" rx="2" />
                                            <path d="M16 8v-2a2 2 0 0 0 -2 -2h-8a2 2 0 0 0 -2 2v8a2 2 0 0 0 2 2h2" />
                                        </svg>
                                    </button>
                                    <button type="button" class="btn btn-ghost-yellow btn-icon" onclick="pasteObject()"
                                        data-bs-toggle="tooltip" data-bs-placement="bottom" aria-label="Paste"
                                        data-bs-original-title="Paste">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon m-0 p-0">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                            <rect x="9" y="3" width="6" height="4" rx="2" />
                                        </svg>
                                    </button>

                                    <button type="button" class="btn btn-ghost-danger btn-icon"
                                        onclick="removeSelected()" data-bs-toggle="tooltip" data-bs-placement="bottom"
                                        aria-label="Delete" data-bs-original-title="Delete">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon m-0 p-0">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M20 13v-4a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v5a2 2 0 0 0 2 2h7" />
                                            <path d="M22 22l-5 -5" />
                                            <path d="M17 22l5 -5" />
                                        </svg>
                                    </button>

                                    <div class="vr"></div>
                                    <button type="button" class="btn btn-ghost-secondary btn-icon" onclick="zoomOut()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon p-0 m-0">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="10" cy="10" r="7" />
                                            <line x1="21" y1="21" x2="15" y2="15" />
                                            <line x1="7" y1="10" x2="13" y2="10" />
                                        </svg>
                                    </button>
                                    <span class="btn disabled" id="zoomLevel">100%</span>
                                    <button type="button" class="btn btn-ghost-secondary btn-icon" onclick="zoomIn()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon p-0 m-0">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <circle cx="10" cy="10" r="7" />
                                            <line x1="21" y1="21" x2="15" y2="15" />
                                            <line x1="7" y1="10" x2="13" y2="10" />
                                            <line x1="10" y1="7" x2="10" y2="13" />
                                        </svg>
                                    </button>
                                    <button type="button" class="btn btn-ghost-secondary"
                                        onclick="resetZoom()">Reset</button>
                                </div>
                            </div>
                            {{-- CANVAS --}}
                            <div class="card-body p-2">
                                <div class="canvas-wrapper">
                                    <canvas id="canvas"></canvas>
                                </div>
                                <div class="mt-2 text-muted small" id="drawingHint" style="display: none;">
                                    Click on the canvas to add points. Click "Finish" or double-click to close the shape.
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- STYLE --}}
    <style>
        .canvas-wrapper {
            max-width: 100%;
            max-height: 70vh;
            overflow: auto;
            border: 1px dashed var(--tblr-border-color);
            background: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        canvas {
            display: block;
        }
    </style>

    {{-- Fabric.js --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

    <script>
        /* ===============================
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    FABR    IC GLOBAL CONFIG
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ================================ */
        fabric.Object.prototype.originX = 'left';
        fabric.Object.prototype.originY = 'top';
        fabric.Object.prototype.transparentCorners = false;
        fabric.Object.prototype.cornerColor = 'green';
        fabric.Object.prototype.borderColor = 'green';
        fabric.Object.prototype.cornerSize = 7;

        const canvas = new fabric.Canvas('canvas');
        let SCALE = 1;

        let currentZoom = 1;
        let originalW = 0;
        let originalH = 0;

        let _clipboard = null;

        // Drawing state
        let isDrawing = false;
        let drawingPoints = [];
        let drawingLines = [];
        let activeShape = null;

        // Shape configurations (SVG Paths)
        const SHAPES = {
            'rect': {
                type: 'rect'
            },
            'circle': {
                type: 'circle'
            },
            'heart': {
                type: 'path',
                path: 'M 272.70141,238.71731 C 206.46141,238.71731 152.70141,292.47731 152.70141,358.71731 C 152.70141,493.46212 288.63461,521.28716 396.70141,617.91731 C 504.76821,521.28716 640.70141,493.46212 640.70141,358.71731 C 640.70141,292.47731 586.94141,238.71731 520.70141,238.71731 C 492.39938,238.71731 466.31524,248.61263 445.70141,265.18606 L 396.70141,304.59231 L 347.70141,265.18606 C 327.08758,248.61263 301.00344,238.71731 272.70141,238.71731 z'
            },
            'star': {
                type: 'path',
                path: 'M 302.34863,165.7196 L 329.58983,235.15064 L 401.76993,238.82582 L 345.52989,286.06822 L 364.5779,356.36872 L 302.34863,316.58628 L 240.11936,356.36872 L 259.16736,286.06822 L 202.92733,238.82582 L 275.10743,235.15064 L 302.34863,165.7196 z'
            },
            'triangle': {
                type: 'path',
                path: 'M 363.5,224.5 L 467,411.5 L 260,411.5 z'
            },
            'hexagon': {
                type: 'path',
                path: 'M 353.5,236.5 L 420,275 L 420,352 L 353.5,390.5 L 287,352 L 287,275 z'
            }
        };

        const COMMON_STYLES = {
            fill: 'rgba(40, 167, 69, 0.15)',
            stroke: '#1e4620',
            strokeWidth: 2,
            strokeDashArray: [5, 5],
            strokeUniform: true
        };

        /* ===============================
            LOAD IMAGE (DEFAULT)
        ================================ */
        loadImage("{{ asset('storage/' . $template->template_image) }}");

        /* ===============================
            REPLACE IMAGE
        ================================ */
        document.getElementById('templateInput').addEventListener('change', e => {
            const reader = new FileReader();
            reader.onload = ev => loadImage(ev.target.result, true);
            reader.readAsDataURL(e.target.files[0]);
        });

        function loadImage(src, reset = false) {
            const tempImg = new Image();

            tempImg.onload = function() {
                fabric.Image.fromURL(src, img => {

                    if (reset) canvas.clear();

                    const maxWidth = 900;
                    const maxHeight = 600;

                    // Use the natural dimensions from the Image element
                    const originalWidth = tempImg.naturalWidth || tempImg.width;
                    const originalHeight = tempImg.naturalHeight || tempImg.height;

                    originalW = originalWidth;
                    originalH = originalHeight;

                    SCALE = Math.min(
                        maxWidth / originalWidth,
                        maxHeight / originalHeight,
                        1
                    );

                    if (reset) {
                        currentZoom = 1;
                        document.getElementById('zoomLevel').innerText = '100%';
                    }

                    setCanvasDimensions();

                    img.set({
                        scaleX: SCALE,
                        scaleY: SCALE,
                        selectable: false,
                        evented: false
                    });

                    canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));

                    if (!reset) loadFrames();
                });
            };

            tempImg.src = src;
        }

        /* ===============================
            LOAD FRAMES
        ================================ */
        function loadFrames() {
            @foreach ($template->frames as $f)
                @if ($f->shape === 'custom')
                    addCustomShape(@json($f->path_data), {
                        left: {{ $f->x }} * SCALE,
                        top: {{ $f->y }} * SCALE,
                        width: {{ $f->width }} * SCALE,
                        height: {{ $f->height }} * SCALE,
                    }, true);
                @else
                    addShape('{{ $f->shape }}', {
                        left: {{ $f->x }} * SCALE,
                        top: {{ $f->y }} * SCALE,
                        width: {{ $f->width }} * SCALE,
                        height: {{ $f->height }} * SCALE,
                    }, true);
                @endif
            @endforeach
        }

        /* ===============================
            ADD SHAPE
        ================================ */
        function addShape(type, options = {}, isLoading = false) {
            let shapeObj;
            const config = SHAPES[type] || SHAPES['rect'];

            const defaultOptions = {
                left: 30,
                top: 30,
                ...COMMON_STYLES,
                data: {
                    shape: type
                }
            };

            const finalOptions = {
                ...defaultOptions,
                ...options
            };

            if (type === 'rect') {
                shapeObj = new fabric.Rect({
                    width: finalOptions.width || (300 * SCALE),
                    height: finalOptions.height || (200 * SCALE),
                    ...finalOptions
                });
            } else if (type === 'circle') {
                const w = finalOptions.width || (300 * SCALE);
                const h = finalOptions.height || (300 * SCALE);
                shapeObj = new fabric.Ellipse({
                    rx: w / 2,
                    ry: h / 2,
                    ...finalOptions
                });
            } else {
                const pathData = config.path;
                shapeObj = new fabric.Path(pathData, {
                    ...finalOptions
                });

                if (!isLoading) {
                    shapeObj.scaleToWidth(200 * SCALE);
                    shapeObj.scaleToHeight(200 * SCALE);
                } else {
                    // For paths, we trust finalOptions (which are passed to constructor for Path)
                    // But if scaleToWidth is better:
                    if (finalOptions.width) shapeObj.scaleToWidth(finalOptions.width);
                    if (finalOptions.height) shapeObj.scaleToHeight(finalOptions.height);
                }
            }

            canvas.add(shapeObj);
            if (!isLoading) canvas.setActiveObject(shapeObj);
        }

        /* ===============================
            CUSTOM SHAPE DRAWING
        ================================ */
        function toggleDrawingMode() {
            isDrawing = !isDrawing;

            const btn = document.getElementById('drawBtn');
            const finishBtn = document.getElementById('finishDrawBtn');
            const hint = document.getElementById('drawingHint');

            if (isDrawing) {
                // Start drawing
                btn.classList.add('btn-success');
                btn.classList.remove('btn-outline-success');
                finishBtn.classList.remove('d-none');
                hint.style.display = 'block';

                canvas.selection = false;
                canvas.forEachObject(o => o.selectable = false);
                canvas.defaultCursor = 'crosshair';

                // reset points
                drawingPoints = [];
                drawingLines = [];

                canvas.on('mouse:down', onMouseDown);
            } else {
                // Cancel/Stop drawing without saving if not finished
                // (Use finishDrawing() to save)
                resetDrawingMode();
            }
        }

        function resetDrawingMode() {
            isDrawing = false;
            const btn = document.getElementById('drawBtn');
            const finishBtn = document.getElementById('finishDrawBtn');
            const hint = document.getElementById('drawingHint');

            btn.classList.remove('btn-success');
            btn.classList.add('btn-outline-success');
            finishBtn.classList.add('d-none');
            hint.style.display = 'none';

            canvas.selection = true;
            canvas.forEachObject(o => o.selectable = true);
            canvas.defaultCursor = 'default';

            canvas.off('mouse:down', onMouseDown);

            // clear temp lines and circles
            drawingLines.forEach(l => canvas.remove(l));
            drawingPoints.forEach(p => canvas.remove(p)); // if we added circles for points

            drawingLines = [];
            drawingPoints = [];
        }

        function onMouseDown(options) {
            if (!isDrawing) return;

            const pointer = canvas.getPointer(options.e);
            const x = pointer.x;
            const y = pointer.y;

            drawingPoints.push({
                x,
                y
            });

            // visual feedback (circle at point)
            const circle = new fabric.Circle({
                radius: 4,
                fill: 'blue',
                left: x,
                top: y,
                originX: 'center',
                originY: 'center',
                selectable: false,
                evented: false
            });
            canvas.add(circle);
            drawingLines.push(circle); // track valid objects to remove later

            // Draw line from prev point
            if (drawingPoints.length > 1) {
                const start = drawingPoints[drawingPoints.length - 2];
                const end = drawingPoints[drawingPoints.length - 1];

                const line = new fabric.Line([start.x, start.y, end.x, end.y], {
                    stroke: 'blue',
                    strokeWidth: 2,
                    selectable: false,
                    evented: false
                });
                canvas.add(line);
                drawingLines.push(line);
            }
        }

        function finishDrawing() {
            if (drawingPoints.length < 3) {
                alert('Minimal 3 titik untuk membuat shape custom');
                return;
            }

            const shapeObj = new fabric.Polygon(drawingPoints, {
                ...COMMON_STYLES,
                data: {
                    shape: 'custom',
                    path_data: JSON.stringify(drawingPoints)
                }
            });

            resetDrawingMode();

            // Add to canvas
            canvas.add(shapeObj);
            canvas.setActiveObject(shapeObj);

            shapeObj.data.path_data = JSON.stringify(shapeObj.points);
        }

        function addCustomShape(pointsData, options = {}, isLoading = false) {
            let points = typeof pointsData === 'string' ? JSON.parse(pointsData) : pointsData;

            // Separate width/height/left/top from other options specifically for Polygon constructor
            // to avoid overriding calculated dimensions incorrectly
            const {
                width,
                height,
                ...polyOptions
            } = options;

            const shapeObj = new fabric.Polygon(points, {
                ...COMMON_STYLES,
                ...polyOptions, // Pass everything except width/height to constructor
                data: {
                    shape: 'custom',
                    path_data: pointsData
                }
            });

            // For custom shapes on load, we MUST rely on scaleToWidth/Height to resize the polygon
            // from its initial points-based size to the saved visual size.
            if (options.width) shapeObj.scaleToWidth(options.width);
            if (options.height) shapeObj.scaleToHeight(options.height);

            canvas.add(shapeObj);
            if (!isLoading) canvas.setActiveObject(shapeObj);
        }

        /* ===============================
            COPY / PASTE
        ================================ */
        function copyObject() {
            const activeObject = canvas.getActiveObject();
            if (activeObject) {
                activeObject.clone(function(cloned) {
                    _clipboard = cloned;
                }, ['data']);
            } else {
                alert('Pilih object terlebih dahulu untuk di copy');
            }
        }

        function pasteObject() {
            if (!_clipboard) {
                alert('Tidak ada object di clipboard');
                return;
            }

            _clipboard.clone(function(clonedObj) {
                canvas.discardActiveObject();
                clonedObj.set({
                    left: clonedObj.left + 20,
                    top: clonedObj.top + 20,
                    evented: true,
                    selectable: true
                });

                if (_clipboard.data) {
                    clonedObj.data = JSON.parse(JSON.stringify(_clipboard.data));
                }

                if (clonedObj.type === 'activeSelection') {
                    clonedObj.canvas = canvas;
                    clonedObj.forEachObject(function(obj) {
                        canvas.add(obj);
                    });
                    clonedObj.setCoords();
                } else {
                    canvas.add(clonedObj);
                }

                _clipboard.top += 20;
                _clipboard.left += 20;

                canvas.setActiveObject(clonedObj);
                canvas.requestRenderAll();
            }, ['data']);
        }

        /* ===============================
            REMOVE
        ================================ */
        function removeSelected() {
            const o = canvas.getActiveObject();
            if (o) canvas.remove(o);
        }

        /* ===============================
            ZOOM FUNCTIONS
        ================================ */
        function setZoom(zoom) {
            currentZoom = zoom;
            canvas.setZoom(currentZoom);
            setCanvasDimensions();
            document.getElementById('zoomLevel').innerText = Math.round(currentZoom * 100) + '%';
        }

        function setCanvasDimensions() {
            if (originalW === 0 || originalH === 0) return;

            const w = originalW * SCALE * currentZoom;
            const h = originalH * SCALE * currentZoom;

            canvas.setWidth(w);
            canvas.setHeight(h);
        }

        function zoomIn() {
            let newZoom = currentZoom + 0.1;
            if (newZoom > 3) newZoom = 3;
            setZoom(newZoom);
        }

        function zoomOut() {
            let newZoom = currentZoom - 0.1;
            if (newZoom < 0.1) newZoom = 0.1;
            setZoom(newZoom);
        }

        function resetZoom() {
            setZoom(1);
        }

        /* ===============================
            SAVE FRAMES
        ================================ */
        function prepareFrames() {
            const frames = canvas.getObjects().map(o => {
                const w = o.width * o.scaleX;
                const h = o.height * o.scaleY;

                let frameData = {
                    x: Math.round(o.left / SCALE),
                    y: Math.round(o.top / SCALE),
                    width: Math.round(w / SCALE),
                    height: Math.round(h / SCALE),
                    shape: o.data.shape || 'rect'
                };

                if (o.data.shape === 'custom') {
                    frameData.path_data = o.data.path_data;
                }

                return frameData;
            });

            if (frames.length === 0) {
                alert('Minimal 1 frame harus dibuat');
                return false;
            }

            document.getElementById('framesInput').value = JSON.stringify(frames);
            return true;
        }
        /* ===============================
            KEYBOARD SHORTCUTS
        ================================ */
        /* ===============================
            KEYBOARD SHORTCUTS
        ================================ */
        window.addEventListener('keydown', function(e) {
            // Check if user is typing in an input field
            const tagName = e.target.tagName.toUpperCase();
            if (tagName === 'INPUT' || tagName === 'TEXTAREA') return;

            // Copy: Ctrl+C (Universal check)
            if ((e.ctrlKey || e.metaKey) && (e.key === 'c' || e.key === 'C' || e.code === 'KeyC')) {
                e.preventDefault(); // This is crucial
                copyObject();
                return;
            }

            // Paste: Ctrl+V (Universal check)
            if ((e.ctrlKey || e.metaKey) && (e.key === 'v' || e.key === 'V' || e.code === 'KeyV')) {
                e.preventDefault();
                pasteObject();
                return;
            }

            // Delete: Delete or Backspace
            if (e.key === 'Delete' || e.code === 'Delete' || e.key === 'Backspace' || e.code === 'Backspace') {
                const activeObject = canvas.getActiveObject();
                if (activeObject) {
                    e.preventDefault();
                    removeSelected();
                }
            }
        });
    </script>
@endsection
