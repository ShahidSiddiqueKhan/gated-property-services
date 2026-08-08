<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\Property;
use App\Models\RenovationProject;
use App\Services\Billing\FeeCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class RenovationProjectController extends Controller
{
    public function __construct(protected FeeCalculator $feeCalculator)
    {
    }

    public function index(Request $request): View
    {
        $query = RenovationProject::with('property');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $projects = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'proposed' => RenovationProject::where('status', 'proposed')->count(),
            'in_progress' => RenovationProject::where('status', 'in_progress')->count(),
            'completed' => RenovationProject::where('status', 'completed')->count(),
        ];

        return view('admin.renovations.index', compact('projects', 'counts'));
    }

    public function create(): View
    {
        $properties = Property::orderBy('title')->get();

        return view('admin.renovations.create', compact('properties'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProject($request);

        $fee = $this->feeCalculator->renovationFee((float) $validated['project_value']);

        $project = RenovationProject::create([
            ...$validated,
            'fee_percent' => $fee['fee_percent'],
            'fee_amount' => $fee['fee_amount'],
        ]);

        AuditLog::record($request->user(), 'Created renovation project', $project, "Proposed {$project->title} — PKR " . number_format($project->project_value) . ' (fee: PKR ' . number_format($fee['fee_amount']) . ')');

        return redirect()->route('admin.renovations.show', $project)->with('success', 'Renovation project created. Awaiting approval.');
    }

    public function show(RenovationProject $renovation): View
    {
        $renovation->load('property', 'milestones', 'media', 'payments', 'approvedBy');

        return view('admin.renovations.show', ['project' => $renovation]);
    }

    public function edit(RenovationProject $renovation): View
    {
        $properties = Property::orderBy('title')->get();

        return view('admin.renovations.edit', ['project' => $renovation, 'properties' => $properties]);
    }

    public function update(Request $request, RenovationProject $renovation): RedirectResponse
    {
        $validated = $this->validateProject($request);
        $fee = $this->feeCalculator->renovationFee((float) $validated['project_value']);

        $renovation->update([
            ...$validated,
            'fee_percent' => $fee['fee_percent'],
            'fee_amount' => $fee['fee_amount'],
        ]);

        AuditLog::record($request->user(), 'Updated renovation project', $renovation, "Updated {$renovation->title}");

        return redirect()->route('admin.renovations.show', $renovation)->with('success', 'Renovation project updated.');
    }

    public function destroy(Request $request, RenovationProject $renovation): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted renovation project', null, "Deleted {$renovation->title}");

        $renovation->delete();

        return redirect()->route('admin.renovations.index')->with('success', 'Renovation project deleted.');
    }

    /**
     * Approve the project and generate the client invoice for GATED's
     * project management fee (billed against the property's owner).
     */
    public function approve(Request $request, RenovationProject $renovation): RedirectResponse
    {
        $renovation->update([
            'approval_status' => 'approved',
            'status' => $renovation->status === 'proposed' ? 'approved' : $renovation->status,
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);

        Payment::create([
            'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
            'user_id' => $renovation->property->user_id,
            'property_id' => $renovation->property_id,
            'type' => 'service',
            'revenue_stream' => Payment::STREAM_RENOVATION_FEE,
            'amount' => $renovation->totalWithFee(),
            'base_amount' => $renovation->project_value,
            'fee_percent' => $renovation->fee_percent,
            'renovation_project_id' => $renovation->id,
            'status' => 'due',
            'due_date' => now()->addDays(7),
            'notes' => "Renovation project management — {$renovation->title}",
        ]);

        AuditLog::record($request->user(), 'Approved renovation project', $renovation, "Approved {$renovation->title} — invoice raised for PKR " . number_format($renovation->totalWithFee()));

        return back()->with('success', 'Project approved and client invoiced for PKR ' . number_format($renovation->totalWithFee(), 2) . '.');
    }

    public function reject(Request $request, RenovationProject $renovation): RedirectResponse
    {
        $renovation->update(['approval_status' => 'rejected', 'status' => 'cancelled']);

        AuditLog::record($request->user(), 'Rejected renovation project', $renovation, "Rejected {$renovation->title}");

        return back()->with('success', 'Project rejected.');
    }

    public function updateStatus(Request $request, RenovationProject $renovation): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:proposed,approved,in_progress,completed,cancelled'],
            'actual_completion_date' => ['nullable', 'date'],
            'final_cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $renovation->update([
            'status' => $validated['status'],
            'actual_completion_date' => $validated['status'] === 'completed'
                ? ($validated['actual_completion_date'] ?? now()->toDateString())
                : $renovation->actual_completion_date,
            'final_cost' => $validated['final_cost'] ?? $renovation->final_cost,
        ]);

        AuditLog::record($request->user(), 'Updated renovation status', $renovation, "Set {$renovation->title} to {$validated['status']}");

        return back()->with('success', 'Project status updated.');
    }

    protected function validateProject(Request $request): array
    {
        $validated = $request->validate([
            'property_id' => ['required', 'exists:properties,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'contractor_name' => ['nullable', 'string', 'max:255'],
            'contractor_contact' => ['nullable', 'string', 'max:255'],
            'project_value' => ['required', 'numeric', 'min:0'],
            'start_date' => ['nullable', 'date'],
            'expected_completion_date' => ['nullable', 'date'],
        ]);

        return $validated;
    }
}
