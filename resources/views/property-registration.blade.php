@extends('layouts.app')

@section('title', 'Register Your Property | GATED Property Services')
@section('meta_description', 'Register your house, apartment, commercial unit, Airbnb property or land with GATED Property Services for professional management.')

@section('content')

    <section class="bg-ink-950 text-white py-16 text-center">
        <div class="max-w-3xl mx-auto px-6">
            <span class="section-eyebrow text-brand-500">Register Property</span>
            <h1 class="mt-3 text-4xl font-heading font-extrabold">Let GATED Manage Your Property</h1>
            <p class="mt-4 text-ink-300">Complete the form below and our team will review your submission within 24 hours.</p>
        </div>
    </section>

    <section class="py-16 bg-ink-50">
        <div class="max-w-4xl mx-auto px-6">

            @if (session('success'))
                <div class="mb-6 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 flex items-center gap-2">
                    <x-icon name="check-circle" class="w-5 h-5 shrink-0" /> {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-brand-50 border border-brand-200 text-brand-800 text-sm p-4">
                    <p class="font-semibold mb-1">Please fix the following:</p>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('property-registration.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Owner Information --}}
                <div class="card p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm">1</span>
                        <h2 class="font-heading font-bold text-lg text-ink-900">Owner Information</h2>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Full Name</label>
                            <input type="text" name="owner_name" value="{{ old('owner_name', auth()->user()->name ?? '') }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Email Address</label>
                            <input type="email" name="owner_email" value="{{ old('owner_email', auth()->user()->email ?? '') }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Phone Number</label>
                            <input type="text" name="owner_phone" value="{{ old('owner_phone') }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Country (if overseas)</label>
                            <input type="text" name="owner_country" value="{{ old('owner_country') }}" placeholder="e.g. United Kingdom" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                    </div>
                </div>

                {{-- Property Information --}}
                <div class="card p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm">2</span>
                        <h2 class="font-heading font-bold text-lg text-ink-900">Property Information</h2>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="text-sm font-semibold text-ink-700">Property Title</label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. 1 Kanal House, DHA Phase 6" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Property Type</label>
                            <select name="type" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                                <option value="">Select type</option>
                                @foreach (['house' => 'House', 'apartment' => 'Apartment', 'flat' => 'Flat', 'commercial' => 'Commercial Unit', 'office' => 'Office', 'airbnb' => 'Airbnb Property', 'vacation_rental' => 'Vacation Rental', 'land' => 'Land'] as $val => $label)
                                    <option value="{{ $val }}" @selected(old('type') === $val)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Listing Type</label>
                            <select name="listing_type" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                                <option value="rent" @selected(old('listing_type') === 'rent')>For Rent</option>
                                <option value="sale" @selected(old('listing_type') === 'sale')>For Sale</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">City</label>
                            <input type="text" name="city" value="{{ old('city') }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Area / Location</label>
                            <input type="text" name="area_location" value="{{ old('area_location') }}" placeholder="e.g. DHA Phase 6" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Size (e.g. 1 Kanal, 10 Marla)</label>
                            <input type="text" name="size_label" value="{{ old('size_label') }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Area (Sqft)</label>
                            <input type="number" name="area_sqft" value="{{ old('area_sqft') }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Bedrooms</label>
                            <input type="number" name="bedrooms" value="{{ old('bedrooms') }}" min="0" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Bathrooms</label>
                            <input type="number" name="bathrooms" value="{{ old('bathrooms') }}" min="0" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Expected Price (PKR)</label>
                            <input type="number" name="price" value="{{ old('price') }}" required min="0" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <div class="sm:col-span-2">
                            <label class="text-sm font-semibold text-ink-700">Description</label>
                            <textarea name="description" rows="4" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- Legal Documents --}}
                <div class="card p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm">3</span>
                        <h2 class="font-heading font-bold text-lg text-ink-900">Legal Documents</h2>
                    </div>
                    <label class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-ink-200 rounded-xl p-8 cursor-pointer hover:border-brand-400 transition text-center">
                        <x-icon name="arrow-up-tray" class="w-8 h-8 text-ink-400" />
                        <span class="text-sm font-semibold text-ink-700">Upload ownership documents, title deeds, or CNIC copies</span>
                        <span class="text-xs text-ink-400">PDF, JPG or PNG &middot; Max 10MB each</span>
                        <input type="file" name="legal_documents[]" multiple class="hidden">
                    </label>
                </div>

                {{-- Tenant Information --}}
                <div class="card p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm">4</span>
                        <h2 class="font-heading font-bold text-lg text-ink-900">Tenant Information <span class="text-sm font-normal text-ink-400">(if currently occupied)</span></h2>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Current Tenant Name</label>
                            <input type="text" name="tenant_name" value="{{ old('tenant_name') }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Tenant Phone</label>
                            <input type="text" name="tenant_phone" value="{{ old('tenant_phone') }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                    </div>
                </div>

                {{-- Property Images --}}
                <div class="card p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm">5</span>
                        <h2 class="font-heading font-bold text-lg text-ink-900">Property Images</h2>
                    </div>
                    <label class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-ink-200 rounded-xl p-8 cursor-pointer hover:border-brand-400 transition text-center">
                        <x-icon name="camera" class="w-8 h-8 text-ink-400" />
                        <span class="text-sm font-semibold text-ink-700">Upload property photos</span>
                        <span class="text-xs text-ink-400">JPG or PNG &middot; Max 5MB each &middot; First photo becomes the cover image</span>
                        <input type="file" name="images[]" multiple accept="image/*" class="hidden">
                    </label>
                </div>

                {{-- Service Selection --}}
                <div class="card p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm">6</span>
                        <h2 class="font-heading font-bold text-lg text-ink-900">Select Services</h2>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-3">
                        @foreach ($services as $service)
                            <label class="flex items-center gap-3 rounded-lg border border-ink-200 p-3 cursor-pointer hover:border-brand-400 transition">
                                <input type="checkbox" name="services[]" value="{{ $service->slug }}" class="rounded border-ink-300 text-brand-600 focus:ring-brand-500">
                                <span class="text-sm font-medium text-ink-800">{{ $service->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <button type="submit" class="btn-primary w-full sm:w-auto justify-center">Submit Property for Review</button>
            </form>
        </div>
    </section>

@endsection
