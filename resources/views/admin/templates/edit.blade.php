@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>✏️ Edit Template — {{ $template->name }}</h2>

        <form method="POST" action="{{ route('admin.templates.update', $template) }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="frames" id="framesInput">

            <div class="mb-2">
                <button type="button" onclick="addRect()">➕ Kotak</button>
                <button type="button" onclick="addCircle()">➕ Bulat</button>
                <button type="button" onclick="removeSelected()">🗑 Hapus</button>

                W <input id="fw" type="number" style="width:70px">
                H <input id="fh" type="number" style="width:70px">
                <button type="button" onclick="applySize()">Apply</button>
            </div>

            <canvas id="canvas" style="border:1px solid #aaa"></canvas>

            <br>
            <button class="btn btn-primary">💾 Simpan Perubahan</button>
            <a href="{{ route('admin.templates.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

    <script>
        const canvas = new fabric.Canvas('canvas');
        let SCALE = 1;

        // ===== LOAD TEMPLATE IMAGE =====
        fabric.Image.fromURL(
            "{{ asset('storage/' . $template->template_image) }}",
            img => {
                const maxWidth = 900;
                SCALE = maxWidth / img.width;

                canvas.setWidth(img.width * SCALE);
                canvas.setHeight(img.height * SCALE);

                img.scale(SCALE);
                canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas));
            }
        );

        // ===== LOAD EXISTING FRAMES =====
        const frames = @json($frames);

        frames.forEach(f => {
            let obj;

            if (f.shape === 'circle') {
                obj = new fabric.Ellipse({
                    left: f.x * SCALE,
                    top: f.y * SCALE,
                    rx: (f.width / 2) * SCALE,
                    ry: (f.height / 2) * SCALE,
                    fill: 'rgba(0,255,0,0.35)',
                    data: {
                        shape: 'circle'
                    }
                });
            } else {
                obj = new fabric.Rect({
                    left: f.x * SCALE,
                    top: f.y * SCALE,
                    width: f.width * SCALE,
                    height: f.height * SCALE,
                    fill: 'rgba(0,255,0,0.35)',
                    data: {
                        shape: 'rect'
                    }
                });
            }

            canvas.add(obj);
        });

        // ===== ACTIONS =====
        function addRect() {
            const r = new fabric.Rect({
                left: 50,
                top: 50,
                width: 300 * SCALE,
                height: 200 * SCALE,
                fill: 'rgba(0,255,0,0.35)',
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
                fill: 'rgba(0,255,0,0.35)',
                data: {
                    shape: 'circle'
                }
            });
            canvas.add(c).setActiveObject(c);
        }

        function removeSelected() {
            const o = canvas.getActiveObject();
            if (o) canvas.remove(o);
        }

        function applySize() {
            const o = canvas.getActiveObject();
            if (!o) return;

            const w = fw.value * SCALE;
            const h = fh.value * SCALE;

            if (o.type === 'rect') {
                o.set({
                    width: w,
                    height: h
                });
            } else {
                o.set({
                    rx: w / 2,
                    ry: h / 2
                });
            }

            o.setCoords();
            canvas.renderAll();
        }

        // ===== SIZE SYNC =====
        canvas.on('selection:created', sync);
        canvas.on('selection:updated', sync);

        function sync() {
            const o = canvas.getActiveObject();
            if (!o) return;

            let w = o.type === 'rect' ?
                o.width * o.scaleX :
                o.rx * 2 * o.scaleX;

            let h = o.type === 'rect' ?
                o.height * o.scaleY :
                o.ry * 2 * o.scaleY;

            fw.value = Math.round(w / SCALE);
            fh.value = Math.round(h / SCALE);
        }

        // ===== SUBMIT =====
        document.querySelector('form').addEventListener('submit', () => {
            const data = canvas.getObjects().map(o => {
                let w = o.type === 'rect' ?
                    o.width * o.scaleX :
                    o.rx * 2 * o.scaleX;

                let h = o.type === 'rect' ?
                    o.height * o.scaleY :
                    o.ry * 2 * o.scaleY;

                return {
                    x: o.left / SCALE,
                    y: o.top / SCALE,
                    width: w / SCALE,
                    height: h / SCALE,
                    shape: o.data.shape
                };
            });

            document.getElementById('framesInput').value = JSON.stringify(data);
        });
    </script>
@endsection
