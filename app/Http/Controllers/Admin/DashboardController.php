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
        $totalRevenue = Payment::where('transaction_status', 'success')
            ->sum('amount');

        $todayRevenue = Payment::where('transaction_status', 'success')
            ->whereDate('created_at', today())
            ->sum('amount');

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
