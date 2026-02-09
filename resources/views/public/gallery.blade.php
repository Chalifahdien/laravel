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
            --bg-color: #121212;
            --text-color: #f8f9fa;
            --accent-color: #2c2c2c;
            --scroll-thumb: #555;
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
            padding: 0;
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* HEADER */
        header {
            padding: 12px 20px;
            background: rgba(0, 0, 0, 0.8);
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 10;
        }

        h1 {
            font-size: 1.1rem;
            margin: 0;
            font-weight: 600;
        }

        .download-btn {
            background-color: var(--primary-color);
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: background 0.2s;
        }

        .download-btn:hover {
            background-color: var(--primary-hover);
        }

        /* MAIN VIEWER */
        #main-viewer {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            position: relative;
            overflow: hidden;
        }

        #main-image,
        #main-video {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            transition: opacity 0.3s;
        }

        .hidden {
            display: none !important;
        }

        /* THUMBNAIL LIST */
        #thumb-list {
            height: 100px;
            background: var(--accent-color);
            display: flex;
            gap: 10px;
            padding: 10px;
            overflow-x: auto;
            scroll-behavior: smooth;
        }

        .thumb {
            height: 100%;
            min-width: 80px;
            /* Adjust based on aspect ratio */
            border-radius: 6px;
            cursor: pointer;
            overflow: hidden;
            border: 2px solid transparent;
            opacity: 0.6;
            transition: all 0.2s;
            position: relative;
        }

        .thumb img,
        .thumb video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumb.active {
            border-color: var(--primary-color);
            opacity: 1;
            transform: scale(1.05);
        }

        .thumb-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: rgba(220, 53, 69, 0.9);
            color: white;
            font-size: 0.6rem;
            padding: 2px 4px;
            border-radius: 3px;
            font-weight: bold;
        }

        /* SCROLLBAR */
        #thumb-list::-webkit-scrollbar {
            height: 6px;
        }

        #thumb-list::-webkit-scrollbar-thumb {
            background: var(--scroll-thumb);
            border-radius: 3px;
        }

        .placeholder-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #777;
        }
    </style>
</head>

<body>

    <header>
        <h1>📸 Hasil Foto</h1>
        <a id="download-link" href="#" class="download-btn" download>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            Unduh
        </a>
    </header>

    <!-- THUMBNAIL LIST (Moved to Top) -->
    <div id="thumb-list">
        <!-- 1. FINAL PHOTO -->
        @if ($session->finalImage)
            <div class="thumb active" data-type="image"
                data-src="{{ asset('storage/' . $session->finalImage->image_path) }}"
                data-download="{{ route('gallery.final.download', $session->id) }}">
                <div class="thumb-badge">FINAL</div>
                <img src="{{ asset('storage/' . $session->finalImage->image_path) }}" loading="lazy">
            </div>

            <!-- 2. LIVE PHOTO -->
            @if ($session->finalImage->video_path)
                @php
                    $videoPath = $session->finalImage->video_path;
                    $ext = strtolower(pathinfo($videoPath, PATHINFO_EXTENSION));
                    $isGif = $ext === 'gif';
                @endphp
                <div class="thumb" data-type="{{ $isGif ? 'image' : 'video' }}"
                    data-src="{{ asset('storage/' . $videoPath) }}"
                    data-download="{{ route('gallery.live.download', $session->id) }}">
                    <div class="thumb-badge">LIVE</div>
                    @if ($isGif)
                        <img src="{{ asset('storage/' . $videoPath) }}">
                    @else
                        <!-- Use video tag but simplified for thumb -->
                        <video muted>
                            <source src="{{ asset('storage/' . $videoPath) }}" type="video/{{ $ext }}">
                        </video>
                    @endif
                </div>
            @endif
        @endif

        <!-- 3. FRAME PHOTOS -->
        @foreach ($session->photos as $index => $photo)
            <div class="thumb" data-type="image" data-src="{{ asset('storage/' . $photo->photo_path) }}"
                data-download="{{ route('gallery.frame.download', $photo->id) }}">
                <img src="{{ asset('storage/' . $photo->photo_path) }}" loading="lazy">
            </div>
        @endforeach
    </div>

    <!-- MAIN VIEWER -->
    <div id="main-viewer">
        <div class="placeholder-text" id="loading-text">Memuat...</div>
        <!-- Media will be injected here by JS -->
        <img id="main-image" class="hidden" src="" alt="Main View">
        <video id="main-video" class="hidden" controls autoplay loop playsinline>
            <source src="" type="video/mp4">
        </video>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const thumbs = document.querySelectorAll('.thumb');
            const mainImage = document.getElementById('main-image');
            const mainVideo = document.getElementById('main-video');
            const downloadLink = document.getElementById('download-link');
            const loadingText = document.getElementById('loading-text');

            function updateView(thumb) {
                // Remove active class from all
                thumbs.forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');

                const type = thumb.dataset.type;
                const src = thumb.dataset.src;
                const downloadUrl = thumb.dataset.download;

                // Update Download Link
                downloadLink.href = downloadUrl;

                // Hide loading
                loadingText.style.display = 'none';

                if (type === 'image') {
                    mainVideo.classList.add('hidden');
                    mainVideo.pause();

                    mainImage.src = src;
                    mainImage.classList.remove('hidden');
                } else if (type === 'video') {
                    mainImage.classList.add('hidden');

                    mainVideo.src = src;
                    mainVideo.classList.remove('hidden');
                    mainVideo.play().catch(e => console.log('Autoplay prevented:', e));
                }
            }

            // Init click listeners
            thumbs.forEach(thumb => {
                thumb.addEventListener('click', () => updateView(thumb));
            });

            // Initialize with first thumb
            if (thumbs.length > 0) {
                updateView(thumbs[0]);
            } else {
                loadingText.innerText = "Tidak ada foto.";
            }
        });
    </script>

</body>

</html>
