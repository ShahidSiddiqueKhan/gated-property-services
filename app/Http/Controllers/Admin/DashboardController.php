<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ContactSubmission;
use App\Models\MaintenanceRequest;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Property;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalClients = User::where('role', 'client')->count();
        $totalProperties = Property::count();
        $occupied = Property::where('status', 'occupied')->count();
        $vacant = Property::where('status', 'vacant')->count();
        $underMaintenance = Property::where('status', 'maintenance')->count();
        $occupancyRate = $totalProperties > 0 ? round(($occupied / $totalProperties) * 100) : 0;
        $pendingApprovals = Property::where('status', 'pending_review')->count();

        $revenueThisMonth = Payment::where('status', 'paid')
            ->whereMonth('paid_date', now()->month)
            ->whereYear('paid_date', now()->year)
            ->sum('amount');

        $pendingPayments = Payment::whereIn('status', ['due', 'overdue', 'pending_review'])->count();
        $pendingPaymentsAmount = Payment::whereIn('status', ['due', 'overdue', 'pending_review'])->sum('amount');

        $openMaintenance = MaintenanceRequest::whereIn('status', ['submitted', 'acknowledged', 'in_progress'])->count();
        $emergencyMaintenance = MaintenanceRequest::where('priority', 'emergency')->whereIn('status', ['submitted', 'acknowledged', 'in_progress'])->count();

        $newLeads = ContactSubmission::where('is_handled', false)->count();
        $newMessages = Message::where('sender', 'client')->where('created_at', '>=', now()->subDays(7))->count();

        $monthlyRevenue = collect(range(5, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            $total = Payment::where('status', 'paid')
                ->whereMonth('paid_date', $date->month)
                ->whereYear('paid_date', $date->year)
                ->sum('amount');

            return ['label' => $date->format('M'), 'total' => (float) $total];
        });

        $recentActivity = AuditLog::with('user')->latest()->take(8)->get();

        return view('admin.dashboard', compact(
            'totalClients', 'totalProperties', 'occupied', 'vacant', 'underMaintenance', 'occupancyRate', 'pendingApprovals',
            'revenueThisMonth', 'pendingPayments', 'pendingPaymentsAmount',
            'openMaintenance', 'emergencyMaintenance', 'newLeads', 'newMessages',
            'monthlyRevenue', 'recentActivity',
        ));
    }
}
