<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ServiceCatalogItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceCatalogController extends Controller
{
    public function index(): View
    {
        $itemsByCategory = [
            'advertising' => ServiceCatalogItem::category('advertising')->orderBy('sort_order')->get(),
            'emergency' => ServiceCatalogItem::category('emergency')->orderBy('sort_order')->get(),
        ];

        return view('admin.service-catalog.index', compact('itemsByCategory'));
    }

    public function create(): View
    {
        return view('admin.service-catalog.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateItem($request);

        $item = ServiceCatalogItem::create($validated);

        AuditLog::record($request->user(), 'Created service catalog item', $item, "Added {$item->category} item: {$item->name}");

        return redirect()->route('admin.service-catalog.index')->with('success', 'Service added.');
    }

    public function edit(ServiceCatalogItem $serviceCatalogItem): View
    {
        return view('admin.service-catalog.edit', ['item' => $serviceCatalogItem]);
    }

    public function update(Request $request, ServiceCatalogItem $serviceCatalogItem): RedirectResponse
    {
        $validated = $this->validateItem($request);

        $serviceCatalogItem->update($validated);

        AuditLog::record($request->user(), 'Updated service catalog item', $serviceCatalogItem, "Updated {$serviceCatalogItem->category} item: {$serviceCatalogItem->name}");

        return redirect()->route('admin.service-catalog.index')->with('success', 'Service updated.');
    }

    public function destroy(Request $request, ServiceCatalogItem $serviceCatalogItem): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted service catalog item', null, "Deleted {$serviceCatalogItem->category} item: {$serviceCatalogItem->name}");

        $serviceCatalogItem->delete();

        return back()->with('success', 'Service deleted.');
    }

    protected function validateItem(Request $request): array
    {
        $validated = $request->validate([
            'category' => ['required', 'in:advertising,emergency'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_max' => ['nullable', 'numeric', 'gte:price'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        return $validated;
    }
}
