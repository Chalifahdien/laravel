<aside class="navbar navbar-vertical navbar-transparent navbar-expand-lg m-2 border rounded-3  bg-white">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        @include('components.logo')
        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <div class="avatar  bg-cyan-lt" data-demo-color="">CH</div>
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    @include('components.profil-menu')
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            @include('components.nav-menu')
        </div>
        <div class="p-2 d-none d-lg-flex border-top bg-white sticky-bottom">
            <div class="nav-item dropdown w-100">
                <a href="#" class="nav-link d-flex justify-content-between text-dark" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <div class="d-flex align-items-center">
                        <div class="avatar avatar-sm bg-cyan-lt" data-demo-color="">{{ Str::substr(auth()->user()->name, 0, 1) }}</div>
                        <span class="ms-2 nav-link-title">
                            {{ Auth::user()->name }}
                        </span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-sm">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M9 18l3 3l3 -3" />
                        <path d="M12 15v6" />
                        <path d="M15 6l-3 -3l-3 3" />
                        <path d="M12 3v6" />
                    </svg>
                </a>
                <div class="dropdown-menu dropup-position w-100 dropdown-menu-center mb-3">
                    @include('components.profil-menu')
                </div>
            </div>
        </div>
    </div>
</aside>