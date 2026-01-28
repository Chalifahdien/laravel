@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Template</h2>

        <form method="POST" action="{{ route('admin.templates.update', $template->id) }}" onsubmit="return prepareFrames()">
            @csrf
            @method('PUT')

            <input type="hidden" name="frames" id="framesInput">

            <div class="mb-2">
                <button type="button" onclick="addRect()">➕ Kotak</button>
                <button type="button" onclick="addCircle()">➕ Bulat</button>
                <button type="button" onclick="removeSelected()">🗑 Hapus</button>
            </div>

            <canvas id="canvas" style="border:1px solid #aaa"></canvas>

            <br><br>
            <button class="btn btn-primary">Update Template</button>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

    <script>
        /* ===============================
       GLOBAL
    ================================ */
        fabric.Object.prototype.originX = 'left';
        fabric.Object.prototype.originY = 'top';

        const canvas = new fabric.Canvas('canvas');
        let SCALE = 1;

        /* ===============================
           LOAD TEMPLATE IMAGE
        ================================ */
        fabric.Image.fromURL("{{ asset('storage/' . $template->template_image) }}", img => {

            const maxWidth = 900;
            SCALE = maxWidth / img.width;

            canvas.setWidth(img.width * SCALE);
            canvas.setHeight(img.height * SCALE);

            img.set({
                scaleX: SCALE,
                scaleY: SCALE,
                selectable: false,
                evented: false
            });

            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));

            loadFrames();
        });

        /* ===============================
           LOAD FRAMES (🔥 FIX UTAMA)
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
            canvas.add(new fabric.Rect({
                left: 50,
                top: 50,
                width: 300 * SCALE,
                height: 200 * SCALE,
                fill: 'rgba(0,255,0,0.35)',
                data: {
                    shape: 'rect'
                }
            }));
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
            }));
        }

        /* ===============================
           REMOVE
        ================================ */
        function removeSelected() {
            const o = canvas.getActiveObject();
            if (o) canvas.remove(o);
        }

        /* ===============================
           SAVE FRAMES (🔥 FIX FINAL)
        ================================ */
        function prepareFrames() {
            const frames = canvas.getObjects().map(o => ({
                x: Math.round(o.left / SCALE),
                y: Math.round(o.top / SCALE),
                width: Math.round(o.getScaledWidth() / SCALE),
                height: Math.round(o.getScaledHeight() / SCALE),
                shape: o.data.shape
            }));

            document.getElementById('framesInput').value = JSON.stringify(frames);
            return true;
        }
    </script>
@endsection
