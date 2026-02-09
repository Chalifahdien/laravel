<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Galeri Foto - Mooein Snap</title>
    <!-- CSS files -->
    <link href="{{ asset('dist/css/admin.css') }}" rel="stylesheet" />
    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
            height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background-color: #1a1a1a;
        }

        .navbar {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* THUMBNAIL STRIP */
        .thumb-strip {
            background: #242424;
            padding: 10px 0;
            white-space: nowrap;
            overflow-x: auto;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            -webkit-overflow-scrolling: touch;
        }

        .thumb-strip::-webkit-scrollbar {
            height: 4px;
        }

        .thumb-strip::-webkit-scrollbar-thumb {
            background: #555;
            border-radius: 2px;
        }

        .thumb-item {
            display: inline-block;
            width: 80px;
            height: 80px;
            margin-right: 8px;
            border-radius: 6px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            border: 2px solid transparent;
            opacity: 0.6;
            transition: all 0.2s;
        }

        .thumb-item:first-child {
            margin-left: 1rem;
        }

        .thumb-item:last-child {
            margin-right: 1rem;
        }

        .thumb-item.active {
            border-color: #206bc4;
            opacity: 1;
            transform: scale(1.05);
            z-index: 1;
        }

        .thumb-item img,
        .thumb-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .thumb-badge {
            position: absolute;
            top: 2px;
            right: 2px;
            background: rgba(214, 57, 57, 0.9);
            color: white;
            font-size: 9px;
            padding: 1px 4px;
            border-radius: 3px;
            font-weight: bold;
            text-transform: uppercase;
        }

        /* MAIN VIEWER */
        .main-viewer {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            padding: 20px;
            position: relative;
        }

        #main-image,
        #main-video {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
            object-fit: contain;
        }

        .hidden {
            display: none !important;
        }

        .loader {
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>

<body class="theme-dark">
    <!-- Navbar -->
    <header class="navbar navbar-expand-md navbar-dark d-print-none">
        <div class="container-xl">
            <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href="#">
                    <!-- Logo Text -->
                    Mooein Snap
                </a>
            </h1>
            <div class="navbar-nav flex-row order-md-last">
                <div class="nav-item">
                    <a href="#" id="download-link" class="btn btn-primary" download>
                        <!-- Download SVG Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                            <path d="M7 11l5 5l5 -5" />
                            <path d="M12 4l0 12" />
                        </svg>
                        Unduh Foto
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Thumbnail Strip (Below Navbar) -->
    <div class="thumb-strip">
        <div class="d-flex align-items-center h-100">
            <!-- 1. FINAL PHOTO -->
            @if ($session->finalImage)
                <div class="thumb-item active" data-type="image"
                    data-src="{{ asset('storage/' . $session->finalImage->image_path) }}"
                    data-download="{{ route('gallery.final.download', $session->id) }}">
                    <div class="thumb-badge">Final</div>
                    <img src="{{ asset('storage/' . $session->finalImage->image_path) }}">
                </div>

                <!-- 2. LIVE PHOTO -->
                @if ($session->finalImage->video_path)
                    @php
                        $videoPath = $session->finalImage->video_path;
                        $ext = strtolower(pathinfo($videoPath, PATHINFO_EXTENSION));
                        $isGif = $ext === 'gif';
                    @endphp
                    <div class="thumb-item" data-type="{{ $isGif ? 'image' : 'video' }}"
                        data-src="{{ asset('storage/' . $videoPath) }}"
                        data-download="{{ route('gallery.live.download', $session->id) }}">
                        <div class="thumb-badge">Live</div>
                        @if ($isGif)
                            <img src="{{ asset('storage/' . $videoPath) }}">
                        @else
                            <video muted>
                                <source src="{{ asset('storage/' . $videoPath) }}" type="video/{{ $ext }}">
                            </video>
                        @endif
                    </div>
                @endif
            @endif

            <!-- 3. FRAME PHOTOS -->
            @foreach ($session->photos as $photo)
                <div class="thumb-item" data-type="image" data-src="{{ asset('storage/' . $photo->photo_path) }}"
                    data-download="{{ route('gallery.frame.download', $photo->id) }}">
                    <img src="{{ asset('storage/' . $photo->photo_path) }}">
                </div>
            @endforeach
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-viewer">
        <div class="loader" id="loading-text">Memuat media...</div>

        <img id="main-image" class="hidden" src="" alt="View">
        <video id="main-video" class="hidden" controls autoplay loop playsinline>
            <source src="" type="video/mp4">
        </video>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const thumbs = document.querySelectorAll('.thumb-item');
            const mainImage = document.getElementById('main-image');
            const mainVideo = document.getElementById('main-video');
            const downloadLink = document.getElementById('download-link');
            const loadingText = document.getElementById('loading-text');

            function updateView(thumb) {
                // Active class
                thumbs.forEach(t => t.classList.remove('active'));
                thumb.classList.add('active');

                // Get Data
                const type = thumb.dataset.type;
                const src = thumb.dataset.src;
                const downloadUrl = thumb.dataset.download;

                // Update Button
                downloadLink.href = downloadUrl;

                // Hide loader
                loadingText.style.display = 'none';

                // Display Logic
                if (type === 'image') {
                    // Hide Video, Show Image
                    mainVideo.classList.add('hidden');
                    mainVideo.pause();

                    mainImage.src = src;
                    mainImage.classList.remove('hidden');
                } else {
                    // Hide Image, Show Video
                    mainImage.classList.add('hidden');

                    mainVideo.src = src;
                    mainVideo.classList.remove('hidden');
                    // Attempt autoplay
                    const playPromise = mainVideo.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(error => {
                            console.log('Autoplay prevented');
                        });
                    }
                }
            }

            // Click Listeners
            thumbs.forEach(thumb => {
                thumb.addEventListener('click', () => updateView(thumb));
            });

            // Initialize first item
            if (thumbs.length > 0) {
                updateView(thumbs[0]);
            } else {
                loadingText.textContent = "Tidak ada foto tersedia.";
            }
        });
    </script>
</body>

</html>
