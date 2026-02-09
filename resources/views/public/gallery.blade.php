<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Hasil Foto Anda</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 16px;
        }

        h1 {
            text-align: center;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
        }

        .card {
            background: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .1);
        }

        img {
            width: 100%;
            border-radius: 6px;
        }

        a.btn {
            display: block;
            margin-top: 6px;
            text-align: center;
            background: #0d6efd;
            color: #fff;
            padding: 6px;
            border-radius: 4px;
            text-decoration: none;
        }

        .final {
            margin-top: 24px;
        }
    </style>
</head>

<body>

    <h1>📸 Hasil Foto Anda</h1>

    <h3>Foto Per Frame</h3>
    <div class="grid">
        @foreach ($session->photos as $photo)
            <div class="card">
                <img src="{{ asset('storage/' . $photo->photo_path) }}">
                <a class="btn" href="{{ route('gallery.frame.download', $photo->id) }}">
                    Download
                </a>
            </div>
        @endforeach
    </div>

    @if ($session->finalImage)
        <div class="final">
            <h3>✨ Foto Final & Live Photo</h3>
            <div class="grid">
                <div class="card">
                    <h4>Foto Final</h4>
                    <img src="{{ asset('storage/' . $session->finalImage->image_path) }}">
                    <a class="btn" href="{{ route('gallery.final.download', $session->id) }}">
                        Download Foto
                    </a>
                </div>

                @if ($session->finalImage->video_path)
                    <div class="card">
                        <h4>Live Photo</h4>
                        <video controls style="width: 100%; border-radius: 6px;">
                            <source src="{{ asset('storage/' . $session->finalImage->video_path) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <a class="btn" href="{{ route('gallery.live.download', $session->id) }}">
                            Download Live Photo
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif

</body>

</html>
