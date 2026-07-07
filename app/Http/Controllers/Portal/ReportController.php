<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $months = collect(range(11, 0))->map(function ($monthsAgo) use ($user) {
            $date = now()->subMonths($monthsAgo);
            $income = Payment::where('user_id', $user->id)
                ->where('status', 'paid')
                ->whereMonth('paid_date', $date->month)
                ->whereYear('paid_date', $date->year)
                ->sum('amount');

            return [
                'label' => $date->format('M Y'),
                'income' => (float) $income,
            ];
        });

        $properties = Property::where('user_id', $user->id)->withSum(['payments as paid_total' => function ($q) {
            $q->where('status', 'paid');
        }], 'amount')->get();

        $totalIncomeYear = Payment::where('user_id', $user->id)->where('status', 'paid')->whereYear('paid_date', now()->year)->sum('amount');
        $totalDue = Payment::where('user_id', $user->id)->whereIn('status', ['due', 'overdue'])->sum('amount');

        return view('portal.reports.index', compact('months', 'properties', 'totalIncomeYear', 'totalDue'));
    }
}
