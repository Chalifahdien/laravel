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
        }

        .gallery-img {
            cursor: pointer;
        }

        /* Lightbox Modal */
        .lightbox-modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.95);
            overflow: auto;
        }

        .lightbox-content {
            position: relative;
            margin: auto;
            padding: 20px;
            max-width: 90%;
            max-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
        }

        .lightbox-img,
        .lightbox-video {
            max-width: 100%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 30px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10000;
        }

        .lightbox-close:hover {
            color: #ccc;
        }

        .hidden {
            display: none !important;
        }
    </style>
</head>

<body class="theme-dark">
    <div class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md navbar-dark d-print-none">
            <div class="container-xl">
                <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                    <a href="#">
                        📸 Galeri Foto
                    </a>
                </h1>
            </div>
        </header>

        <!-- Page Body -->
        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl">

                    <!-- Gallery Grid -->
                    <div class="row row-cards">

                        <!-- FINAL PHOTO -->
                        @if ($session->finalImage)
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="card">
                                    <img src="{{ asset('storage/' . $session->finalImage->image_path) }}"
                                        class="card-img-top gallery-img" style="aspect-ratio: 3/4; object-fit: cover;"
                                        data-type="image"
                                        data-src="{{ asset('storage/' . $session->finalImage->image_path) }}"
                                        alt="Final Photo">

                                    <div class="card-body">
                                        <div class="fw-semibold">
                                            <span class="badge bg-blue me-1">FINAL</span>
                                            Foto Final
                                        </div>
                                        <div class="text-secondary">
                                            Session #{{ $session->id }}
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <a href="{{ route('gallery.final.download', $session->id) }}"
                                            class="btn btn-primary w-100" download>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                <path d="M7 11l5 5l5 -5" />
                                                <path d="M12 4l0 12" />
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- LIVE PHOTO -->
                            @if ($session->finalImage->video_path)
                                @php
                                    $videoPath = $session->finalImage->video_path;
                                    $ext = strtolower(pathinfo($videoPath, PATHINFO_EXTENSION));
                                    $isGif = $ext === 'gif';
                                @endphp
                                <div class="col-sm-6 col-md-4 col-lg-3">
                                    <div class="card">
                                        @if ($isGif)
                                            <img src="{{ asset('storage/' . $videoPath) }}"
                                                class="card-img-top gallery-img"
                                                style="aspect-ratio: 3/4; object-fit: cover;" data-type="image"
                                                data-src="{{ asset('storage/' . $videoPath) }}" alt="Live Photo">
                                        @else
                                            <video class="card-img-top gallery-img"
                                                style="aspect-ratio: 3/4; object-fit: cover;" muted autoplay loop
                                                playsinline data-type="video"
                                                data-src="{{ asset('storage/' . $videoPath) }}">
                                                <source src="{{ asset('storage/' . $videoPath) }}"
                                                    type="video/{{ $ext }}">
                                            </video>
                                        @endif

                                        <div class="card-body">
                                            <div class="fw-semibold">
                                                <span class="badge bg-red me-1">LIVE</span>
                                                Live Photo
                                            </div>
                                            <div class="text-secondary">
                                                Session #{{ $session->id }}
                                            </div>
                                        </div>

                                        <div class="card-footer">
                                            <a href="{{ route('gallery.live.download', $session->id) }}"
                                                class="btn btn-primary w-100" download>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                    <path d="M7 11l5 5l5 -5" />
                                                    <path d="M12 4l0 12" />
                                                </svg>
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif

                        <!-- FRAME PHOTOS -->
                        @foreach ($session->photos as $index => $photo)
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="card">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                        class="card-img-top gallery-img" style="aspect-ratio: 3/4; object-fit: cover;"
                                        data-type="image" data-src="{{ asset('storage/' . $photo->photo_path) }}"
                                        alt="Frame {{ $index + 1 }}">

                                    <div class="card-body">
                                        <div class="fw-semibold">
                                            Frame {{ $index + 1 }}
                                        </div>
                                        <div class="text-secondary">
                                            Session #{{ $session->id }}
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <a href="{{ route('gallery.frame.download', $photo->id) }}"
                                            class="btn btn-outline-primary w-100" download>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                <path d="M7 11l5 5l5 -5" />
                                                <path d="M12 4l0 12" />
                                            </svg>
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div id="lightbox" class="lightbox-modal">
        <span class="lightbox-close">&times;</span>
        <div class="lightbox-content">
            <img id="lightbox-image" class="lightbox-img hidden" src="" alt="">
            <video id="lightbox-video" class="lightbox-video hidden" controls autoplay loop playsinline>
                <source src="" type="video/mp4">
            </video>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const lightbox = document.getElementById('lightbox');
            const lightboxImg = document.getElementById('lightbox-image');
            const lightboxVideo = document.getElementById('lightbox-video');
            const closeBtn = document.querySelector('.lightbox-close');

            // Get all clickable media
            const galleryItems = document.querySelectorAll('.gallery-img');

            galleryItems.forEach(item => {
                item.addEventListener('click', () => {
                    const type = item.dataset.type;
                    const src = item.dataset.src;

                    if (type === 'image') {
                        lightboxVideo.classList.add('hidden');
                        lightboxVideo.pause();

                        lightboxImg.src = src;
                        lightboxImg.classList.remove('hidden');
                    } else if (type === 'video') {
                        lightboxImg.classList.add('hidden');

                        lightboxVideo.querySelector('source').src = src;
                        lightboxVideo.load();
                        lightboxVideo.classList.remove('hidden');
                        lightboxVideo.play();
                    }

                    lightbox.style.display = 'block';
                });
            });

            // Close lightbox
            closeBtn.addEventListener('click', () => {
                lightbox.style.display = 'none';
                lightboxVideo.pause();
            });

            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) {
                    lightbox.style.display = 'none';
                    lightboxVideo.pause();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && lightbox.style.display === 'block') {
                    lightbox.style.display = 'none';
                    lightboxVideo.pause();
                }
            });
        });
    </script>
</body>

</html>
