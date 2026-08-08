<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\Property;
use App\Services\Billing\FeeCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LeaseController extends Controller
{
    public function __construct(protected FeeCalculator $feeCalculator)
    {
    }

    public function index(Request $request): View
    {
        $query = Lease::with('property');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $leases = $query->latest()->paginate(15)->withQueryString();

        return view('admin.leases.index', compact('leases'));
    }

    public function create(): View
    {
        $properties = Property::orderBy('title')->get();

        return view('admin.leases.create', compact('properties'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateLease($request);
        $placementFeePercent = $request->input('placement_fee_percent');

        $lease = Lease::create($validated);

        // Reflect an active lease on the property's occupancy status.
        if ($lease->status === 'active') {
            $lease->property->update(['status' => 'occupied']);
        }

        AuditLog::record($request->user(), 'Created lease', $lease, "Created lease for {$lease->tenant_name} on {$lease->property->title}");

        $message = 'Lease created.';

        // Tenant placement fee — standard 50-100% of one month's rent,
        // exact % agreed per client and set by admin at placement time.
        if ($request->boolean('charge_placement_fee') && $placementFeePercent) {
            $fee = $this->feeCalculator->tenantPlacementFee((float) $lease->rent_amount, (float) $placementFeePercent);

            Payment::create([
                'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
                'user_id' => $lease->property->user_id,
                'property_id' => $lease->property_id,
                'lease_id' => $lease->id,
                'type' => 'service',
                'revenue_stream' => Payment::STREAM_TENANT_PLACEMENT,
                'amount' => $fee['fee_amount'],
                'base_amount' => $fee['monthly_rent'],
                'fee_percent' => $fee['fee_percent'],
                'status' => 'due',
                'due_date' => now()->addDays(7),
                'notes' => "Tenant placement fee — {$lease->tenant_name}",
            ]);

            $message = 'Lease created and tenant placement fee of PKR ' . number_format($fee['fee_amount'], 2) . ' invoiced.';
        }

        return redirect()->route('admin.leases.index')->with('success', $message);
    }

    public function edit(Lease $lease): View
    {
        $properties = Property::orderBy('title')->get();

        return view('admin.leases.edit', compact('lease', 'properties'));
    }

    public function update(Request $request, Lease $lease): RedirectResponse
    {
        $validated = $this->validateLease($request);

        $lease->update($validated);

        if ($lease->status === 'active') {
            $lease->property->update(['status' => 'occupied']);
        } elseif ($lease->status === 'ended' && $lease->property->status === 'occupied') {
            $lease->property->update(['status' => 'vacant']);
        }

        AuditLog::record($request->user(), 'Updated lease', $lease, "Updated lease for {$lease->tenant_name}");

        return redirect()->route('admin.leases.index')->with('success', 'Lease updated.');
    }

    public function destroy(Request $request, Lease $lease): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted lease', null, "Deleted lease for {$lease->tenant_name}");

        $lease->delete();

        return back()->with('success', 'Lease deleted.');
    }

    protected function validateLease(Request $request): array
    {
        return $request->validate([
            'property_id' => ['required', 'exists:properties,id'],
            'tenant_name' => ['required', 'string', 'max:255'],
            'tenant_email' => ['nullable', 'email', 'max:255'],
            'tenant_phone' => ['nullable', 'string', 'max:30'],
            'rent_amount' => ['required', 'numeric', 'min:0'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:active,ended,pending'],
        ]);
    }
}
