<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Property::with('owner', 'coverImage');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")->orWhere('reference_no', 'like', "%{$search}%");
            });
        }

        $properties = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'all' => Property::count(),
            'pending_review' => Property::where('status', 'pending_review')->count(),
            'occupied' => Property::where('status', 'occupied')->count(),
            'vacant' => Property::where('status', 'vacant')->count(),
        ];

        return view('admin.properties.index', compact('properties', 'counts'));
    }

    public function create(): View
    {
        $clients = User::where('role', 'client')->orderBy('name')->get();

        return view('admin.properties.create', compact('clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProperty($request);

        $property = Property::create([
            ...$validated,
            'reference_no' => 'GPS-' . strtoupper(Str::random(6)),
            'slug' => Str::slug($validated['title']) . '-' . strtolower(Str::random(5)),
        ]);

        if ($request->hasFile('images')) {
            $this->storeImages($request, $property);
        }

        AuditLog::record($request->user(), 'Created property', $property, "Created {$property->title}");

        return redirect()->route('admin.properties.show', $property)->with('success', 'Property created.');
    }

    public function show(Property $property): View
    {
        $property->load('owner', 'images', 'leases', 'payments', 'maintenanceRequests', 'documents');

        return view('admin.properties.show', compact('property'));
    }

    public function edit(Property $property): View
    {
        $clients = User::where('role', 'client')->orderBy('name')->get();

        return view('admin.properties.edit', compact('property', 'clients'));
    }

    public function update(Request $request, Property $property): RedirectResponse
    {
        $validated = $this->validateProperty($request, $property);

        $property->update($validated);

        if ($request->hasFile('images')) {
            $this->storeImages($request, $property);
        }

        AuditLog::record($request->user(), 'Updated property', $property, "Updated {$property->title}");

        return redirect()->route('admin.properties.show', $property)->with('success', 'Property updated.');
    }

    public function approve(Request $request, Property $property): RedirectResponse
    {
        $property->update([
            'status' => $request->input('status', 'vacant'),
            'published_at' => now(),
        ]);

        AuditLog::record($request->user(), 'Approved property', $property, "Approved & published {$property->title}");

        return back()->with('success', $property->title . ' is now live on the public site.');
    }

    public function toggleFeatured(Request $request, Property $property): RedirectResponse
    {
        $property->update(['is_featured' => ! $property->is_featured]);

        AuditLog::record($request->user(), 'Toggled featured', $property, ($property->is_featured ? 'Featured' : 'Unfeatured') . " {$property->title}");

        return back()->with('success', 'Featured status updated.');
    }

    public function destroy(Request $request, Property $property): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted property', null, "Deleted {$property->title} (Ref: {$property->reference_no})");

        $property->delete();

        return redirect()->route('admin.properties.index')->with('success', 'Property deleted.');
    }

    protected function validateProperty(Request $request, ?Property $property = null): array
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:house,apartment,flat,commercial,office,airbnb,vacation_rental,land'],
            'category' => ['required', 'in:residential,commercial,airbnb'],
            'listing_type' => ['required', 'in:rent,sale'],
            'status' => ['required', 'in:occupied,vacant,maintenance,pending_review'],
            'price' => ['required', 'numeric', 'min:0'],
            'price_period' => ['nullable', 'in:month,night,total'],
            'city' => ['required', 'string', 'max:255'],
            'area_location' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'size_label' => ['nullable', 'string', 'max:255'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'area_sqft' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'virtual_tour_url' => ['nullable', 'url', 'max:500'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'is_featured' => ['nullable', 'boolean'],
            'is_published' => ['nullable', 'boolean'],
            'images.*' => ['nullable', 'image', 'max:5120'],
        ]);

        $validated['address'] = $validated['address'] ?: $validated['area_location'] . ', ' . $validated['city'];
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['published_at'] = $request->boolean('is_published') ? ($property?->published_at ?? now()) : null;

        unset($validated['is_published'], $validated['images']);

        return $validated;
    }

    protected function storeImages(Request $request, Property $property): void
    {
        $existingCount = $property->images()->count();

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('properties/' . $property->id, 'public');
            PropertyImage::create([
                'property_id' => $property->id,
                'path' => $path,
                'is_cover' => $existingCount === 0 && $index === 0,
                'sort_order' => $existingCount + $index,
            ]);
        }
    }
}
