@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Upload Template Photobooth</h2>

        {{-- @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif --}}

        @if ($errors->any())
            <div class="alert alert-danger">
                {{ implode(', ', $errors->all()) }}
            </div>
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

            <canvas id="canvas" style="border:1px solid #aaa;"></canvas>

            <br><br>
            <button class="btn btn-primary">Simpan Template</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

    <script>
        const canvas = new fabric.Canvas('canvas');
        let SCALE = 1;

        document.getElementById('templateInput').addEventListener('change', e => {
            const reader = new FileReader();
            reader.onload = ev => {
                fabric.Image.fromURL(ev.target.result, img => {
                    const maxWidth = 900;
                    SCALE = maxWidth / img.width;

                    canvas.setWidth(img.width * SCALE);
                    canvas.setHeight(img.height * SCALE);

                    img.scale(SCALE);
                    canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
                });
            };
            reader.readAsDataURL(e.target.files[0]);
        });

        function addRect() {
            const r = new fabric.Rect({
                left: 50,
                top: 50,
                width: 300 * SCALE,
                height: 200 * SCALE,
                fill: 'rgba(0, 255, 0, 0.35)', // hijau transparan
                selectable: true,
                hasControls: true,
                data: {
                    shape: 'rect'
                }
            });
            canvas.add(r).setActiveObject(r);
        }


        function addCircle() {
            const c = new fabric.Ellipse({
                left: 100,
                top: 100,
                rx: 150 * SCALE,
                ry: 150 * SCALE,
                fill: 'rgba(0, 255, 0, 0.35)', // hijau transparan
                selectable: true,
                hasControls: true,
                data: {
                    shape: 'circle'
                }
            });
            canvas.add(c).setActiveObject(c);
        }

        fabric.Object.prototype.set({
            transparentCorners: false,
            cornerColor: 'green',
            cornerStrokeColor: '#006400',
            borderColor: 'green',
            cornerSize: 10
        });



        function removeSelected() {
            const o = canvas.getActiveObject();
            if (o) canvas.remove(o);
        }

        function prepareFrames() {
            const objects = canvas.getObjects();

            if (objects.length === 0) {
                alert('Minimal 1 frame harus dibuat');
                return false;
            }

            const frames = objects.map(o => {
                let w = o.type === 'rect' ?
                    o.width * o.scaleX :
                    o.rx * 2 * o.scaleX;

                let h = o.type === 'rect' ?
                    o.height * o.scaleY :
                    o.ry * 2 * o.scaleY;

                return {
                    x: Math.round(o.left / SCALE),
                    y: Math.round(o.top / SCALE),
                    width: Math.round(w / SCALE),
                    height: Math.round(h / SCALE),
                    shape: o.data.shape
                };
            });

            document.getElementById('framesInput').value = JSON.stringify(frames);
            return true;
        }
    </script>
@endsection
