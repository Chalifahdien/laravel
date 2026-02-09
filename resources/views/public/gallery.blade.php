<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Hasil Foto Anda | Mooein Snap</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        :root {
            --primary-color: #0d6efd;
            --primary-hover: #0b5ed7;
            --bg-color: #f8f9fa;
            --card-bg: #ffffff;
            --text-color: #212529;
            --text-muted: #6c757d;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --radius: 12px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background: var(--bg-color);
            color: var(--text-color);
            margin: 0;
            padding: 20px;
            line-height: 1.5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
        }

        header {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px 0;
        }

        h1 {
            font-size: 2rem;
            margin: 0 0 10px 0;
            font-weight: 700;
        }

        p.subtitle {
            color: var(--text-muted);
            margin: 0;
        }

        h3 {
            font-size: 1.25rem;
            margin-bottom: 16px;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
            color: #495057;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 20px;
            margin-bottom: 40px;
        }

        .card {
            background: var(--card-bg);
            padding: 12px;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            transition: transform 0.2s;
            display: flex;
            flex-direction: column;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .media-wrapper {
            position: relative;
            border-radius: 8px;
            overflow: hidden;
            background: #eee;
            margin-bottom: 12px;
        }

        /*
           Using aspect-ratio to prevent layout shift.
           Adjust aspect-ratio as needed (e.g., 2/3 for photo strips).
           Using 'auto' allows the image to define height within the grid.
        */
        .media-wrapper img,
        .media-wrapper video {
            display: block;
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        h4 {
            margin: 0 0 12px 0;
            font-size: 1rem;
            text-align: center;
        }

        a.btn {
            display: inline-block;
            width: 100%;
            text-align: center;
            background: var(--primary-color);
            color: #fff;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background-color 0.2s;
            margin-top: auto;
            /* Pushes button to bottom of flex card */
            border: none;
            cursor: pointer;
        }

        a.btn:hover {
            background: var(--primary-hover);
        }

        a.btn svg {
            vertical-align: text-bottom;
            margin-right: 6px;
        }

        .badge {
            display: inline-block;
            padding: 4px 8px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #fff;
            background-color: #dc3545;
            /* Red for LIVE */
            border-radius: 4px;
            position: absolute;
            top: 8px;
            right: 8px;
            z-index: 10;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        footer {
            text-align: center;
            margin-top: 60px;
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        @media (max-width: 600px) {
            .grid {
                grid-template-columns: repeat(2, 1fr);
                /* Force 2 columns on mobile */
                gap: 12px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <header>
            <h1>📸 Hasil Foto Anda</h1>
            <p class="subtitle">Terima kasih telah berfoto bersama kami!</p>
        </header>

        <!-- SECTION: FRAME PHOTOS -->
        <section>
            <h3>Foto Frame</h3>
            <div class="grid">
                @foreach ($session->photos as $index => $photo)
                    <div class="card">
                        <div class="media-wrapper">
                            <img src="{{ asset('storage/' . $photo->photo_path) }}" alt="Frame {{ $index + 1 }}"
                                loading="lazy">
                        </div>
                        <a class="btn" href="{{ route('gallery.frame.download', $photo->id) }}" download>
                            Unduh
                        </a>
                    </div>
                @endforeach
            </div>
        </section>

        <!-- SECTION: FINAL & LIVE -->
        @if ($session->finalImage)
            <section class="final">
                <h3>✨ Foto Final & Live Photo</h3>
                <div class="grid">
                    <!-- FINAL PHOTO -->
                    <div class="card">
                        <h4>Foto Final</h4>
                        <div class="media-wrapper">
                            <img src="{{ asset('storage/' . $session->finalImage->image_path) }}" alt="Final Photo"
                                loading="lazy">
                        </div>
                        <a class="btn" href="{{ route('gallery.final.download', $session->id) }}" download>
                            Unduh Foto
                        </a>
                    </div>

                    <!-- LIVE PHOTO -->
                    @if ($session->finalImage->video_path)
                        @php
                            $videoPath = $session->finalImage->video_path;
                            $extension = strtolower(pathinfo($videoPath, PATHINFO_EXTENSION));
                        @endphp

                        <div class="card">
                            <h4>Live Photo</h4>
                            <div class="media-wrapper">
                                <span class="badge">LIVE</span>

                                @if ($extension === 'gif')
                                    <img src="{{ asset('storage/' . $videoPath) }}" alt="Live Photo GIF">
                                @else
                                    <video controls autoplay loop muted playsinline>
                                        <source src="{{ asset('storage/' . $videoPath) }}"
                                            type="video/{{ $extension }}">
                                        Browser Anda tidak mendukung tag video.
                                    </video>
                                @endif
                            </div>
                            <a class="btn" href="{{ route('gallery.live.download', $session->id) }}" download>
                                Unduh Live Photo
                            </a>
                        </div>
                    @endif
                </div>
            </section>
        @endif

        <footer>
            &copy; {{ date('Y') }} Mooein Snap. All rights reserved.
        </footer>
    </div>

</body>

</html>
