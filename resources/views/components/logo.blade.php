{{-- logo --}}
<div href="." class="navbar-brand text-dark d-none d-lg-flex align-items-center sticky-top border-bottom bg-white">
    @if (!empty($systemSettings?->logo_path))
        <img src="{{ asset('storage/' . $systemSettings->logo_path) }}" class="navbar-brand-image ms-2"
            style="height: 34px; width: auto;" alt="Logo">
    @endif
    <h1 class="m-0">{{ $systemSettings?->system_name ?? 'MOOEIN SNAP' }}</h1>
</div>
<div href="." class="navbar-brand text-dark flex-row d-lg-none">
    @if (!empty($systemSettings?->logo_path))
        <img src="{{ asset('storage/' . $systemSettings->logo_path) }}" class="navbar-brand-image ms-2"
            style="height: 28px; width: auto;" alt="Logo">
    @endif
    <h1 class="m-0">{{ $systemSettings?->system_name ?? 'MOOEIN SNAP' }}</h1>
</div>
{{-- end logo --}}
