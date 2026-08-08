<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\RenovationMilestone;
use App\Models\RenovationProject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RenovationMilestoneController extends Controller
{
    public function store(Request $request, RenovationProject $renovation): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['nullable', 'date'],
        ]);

        $renovation->milestones()->create([
            ...$validated,
            'sort_order' => $renovation->milestones()->count(),
        ]);

        AuditLog::record($request->user(), 'Added renovation milestone', $renovation, "Added milestone: {$validated['title']}");

        return back()->with('success', 'Milestone added.');
    }

    public function update(Request $request, RenovationProject $renovation, RenovationMilestone $milestone): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed'],
        ]);

        $milestone->update([
            'status' => $validated['status'],
            'completed_at' => $validated['status'] === 'completed' ? now() : null,
        ]);

        AuditLog::record($request->user(), 'Updated renovation milestone', $renovation, "Set milestone '{$milestone->title}' to {$validated['status']}");

        return back()->with('success', 'Milestone updated.');
    }

    public function destroy(Request $request, RenovationProject $renovation, RenovationMilestone $milestone): RedirectResponse
    {
        $milestone->delete();

        AuditLog::record($request->user(), 'Deleted renovation milestone', $renovation, "Deleted milestone: {$milestone->title}");

        return back()->with('success', 'Milestone deleted.');
    }
}
