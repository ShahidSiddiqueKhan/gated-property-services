@extends('layouts.app')

@section('title', $property->title . ' | GATED Property Services')
@section('meta_description', \Illuminate\Support\Str::limit($property->description, 155))

@section('content')

    @php
        $cover = $property->images->firstWhere('is_cover', true) ?? $property->images->first();
        $imageUrl = \App\Support\Media::url($cover?->path, 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80');
    @endphp

    <section class="bg-ink-950">
        <div class="relative h-[380px] lg:h-[460px]">
            <img src="{{ $imageUrl }}" class="w-full h-full object-cover opacity-70" alt="{{ $property->title }}">
            <div class="absolute inset-0 bg-gradient-to-t from-ink-950 via-ink-950/40 to-transparent"></div>
            <div class="absolute inset-x-0 bottom-0">
                <div class="max-w-7xl mx-auto px-6 pb-8">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="badge bg-brand-600 text-white">{{ $property->listing_type === 'sale' ? 'For Sale' : 'For Rent' }}</span>
                        <span class="badge bg-white/90 text-ink-800">{{ ucfirst(str_replace('_',' ',$property->type)) }}</span>
                        <span class="badge bg-white/90 text-ink-800">Ref: {{ $property->reference_no }}</span>
                    </div>
                    <h1 class="text-3xl lg:text-4xl font-heading font-extrabold text-white">{{ $property->title }}</h1>
                    <p class="mt-2 text-ink-200 flex items-center gap-1"><x-icon name="map-pin" class="w-4 h-4" /> {{ $property->address }}</p>
                </div>
            </div>
        </div>
    </section>

    @if ($property->images->count() > 1)
    <section class="bg-white py-6 border-b border-ink-100">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-4 sm:grid-cols-6 gap-3">
            @foreach ($property->images as $img)
                <img src="{{ \App\Support\Media::url($img->path) }}" class="rounded-lg aspect-square object-cover" alt="{{ $property->title }} photo">
            @endforeach
        </div>
    </section>
    @endif

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2">
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-8">
                    @if ($property->bedrooms)
                        <div class="card p-4 text-center"><div class="font-heading font-extrabold text-lg text-ink-900">{{ $property->bedrooms }}</div><div class="text-xs text-ink-500">Bedrooms</div></div>
                    @endif
                    @if ($property->bathrooms)
                        <div class="card p-4 text-center"><div class="font-heading font-extrabold text-lg text-ink-900">{{ $property->bathrooms }}</div><div class="text-xs text-ink-500">Bathrooms</div></div>
                    @endif
                    @if ($property->area_sqft)
                        <div class="card p-4 text-center"><div class="font-heading font-extrabold text-lg text-ink-900">{{ number_format($property->area_sqft) }}</div><div class="text-xs text-ink-500">Sqft</div></div>
                    @endif
                    @if ($property->size_label)
                        <div class="card p-4 text-center"><div class="font-heading font-extrabold text-lg text-ink-900">{{ $property->size_label }}</div><div class="text-xs text-ink-500">Size</div></div>
                    @endif
                </div>

                <h2 class="font-heading font-bold text-xl text-ink-900">About This Property</h2>
                <p class="mt-3 text-ink-600 leading-relaxed">{{ $property->description }}</p>

                @if (!empty($property->amenities))
                <h3 class="mt-8 font-heading font-bold text-lg text-ink-900">Amenities</h3>
                <div class="mt-4 grid sm:grid-cols-2 gap-3">
                    @foreach ($property->amenities as $amenity)
                        <div class="flex items-center gap-2 text-sm text-ink-700"><x-icon name="check-circle" class="w-5 h-5 text-brand-600 shrink-0" /> {{ $amenity }}</div>
                    @endforeach
                </div>
                @endif

                @if ($property->video_url || $property->virtual_tour_url)
                <h3 class="mt-8 font-heading font-bold text-lg text-ink-900">Video Walkthrough &amp; Virtual Tour</h3>
                <div class="mt-4 grid sm:grid-cols-2 gap-4">
                    @if ($property->video_url)
                        <a href="{{ $property->video_url }}" target="_blank" rel="noopener" class="card p-5 flex items-center gap-3 hover:shadow-lg transition">
                            <span class="w-11 h-11 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon name="video-camera" class="w-5 h-5" /></span>
                            <div>
                                <div class="font-semibold text-sm text-ink-900">Video Walkthrough</div>
                                <div class="text-xs text-ink-500">Watch on external player &rarr;</div>
                            </div>
                        </a>
                    @endif
                    @if ($property->virtual_tour_url)
                        <a href="{{ $property->virtual_tour_url }}" target="_blank" rel="noopener" class="card p-5 flex items-center gap-3 hover:shadow-lg transition">
                            <span class="w-11 h-11 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon name="camera" class="w-5 h-5" /></span>
                            <div>
                                <div class="font-semibold text-sm text-ink-900">360&deg; Virtual Tour</div>
                                <div class="text-xs text-ink-500">Explore interactively &rarr;</div>
                            </div>
                        </a>
                    @endif
                </div>
                @endif
            </div>

            <aside class="space-y-6">
                <div class="card p-6">
                    <div class="font-heading font-extrabold text-2xl text-brand-600">
                        PKR {{ number_format($property->price) }}
                        @if ($property->price_period === 'month')<span class="text-sm font-normal text-ink-400">/ month</span>@endif
                        @if ($property->price_period === 'night')<span class="text-sm font-normal text-ink-400">/ night</span>@endif
                    </div>
                    <p class="mt-1 text-xs text-ink-500">Managed fully by GATED Property Services</p>
                    <a href="{{ route('contact.show') }}" class="btn-primary w-full mt-5 justify-center">Enquire About This Property</a>
                    <a href="tel:+923001234567" class="btn-outline w-full mt-3 justify-center">Call +92 300 1234567</a>
                </div>

                <div class="card p-6">
                    <h3 class="font-heading font-bold text-ink-900 text-sm">Why book through GATED?</h3>
                    <ul class="mt-3 space-y-2 text-sm text-ink-600">
                        <li class="flex items-center gap-2"><x-icon name="shield-check" class="w-4 h-4 text-brand-600" /> Verified &amp; professionally managed</li>
                        <li class="flex items-center gap-2"><x-icon name="clock" class="w-4 h-4 text-brand-600" /> 24/7 client support</li>
                        <li class="flex items-center gap-2"><x-icon name="document-text" class="w-4 h-4 text-brand-600" /> Transparent lease documentation</li>
                    </ul>
                </div>
            </aside>
        </div>

        @if ($related->count())
        <div class="mt-16 pt-10 border-t border-ink-100">
            <h3 class="font-heading font-bold text-ink-900 mb-6">Similar Properties</h3>
            <div class="grid sm:grid-cols-3 gap-8">
                @foreach ($related as $r)
                    @include('properties.partials.card', ['property' => $r])
                @endforeach
            </div>
        </div>
        @endif
    </section>

@endsection
