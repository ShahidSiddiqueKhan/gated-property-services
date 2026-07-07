<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use App\Models\Payment;
use App\Models\Property;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $months = collect(range(11, 0))->map(function ($monthsAgo) {
            $date = now()->subMonths($monthsAgo);
            $income = Payment::where('status', 'paid')
                ->whereMonth('paid_date', $date->month)
                ->whereYear('paid_date', $date->year)
                ->sum('amount');

            return ['label' => $date->format('M Y'), 'income' => (float) $income];
        });

        $totalIncomeYear = Payment::where('status', 'paid')->whereYear('paid_date', now()->year)->sum('amount');
        $totalOutstanding = Payment::whereIn('status', ['due', 'overdue', 'pending_review'])->sum('amount');

        $occupancyRate = Property::count() > 0
            ? round(Property::where('status', 'occupied')->count() / Property::count() * 100)
            : 0;

        $propertiesByCity = Property::selectRaw('city, count(*) as total')->groupBy('city')->orderByDesc('total')->get();

        $topProperties = Property::withSum(['payments as paid_total' => function ($q) {
            $q->where('status', 'paid');
        }], 'amount')->orderByDesc('paid_total')->take(8)->get();

        $maintenanceByCategory = MaintenanceRequest::selectRaw('category, count(*) as total')->groupBy('category')->get();

        return view('admin.reports.index', compact(
            'months', 'totalIncomeYear', 'totalOutstanding', 'occupancyRate',
            'propertiesByCity', 'topProperties', 'maintenanceByCategory',
        ));
    }
}
