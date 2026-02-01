@extends('layouts.app')

@section('content')
    <div id="profile-page">
        <div class="page-header d-print-none">
            <div class="container-xl">
                <div class="row g-2 align-items-center">
                    <div class="col">
                        <div class="page-pretitle">Account</div>
                        <h2 class="page-title">Profile</h2>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        <div class="btn-list">
                            <a href="{{ route('dashboard') }}" class="btn btn-link">
                                ← Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container-xl">
                <div class="row row-cards">
                    <div class="col-12 col-lg-4">
                        <div class="row row-cards">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <span class="avatar avatar-xl mb-3 bg-cyan-lt">
                                            {{ strtoupper(Str::substr(auth()->user()->name, 0, 1)) }}
                                        </span>

                                        <h3 class="mb-1">{{ auth()->user()->name }}</h3>
                                        <div class="text-muted">{{ auth()->user()->email }}</div>

                                        <div class="mt-3">
                                            <span class="badge bg-blue-lt text-uppercase">
                                                {{ auth()->user()->role }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="card-footer">
                                        <div class="row g-2">
                                            <div class="col-12">
                                                <div class="text-muted small">Member since</div>
                                                <div class="fw-semibold">
                                                    {{ auth()->user()->created_at?->format('d M Y') ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="text-muted small">Last updated</div>
                                                <div class="fw-semibold">
                                                    {{ auth()->user()->updated_at?->format('d M Y H:i') ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-muted mb-2">Security Tips</div>
                                        <div class="d-flex flex-column gap-2">
                                            <div class="d-flex gap-2 align-items-start">
                                                <span class="badge bg-green-lt mt-1">1</span>
                                                <div>Use a password with at least 8 characters.</div>
                                            </div>
                                            <div class="d-flex gap-2 align-items-start">
                                                <span class="badge bg-green-lt mt-1">2</span>
                                                <div>Avoid using your email or name as your password.</div>
                                            </div>
                                            <div class="d-flex gap-2 align-items-start">
                                                <span class="badge bg-green-lt mt-1">3</span>
                                                <div>Change your password regularly.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-8">
                        <div class="row row-cards">
                            <div class="col-12">
                                <form action="{{ route('profile.update') }}" method="POST" class="card">
                                    @csrf
                                    @method('PUT')

                                    <div class="card-header">
                                        <h3 class="card-title">Edit Profile</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Full name</label>
                                                <input type="text" name="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    value="{{ old('name', auth()->user()->name) }}" autocomplete="name"
                                                    required>
                                                @error('name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email"
                                                    class="form-control @error('email') is-invalid @enderror"
                                                    value="{{ old('email', auth()->user()->email) }}" autocomplete="email"
                                                    required>
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                                Reset
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                Save changes
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="col-12">
                                <form id="profile-password-form" action="{{ route('profile.password') }}" method="POST"
                                    class="card">
                                    @csrf
                                    @method('PUT')

                                    <div class="card-header">
                                        <h3 class="card-title">Change Password</h3>
                                        <div class="card-actions">
                                            <span class="text-muted">Minimum 8 characters</span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label">Current password</label>
                                                <div class="input-group input-group-flat">
                                                    <input id="profile_current_password" type="password"
                                                        name="current_password"
                                                        class="form-control @error('current_password') is-invalid @enderror"
                                                        autocomplete="current-password" required>
                                                    <span class="input-group-text">
                                                        <a href="#" class="link-secondary" aria-label="Show password"
                                                            data-profile-toggle-password="#profile_current_password">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22"
                                                                height="22" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                    </span>
                                                </div>
                                                @error('current_password')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="form-label">New password</label>
                                                <div class="input-group input-group-flat">
                                                    <input id="profile_new_password" type="password" name="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        autocomplete="new-password" required>
                                                    <span class="input-group-text">
                                                        <a href="#" class="link-secondary"
                                                            aria-label="Show password"
                                                            data-profile-toggle-password="#profile_new_password">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22"
                                                                height="22" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
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
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="col-12 col-md-6">
                                                <label class="form-label">Confirm new password</label>
                                                <div class="input-group input-group-flat">
                                                    <input id="profile_new_password_confirmation" type="password"
                                                        name="password_confirmation" class="form-control"
                                                        autocomplete="new-password" required>
                                                    <span class="input-group-text">
                                                        <a href="#" class="link-secondary"
                                                            aria-label="Show password"
                                                            data-profile-toggle-password="#profile_new_password_confirmation">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="22"
                                                                height="22" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="icon icon-tabler icon-tabler-eye">
                                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                                <path
                                                                    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                            </svg>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-footer text-end">
                                        <button type="submit" class="btn btn-warning">
                                            Update password
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const root = document.getElementById('profile-page');
            if (!root) return;

            const eye = `
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icon-tabler-eye">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                    <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                </svg>
            `;

            const eyeOff = `
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icon-tabler-eye-off">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
                    <path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" />
                    <path d="M3 3l18 18" />
                </svg>
            `;

            root.querySelectorAll('[data-profile-toggle-password]').forEach((link) => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    const selector = link.getAttribute('data-profile-toggle-password');
                    const input = selector ? root.querySelector(selector) : null;
                    if (!input) return;

                    const isPassword = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPassword ? 'text' : 'password');
                    link.innerHTML = isPassword ? eyeOff : eye;
                });
            });
        });
    </script>
@endpush
