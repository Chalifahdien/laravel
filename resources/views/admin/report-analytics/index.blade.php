@extends('layouts.app')

@section('content')
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

        $isPresetActive = function ($p) use ($startDate, $endDate) {
            return $p['start'] === $startDate && $p['end'] === $endDate;
        };
    @endphp

    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">Analytics</div>
                    <h2 class="page-title mb-1">Report Analytics</h2>
                    <div class="text-muted small">
                        Period <strong>{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }}</strong> to
                        <strong>{{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</strong>
                        <span class="mx-1">·</span>
                        Generated {{ now()->format('d M Y H:i') }}
                    </div>
                </div>
                <div class="col-auto d-print-none">
                    <a href="{{ route('admin.report-analytics.index') }}" class="btn btn-outline-secondary me-1">Reset</a>
                    <button type="button" class="btn btn-indigo" onclick="window.print()">Print</button>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body report-analytics">
        <div class="container-xl">
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

            {{-- Stat cards --}}
            <div class="row row-deck">
                <div class="col-6 col-xl-3">
                    <div class="card mb-3">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <span class="bg-blue-lt text-blue avatar me-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M3 12h4l3 8l4 -16l3 8h4" />
                                    </svg>
                                </span>
                                <div>
                                    <div class="subheader">Total Sessions</div>
                                    <div class="h1 mb-0">{{ number_format($totalSessions) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card mb-3">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <span class="bg-green-lt text-green avatar me-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon m-0">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M4 6a8 3 0 1 0 16 0a8 3 0 1 0 -16 0" />
                                        <path d="M4 6v6a8 3 0 0 0 16 0v-6" />
                                        <path d="M4 12v6a8 3 0 0 0 16 0v-6" />
                                    </svg>
                                </span>
                                <div>
                                    <div class="subheader">Total Revenue</div>
                                    <div class="h1 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card mb-3">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <span class="bg-cyan-lt text-cyan avatar me-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon m-0">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                </span>
                                <div>
                                    <div class="subheader">Successful Transactions</div>
                                    <div class="h1 mb-0">{{ number_format($successfulPayments) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-xl-3">
                    <div class="card mb-3">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <span class="bg-purple-lt text-purple avatar me-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="icon m-0">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                        <path
                                            d="M3 13a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v6a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1l0 -6" />
                                        <path
                                            d="M9 9a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1l0 -10" />
                                        <path
                                            d="M15 5a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v14a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1l0 -14" />
                                        <path d="M4 20h14" />
                                    </svg>
                                </span>
                                <div>
                                    <div class="subheader">Avg per Session</div>
                                    <div class="h1 mb-0">Rp {{ number_format($avgRevenuePerSession, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Charts --}}
            <div class="row row-deck">
                <div class="col-lg-6">
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Sessions per Day</h3>
                        </div>
                        <div class="card-body">
                            <div id="chart-sessions" class="chart-sm"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-3">
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
            <div class="row row-deck">
                <div class="col-lg-6">
                    <div class="card mb-3">
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
                                        <tr class="border-bottom-0">
                                            <td colspan="3" class="text-center text-muted py-4">No data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-3">
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
            <div class="row row-deck">
                <div class="col-lg-6">
                    <div class="card mb-3">
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
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item['name'] }}</td>
                                            <td class="text-end">{{ number_format($item['session_count']) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="border-bottom-0">
                                            <td colspan="3" class="text-center text-muted py-4">No data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-3">
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
                                        <tr class="border-bottom-0">
                                            <td colspan="3" class="text-center text-muted py-4">No data</td>
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

            const chartDefaults = {
                chart: {
                    fontFamily: 'inherit'
                },
                grid: {
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: 'light'
                }
            };

            if (document.getElementById('chart-sessions') && typeof ApexCharts !== 'undefined') {
                new ApexCharts(document.getElementById('chart-sessions'), {
                    ...chartDefaults,
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
                            opacityFrom: 0.4,
                            opacityTo: 0.05
                        }
                    },
                    tooltip: {
                        ...chartDefaults.tooltip,
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
                    ...chartDefaults,
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
                            columnWidth: '65%'
                        }
                    },
                    dataLabels: {
                        enabled: false
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
                                return v >= 1e6 ? (v / 1e6) + 'M' : new Intl.NumberFormat('en-US')
                                    .format(v);
                            }
                        }
                    },
                    colors: ['#2fb344'],
                    tooltip: {
                        ...chartDefaults.tooltip,
                        y: {
                            formatter: function(v) {
                                return 'Rp ' + new Intl.NumberFormat('en-US').format(v);
                            }
                        }
                    }
                }).render();
            }
        });
    </script>
@endsection
