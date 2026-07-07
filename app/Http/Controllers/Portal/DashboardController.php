<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use App\Models\Message;
use App\Models\Payment;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $properties = Property::where('user_id', $user->id)->with('coverImage')->latest()->get();

        $totalProperties = $properties->count();
        $occupied = $properties->where('status', 'occupied')->count();
        $vacant = $properties->where('status', 'vacant')->count();

        $monthlyRent = Payment::where('user_id', $user->id)
            ->where('type', 'rent')
            ->whereMonth('due_date', now()->month)
            ->whereYear('due_date', now()->year)
            ->sum('amount');

        $thisMonthEarning = Payment::where('user_id', $user->id)
            ->where('status', 'paid')
            ->whereMonth('paid_date', now()->month)
            ->whereYear('paid_date', now()->year)
            ->sum('amount');

        $rentReceived = $thisMonthEarning;

        $invoicesDue = Payment::where('user_id', $user->id)->whereIn('status', ['due', 'overdue'])->count();

        $maintenanceInProgress = MaintenanceRequest::where('user_id', $user->id)
            ->whereIn('status', ['submitted', 'acknowledged', 'in_progress'])
            ->count();

        $unreadMessages = Message::where('user_id', $user->id)->where('sender', 'staff')->where('is_read', false)->count();

        // Monthly overview for the last 6 months
        $monthlyOverview = collect(range(5, 0))->map(function ($monthsAgo) use ($user) {
            $date = now()->subMonths($monthsAgo);
            $total = Payment::where('user_id', $user->id)
                ->where('status', 'paid')
                ->whereMonth('paid_date', $date->month)
                ->whereYear('paid_date', $date->year)
                ->sum('amount');

            return [
                'label' => $date->format('M'),
                'total' => (float) $total,
            ];
        });

        $recentActivities = collect()
            ->concat(
                Payment::where('user_id', $user->id)->where('status', 'paid')->latest('paid_date')->take(3)->get()
                    ->map(fn ($p) => [
                        'icon' => 'banknotes',
                        'title' => "Rent Received - PKR " . number_format($p->amount),
                        'time' => $p->paid_date,
                    ])
            )
            ->concat(
                MaintenanceRequest::where('user_id', $user->id)->where('status', 'completed')->latest('completed_at')->take(2)->get()
                    ->map(fn ($m) => [
                        'icon' => 'wrench-screwdriver',
                        'title' => "Maintenance Completed - {$m->title}",
                        'time' => $m->completed_at,
                    ])
            )
            ->sortByDesc('time')
            ->take(5);

        return view('portal.dashboard', compact(
            'properties',
            'totalProperties',
            'occupied',
            'vacant',
            'monthlyRent',
            'thisMonthEarning',
            'rentReceived',
            'invoicesDue',
            'maintenanceInProgress',
            'unreadMessages',
            'monthlyOverview',
            'recentActivities',
        ));
    }
}
