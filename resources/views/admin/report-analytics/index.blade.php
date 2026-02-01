@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Report</div>
                    <h2 class="page-title">Report Analytics</h2>
                    <div class="text-muted mt-1">
                        Period:
                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</span>
                        –
                        <span class="fw-semibold">{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
                    </div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="btn-list">
                        <a href="{{ route('admin.report-analytics.index') }}" class="btn btn-outline-secondary">
                            Reset
                        </a>
                        <button type="button" class="btn btn-outline-primary" onclick="window.print()">
                            Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">

            @php
                $preset = [
                    'last_7' => [
                        'label' => 'Last 7 days',
                        'start' => \Carbon\Carbon::now()->subDays(6)->format('Y-m-d'),
                        'end' => \Carbon\Carbon::now()->format('Y-m-d'),
                    ],
                    'last_30' => [
                        'label' => 'Last 30 days',
                        'start' => \Carbon\Carbon::now()->subDays(30)->format('Y-m-d'),
                        'end' => \Carbon\Carbon::now()->format('Y-m-d'),
                    ],
                    'this_month' => [
                        'label' => 'This month',
                        'start' => \Carbon\Carbon::now()->startOfMonth()->format('Y-m-d'),
                        'end' => \Carbon\Carbon::now()->format('Y-m-d'),
                    ],
                    'last_month' => [
                        'label' => 'Last month',
                        'start' => \Carbon\Carbon::now()->subMonthNoOverflow()->startOfMonth()->format('Y-m-d'),
                        'end' => \Carbon\Carbon::now()->subMonthNoOverflow()->endOfMonth()->format('Y-m-d'),
                    ],
                ];

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

            {{-- Period filter --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Period Filter</h3>
                    <div class="card-actions">
                        <div class="btn-list">
                            @foreach ($preset as $key => $p)
                                <a class="btn btn-sm btn-outline-secondary"
                                    href="{{ route('admin.report-analytics.index', ['start_date' => $p['start'], 'end_date' => $p['end']]) }}">
                                    {{ $p['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.report-analytics.index') }}" method="GET"
                        class="row g-2 align-items-end">
                        <div class="col-12 col-md-4 col-lg-3">
                            <label class="form-label">From Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}"
                                required>
                        </div>
                        <div class="col-12 col-md-4 col-lg-3">
                            <label class="form-label">To Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
                        </div>
                        <div class="col-12 col-md-auto">
                            <button type="submit" class="btn btn-primary w-100">
                                Apply
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary cards --}}
            <div class="row row-deck row-cards mb-3">
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="subheader">Total Sessions</div>
                            <div class="h1 mb-0">{{ number_format($totalSessions) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="subheader">Total Revenue</div>
                            <div class="h2 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="subheader">Successful Transactions</div>
                            <div class="h1 mb-0">{{ number_format($successfulPayments) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="subheader">Avg. Revenue per Session</div>
                            <div class="h2 mb-0">Rp {{ number_format($avgRevenuePerSession, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="row row-deck row-cards mb-3">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Sessions per Day</h3>
                        </div>
                        <div class="card-body">
                            <div id="chart-sessions" class="chart-sm"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Revenue per Day</h3>
                        </div>
                        <div class="card-body">
                            <div id="chart-revenue" class="chart-sm"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Payment status & Session status --}}
            <div class="row row-deck row-cards mb-3">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Payment Status</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th class="text-end">Count</th>
                                        <th class="text-end">Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($paymentStatusBreakdown->sortKeys() as $status => $row)
                                        @php($pStatus = strtolower($status))
                                        <tr class="border-bottom-0">
                                            <td>
                                                <span
                                                    class="badge {{ $paymentBadge[$pStatus] ?? 'bg-secondary-lt' }} text-uppercase">{{ $status }}</span>
                                            </td>
                                            <td class="text-end">{{ number_format($row->total) }}</td>
                                            <td class="text-end">
                                                Rp {{ number_format($row->total_amount ?? 0, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Session Status</h3>
                        </div>
                        <div class="card-body">
                            @forelse ($sessionStatusBreakdown as $status => $total)
                                @php($sStatus = strtolower($status))
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted text-uppercase">{{ $status }}</span>
                                    <span
                                        class="badge {{ $sessionBadge[$sStatus] ?? 'bg-blue-lt' }}">{{ number_format($total) }}</span>
                                </div>
                            @empty
                                <div class="text-muted">No data</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top machines & Top templates --}}
            <div class="row row-deck row-cards">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Top Machines (by Sessions)</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Machine</th>
                                        <th class="text-end">Sessions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topMachinesData as $index => $item)
                                        <tr class="border-bottom-0">
                                            <td class="border-bottom">{{ $index + 1 }}</td>
                                            <td class="border-bottom">{{ $item['name'] }}</td>
                                            <td class="text-end border-bottom">{{ number_format($item['session_count']) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Top Templates (by Usage)</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Template</th>
                                        <th class="text-end">Usage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topTemplatesData as $index => $item)
                                        <tr class="border-bottom-0">
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item['name'] }}</td>
                                            <td class="text-end">
                                                {{ number_format($item['usage_count']) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No data</td>
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const chartLabels = @json($dateRange->pluck('label'));
            const sessionsData = @json($dateRange->pluck('total'));
            const revenueData = @json($revenueChartData->values());

            if (document.getElementById('chart-sessions') && typeof ApexCharts !== 'undefined') {
                new ApexCharts(document.getElementById('chart-sessions'), {
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
                        data: sessionsData
                    }],
                    xaxis: {
                        categories: chartLabels,
                        labels: {
                            rotate: -35,
                            hideOverlappingLabels: true
                        }
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

            if (document.getElementById('chart-revenue') && typeof ApexCharts !== 'undefined') {
                new ApexCharts(document.getElementById('chart-revenue'), {
                    chart: {
                        type: 'bar',
                        height: 280,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            borderRadius: 6,
                            columnWidth: '60%'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    grid: {
                        strokeDashArray: 4
                    },
                    series: [{
                        name: 'Revenue (Rp)',
                        data: revenueData
                    }],
                    xaxis: {
                        categories: chartLabels,
                        labels: {
                            rotate: -35,
                            hideOverlappingLabels: true
                        }
                    },
                    yaxis: {
                        labels: {
                            formatter: function(v) {
                                return new Intl.NumberFormat('id-ID').format(v);
                            }
                        }
                    },
                    colors: ['#2fb344'],
                    tooltip: {
                        y: {
                            formatter: function(v) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(v);
                            }
                        }
                    }
                }).render();
            }
        });
    </script>
@endsection
