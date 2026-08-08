<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Property;
use App\Models\PropertyPackage;
use App\Services\Billing\FeeCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PropertyPackageController extends Controller
{
    public function __construct(protected FeeCalculator $feeCalculator)
    {
    }

    /**
     * Subscribe a property to a package (or switch packages). Ends any
     * currently active subscription and starts a fresh one, snapshotting
     * the package's price/commission at the time of subscribing so past
     * billing history stays accurate even if the package is edited later.
     */
    public function store(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'package_id' => ['required', 'exists:packages,id'],
            'frequency' => ['required', 'in:monthly,quarterly,annually'],
            'commission_percent' => ['nullable', 'numeric', 'min:0', 'max:12'],
            'started_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $package = Package::findOrFail($validated['package_id']);
        $pricing = $this->feeCalculator->frequencyPrice((float) $package->monthly_price, $validated['frequency']);

        $commissionPercent = $validated['commission_percent'] ?? $package->rent_commission_percent;
        $overridden = $commissionPercent != $package->rent_commission_percent;

        $property->propertyPackages()->where('status', 'active')->update(['status' => 'expired']);

        $startedAt = $validated['started_at'] ?? now()->toDateString();

        $propertyPackage = PropertyPackage::create([
            'property_id' => $property->id,
            'package_id' => $package->id,
            'frequency' => $validated['frequency'],
            'base_price' => $pricing['base_price'],
            'discount_percent' => $pricing['discount_percent'],
            'final_price' => $pricing['final_price'],
            'commission_percent' => $commissionPercent,
            'commission_overridden' => $overridden,
            'status' => 'active',
            'started_at' => $startedAt,
            'renews_at' => Carbon::parse($startedAt)->addMonths($pricing['months'])->toDateString(),
            'notes' => $validated['notes'] ?? null,
            'created_by' => $request->user()->id,
        ]);

        Payment::create([
            'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
            'user_id' => $property->user_id,
            'property_id' => $property->id,
            'type' => 'service',
            'revenue_stream' => Payment::STREAM_PACKAGE_FEE,
            'amount' => $pricing['final_price'],
            'base_amount' => $pricing['gross_price'],
            'fee_percent' => $pricing['discount_percent'],
            'status' => 'due',
            'due_date' => $startedAt,
            'property_package_id' => $propertyPackage->id,
            'notes' => "{$package->name} package — " . ucfirst($validated['frequency']) . ' billing',
        ]);

        AuditLog::record($request->user(), 'Assigned package', $property, "Assigned {$package->name} ({$validated['frequency']}) to {$property->title} at {$commissionPercent}% rent commission");

        return back()->with('success', "{$package->name} package assigned. Invoice created for PKR " . number_format($pricing['final_price'], 2) . '.');
    }

    /**
     * Adjust commission override or cancel a property's active package
     * without deleting billing history.
     */
    public function update(Request $request, Property $property, PropertyPackage $propertyPackage): RedirectResponse
    {
        $validated = $request->validate([
            'commission_percent' => ['required', 'numeric', 'min:0', 'max:12'],
        ]);

        $propertyPackage->update([
            'commission_percent' => $validated['commission_percent'],
            'commission_overridden' => $validated['commission_percent'] != $propertyPackage->package->rent_commission_percent,
        ]);

        AuditLog::record($request->user(), 'Updated package commission', $property, "Set rent commission to {$validated['commission_percent']}% for {$property->title}");

        return back()->with('success', 'Commission override updated.');
    }

    public function cancel(Request $request, Property $property, PropertyPackage $propertyPackage): RedirectResponse
    {
        $propertyPackage->update([
            'status' => 'cancelled',
            'cancelled_at' => now()->toDateString(),
        ]);

        AuditLog::record($request->user(), 'Cancelled package', $property, "Cancelled {$propertyPackage->package->name} package for {$property->title}");

        return back()->with('success', 'Package subscription cancelled.');
    }
}
