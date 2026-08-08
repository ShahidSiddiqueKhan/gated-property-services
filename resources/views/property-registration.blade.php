@extends('layouts.app')

@section('title', 'Register Your Property | GATED Property Services')
@section('meta_description', 'Register your house, apartment, commercial unit, Airbnb property or land with GATED Property Services for professional management.')

@section('content')

    <section class="bg-ink-950 text-white py-16 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 15% 20%, var(--color-brand-600) 0%, transparent 40%), radial-gradient(circle at 85% 80%, var(--color-brand-700) 0%, transparent 40%);"></div>
        <div class="max-w-3xl mx-auto px-6 relative" data-reveal>
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

            @php
                // Map form fields to their wizard step so validation errors reopen on the right step.
                $fieldSteps = [
                    'owner_name' => 1, 'owner_email' => 1, 'owner_phone' => 1, 'owner_country' => 1,
                    'title' => 2, 'type' => 2, 'listing_type' => 2, 'city' => 2, 'area_location' => 2,
                    'size_label' => 2, 'area_sqft' => 2, 'bedrooms' => 2, 'bathrooms' => 2, 'price' => 2, 'description' => 2,
                    'legal_documents' => 3,
                    'tenant_name' => 4, 'tenant_phone' => 4,
                    'images' => 5,
                    'services' => 6,
                ];
                $initialStep = 1;
                foreach ($errors->keys() as $key) {
                    $base = rtrim(preg_replace('/\.\d+$/', '', preg_replace('/\[\]$/', '', $key)), '[]');
                    if (isset($fieldSteps[$base])) {
                        $initialStep = max($initialStep, $fieldSteps[$base]);
                    }
                }
            @endphp
            <div
                x-data="{
                    step: {{ $initialStep }},
                    totalSteps: 6,
                    stepNames: ['Owner', 'Property', 'Documents', 'Tenant', 'Photos', 'Services'],
                    next() { if (this.step < this.totalSteps) this.step++; window.scrollTo({ top: document.getElementById('wizard-top').offsetTop - 100, behavior: 'smooth' }); },
                    prev() { if (this.step > 1) this.step--; window.scrollTo({ top: document.getElementById('wizard-top').offsetTop - 100, behavior: 'smooth' }); },
                    goTo(n) { this.step = n; }
                }"
                id="wizard-top"
            >
                {{-- Progress bar --}}
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-3">
                        <template x-for="(name, idx) in stepNames" :key="idx">
                            <div class="flex-1 flex items-center" :class="{ 'flex-none': idx === stepNames.length - 1 }">
                                <button
                                    type="button"
                                    @click="goTo(idx + 1)"
                                    class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 transition-all duration-300"
                                    :class="step > idx + 1 ? 'bg-brand-600 text-white' : (step === idx + 1 ? 'bg-brand-600 text-white ring-4 ring-brand-100' : 'bg-ink-200 text-ink-500')"
                                >
                                    <span x-show="step <= idx + 1" x-text="idx + 1"></span>
                                    <x-icon name="check" class="w-4 h-4" x-show="step > idx + 1" x-cloak />
                                </button>
                                <div class="hidden sm:block ml-2 text-xs font-semibold whitespace-nowrap" :class="step >= idx + 1 ? 'text-ink-900' : 'text-ink-400'" x-text="name"></div>
                                <div class="flex-1 h-0.5 mx-3" :class="step > idx + 1 ? 'bg-brand-600' : 'bg-ink-200'" x-show="idx < stepNames.length - 1"></div>
                            </div>
                        </template>
                    </div>
                    <div class="h-1.5 bg-ink-200 rounded-full overflow-hidden">
                        <div class="h-full bg-brand-600 rounded-full transition-all duration-500 ease-out" :style="`width: ${(step / totalSteps) * 100}%`"></div>
                    </div>
                </div>

            <form method="POST" action="{{ route('property-registration.store') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                {{-- Owner Information --}}
                <div class="card p-8" x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
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
                    <div class="mt-8 flex justify-end">
                        <button type="button" @click="next()" class="btn-primary">Continue <x-icon name="arrow-right" class="w-4 h-4" /></button>
                    </div>
                </div>

                {{-- Property Information --}}
                <div class="card p-8" x-show="step === 2" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
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
                    <div class="mt-8 flex justify-between">
                        <button type="button" @click="prev()" class="btn-outline">Back</button>
                        <button type="button" @click="next()" class="btn-primary">Continue <x-icon name="arrow-right" class="w-4 h-4" /></button>
                    </div>
                </div>

                {{-- Legal Documents --}}
                <div class="card p-8" x-show="step === 3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm">3</span>
                        <h2 class="font-heading font-bold text-lg text-ink-900">Legal Documents</h2>
                    </div>
                    <label class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-ink-200 rounded-xl p-8 cursor-pointer hover:border-brand-400 hover:bg-brand-50/30 transition-all duration-300 text-center">
                        <x-icon name="arrow-up-tray" class="w-8 h-8 text-ink-400" />
                        <span class="text-sm font-semibold text-ink-700">Upload ownership documents, title deeds, or CNIC copies</span>
                        <span class="text-xs text-ink-400">PDF, JPG or PNG &middot; Max 10MB each</span>
                        <input type="file" name="legal_documents[]" multiple class="hidden">
                    </label>
                    <div class="mt-8 flex justify-between">
                        <button type="button" @click="prev()" class="btn-outline">Back</button>
                        <button type="button" @click="next()" class="btn-primary">Continue <x-icon name="arrow-right" class="w-4 h-4" /></button>
                    </div>
                </div>

                {{-- Tenant Information --}}
                <div class="card p-8" x-show="step === 4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
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
                    <div class="mt-8 flex justify-between">
                        <button type="button" @click="prev()" class="btn-outline">Back</button>
                        <button type="button" @click="next()" class="btn-primary">Continue <x-icon name="arrow-right" class="w-4 h-4" /></button>
                    </div>
                </div>

                {{-- Property Images --}}
                <div class="card p-8" x-show="step === 5" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm">5</span>
                        <h2 class="font-heading font-bold text-lg text-ink-900">Property Images</h2>
                    </div>
                    <label class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-ink-200 rounded-xl p-8 cursor-pointer hover:border-brand-400 hover:bg-brand-50/30 transition-all duration-300 text-center">
                        <x-icon name="camera" class="w-8 h-8 text-ink-400" />
                        <span class="text-sm font-semibold text-ink-700">Upload property photos</span>
                        <span class="text-xs text-ink-400">JPG or PNG &middot; Max 5MB each &middot; First photo becomes the cover image</span>
                        <input type="file" name="images[]" multiple accept="image/*" class="hidden">
                    </label>
                    <div class="mt-8 flex justify-between">
                        <button type="button" @click="prev()" class="btn-outline">Back</button>
                        <button type="button" @click="next()" class="btn-primary">Continue <x-icon name="arrow-right" class="w-4 h-4" /></button>
                    </div>
                </div>

                {{-- Service Selection --}}
                <div class="card p-8" x-show="step === 6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0" x-cloak>
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-8 h-8 rounded-full bg-brand-600 text-white flex items-center justify-center font-bold text-sm">6</span>
                        <h2 class="font-heading font-bold text-lg text-ink-900">Select Services</h2>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-3">
                        @foreach ($services as $service)
                            <label class="flex items-center gap-3 rounded-lg border border-ink-200 p-3 cursor-pointer hover:border-brand-400 hover:bg-brand-50/30 transition-all duration-200">
                                <input type="checkbox" name="services[]" value="{{ $service->slug }}" class="rounded border-ink-300 text-brand-600 focus:ring-brand-500">
                                <span class="text-sm font-medium text-ink-800">{{ $service->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    <div class="mt-8 flex justify-between">
                        <button type="button" @click="prev()" class="btn-outline">Back</button>
                        <button type="submit" class="btn-primary">Submit Property for Review <x-icon name="check-circle" class="w-4 h-4" /></button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </section>

@endsection
