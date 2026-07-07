<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\MaintenanceRequest;
use App\Models\MaintenanceUpdate;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceController extends Controller
{
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
}
