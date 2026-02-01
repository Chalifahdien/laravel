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
            Dashboard
        </span>
    </a>
    <a class="nav-link mb-1  {{ Request::is('transactions*') ? 'aktif' : '' }}" href="/transactions">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-receipt-dollar">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" />
            <path d="M14.8 8a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1" />
            <path d="M12 6v10" />
        </svg>
        <span class="ms-2 nav-link-title">
            Transactions
        </span>
    </a>
    <a class="nav-link mb-1  {{ Request::is('invoices*') ? 'aktif' : '' }}" href="/invoices">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-file-invoice">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" />
            <path d="M9 7h1" />
            <path d="M9 13h1" />
            <path d="M13 17h1" />
        </svg>
        <span class="ms-2 nav-link-title">
            Invoice
        </span>
    </a>
    <a class="nav-link mb-1  {{ Request::is('report-analytics*') ? 'aktif' : '' }}" href="/report-analytics">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-chart-bar">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
            <path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
            <path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
            <path d="M4 20h14" />
        </svg>
        <span class="ms-2 nav-link-title">
            Report Analytics
        </span>
    </a>
    <a class="nav-link mb-1  {{ Request::is('banner-promo*') ? 'aktif' : '' }}" href="/banner-promo">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-ticket">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M15 5l0 2" />
            <path d="M15 11l0 2" />
            <path d="M15 17l0 2" />
            <path
                d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" />
        </svg>
        <span class="ms-2 nav-link-title">
            Banner Promo
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
    <a class="nav-link mb-1  {{ Request::is(patterns: 'gallery*') ? 'aktif' : '' }}" href="/gallery">
        <!-- Download SVG icon from http://tabler.io/icons/icon/home -->
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-library-photo">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path
                d="M7 5.667a2.667 2.667 0 0 1 2.667 -2.667h8.666a2.667 2.667 0 0 1 2.667 2.667v8.666a2.667 2.667 0 0 1 -2.667 2.667h-8.666a2.667 2.667 0 0 1 -2.667 -2.667l0 -8.666" />
            <path d="M4.012 7.26a2.005 2.005 0 0 0 -1.012 1.737v10c0 1.1 .9 2 2 2h10c.75 0 1.158 -.385 1.5 -1" />
            <path d="M17 7h.01" />
            <path d="M7 13l3.644 -3.644a1.21 1.21 0 0 1 1.712 0l3.644 3.644" />
            <path d="M15 12l1.644 -1.644a1.21 1.21 0 0 1 1.712 0l2.644 2.644" />
        </svg>
        <!-- </span> -->
        <span class="ms-2 nav-link-title">
            Gallery
        </span>
    </a>
    <a class="nav-link mb-1  {{ Request::is(patterns: 'machines*') ? 'aktif' : '' }}" href="/machines">
        <!-- Download SVG icon from http://tabler.io/icons/icon/home -->
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-device-desktop">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M3 5a1 1 0 0 1 1 -1h16a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-16a1 1 0 0 1 -1 -1v-10" />
            <path d="M7 20h10" />
            <path d="M9 16v4" />
            <path d="M15 16v4" />
        </svg>
        <!-- </span> -->
        <span class="ms-2 nav-link-title">
            Machines
        </span>
    </a>
    <a class="nav-link mb-1  {{ Request::is(patterns: 'paper-sizes*') ? 'aktif' : '' }}" href="/paper-sizes">
        <!-- Download SVG icon from http://tabler.io/icons/icon/home -->
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-file">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
            <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2" />
        </svg>
        <!-- </span> -->
        <span class="ms-2 nav-link-title">
            Paper Sizes
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
