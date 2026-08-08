<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class FinanceController extends Controller
{
    /**
     * Property-isolated financial dashboard. Every owned property gets its
     * own summary card — rent collected vs. GATED's commission, maintenance
     * contractor costs vs. GATED's fee, renovation spend, tenant placement,
     * advertising and emergency charges, package billing, and outstanding
     * balance — so a multi-property client never sees figures blended
     * across properties.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $properties = Property::where('user_id', $user->id)
            ->with(['activePackage.package', 'payments' => fn ($q) => $q->latest('due_date')])
            ->orderBy('title')
            ->get();

        $selectedId = $request->integer('property') ?: $properties->first()?->id;
        $selectedProperty = $properties->firstWhere('id', $selectedId);

        $summaries = $properties->mapWithKeys(fn ($property) => [$property->id => $this->summarize($property->payments)]);

        $overall = $this->summarize(Payment::where('user_id', $user->id)->get());

        return view('portal.finances.index', compact('properties', 'selectedProperty', 'summaries', 'overall'));
    }

    /**
     * @param  Collection<int, Payment>  $payments
     */
    protected function summarize(Collection $payments): array
    {
        $byStream = $payments->groupBy(fn ($p) => $p->revenue_stream ?? $p->type);

        $sumPaid = fn (Collection $items) => (float) $items->where('status', 'paid')->sum('amount');
        $sumAll = fn (Collection $items) => (float) $items->sum('amount');

        $rent = $byStream->get(Payment::STREAM_RENT_COMMISSION, collect());
        $maintenance = $byStream->get(Payment::STREAM_MAINTENANCE_FEE, collect());
        $renovation = $byStream->get(Payment::STREAM_RENOVATION_FEE, collect());
        $placement = $byStream->get(Payment::STREAM_TENANT_PLACEMENT, collect());
        $advertising = $byStream->get(Payment::STREAM_ADVERTISING, collect());
        $emergency = $byStream->get(Payment::STREAM_EMERGENCY_SERVICE, collect());
        $packageFees = $byStream->get(Payment::STREAM_PACKAGE_FEE, collect());

        return [
            'total_paid' => $sumPaid($payments),
            'total_outstanding' => (float) $payments->whereIn('status', ['due', 'overdue', 'pending_review'])->sum('amount'),
            'rent_collected' => (float) $rent->sum('base_amount'),
            'rent_commission' => (float) $rent->sum(fn ($p) => $p->amount - $p->owner_amount),
            'owner_net_from_rent' => (float) $rent->sum('owner_amount'),
            'maintenance_contractor_cost' => (float) $maintenance->sum('base_amount'),
            'maintenance_gated_fee' => (float) $maintenance->sum(fn ($p) => $p->amount - $p->base_amount),
            'renovation_project_value' => (float) $renovation->sum('base_amount'),
            'renovation_gated_fee' => (float) $renovation->sum(fn ($p) => $p->amount - $p->base_amount),
            'tenant_placement_fees' => $sumAll($placement),
            'advertising_fees' => $sumAll($advertising),
            'emergency_fees' => $sumAll($emergency),
            'package_fees' => $sumAll($packageFees),
            'payments' => $payments->sortByDesc('due_date')->values(),
        ];
    }
}
