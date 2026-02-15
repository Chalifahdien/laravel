<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotoSession;
use App\Models\Payment;
use App\Models\Machine;
use App\Models\Template;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportAnalyticController extends Controller
{
    public function index(Request $request)
    {
        // Default: last 30 days
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // =====================
        // SUMMARY CARDS
        // =====================
        $totalSessions = PhotoSession::whereBetween('created_at', [$start, $end])->count();

        $totalRevenue = PhotoSession::with(['payment', 'finalImage', 'machine'])
            ->whereBetween('created_at', [$start, $end])
            ->has('finalImage')
            ->whereHas('payment', function ($q) {
                $q->where('transaction_status', 'PAID');
            })
            ->get()
            ->reduce(function ($carry, $session) {
                $paymentAmount = $session->payment->amount ?? 0;

                if ($paymentAmount == 0) {
                    return $carry + 0;
                }

                $printQty = $session->finalImage->print_quantity ?? 1;
                $additionalPrints = max(0, $printQty - 1);
                $additionalCost = $session->machine->additional_print_cost ?? 0;

                return $carry + $paymentAmount + ($additionalPrints * $additionalCost);
            }, 0);

        $successfulPayments = PhotoSession::whereBetween('created_at', [$start, $end])
            ->has('finalImage')
            ->whereHas('payment', function ($q) {
                $q->where('transaction_status', 'PAID');
            })
            ->count();

        $avgRevenuePerSession = $totalSessions > 0
            ? round($totalRevenue / $totalSessions, 0)
            : 0;

        // =====================
        // PAYMENT STATUS BREAKDOWN
        // =====================
        $paymentStatusBreakdown = Payment::whereBetween('created_at', [$start, $end])
            ->selectRaw('transaction_status, COUNT(*) as total, COALESCE(SUM(amount), 0) as total_amount')
            ->groupBy('transaction_status')
            ->get()
            ->keyBy('transaction_status');

        // =====================
        // SESSION STATUS BREAKDOWN
        // =====================
        $sessionStatusBreakdown = PhotoSession::whereBetween('created_at', [$start, $end])
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // =====================
        // SESSIONS PER DAY (untuk chart)
        // =====================
        $sessionsPerDay = PhotoSession::whereBetween('created_at', [$start, $end])
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Isi tanggal kosong dengan 0
        $dateRange = collect();
        for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
            $dateStr = $d->format('Y-m-d');
            $total = $sessionsPerDay->firstWhere('date', $dateStr)?->total ?? 0;
            $dateRange->push(['date' => $dateStr, 'label' => $d->format('d M'), 'total' => $total]);
        }

        // =====================
        // REVENUE PER DAY (untuk chart)
        // =====================
        $revenuePerDayData = PhotoSession::with(['payment', 'finalImage', 'machine'])
            ->whereBetween('created_at', [$start, $end])
            ->has('finalImage')
            ->whereHas('payment', function ($q) {
                $q->where('transaction_status', 'PAID');
            })
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('Y-m-d');
            });

        $revenueChartData = $dateRange->map(function ($row) use ($revenuePerDayData) {
            $sessions = $revenuePerDayData->get($row['date']);

            if (!$sessions) {
                return 0;
            }

            return $sessions->reduce(function ($carry, $session) {
                $paymentAmount = $session->payment->amount ?? 0;

                if ($paymentAmount == 0) {
                    return $carry + 0;
                }

                $printQty = $session->finalImage->print_quantity ?? 1;
                $additionalPrints = max(0, $printQty - 1);
                $additionalCost = $session->machine->additional_print_cost ?? 0;

                return $carry + $paymentAmount + ($additionalPrints * $additionalCost);
            }, 0);
        });

        // =====================
        // TOP MACHINES BY SESSIONS
        // =====================
        $topMachines = PhotoSession::whereBetween('created_at', [$start, $end])
            ->whereNotNull('machine_id')
            ->selectRaw('machine_id, COUNT(*) as session_count')
            ->groupBy('machine_id')
            ->orderByDesc('session_count')
            ->limit(10)
            ->get();

        $machineIds = $topMachines->pluck('machine_id')->unique()->filter();
        $machines = Machine::whereIn('id', $machineIds)->get()->keyBy('id');

        $topMachinesData = $topMachines->map(function ($row) use ($machines) {
            return [
                'name' => $machines->get($row->machine_id)?->name ?? 'Unknown',
                'session_count' => $row->session_count,
            ];
        });

        // =====================
        // TOP TEMPLATES BY USAGE
        // =====================
        $topTemplates = PhotoSession::whereBetween('created_at', [$start, $end])
            ->whereNotNull('template_id')
            ->selectRaw('template_id, COUNT(*) as usage_count')
            ->groupBy('template_id')
            ->orderByDesc('usage_count')
            ->limit(10)
            ->get();

        $templateIds = $topTemplates->pluck('template_id')->unique()->filter();
        $templates = Template::whereIn('id', $templateIds)->get()->keyBy('id');

        $topTemplatesData = $topTemplates->map(function ($row) use ($templates) {
            return [
                'name' => $templates->get($row->template_id)?->name ?? 'Unknown',
                'usage_count' => $row->usage_count,
            ];
        });

        return view('admin.report-analytics.index', compact(
            'startDate',
            'endDate',
            'totalSessions',
            'totalRevenue',
            'successfulPayments',
            'avgRevenuePerSession',
            'paymentStatusBreakdown',
            'sessionStatusBreakdown',
            'dateRange',
            'sessionsPerDay',
            'revenueChartData',
            'topMachinesData',
            'topTemplatesData'
        ));
    }
}
