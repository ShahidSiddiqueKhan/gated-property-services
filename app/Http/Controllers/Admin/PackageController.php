<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Package;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PackageController extends Controller
{
    public function index(): View
    {
        $packages = Package::orderBy('sort_order')->withCount('propertyPackages')->get();

        return view('admin.packages.index', compact('packages'));
    }

    public function create(): View
    {
        return view('admin.packages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePackage($request);
        $validated['slug'] = Str::slug($validated['name']);

        $package = Package::create($validated);

        AuditLog::record($request->user(), 'Created package', $package, "Added package: {$package->name}");

        return redirect()->route('admin.packages.index')->with('success', 'Package created.');
    }

    public function edit(Package $package): View
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package): RedirectResponse
    {
        $validated = $this->validatePackage($request);

        $package->update($validated);

        AuditLog::record($request->user(), 'Updated package', $package, "Updated package: {$package->name}");

        return redirect()->route('admin.packages.index')->with('success', 'Package updated.');
    }

    public function destroy(Request $request, Package $package): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted package', null, "Deleted package: {$package->name}");

        $package->delete();

        return back()->with('success', 'Package deleted.');
    }

    protected function validatePackage(Request $request): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'features' => ['nullable', 'string'],
            'monthly_price' => ['required', 'numeric', 'min:0'],
            'rent_commission_percent' => ['required', 'numeric', 'min:0', 'max:100'],
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
