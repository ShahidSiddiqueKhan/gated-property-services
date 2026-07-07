<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::orderBy('sort_order')->get();

        return view('admin.services.index', compact('services'));
    }

    public function create(): View
    {
        return view('admin.services.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateService($request);

        $validated['slug'] = Str::slug($validated['name']);

        $service = Service::create($validated);

        AuditLog::record($request->user(), 'Created service', $service, "Added service: {$service->name}");

        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }

    public function edit(Service $service): View
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service): RedirectResponse
    {
        $validated = $this->validateService($request);

        $service->update($validated);

        AuditLog::record($request->user(), 'Updated service', $service, "Updated service: {$service->name}");

        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }

    public function destroy(Request $request, Service $service): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted service', null, "Deleted service: {$service->name}");

        $service->delete();

        return back()->with('success', 'Service deleted.');
    }

    protected function validateService(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:100'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['features'] = $validated['features']
            ? array_values(array_filter(array_map('trim', explode("\n", $validated['features']))))
            : [];

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        return $validated;
    }
}
