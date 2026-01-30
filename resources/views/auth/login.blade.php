<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | YASHA SNAP</title>

    {{-- Tabler CSS --}}
    <link href="{{ asset('dist/css/admin.css') }}" rel="stylesheet" />

    {{-- Font --}}
    <style>
        @import url("https://rsms.me/inter/inter.css");

        body {
            font-family: Inter, sans-serif;
            min-height: 100vh;
        }

        .login-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 30px 60px rgba(0, 0, 0, .25);
        }

        .login-brand {
            background: linear-gradient(180deg, #1e293b, #020617);
            color: #fff;
        }

        .login-brand h1 {
            font-weight: 800;
            letter-spacing: 2px;
        }

        .login-brand p {
            opacity: .8;
        }

        .form-control {
            height: 45px;
        }

        .btn-primary {
            height: 45px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="login-wrapper">
        <div class="container container-tight">
            <div class="card login-card">
                <div class="row g-0">

                    {{-- LEFT BRAND --}}
                    <div class="col-md-5 d-none d-md-flex login-brand align-items-center justify-content-center">
                        <div class="text-center px-4">
                            <h1>YASHA SNAP</h1>
                            <p class="mt-3">
                                Modern Photobooth System<br>
                                Fast · Interactive · Professional
                            </p>
                        </div>
                    </div>

                    {{-- RIGHT FORM --}}
                    <div class="col-md-7">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h2 text-center mb-1">Welcome Back</h2>
                            <p class="text-muted text-center mb-4">
                                Please sign in to your account
                            </p>

                            <form action="{{ url('/login') }}" method="POST">
                                @csrf

                                {{-- EMAIL --}}
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="name@email.com" required autofocus>
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                {{-- PASSWORD --}}
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="input-group input-group-flat">
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Your password" required id="password">

                                        <span class="input-group-text">
                                            <a href="#" onclick="togglePassword(event)" id="togglePasswordIcon">
                                                <!-- DEFAULT: password hidden -->
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icon-tabler-eye">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path
                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg>
                                            </a>
                                        </span>
                                    </div>
                                    @error('password')
                                        <div class="text-danger small mt-1">
                                            Invalid email or password
                                        </div>
                                    @enderror
                                </div>

                                {{-- BUTTON --}}
                                <div class="form-footer mt-4">
                                    <button type="submit" class="btn btn-primary w-100">
                                        Sign In
                                    </button>
                                </div>
                            </form>

                            <div class="text-center text-muted mt-4">
                                © {{ date('Y') }} YASHA SNAP. All rights reserved.
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Tabler JS --}}
    <script src="{{ asset('dist/js/tabler.min.js') }}"></script>

    <script>
        function togglePassword(e) {
            e.preventDefault();

            const input = document.getElementById('password');
            const icon = document.getElementById('togglePasswordIcon');

            if (input.type === 'password') {
                input.type = 'text';

                // SHOW eye-off (password visible)
                icon.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icon-tabler-eye-off">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
                <path
                    d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" />
                <path d="M3 3l18 18" />
            </svg>
        `;
            } else {
                input.type = 'password';

                // SHOW eye (password hidden)
                icon.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="icon icon-tabler icon-tabler-eye">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                <path
                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
            </svg>
        `;
            }
        }
    </script>
</body>

</html>
