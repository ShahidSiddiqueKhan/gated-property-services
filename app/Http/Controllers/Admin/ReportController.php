<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use App\Models\Payment;
use App\Models\Property;
use App\Models\PropertyPackage;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function index(): View
    {
        $paidPayments = Payment::where('status', 'paid')->with('property', 'user')->get();

        $months = collect(range(11, 0))->map(function ($monthsAgo) use ($paidPayments) {
            $date = now()->subMonths($monthsAgo);
            $revenue = $this->gatedRevenue($paidPayments->filter(
                fn ($p) => $p->paid_date && $p->paid_date->month === $date->month && $p->paid_date->year === $date->year
            ));

            return ['label' => $date->format('M Y'), 'income' => $revenue];
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

        // ------------------------------------------------------------------
        // GATED's actual earned revenue — separated by stream, property, and
        // client. Unlike "amount", this excludes contractor/rent pass-through
        // (only the commission/fee portion counts as GATED revenue).
        // ------------------------------------------------------------------
        $totalRevenue = $this->gatedRevenue($paidPayments);

        $revenueByStream = collect(Payment::streamLabels())->mapWithKeys(function ($label, $stream) use ($paidPayments) {
            return [$stream => ['label' => $label, 'amount' => $this->gatedRevenue($paidPayments->where('revenue_stream', $stream))]];
        })->filter(fn ($row) => $row['amount'] > 0)->sortByDesc('amount');

        $revenueByProperty = $paidPayments->whereNotNull('property_id')->groupBy('property_id')
            ->map(fn ($items) => ['property' => $items->first()->property, 'amount' => $this->gatedRevenue($items)])
            ->filter(fn ($row) => $row['property'] && $row['amount'] > 0)
            ->sortByDesc('amount')->take(10)->values();

        $revenueByClient = $paidPayments->whereNotNull('user_id')->groupBy('user_id')
            ->map(fn ($items) => ['client' => $items->first()->user, 'amount' => $this->gatedRevenue($items)])
            ->filter(fn ($row) => $row['client'] && $row['amount'] > 0)
            ->sortByDesc('amount')->take(10)->values();

        $now = now();
        $quarterStart = $now->copy()->firstOfQuarter();
        $revenueThisQuarter = $this->gatedRevenue($paidPayments->filter(fn ($p) => $p->paid_date && $p->paid_date->gte($quarterStart)));
        $revenueThisYear = $this->gatedRevenue($paidPayments->filter(fn ($p) => $p->paid_date && $p->paid_date->year === $now->year));
        $revenueThisMonth = $this->gatedRevenue($paidPayments->filter(fn ($p) => $p->paid_date && $p->paid_date->month === $now->month && $p->paid_date->year === $now->year));

        $upcomingRenewals = PropertyPackage::with('property', 'package')->renewingWithin(30)->orderBy('renews_at')->get();

        return view('admin.reports.index', compact(
            'months', 'totalIncomeYear', 'totalOutstanding', 'occupancyRate',
            'propertiesByCity', 'topProperties', 'maintenanceByCategory',
            'totalRevenue', 'revenueByStream', 'revenueByProperty', 'revenueByClient',
            'revenueThisMonth', 'revenueThisQuarter', 'revenueThisYear', 'upcomingRenewals',
        ));
    }

    /**
     * GATED's actual earned revenue from a set of paid payments. Rent and
     * maintenance/renovation payments carry pass-through amounts (rent owed
     * to owners, contractor invoices) — only the commission/fee slice counts
     * here. Package fees, tenant placement, advertising, and emergency
     * charges are 100% GATED revenue.
     *
     * @param  Collection<int, Payment>  $payments
     */
    protected function gatedRevenue(Collection $payments): float
    {
        return (float) $payments->sum(function (Payment $payment) {
            return match ($payment->revenue_stream) {
                Payment::STREAM_RENT_COMMISSION => (float) $payment->amount - (float) ($payment->owner_amount ?? 0),
                Payment::STREAM_MAINTENANCE_FEE, Payment::STREAM_RENOVATION_FEE => (float) $payment->amount - (float) ($payment->base_amount ?? 0),
                default => (float) $payment->amount,
            };
        });
    }
}
