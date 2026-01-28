@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Upload Template Photobooth</h2>

        @if ($errors->any())
            <div class="alert alert-danger">{{ implode(', ', $errors->all()) }}</div>
        @endif

        <form method="POST" action="{{ route('admin.templates.upload') }}" enctype="multipart/form-data"
            onsubmit="return prepareFrames()">
            @csrf

            <input type="hidden" name="frames" id="framesInput">

            <div class="mb-2">
                <input class="form-control" type="text" name="name" placeholder="Nama Template" required>
            </div>

            <div class="mb-2">
                <select class="form-select" name="paper_size_id" required>
                    @foreach ($paperSizes as $p)
                        <option value="{{ $p->id }}">
                            {{ $p->name }} ({{ $p->width_mm }}×{{ $p->height_mm }}mm)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-2">
                <input class="form-control" type="file" name="template" id="templateInput" accept="image/png" required>
            </div>

            <hr>

            <div class="mb-2">
                <button type="button" onclick="addRect()">➕ Kotak</button>
                <button type="button" onclick="addCircle()">➕ Bulat</button>
                <button type="button" onclick="removeSelected()">🗑 Hapus</button>
            </div>

            <canvas id="canvas" style="border:1px solid #aaa"></canvas>

            <br><br>
            <button class="btn btn-primary">Simpan Template</button>
        </form>
    </div>

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

        /* ===============================
           CANVAS
        ================================ */
        const canvas = new fabric.Canvas('canvas');
        let SCALE = 1;

        /* ===============================
           LOAD TEMPLATE IMAGE
        ================================ */
        document.getElementById('templateInput').addEventListener('change', e => {
            const reader = new FileReader();
            reader.onload = ev => {
                fabric.Image.fromURL(ev.target.result, img => {

                    canvas.clear();

                    const maxWidth = 900;
                    SCALE = maxWidth / img.width;

                    canvas.setWidth(img.width * SCALE);
                    canvas.setHeight(img.height * SCALE);

                    img.set({
                        scaleX: SCALE,
                        scaleY: SCALE,
                        originX: 'left',
                        originY: 'top',
                        selectable: false,
                        evented: false
                    });

                    canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
                });
            };
            reader.readAsDataURL(e.target.files[0]);
        });

        /* ===============================
           ADD FRAMES
        ================================ */
        function addRect() {
            canvas.add(new fabric.Rect({
                left: 50,
                top: 50,
                width: 300 * SCALE,
                height: 200 * SCALE,
                fill: 'rgba(0,255,0,0.35)',
                data: {
                    shape: 'rect'
                }
            })).setActiveObject(canvas.getObjects().slice(-1)[0]);
        }

        function addCircle() {
            canvas.add(new fabric.Ellipse({
                left: 100,
                top: 100,
                rx: 150 * SCALE,
                ry: 150 * SCALE,
                fill: 'rgba(0,255,0,0.35)',
                data: {
                    shape: 'circle'
                }
            })).setActiveObject(canvas.getObjects().slice(-1)[0]);
        }

        /* ===============================
           REMOVE
        ================================ */
        function removeSelected() {
            const o = canvas.getActiveObject();
            if (o) canvas.remove(o);
        }

        /* ===============================
           PREPARE FRAMES (🔥 FIX UTAMA)
        ================================ */
        function prepareFrames() {
            const objects = canvas.getObjects();

            if (objects.length === 0) {
                alert('Minimal 1 frame harus dibuat');
                return false;
            }

            const frames = objects.map(o => {
                return {
                    x: Math.round(o.left / SCALE),
                    y: Math.round(o.top / SCALE),
                    width: Math.round(o.getScaledWidth() / SCALE),
                    height: Math.round(o.getScaledHeight() / SCALE),
                    shape: o.data.shape
                };
            });

            document.getElementById('framesInput').value = JSON.stringify(frames);
            return true;
        }
    </script>
@endsection
