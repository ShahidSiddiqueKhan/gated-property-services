<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Lease;
use App\Models\Property;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaseController extends Controller
{
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

        $lease = Lease::create($validated);

        // Reflect an active lease on the property's occupancy status.
        if ($lease->status === 'active') {
            $lease->property->update(['status' => 'occupied']);
        }

        AuditLog::record($request->user(), 'Created lease', $lease, "Created lease for {$lease->tenant_name} on {$lease->property->title}");

        return redirect()->route('admin.leases.index')->with('success', 'Lease created.');
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
