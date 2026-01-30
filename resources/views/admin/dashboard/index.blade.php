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
            </div>
        </div>
    </div>

    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">

            {{-- =====================
                STAT CARDS
            ===================== --}}
            <div class="row row-deck row-cards">

                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="subheader">Total Sessions</div>
                            <div class="h1 mb-0">{{ $totalSessions }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="subheader">Today Sessions</div>
                            <div class="h1 mb-0">{{ $todaySessions }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="subheader">Active Machines</div>
                            <div class="h1 mb-0">{{ $activeMachines }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="subheader">Active Templates</div>
                            <div class="h1 mb-0">{{ $activeTemplates }}</div>
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
                            <div class="subheader">Total Revenue</div>
                            <div class="h1">
                                Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card card-sm">
                        <div class="card-body">
                            <div class="subheader">Today Revenue</div>
                            <div class="h1">
                                Rp {{ number_format($todayRevenue, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- =====================
                SESSION STATUS
            ===================== --}}
            <div class="row row-deck row-cards mt-3">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Session Status</h3>
                        </div>
                        <div class="card-body">
                            @forelse ($sessionStatus as $status => $total)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted text-uppercase">
                                        {{ $status }}
                                    </span>
                                    <span class="fw-bold">{{ $total }}</span>
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
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Latest Transactions</h3>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-vcenter table-bordered card-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Session</th>
                                        <th>Machine</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Final Image</th>
                                        <th>Created</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($latestSessions as $session)
                                        <tr style="cursor:pointer"
                                            onclick="window.location='{{ route('transactions.show', $session) }}'">
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="fw-semibold">#{{ $session->id }}</td>
                                            <td>{{ $session->machine->name ?? '-' }}</td>
                                            <td>
                                                @if ($session->payment)
                                                    <div class="fw-semibold">
                                                        Rp {{ number_format($session->payment->amount, 0, ',', '.') }}
                                                    </div>
                                                    <span class="badge bg-blue-lt mt-2">
                                                        {{ strtoupper($session->payment->transaction_status) }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-green-lt">
                                                    {{ strtoupper($session->status) }}
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
                                            <td colspan="7" class="text-center text-muted">
                                                No recent transactions
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="card-footer text-end">
                            <a href="{{ route('transactions.index') }}" class="btn btn-outline-primary">
                                View all transactions
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
