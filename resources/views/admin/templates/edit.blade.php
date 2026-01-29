@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">

                <div class="col">
                    <div class="page-pretitle">Photobooth</div>
                    <h2 class="page-title">✏️ Edit Template Photobooth</h2>
                </div>

                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ url('/templates') }}" class="btn btn-link">← Kembali</a>
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

            <form method="POST" action="{{ route('admin.templates.update', $template->id) }}" enctype="multipart/form-data"
                onsubmit="return prepareFrames()">
                @csrf
                @method('PUT')

                <input type="hidden" name="frames" id="framesInput">

                <div class="row g-3">

                    {{-- FORM INFO --}}
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body">

                                <div class="mb-3">
                                    <label class="form-label">Nama Template</label>
                                    <input class="form-control" type="text" name="name"
                                        value="{{ old('name', $template->name) }}" required>
                                </div>

                                <div class="mb-2">
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
                                {{--
                                <div class="mb-3">
                                    <label class="form-label">Ganti Template PNG (opsional)</label>
                                    <input class="form-control" type="file" name="template" id="templateInput"
                                        accept="image/png">
                                    <div class="form-hint">
                                        Kosongkan jika tidak ingin mengganti gambar
                                    </div>
                                </div> --}}

                            </div>
                        </div>
                    </div>

                    {{-- CANVAS EDITOR --}}
                    <div class="col-lg-8">
                        <div class="card">

                            {{-- TOOLBAR --}}
                            <div class="card-header">
                                <div class="btn-list">
                                    <button type="button" class="btn btn-outline-purple" onclick="addRect()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon m-0 p-0 me-2">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M4 6a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2l0 -12" />
                                        </svg>Reactangle
                                    </button>

                                    <button type="button" class="btn btn-outline-indigo" onclick="addCircle()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon m-0 p-0 me-2">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                        </svg>Circle
                                    </button>

                                    <button type="button" class="btn btn-outline-danger" onclick="removeSelected()">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon m-0 p-0 me-2">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M20 13v-4a2 2 0 0 0 -2 -2h-12a2 2 0 0 0 -2 2v5a2 2 0 0 0 2 2h7" />
                                            <path d="M22 22l-5 -5" />
                                            <path d="M17 22l5 -5" />
                                        </svg>Remove
                                    </button>
                                </div>
                            </div>

                            {{-- CANVAS --}}
                            <div class="card-body p-2">
                                <div class="canvas-wrapper">
                                    <canvas id="canvas"></canvas>
                                </div>
                            </div>

                        </div>
                    </div>

                    {{-- ACTION --}}
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ url('/templates') }}" class="btn btn-link">Batal</a>
                            <button class="btn btn-primary">💾 Update Template</button>
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
                                    FABRIC GLOBAL CONFIG
                                ================================ */
        fabric.Object.prototype.originX = 'left';
        fabric.Object.prototype.originY = 'top';
        fabric.Object.prototype.transparentCorners = false;
        fabric.Object.prototype.cornerColor = 'green';
        fabric.Object.prototype.borderColor = 'green';
        fabric.Object.prototype.cornerSize = 10;

        const canvas = new fabric.Canvas('canvas');
        let SCALE = 1;

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
            fabric.Image.fromURL(src, img => {

                if (reset) canvas.clear();

                const maxWidth = 900;
                const maxHeight = 600;

                SCALE = Math.min(
                    maxWidth / img.width,
                    maxHeight / img.height,
                    1
                );

                canvas.setWidth(img.width * SCALE);
                canvas.setHeight(img.height * SCALE);

                img.set({
                    scaleX: SCALE,
                    scaleY: SCALE,
                    selectable: false,
                    evented: false
                });

                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));

                if (!reset) loadFrames();
            });
        }

        /* ===============================
            LOAD FRAMES
        ================================ */
        function loadFrames() {
            @foreach ($template->frames as $f)
                @if ($f->shape === 'circle')
                    canvas.add(new fabric.Ellipse({
                        left: {{ $f->x }} * SCALE,
                        top: {{ $f->y }} * SCALE,
                        rx: ({{ $f->width }} / 2) * SCALE,
                        ry: ({{ $f->height }} / 2) * SCALE,
                        fill: 'rgba(0,255,0,0.35)',
                        data: {
                            shape: 'circle'
                        }
                    }));
                @else
                    canvas.add(new fabric.Rect({
                        left: {{ $f->x }} * SCALE,
                        top: {{ $f->y }} * SCALE,
                        width: {{ $f->width }} * SCALE,
                        height: {{ $f->height }} * SCALE,
                        fill: 'rgba(0,255,0,0.35)',
                        data: {
                            shape: 'rect'
                        }
                    }));
                @endif
            @endforeach
        }

        /* ===============================
            ADD FRAME
        ================================ */
        function addRect() {
            const o = new fabric.Rect({
                left: 30,
                top: 30,
                width: 300 * SCALE,
                height: 200 * SCALE,
                fill: 'rgba(0,255,0,0.35)',
                data: {
                    shape: 'rect'
                }
            });
            canvas.add(o).setActiveObject(o);
        }

        function addCircle() {
            const o = new fabric.Ellipse({
                left: 30,
                top: 30,
                rx: 150 * SCALE,
                ry: 150 * SCALE,
                fill: 'rgba(0,255,0,0.35)',
                data: {
                    shape: 'circle'
                }
            });
            canvas.add(o).setActiveObject(o);
        }

        /* ===============================
            REMOVE
        ================================ */
        function removeSelected() {
            const o = canvas.getActiveObject();
            if (o) canvas.remove(o);
        }

        /* ===============================
            SAVE FRAMES
        ================================ */
        function prepareFrames() {
            const frames = canvas.getObjects().map(o => ({
                x: Math.round(o.left / SCALE),
                y: Math.round(o.top / SCALE),
                width: Math.round(o.getScaledWidth() / SCALE),
                height: Math.round(o.getScaledHeight() / SCALE),
                shape: o.data.shape
            }));

            if (frames.length === 0) {
                alert('Minimal 1 frame harus dibuat');
                return false;
            }

            document.getElementById('framesInput').value = JSON.stringify(frames);
            return true;
        }
    </script>
@endsection
