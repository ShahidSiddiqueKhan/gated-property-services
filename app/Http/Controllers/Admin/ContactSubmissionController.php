<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ContactSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactSubmissionController extends Controller
{
    public function index(Request $request): View
    {
        $query = ContactSubmission::query();

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        if ($request->boolean('unhandled_only')) {
            $query->where('is_handled', false);
        }

        $submissions = $query->latest()->paginate(15)->withQueryString();

        return view('admin.leads.index', compact('submissions'));
    }

    public function toggleHandled(Request $request, ContactSubmission $submission): RedirectResponse
    {
        $submission->update(['is_handled' => ! $submission->is_handled]);

        AuditLog::record($request->user(), 'Updated lead', $submission, ($submission->is_handled ? 'Marked handled' : 'Reopened') . ": {$submission->name}");

        return back()->with('success', 'Lead updated.');
    }

    public function destroy(Request $request, ContactSubmission $submission): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted lead', null, "Deleted lead from {$submission->name}");

        $submission->delete();

        return back()->with('success', 'Lead deleted.');
    }
}
