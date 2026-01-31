@extends('layouts.app')

@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">Report</div>
                    <h2 class="page-title">Report Analytics</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">

            {{-- Period filter --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h3 class="card-title">Period Filter</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.report-analytics.index') }}" method="GET"
                        class="row g-2 align-items-end">
                        <div class="col-auto">
                            <label class="form-label">From Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}"
                                required>
                        </div>
                        <div class="col-auto">
                            <label class="form-label">To Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}" required>
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Summary cards --}}
            <div class="row row-deck row-cards mb-3">
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="subheader">Total Sessions</div>
                            <div class="h1 mb-0">{{ number_format($totalSessions) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="subheader">Total Revenue</div>
                            <div class="h2 mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="subheader">Successful Transactions</div>
                            <div class="h1 mb-0">{{ number_format($successfulPayments) }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card">
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
                            <table class="table table-vcenter table-bordered card-table">
                                <thead>
                                    <tr>
                                        <th>Status</th>
                                        <th class="text-end">Count</th>
                                        <th class="text-end">Total Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($paymentStatusBreakdown as $status => $row)
                                        <tr class="border-bottom-0">
                                            <td>
                                                <span class="badge bg-blue-lt text-uppercase">{{ $status }}</span>
                                            </td>
                                            <td class="text-end">{{ number_format($row->total) }}</td>
                                            <td class="text-end">Rp
                                                {{ number_format($row->total_amount ?? 0, 0, ',', '.') }}</td>
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
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted text-uppercase">{{ $status }}</span>
                                    <span class="fw-bold">{{ number_format($total) }}</span>
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
                            <table class="table table-vcenter table-bordered card-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Machine</th>
                                        <th class="text-end">Sessions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($topMachinesData as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item['name'] }}</td>
                                            <td class="text-end">{{ number_format($item['session_count']) }}</td>
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
                            <table class="table table-vcenter table-bordered card-table">
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
                                            <td colspan="3" class="text-center text-muted border-bottom-0">No data</td>
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
                    series: [{
                        name: 'Sessions',
                        data: sessionsData
                    }],
                    xaxis: {
                        categories: chartLabels,
                        labels: {
                            rotate: -45
                        }
                    },
                    colors: ['#206bc4'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            opacityFrom: 0.4,
                            opacityTo: 0.1
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
                            borderRadius: 4,
                            columnWidth: '60%'
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
                            rotate: -45
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
