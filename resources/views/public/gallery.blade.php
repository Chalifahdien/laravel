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
            background: #000;
        }

        .page {
            background: #000;
        }

        /* Instagram-like card */
        .ig-card {
            background: #1a1a1a;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 24px;
            border: 1px solid #262626;
        }

        .ig-card-header {
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #262626;
        }

        .ig-card-title {
            font-weight: 600;
            font-size: 14px;
            color: #fff;
            margin: 0;
        }

        .ig-card-image-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 100%;
            /* Square aspect ratio */
            background: #000;
            overflow: hidden;
        }

        .ig-card-image-wrapper img,
        .ig-card-image-wrapper video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
        }

        .ig-card-footer {
            padding: 12px 16px;
        }

        .badge-top-right {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 2;
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
        <header class="navbar navbar-expand-md navbar-dark d-print-none" style="border-bottom: 1px solid #262626;">
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
                <div class="container" style="max-width: 935px;">

                    <!-- FINAL PHOTO -->
                    @if ($session->finalImage)
                        <div class="ig-card">
                            <div class="ig-card-header">
                                <div class="ig-card-title">
                                    <span class="badge bg-blue me-2">FINAL</span>
                                    Foto Final
                                </div>
                            </div>
                            <div class="ig-card-image-wrapper">
                                <img src="{{ asset('storage/' . $session->finalImage->image_path) }}" data-type="image"
                                    data-src="{{ asset('storage/' . $session->finalImage->image_path) }}"
                                    alt="Final Photo">
                            </div>
                            <div class="ig-card-footer">
                                <a href="{{ route('gallery.final.download', $session->id) }}"
                                    class="btn btn-primary w-100" download>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                        viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                        <path d="M7 11l5 5l5 -5" />
                                        <path d="M12 4l0 12" />
                                    </svg>
                                    Unduh Foto Final
                                </a>
                            </div>
                        </div>

                        <!-- LIVE PHOTO -->
                        @if ($session->finalImage->video_path)
                            @php
                                $videoPath = $session->finalImage->video_path;
                                $ext = strtolower(pathinfo($videoPath, PATHINFO_EXTENSION));
                                $isGif = $ext === 'gif';
                            @endphp
                            <div class="ig-card">
                                <div class="ig-card-header">
                                    <div class="ig-card-title">
                                        <span class="badge bg-red me-2">LIVE</span>
                                        Live Photo
                                    </div>
                                </div>
                                <div class="ig-card-image-wrapper">
                                    @if ($isGif)
                                        <img src="{{ asset('storage/' . $videoPath) }}" data-type="image"
                                            data-src="{{ asset('storage/' . $videoPath) }}" alt="Live Photo">
                                    @else
                                        <video muted autoplay loop playsinline data-type="video"
                                            data-src="{{ asset('storage/' . $videoPath) }}">
                                            <source src="{{ asset('storage/' . $videoPath) }}"
                                                type="video/{{ $ext }}">
                                        </video>
                                    @endif
                                </div>
                                <div class="ig-card-footer">
                                    <a href="{{ route('gallery.live.download', $session->id) }}"
                                        class="btn btn-primary w-100" download>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24"
                                            height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                            fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                            <path d="M7 11l5 5l5 -5" />
                                            <path d="M12 4l0 12" />
                                        </svg>
                                        Unduh Live Photo
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- FRAME PHOTOS GRID -->
                    @if ($session->photos->count() > 0)
                        <div class="ig-card">
                            <div class="ig-card-header">
                                <div class="ig-card-title">
                                    Foto Frame ({{ $session->photos->count() }})
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="row g-2">
                                    @foreach ($session->photos as $index => $photo)
                                        <div class="col-4">
                                            <div
                                                style="position: relative; padding-bottom: 100%; background: #000; border-radius: 4px; overflow: hidden;">
                                                <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; cursor: pointer;"
                                                    data-type="image"
                                                    data-src="{{ asset('storage/' . $photo->photo_path) }}"
                                                    data-download="{{ route('gallery.frame.download', $photo->id) }}"
                                                    alt="Frame {{ $index + 1 }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="ig-card-footer">
                                <small class="text-muted">Klik foto untuk melihat ukuran penuh dan mengunduh</small>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <!-- Lightbox Modal (For viewing full size and downloading) -->
    <div id="lightbox" class="lightbox-modal">
        <span class="lightbox-close">&times;</span>
        <div class="lightbox-content">
            <img id="lightbox-image" class="lightbox-img hidden" src="" alt="">
            <!-- Video element is needed for Live Photos (videos/GIFs) -->
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
            const galleryItems = document.querySelectorAll('[data-type]');

            galleryItems.forEach(item => {
                item.addEventListener('click', () => {
                    const type = item.dataset.type;
                    const src = item.dataset.src;

                    if (type === 'image') {
                        // Hide video, show image
                        lightboxVideo.classList.add('hidden');
                        lightboxVideo.pause();

                        lightboxImg.src = src;
                        lightboxImg.classList.remove('hidden');
                    } else if (type === 'video') {
                        // Hide image, show video
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

            // Close on background click
            lightbox.addEventListener('click', (e) => {
                if (e.target === lightbox) {
                    lightbox.style.display = 'none';
                    lightboxVideo.pause();
                }
            });

            // Close on ESC key
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
