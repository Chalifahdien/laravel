<ul class="navbar-nav px-1 dropdown-menu my-1 z-1">
    <a class="nav-link mb-1  {{ Request::is('/') ? 'aktif' : '' }}" href="/">
        <!-- Download SVG icon from http://tabler.io/icons/icon/home -->
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
            <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
        </svg>
        <!-- </span> -->
        <span class="ms-2 nav-link-title">
            Beranda
        </span>
    </a>
    <a class="nav-link mb-1  {{ Request::is(patterns: 'templates*') ? 'aktif' : '' }}" href="/templates">
        <!-- Download SVG icon from http://tabler.io/icons/icon/home -->
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-photo-scan">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M15 8h.01" />
            <path d="M6 13l2.644 -2.644a1.21 1.21 0 0 1 1.712 0l3.644 3.644" />
            <path d="M13 13l1.644 -1.644a1.21 1.21 0 0 1 1.712 0l1.644 1.644" />
            <path d="M4 8v-2a2 2 0 0 1 2 -2h2" />
            <path d="M4 16v2a2 2 0 0 0 2 2h2" />
            <path d="M16 4h2a2 2 0 0 1 2 2v2" />
            <path d="M16 20h2a2 2 0 0 0 2 -2v-2" />
        </svg>
        <!-- </span> -->
        <span class="ms-2 nav-link-title">
            Templates
        </span>
    </a>
    <a class="nav-link mb-1  {{ Request::is(patterns: 'machines*') ? 'aktif' : '' }}" href="/machines">
        <!-- Download SVG icon from http://tabler.io/icons/icon/home -->
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-camera">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
                d="M5 7h1a2 2 0 0 0 2 -2a1 1 0 0 1 1 -1h6a1 1 0 0 1 1 1a2 2 0 0 0 2 2h1a2 2 0 0 1 2 2v9a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-9a2 2 0 0 1 2 -2" />
            <path d="M9 13a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
        </svg>
        <!-- </span> -->
        <span class="ms-2 nav-link-title">
            Machines
        </span>
    </a>
    {{-- <a class="nav-link mb-1  {{ Request::is(patterns: 'users') ? 'aktif' : '' }}" href="/users">
        <!-- Download SVG icon from http://tabler.io/icons/icon/home -->
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-users">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
            <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
        </svg>
        <!-- </span> -->
        <span class="ms-2 nav-link-title">
            Pengguna
        </span>
    </a> --}}
</ul>
