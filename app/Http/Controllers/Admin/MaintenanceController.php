<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceUpdate;
use App\Models\Payment;
use App\Services\Billing\FeeCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
    public function __construct(protected FeeCalculator $feeCalculator)
    {
    }

    public function index(Request $request): View
    {
        $query = MaintenanceRequest::with('property', 'user');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->string('priority'));
        }

        $requests = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'submitted' => MaintenanceRequest::where('status', 'submitted')->count(),
            'in_progress' => MaintenanceRequest::where('status', 'in_progress')->count(),
            'emergency' => MaintenanceRequest::where('priority', 'emergency')->whereIn('status', ['submitted', 'acknowledged', 'in_progress'])->count(),
        ];

        return view('admin.maintenance.index', compact('requests', 'counts'));
    }

    public function show(MaintenanceRequest $maintenanceRequest): View
    {
        $maintenanceRequest->load('property', 'user', 'images', 'updates');

        return view('admin.maintenance.show', compact('maintenanceRequest'));
    }

    public function update(Request $request, MaintenanceRequest $maintenanceRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:submitted,acknowledged,in_progress,completed,cancelled'],
            'assigned_to' => ['nullable', 'string', 'max:255'],
            'estimated_completion' => ['nullable', 'date'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        $maintenanceRequest->update([
            'status' => $validated['status'],
            'assigned_to' => $validated['assigned_to'] ?? $maintenanceRequest->assigned_to,
            'estimated_completion' => $validated['estimated_completion'] ?? $maintenanceRequest->estimated_completion,
            'completed_at' => $validated['status'] === 'completed' ? now() : $maintenanceRequest->completed_at,
        ]);

        MaintenanceUpdate::create([
            'maintenance_request_id' => $maintenanceRequest->id,
            'status' => $validated['status'],
            'note' => $validated['note'] ?? 'Status updated by GATED team.',
            'created_by' => $request->user()->name,
        ]);

        AuditLog::record($request->user(), 'Updated maintenance request', $maintenanceRequest, "Set {$maintenanceRequest->ticket_no} to {$validated['status']}");

        return back()->with('success', 'Maintenance request updated.');
    }

    /**
     * Log the contractor's invoice for a job — computes GATED's tiered
     * coordination fee via FeeCalculator, stores the transparent
     * contractor-cost/fee/total breakdown, and generates a client invoice.
     */
    public function bill(Request $request, MaintenanceRequest $maintenanceRequest): RedirectResponse
    {
        $validated = $request->validate([
            'contractor_cost' => ['required', 'numeric', 'min:0'],
            'invoice' => ['nullable', 'file', 'max:10240'],
        ]);

        $fee = $this->feeCalculator->maintenanceFee((float) $validated['contractor_cost']);

        $invoicePath = $maintenanceRequest->invoice_path;
        if ($request->hasFile('invoice')) {
            $invoicePath = $request->file('invoice')->store('maintenance/' . $maintenanceRequest->id, 'public');
        }

        $payment = Payment::create([
            'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
            'user_id' => $maintenanceRequest->user_id,
            'property_id' => $maintenanceRequest->property_id,
            'type' => 'maintenance',
            'revenue_stream' => Payment::STREAM_MAINTENANCE_FEE,
            'amount' => $fee['total'],
            'base_amount' => $fee['contractor_cost'],
            'fee_percent' => $fee['fee_percent'],
            'status' => 'due',
            'due_date' => now()->addDays(7),
            'notes' => "Maintenance coordination — {$maintenanceRequest->ticket_no}: {$maintenanceRequest->title}",
        ]);

        $maintenanceRequest->update([
            'contractor_cost' => $fee['contractor_cost'],
            'gated_fee_percent' => $fee['fee_percent'],
            'gated_fee_amount' => $fee['fee_amount'],
            'total_cost' => $fee['total'],
            'invoice_path' => $invoicePath,
            'payment_id' => $payment->id,
        ]);

        AuditLog::record($request->user(), 'Billed maintenance job', $maintenanceRequest, "Logged contractor invoice PKR " . number_format($fee['contractor_cost']) . " + GATED fee PKR " . number_format($fee['fee_amount']) . " for {$maintenanceRequest->ticket_no}");

        return back()->with('success', 'Contractor invoice logged and client billed PKR ' . number_format($fee['total'], 2) . '.');
    }
}
