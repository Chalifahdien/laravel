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
            align-items: center;
            justify-content: center;
        }

        .lightbox-content {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            max-width: 90vw;
            max-height: 90vh;
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

        .card {
            position: relative;
            overflow: hidden;
            background-color: #03045e;
        }

        /* tombol download overlay */
        .download-overlay {
            position: absolute;
            top: 12px;
            right: 12px;
            z-index: 10;
        }

        .label-overlay {
            position: absolute;
            top: 12px;
            left: 12px;
            z-index: 10;
        }


        .download-overlay .btn {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            padding: 0;

            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* optional hover effect */
        .download-overlay .btn:hover {
            transform: scale(1.08);
            transition: 0.2s;
        }
    </style>
</head>

<body class="theme-dark" style="background-color: #B8FB3C;">
    <div class="page">
        <!-- Navbar -->
        <header class="navbar navbar-expand-md navbar-dark d-print-none border-0" style="background-color: #03045e;">
            <div class="container-xl d-flex justify-content-center text-center py-5 border-0" style="color: #B8FB3C;">
                <div class="">
                    <h1 class="mb-1 mt-0">
                        Your Memories
                    </h1>
                    <h3 class="mb-0">
                        Treasure your special moments forever
                    </h3>
                </div>
            </div>
        </header>

        <!-- Page Body -->
        <div class="page-wrapper">
            <div class="page-body">
                <div class="container-xl">

                    <!-- Gallery Grid -->
                    <div class="card mb-4">
                        <!-- CARD HEADER -->
                        <div class="card-header">
                            <h2 class="mb-0" style="color: #B8FB3C;">
                                Featured Photos
                            </h2>
                        </div>
                        <div class="card-body">
                            <div class="row d-flex justify-content-center">

                                <!-- FINAL PHOTO -->
                                @if ($session->finalImage)
                                    <div class="col-sm-12 col-md-6">
                                        <div class="card">

                                            <!-- LABEL kiri atas -->
                                            <div class="label-overlay">
                                                <span class="badge bg-blue-lt">
                                                    FINAL
                                                </span>
                                            </div>


                                            <!-- tombol download kanan atas -->
                                            <div class="download-overlay">
                                                <a href="{{ route('gallery.final.download', $token) }}"
                                                    class="btn btn-primary" download>

                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0"
                                                        width="20" height="20" viewBox="0 0 24 24"
                                                        stroke-width="2" stroke="currentColor" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round">

                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                        <path d="M7 11l5 5l5 -5" />
                                                        <path d="M12 4l0 12" />

                                                    </svg>

                                                </a>
                                            </div>


                                            <!-- IMAGE -->
                                            <img src="{{ asset('storage/' . $session->finalImage->image_path) }}"
                                                class="card-img-top gallery-img"
                                                style="aspect-ratio: 3/4; object-fit: cover;" data-type="image"
                                                data-src="{{ asset('storage/' . $session->finalImage->image_path) }}"
                                                alt="Final Photo">

                                        </div>
                                    </div>


                                    <!-- LIVE PHOTO -->
                                    @if ($session->finalImage->video_path)
                                        @php
                                            $videoPath = $session->finalImage->video_path;
                                            $ext = strtolower(pathinfo($videoPath, PATHINFO_EXTENSION));
                                            $isGif = $ext === 'gif';
                                        @endphp
                                        <div class="col-sm-12 col-md-6 mt-3 mt-md-0">
                                            <div class="card">

                                                <!-- LABEL kiri atas -->
                                                <div class="label-overlay">
                                                    <span class="badge bg-red-lt">
                                                        LIVE
                                                    </span>
                                                </div>


                                                <!-- tombol download kanan atas -->
                                                <div class="download-overlay">
                                                    <a href="{{ route('gallery.live.download', $token) }}"
                                                        class="btn btn-primary" download>

                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0"
                                                            width="20" height="20" viewBox="0 0 24 24"
                                                            stroke-width="2" stroke="currentColor" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round">

                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                            <path d="M7 11l5 5l5 -5" />
                                                            <path d="M12 4l0 12" />

                                                        </svg>

                                                    </a>
                                                </div>


                                                <!-- MEDIA -->
                                                @if ($isGif)
                                                    <img src="{{ asset('storage/' . $videoPath) }}"
                                                        class="card-img-top gallery-img"
                                                        style="aspect-ratio: 3/4; object-fit: cover;" data-type="image"
                                                        data-src="{{ asset('storage/' . $videoPath) }}"
                                                        alt="Live Photo">
                                                @else
                                                    <video class="card-img-top gallery-img"
                                                        style="aspect-ratio: 3/4; object-fit: cover;" muted autoplay
                                                        loop playsinline data-type="video"
                                                        data-src="{{ asset('storage/' . $videoPath) }}">

                                                        <source src="{{ asset('storage/' . $videoPath) }}"
                                                            type="video/{{ $ext }}">

                                                    </video>
                                                @endif

                                            </div>
                                        </div>

                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h2 class="mb-0" style="color: #B8FB3C;">
                                Frame Photos
                            </h2>
                        </div>
                        <div class="card-body">
                            <div class="row d-flex">
                                <!-- FRAME PHOTOS -->
                                {{-- @for ($i = 1; $i <= 5; $i++) --}}
                                @foreach ($session->photos as $index => $photo)
                                    <div class="col-sm-6 col-md-4 mb-3">
                                        <div class="card">

                                            <!-- LABEL kiri atas -->
                                            <div class="label-overlay">
                                                <span class="badge bg-dark-lt">
                                                    FRAME
                                                </span>
                                            </div>


                                            <!-- tombol download kanan atas -->
                                            <div class="download-overlay">
                                                <a href="{{ route('gallery.frame.download', ['token' => $token, 'photo_id' => $photo->id]) }}"
                                                    class="btn btn-primary" download>

                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon m-0"
                                                        width="20" height="20" viewBox="0 0 24 24"
                                                        stroke-width="2" stroke="currentColor" fill="none"
                                                        stroke-linecap="round" stroke-linejoin="round">

                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2" />
                                                        <path d="M7 11l5 5l5 -5" />
                                                        <path d="M12 4l0 12" />

                                                    </svg>

                                                </a>
                                            </div>


                                            <!-- IMAGE -->
                                            <img src="{{ asset('storage/' . $photo->photo_path) }}"
                                                class="card-img-top gallery-img"
                                                style="aspect-ratio: 3/4; object-fit: cover;" data-type="image"
                                                data-src="{{ asset('storage/' . $photo->photo_path) }}"
                                                alt="Frame {{ $index + 1 }}">

                                        </div>
                                    </div>
                                @endforeach
                                {{-- @endfor --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- FOOTER -->
        <footer class="navbar navbar-expand-md navbar-dark d-print-none border-0" style="background-color: #03045e;">

            <div class="container-xl d-flex justify-content-center text-center border-0" style="color: #B8FB3C;">

                <div>
                    <div class="mb-0" style="font-size:14px;">
                        © 2026 MooeinSnap. All rights reserved.
                    </div>

                    <div style="font-size:13px; opacity:0.8;">
                        Capture. Keep. Relive.
                    </div>
                </div>

            </div>

        </footer>


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

                    lightbox.style.display = 'flex';
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
