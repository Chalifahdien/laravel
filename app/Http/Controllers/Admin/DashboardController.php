<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhotoSession;
use App\Models\Machine;
use App\Models\Template;
use App\Models\Payment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // =====================
        // BASIC STATS
        // =====================
        $totalSessions = PhotoSession::count();

        $todaySessions = PhotoSession::whereDate('created_at', today())->count();

        $activeMachines = Machine::where('is_active', true)->count();

        $activeTemplates = Template::where('is_active', true)->count();

        // =====================
        // REVENUE
        // =====================
        // =====================
        // REVENUE
        // =====================
        // Calculate Total Revenue
        $totalSessionsData = PhotoSession::with(['payment', 'finalImage', 'machine'])
            ->whereHas('payment', function ($q) {
                $q->where('transaction_status', 'success');
            })
            ->get();

        $totalRevenue = $totalSessionsData->reduce(function ($carry, $session) {
            $paymentAmount = $session->payment->amount ?? 0;

            // If payment is 0 (Free Mode), total check is 0
            if ($paymentAmount == 0) {
                return $carry + 0;
            }

            $printQty = $session->finalImage->print_quantity ?? 1;
            $additionalPrints = max(0, $printQty - 1);
            $additionalCost = $session->machine->additional_print_cost ?? 0;

            return $carry + $paymentAmount + ($additionalPrints * $additionalCost);
        }, 0);

        // Calculate Today Revenue
        $todaySessionsData = PhotoSession::with(['payment', 'finalImage', 'machine'])
            ->whereHas('payment', function ($q) {
                $q->where('transaction_status', 'success')
                    ->whereDate('created_at', today());
            })
            ->get();

        $todayRevenue = $todaySessionsData->reduce(function ($carry, $session) {
            $paymentAmount = $session->payment->amount ?? 0;

            // If payment is 0 (Free Mode), total check is 0
            if ($paymentAmount == 0) {
                return $carry + 0;
            }

            $printQty = $session->finalImage->print_quantity ?? 1;
            $additionalPrints = max(0, $printQty - 1);
            $additionalCost = $session->machine->additional_print_cost ?? 0;

            return $carry + $paymentAmount + ($additionalPrints * $additionalCost);
        }, 0);

        // =====================
        // SESSION STATUS COUNT
        // =====================
        $sessionStatus = PhotoSession::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // =====================
        // LATEST TRANSACTIONS
        // =====================
        $latestSessions = PhotoSession::with([
            'machine',
            'payment',
            'template',
            'finalImage'
        ])
            ->latest()
            ->limit(5)
            ->get();

        // =====================
        // SESSION PER DAY (7 days)
        // =====================
        $sessionsPerDay = PhotoSession::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.dashboard.index', compact(
            'totalSessions',
            'todaySessions',
            'activeMachines',
            'activeTemplates',
            'totalRevenue',
            'todayRevenue',
            'sessionStatus',
            'latestSessions',
            'sessionsPerDay'
        ));
    }
}
