@extends('layouts.app')

@section('title', 'Properties For Rent & Sale | GATED Property Services')
@section('meta_description', 'Browse residential, commercial and Airbnb properties managed by GATED Property Services across Pakistan.')

@section('content')

    <section class="bg-ink-950 text-white py-16 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 15% 20%, var(--color-brand-600) 0%, transparent 40%), radial-gradient(circle at 85% 80%, var(--color-brand-700) 0%, transparent 40%);"></div>
        <div class="max-w-7xl mx-auto px-6 text-center relative" data-reveal>
            <span class="section-eyebrow text-brand-500">Our Portfolio</span>
            <h1 class="mt-3 text-4xl font-heading font-extrabold">Featured &amp; Managed Properties</h1>
            <p class="mt-4 max-w-2xl mx-auto text-ink-300">Interactive listings from across our managed portfolio &mdash; residential, commercial and Airbnb.</p>
        </div>
    </section>

    <section class="py-10 bg-white border-b border-ink-100">
        <div class="max-w-7xl mx-auto px-6">
            <form method="GET" action="{{ route('properties.index') }}" class="grid sm:grid-cols-2 lg:grid-cols-5 gap-3" data-reveal>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or area..." class="rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500 lg:col-span-2">

                <select name="type" class="rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    <option value="">All Types</option>
                    @foreach (['house' => 'House', 'apartment' => 'Apartment', 'flat' => 'Flat', 'commercial' => 'Commercial', 'office' => 'Office', 'airbnb' => 'Airbnb', 'vacation_rental' => 'Vacation Rental', 'land' => 'Land'] as $val => $label)
                        <option value="{{ $val }}" @selected(request('type') === $val)>{{ $label }}</option>
                    @endforeach
                </select>

                <select name="listing_type" class="rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    <option value="">Rent or Sale</option>
                    <option value="rent" @selected(request('listing_type') === 'rent')>For Rent</option>
                    <option value="sale" @selected(request('listing_type') === 'sale')>For Sale</option>
                </select>

                <select name="city" class="rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    <option value="">All Cities</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city }}" @selected(request('city') === $city)>{{ $city }}</option>
                    @endforeach
                </select>

                <button type="submit" class="btn-primary lg:col-span-5 justify-center sm:justify-start sm:w-auto">
                    <x-icon name="magnifying-glass" class="w-4 h-4" /> Search Properties
                </button>
            </form>
        </div>
    </section>

    <section class="py-16 bg-ink-50">
        <div class="max-w-7xl mx-auto px-6">
            @if ($properties->count())
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($properties as $property)
                        <div data-reveal data-reveal-delay="{{ min($loop->iteration, 6) }}">
                            @include('properties.partials.card', ['property' => $property])
                        </div>
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $properties->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <p class="text-ink-500">No properties match your search. Try adjusting your filters.</p>
                </div>
            @endif
        </div>
    </section>

    <section class="py-16 bg-brand-600 text-center">
        <div class="max-w-2xl mx-auto px-6" data-reveal>
            <h2 class="text-2xl font-heading font-extrabold text-white">Own a property you'd like us to manage?</h2>
            <a href="{{ route('property-registration.create') }}" class="btn-dark mt-6">Register Your Property</a>
        </div>
    </section>

@endsection
