@extends('layouts.app')

@section('title', $property->title . ' | GATED Property Services')
@section('meta_description', \Illuminate\Support\Str::limit($property->description, 155))

@section('content')

    @php
        $fallbackImage = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1200&q=80';
        $cover = $property->images->firstWhere('is_cover', true) ?? $property->images->first();
        $galleryImages = $property->images->count()
            ? $property->images->map(fn ($img) => \App\Support\Media::url($img->path))->values()
            : collect([\App\Support\Media::url($cover?->path, $fallbackImage)]);
        $coverIndex = max(0, $galleryImages->search(\App\Support\Media::url($cover?->path, $fallbackImage)) ?: 0);
    @endphp

    <div x-data="{
            activeIndex: {{ $coverIndex }},
            images: {{ \Illuminate\Support\Js::from($galleryImages) }},
            lightboxOpen: false,
            next() { this.activeIndex = (this.activeIndex + 1) % this.images.length; },
            prev() { this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length; },
        }"
    >
        <section class="bg-ink-950">
            <div class="relative h-[380px] lg:h-[460px] cursor-zoom-in" @click="lightboxOpen = true">
                <template x-for="(img, i) in images" :key="i">
                    <img :src="img" x-show="activeIndex === i" x-cloak class="absolute inset-0 w-full h-full object-cover opacity-70" alt="{{ $property->title }}">
                </template>
                <div class="absolute inset-0 bg-gradient-to-t from-ink-950 via-ink-950/40 to-transparent pointer-events-none"></div>

                <template x-if="images.length > 1">
                    <button type="button" @click.stop="prev()" class="absolute left-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition backdrop-blur-sm">
                        <x-icon name="chevron-right" class="w-5 h-5 rotate-180" />
                    </button>
                </template>
                <template x-if="images.length > 1">
                    <button type="button" @click.stop="next()" class="absolute right-4 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition backdrop-blur-sm">
                        <x-icon name="chevron-right" class="w-5 h-5" />
                    </button>
                </template>

                <template x-if="images.length > 1">
                    <div class="absolute top-4 right-4 badge bg-ink-950/70 text-white flex items-center gap-1.5">
                        <x-icon name="camera" class="w-3.5 h-3.5" />
                        <span><span x-text="activeIndex + 1"></span> / <span x-text="images.length"></span></span>
                    </div>
                </template>

                <div class="absolute inset-x-0 bottom-0 pointer-events-none">
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

        @if ($galleryImages->count() > 1)
        <section class="bg-white py-6 border-b border-ink-100">
            <div class="max-w-7xl mx-auto px-6 grid grid-cols-4 sm:grid-cols-6 gap-3">
                <template x-for="(img, i) in images" :key="i">
                    <button
                        type="button"
                        @click="activeIndex = i"
                        class="relative rounded-lg overflow-hidden aspect-square group transition-all duration-200"
                        :class="activeIndex === i ? 'ring-2 ring-brand-600 ring-offset-2' : 'opacity-80 hover:opacity-100'"
                    >
                        <img :src="img" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" alt="{{ $property->title }} photo">
                    </button>
                </template>
            </div>
        </section>
        @endif

        {{-- Fullscreen lightbox, OLX/Zameen-style --}}
        <div
            x-show="lightboxOpen"
            x-cloak
            x-transition.opacity
            @click="lightboxOpen = false"
            @keydown.escape.window="lightboxOpen = false"
            @keydown.arrow-right.window="next()"
            @keydown.arrow-left.window="prev()"
            class="fixed inset-0 z-[100] bg-ink-950/95 flex flex-col items-center justify-center p-4"
        >
            <button type="button" @click.stop="lightboxOpen = false" class="absolute top-5 right-5 w-11 h-11 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                <x-icon name="x-mark" class="w-6 h-6" />
            </button>

            <template x-if="images.length > 1">
                <button type="button" @click.stop="prev()" class="absolute left-3 sm:left-8 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                    <x-icon name="chevron-right" class="w-6 h-6 rotate-180" />
                </button>
            </template>
            <template x-if="images.length > 1">
                <button type="button" @click.stop="next()" class="absolute right-3 sm:right-8 top-1/2 -translate-y-1/2 w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition">
                    <x-icon name="chevron-right" class="w-6 h-6" />
                </button>
            </template>

            <div class="flex-1 flex items-center justify-center w-full max-h-[75vh]" @click.stop>
                <template x-for="(img, i) in images" :key="i">
                    <img :src="img" x-show="activeIndex === i" x-cloak class="max-w-full max-h-[75vh] object-contain rounded-lg">
                </template>
            </div>

            <template x-if="images.length > 1">
                <div class="mt-5 flex items-center gap-2 max-w-full overflow-x-auto px-4 pb-1" @click.stop>
                    <template x-for="(img, i) in images" :key="i">
                        <button type="button" @click="activeIndex = i" class="shrink-0 w-14 h-14 rounded-md overflow-hidden transition-all duration-200" :class="activeIndex === i ? 'ring-2 ring-brand-500' : 'opacity-50 hover:opacity-80'">
                            <img :src="img" class="w-full h-full object-cover">
                        </button>
                    </template>
                </div>
            </template>

            <div class="mt-3 text-white/70 text-sm" x-show="images.length > 1">
                <span x-text="activeIndex + 1"></span> / <span x-text="images.length"></span>
            </div>
        </div>
    </div>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2" data-reveal>
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
                        <a href="{{ $property->video_url }}" target="_blank" rel="noopener" class="card p-5 flex items-center gap-3 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
                            <span class="w-11 h-11 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon name="video-camera" class="w-5 h-5" /></span>
                            <div>
                                <div class="font-semibold text-sm text-ink-900">Video Walkthrough</div>
                                <div class="text-xs text-ink-500">Watch on external player &rarr;</div>
                            </div>
                        </a>
                    @endif
                    @if ($property->virtual_tour_url)
                        <a href="{{ $property->virtual_tour_url }}" target="_blank" rel="noopener" class="card p-5 flex items-center gap-3 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300">
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

            <aside class="space-y-6" data-reveal data-reveal-delay="2">
                <div class="card p-6">
                    <div class="font-heading font-extrabold text-2xl text-brand-600">
                        PKR {{ number_format($property->price) }}
                        @if ($property->price_period === 'month')<span class="text-sm font-normal text-ink-400">/ month</span>@endif
                        @if ($property->price_period === 'night')<span class="text-sm font-normal text-ink-400">/ night</span>@endif
                    </div>
                    <p class="mt-1 text-xs text-ink-500">Managed fully by GATED Property Services</p>
                    <a href="{{ route('contact.show') }}" class="btn-primary w-full mt-5 justify-center">Enquire About This Property</a>
                    <a href="tel:+923215381128" class="btn-outline w-full mt-3 justify-center">Call +92 321 5381128</a>
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
                    <div data-reveal data-reveal-delay="{{ min($loop->iteration, 6) }}">
                        @include('properties.partials.card', ['property' => $r])
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </section>

@endsection
