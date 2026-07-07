<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('role', 'client')->withCount(['properties', 'maintenanceRequests']);

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%");
            });
        }

        $clients = $query->latest()->paginate(15)->withQueryString();

        return view('admin.clients.index', compact('clients'));
    }

    public function show(User $client): View
    {
        abort_unless($client->role === 'client', 404);

        $client->load(['properties.coverImage', 'payments', 'maintenanceRequests', 'documents', 'tasks']);

        return view('admin.clients.show', compact('client'));
    }

    public function edit(User $client): View
    {
        abort_unless($client->role === 'client', 404);

        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, User $client): RedirectResponse
    {
        abort_unless($client->role === 'client', 404);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($client->id)],
            'phone' => ['nullable', 'string', 'max:30'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'is_overseas' => ['nullable', 'boolean'],
            'new_password' => ['nullable', 'string', 'min:8'],
        ]);

        $validated['is_overseas'] = $request->boolean('is_overseas');

        if (! empty($validated['new_password'])) {
            $client->password = Hash::make($validated['new_password']);
        }
        unset($validated['new_password']);

        $client->update($validated);

        AuditLog::record($request->user(), 'Updated client profile', $client, "Updated details for {$client->name}");

        return redirect()->route('admin.clients.show', $client)->with('success', 'Client updated successfully.');
    }
}
