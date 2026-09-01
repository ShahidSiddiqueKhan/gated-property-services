<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\Service;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PropertyRegistrationController extends Controller
{
    public function create(): View
    {
        $services = Service::where('is_active', true)->orderBy('sort_order')->get();

        return view('property-registration', compact('services'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            // Owner information
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'email', 'max:255'],
            'owner_phone' => ['required', 'string', 'max:30'],
            'owner_country' => ['nullable', 'string', 'max:255'],

            // Property information
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:house,apartment,flat,commercial,office,airbnb,vacation_rental,land'],
            'listing_type' => ['required', 'in:rent,sale'],
            'city' => ['required', 'string', 'max:255'],
            'area_location' => ['required', 'string', 'max:255'],
            'size_label' => ['nullable', 'string', 'max:255'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'area_sqft' => ['nullable', 'integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],

            // Tenant information (optional, if already occupied)
            'tenant_name' => ['nullable', 'string', 'max:255'],
            'tenant_phone' => ['nullable', 'string', 'max:30'],

            // Uploads
            'legal_documents.*' => ['nullable', 'file', 'max:10240'],
            'images.*' => ['nullable', 'image', 'max:5120'],

            // Services selection
            'services' => ['nullable', 'array'],
        ]);

        $category = match ($validated['type']) {
            'commercial', 'office' => 'commercial',
            'airbnb', 'vacation_rental' => 'airbnb',
            default => 'residential',
        };

        $property = Property::create([
            'reference_no' => 'GPS-' . strtoupper(Str::random(6)),
            'user_id' => $request->user()?->id,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . strtolower(Str::random(5)),
            'type' => $validated['type'],
            'category' => $category,
            'listing_type' => $validated['listing_type'],
            'status' => 'pending_review',
            'price' => $validated['price'],
            'price_period' => $validated['listing_type'] === 'sale' ? 'total' : 'month',
            'address' => $validated['area_location'] . ', ' . $validated['city'],
            'city' => $validated['city'],
            'area_location' => $validated['area_location'],
            'size_label' => $validated['size_label'] ?? null,
            'bedrooms' => $validated['bedrooms'] ?? null,
            'bathrooms' => $validated['bathrooms'] ?? null,
            'area_sqft' => $validated['area_sqft'] ?? null,
            'description' => $validated['description'] ?? null,
            'services_requested' => $validated['services'] ?? [],
            'is_featured' => false,
            'published_at' => null,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('properties/' . $property->id, 'public');
                PropertyImage::create([
                    'property_id' => $property->id,
                    'path' => $path,
                    'is_cover' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        if ($request->hasFile('legal_documents')) {
            $documents = [];
            foreach ($request->file('legal_documents') as $file) {
                $documents[] = [
                    'path' => $file->store('properties/' . $property->id . '/legal', 'public'),
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
            $property->update(['legal_documents' => $documents]);
        }

        return redirect()
            ->route('property-registration.create')
            ->with('success', 'Thank you! Your property (Ref: ' . $property->reference_no . ') has been submitted and is under review. Our team will contact you within 24 hours.');
    }
}
