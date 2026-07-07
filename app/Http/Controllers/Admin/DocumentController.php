<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Document;
use App\Models\Property;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Document::with('user', 'property');

        if ($request->filled('type')) {
            $query->where('type', $request->string('type'));
        }

        $documents = $query->latest()->paginate(15)->withQueryString();

        return view('admin.documents.index', compact('documents'));
    }

    public function create(): View
    {
        $clients = User::where('role', 'client')->orderBy('name')->get();
        $properties = Property::orderBy('title')->get();

        return view('admin.documents.create', compact('clients', 'properties'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'property_id' => ['nullable', 'exists:properties,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:lease_agreement,inspection_report,invoice,tax_document,legal,other'],
            'file' => ['required', 'file', 'max:10240'],
        ]);

        $path = $request->file('file')->store('documents/' . $validated['user_id'], 'public');

        $document = Document::create([
            'user_id' => $validated['user_id'],
            'property_id' => $validated['property_id'] ?? null,
            'title' => $validated['title'],
            'type' => $validated['type'],
            'file_path' => $path,
            'uploaded_by' => 'staff',
        ]);

        AuditLog::record($request->user(), 'Uploaded document', $document, "Uploaded {$document->title}");

        return redirect()->route('admin.documents.index')->with('success', 'Document uploaded.');
    }

    public function destroy(Request $request, Document $document): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted document', null, "Deleted {$document->title}");

        $document->delete();

        return back()->with('success', 'Document deleted.');
    }
}
