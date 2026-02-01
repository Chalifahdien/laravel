@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Overview</div>
                    <h2 class="page-title">Dashboard</h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('admin.report-analytics.index') }}" class="btn btn-outline-primary">
                            <!-- chart-bar -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path d="M9 8m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path d="M15 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z" />
                                <path d="M4 20h14" />
                            </svg>
                            Report Analytics
                        </a>
                        <a href="{{ route('transactions.index') }}" class="btn btn-primary">
                            <!-- receipt-dollar -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" />
                                <path d="M14.8 8a2 2 0 0 0 -1.8 -1h-2a2 2 0 1 0 0 4h2a2 2 0 1 1 0 4h-2a2 2 0 0 1 -1.8 -1" />
                                <path d="M12 6v10" />
                            </svg>
                            Transactions
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">

            @php
                $sessionsPerDay = $sessionsPerDay ?? collect();

                $paymentBadge = [
                    'success' => 'bg-green-lt',
                    'settlement' => 'bg-green-lt',
                    'capture' => 'bg-green-lt',
                    'pending' => 'bg-yellow-lt',
                    'deny' => 'bg-red-lt',
                    'cancel' => 'bg-red-lt',
                    'expire' => 'bg-red-lt',
                    'failure' => 'bg-red-lt',
                    'failed' => 'bg-red-lt',
                ];

                $sessionBadge = [
                    'done' => 'bg-green-lt',
                    'completed' => 'bg-green-lt',
                    'success' => 'bg-green-lt',
                    'pending' => 'bg-yellow-lt',
                    'processing' => 'bg-azure-lt',
                    'failed' => 'bg-red-lt',
                    'canceled' => 'bg-red-lt',
                    'cancelled' => 'bg-red-lt',
                ];
            @endphp

            {{-- =====================
                STAT CARDS
            ===================== --}}
            <div class="row row-deck row-cards">

                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-blue-lt text-blue avatar">
                                        <!-- activity -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3 12h4l3 8l4 -16l3 8h4" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="subheader">Total Sessions</div>
                                    <div class="h1 mb-0">{{ number_format($totalSessions) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-azure-lt text-azure avatar">
                                        <!-- calendar -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z" />
                                            <path d="M16 3v4" />
                                            <path d="M8 3v4" />
                                            <path d="M4 11h16" />
                                            <path d="M8 15h2v2h-2z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="subheader">Today Sessions</div>
                                    <div class="h1 mb-0">{{ number_format($todaySessions) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-cyan-lt text-cyan avatar">
                                        <!-- device-desktop -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M3 4m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" />
                                            <path d="M7 20h10" />
                                            <path d="M9 16v4" />
                                            <path d="M15 16v4" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="subheader">Active Machines</div>
                                    <div class="h1 mb-0">{{ number_format($activeMachines) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-purple-lt text-purple avatar">
                                        <!-- layout-grid -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M4 4m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                            <path
                                                d="M14 4m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                            <path
                                                d="M4 14m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                            <path
                                                d="M14 14m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v2a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="subheader">Active Templates</div>
                                    <div class="h1 mb-0">{{ number_format($activeTemplates) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- =====================
                REVENUE
            ===================== --}}
            <div class="row row-deck row-cards mt-3">
                <div class="col-md-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-green-lt text-green avatar">
                                        <!-- coin -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 12m-7 0a7 3 0 1 0 14 0a7 3 0 1 0 -14 0" />
                                            <path d="M5 12v4a7 3 0 0 0 14 0v-4" />
                                            <path d="M5 16v4a7 3 0 0 0 14 0v-4" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="subheader">Total Revenue</div>
                                    <div class="h1 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="bg-yellow-lt text-yellow avatar">
                                        <!-- receipt -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M5 21v-16a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v16l-3 -2l-2 2l-2 -2l-2 2l-2 -2l-3 2" />
                                            <path d="M14 8h-6" />
                                            <path d="M14 12h-6" />
                                            <path d="M14 16h-6" />
                                        </svg>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="subheader">Today Revenue</div>
                                    <div class="h1 mb-0">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- =====================
                ACTIVITY (7 DAYS) + SESSION STATUS
            ===================== --}}
            <div class="row row-deck row-cards mt-3">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Sessions (Last 7 Days)</h3>
                            <div class="card-actions">
                                <span class="text-muted">Today: {{ number_format($todaySessions) }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="chart-sessions-7d" class="chart-lg"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Session Status</h3>
                        </div>
                        <div class="card-body">
                            @forelse ($sessionStatus as $status => $total)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted text-uppercase">{{ $status }}</span>
                                    <span
                                        class="badge {{ $sessionBadge[strtolower($status)] ?? 'bg-blue-lt' }}">{{ number_format($total) }}</span>
                                </div>
                            @empty
                                <div class="text-muted">No data</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- =====================
                    LATEST TRANSACTIONS
                ===================== --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Latest Transactions</h3>
                            <div class="card-actions">
                                <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary btn-sm">
                                    View all
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="w-1">#</th>
                                        <th>Order</th>
                                        <th>Machine</th>
                                        <th class="text-end">Amount</th>
                                        <th>Payment</th>
                                        <th>Session</th>
                                        <th class="text-center">Final</th>
                                        <th class="text-nowrap">Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($latestSessions as $session)
                                        <tr class="js-row-link border-bottom-0"
                                            data-url="{{ route('transactions.show', $session) }}"
                                            style="cursor: pointer;">
                                            <td class="text-muted">{{ $loop->iteration }}</td>
                                            <td class="fw-semibold">
                                                {{ $session->payment?->order_id ? '#' . $session->payment->order_id : '-' }}
                                            </td>
                                            <td>{{ $session->machine->name ?? '-' }}</td>
                                            <td>
                                                <span class="fw-semibold">
                                                    Rp {{ number_format($session->payment?->amount ?? 0, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td>
                                                @php($pStatus = strtolower($session->payment?->transaction_status ?? 'unknown'))
                                                <span class="badge {{ $paymentBadge[$pStatus] ?? 'bg-secondary-lt' }}">
                                                    {{ strtoupper($session->payment?->transaction_status ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                @php($sStatus = strtolower($session->status ?? 'unknown'))
                                                <span class="badge {{ $sessionBadge[$sStatus] ?? 'bg-blue-lt' }}">
                                                    {{ strtoupper($session->status ?? 'N/A') }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($session->finalImage)
                                                    <span class="badge bg-success-lt">YES</span>
                                                @else
                                                    <span class="badge bg-secondary-lt">NO</span>
                                                @endif
                                            </td>
                                            <td class="text-muted">
                                                {{ $session->created_at->format('d M Y H:i') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">
                                                No recent transactions
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.js-row-link').forEach((row) => {
                row.addEventListener('click', () => {
                    const url = row.dataset.url;
                    if (url) window.location.href = url;
                });
            });

            const labels = @json(collect(range(6, 0))->map(function ($i) {
                        $d = \Carbon\Carbon::now()->subDays($i);
                        return $d->format('d M');
                    })->values());

            const data = @json(collect(range(6, 0))->map(function ($i) use ($sessionsPerDay) {
                        $d = \Carbon\Carbon::now()->subDays($i)->format('Y-m-d');
                        return $sessionsPerDay->firstWhere('date', $d)?->total ?? 0;
                    })->values());

            if (document.getElementById('chart-sessions-7d') && typeof ApexCharts !== 'undefined') {
                new ApexCharts(document.getElementById('chart-sessions-7d'), {
                    chart: {
                        type: 'area',
                        height: 280,
                        toolbar: {
                            show: false
                        },
                        zoom: {
                            enabled: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    grid: {
                        strokeDashArray: 4
                    },
                    series: [{
                        name: 'Sessions',
                        data
                    }],
                    xaxis: {
                        categories: labels
                    },
                    colors: ['#206bc4'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            opacityFrom: 0.35,
                            opacityTo: 0.05
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(v) {
                                return v + ' sessions';
                            }
                        }
                    }
                }).render();
            }
        });
    </script>
@endpush
