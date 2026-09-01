@extends('layouts.portal')

@section('title', $property->title)
@section('subtitle', 'Ref: ' . $property->reference_no)

@section('content')

    <a href="{{ route('portal.properties.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to My Properties</a>

    @php
        $fallbackImage = 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1000&q=80';
        $cover = $property->images->firstWhere('is_cover', true) ?? $property->images->first();
        $galleryImages = $property->images->count()
            ? $property->images->map(fn ($img) => \App\Support\Media::url($img->path))->values()
            : collect([\App\Support\Media::url($cover?->path, $fallbackImage)]);
        $coverIndex = max(0, $galleryImages->search(\App\Support\Media::url($cover?->path, $fallbackImage)) ?: 0);
    @endphp

    <div class="grid lg:grid-cols-3 gap-6 mt-4">
        <div class="lg:col-span-2 space-y-6">
            <div class="card overflow-hidden" x-data="{
                    activeIndex: {{ $coverIndex }},
                    images: {{ \Illuminate\Support\Js::from($galleryImages) }},
                    lightboxOpen: false,
                    next() { this.activeIndex = (this.activeIndex + 1) % this.images.length; },
                    prev() { this.activeIndex = (this.activeIndex - 1 + this.images.length) % this.images.length; },
                }">
                <div class="relative aspect-video cursor-zoom-in" @click="lightboxOpen = true">
                    <template x-for="(img, i) in images" :key="i">
                        <img :src="img" x-show="activeIndex === i" x-cloak class="absolute inset-0 w-full h-full object-cover" alt="{{ $property->title }}">
                    </template>
                    <template x-if="images.length > 1">
                        <button type="button" @click.stop="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-ink-950/50 hover:bg-ink-950/70 text-white flex items-center justify-center transition">
                            <x-icon name="chevron-right" class="w-4 h-4 rotate-180" />
                        </button>
                    </template>
                    <template x-if="images.length > 1">
                        <button type="button" @click.stop="next()" class="absolute right-3 top-1/2 -translate-y-1/2 w-9 h-9 rounded-full bg-ink-950/50 hover:bg-ink-950/70 text-white flex items-center justify-center transition">
                            <x-icon name="chevron-right" class="w-4 h-4" />
                        </button>
                    </template>
                    <template x-if="images.length > 1">
                        <div class="absolute top-3 right-3 badge bg-ink-950/70 text-white flex items-center gap-1.5">
                            <x-icon name="camera" class="w-3.5 h-3.5" />
                            <span><span x-text="activeIndex + 1"></span> / <span x-text="images.length"></span></span>
                        </div>
                    </template>
                </div>

                @if ($galleryImages->count() > 1)
                    <div class="p-3 grid grid-cols-5 sm:grid-cols-6 gap-2 border-b border-ink-100">
                        <template x-for="(img, i) in images" :key="i">
                            <button type="button" @click="activeIndex = i" class="relative rounded-md overflow-hidden aspect-square transition-all duration-200" :class="activeIndex === i ? 'ring-2 ring-brand-600' : 'opacity-70 hover:opacity-100'">
                                <img :src="img" class="w-full h-full object-cover">
                            </button>
                        </template>
                    </div>
                @endif

                {{-- Lightbox --}}
                <div x-show="lightboxOpen" x-cloak x-transition.opacity @click="lightboxOpen = false" @keydown.escape.window="lightboxOpen = false" @keydown.arrow-right.window="next()" @keydown.arrow-left.window="prev()" class="fixed inset-0 z-[100] bg-ink-950/95 flex flex-col items-center justify-center p-4">
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
                </div>

                <div class="p-6">
                    <h2 class="font-heading font-bold text-xl text-ink-900">{{ $property->title }}</h2>
                    <p class="text-sm text-ink-500 mt-1 flex items-center gap-1"><x-icon name="map-pin" class="w-4 h-4" /> {{ $property->address }}</p>
                    <p class="mt-4 text-sm text-ink-600 leading-relaxed">{{ $property->description }}</p>
                    <div class="mt-5 grid grid-cols-3 gap-3 text-center">
                        @if ($property->bedrooms)<div class="rounded-lg bg-ink-50 p-3"><div class="font-bold text-ink-900">{{ $property->bedrooms }}</div><div class="text-xs text-ink-500">Beds</div></div>@endif
                        @if ($property->bathrooms)<div class="rounded-lg bg-ink-50 p-3"><div class="font-bold text-ink-900">{{ $property->bathrooms }}</div><div class="text-xs text-ink-500">Baths</div></div>@endif
                        @if ($property->size_label)<div class="rounded-lg bg-ink-50 p-3"><div class="font-bold text-ink-900">{{ $property->size_label }}</div><div class="text-xs text-ink-500">Size</div></div>@endif
                    </div>
                </div>
            </div>

            @if (!empty($property->legal_documents))
                <div class="card p-6">
                    <h3 class="font-heading font-bold text-ink-900 mb-4">Legal Documents</h3>
                    <div class="space-y-2">
                        @foreach ($property->legal_documents as $doc)
                            @php
                                $path = is_array($doc) ? ($doc['path'] ?? null) : $doc;
                                $name = is_array($doc) ? ($doc['name'] ?? 'Document ' . $loop->iteration) : 'Document ' . $loop->iteration;
                                $size = is_array($doc) ? ($doc['size'] ?? null) : null;
                                $ext = strtoupper(pathinfo($name, PATHINFO_EXTENSION)) ?: 'FILE';
                            @endphp
                            <a href="{{ \App\Support\Media::url($path) }}" target="_blank" class="flex items-center gap-3 rounded-lg border border-ink-100 p-3 hover:border-brand-300 hover:bg-brand-50/30 transition">
                                <span class="w-9 h-9 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center text-[10px] font-bold shrink-0">{{ $ext }}</span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-ink-800 truncate">{{ $name }}</div>
                                    @if ($size)
                                        <div class="text-xs text-ink-400">{{ $size < 1024*1024 ? number_format($size / 1024, 1) . ' KB' : number_format($size / 1024 / 1024, 1) . ' MB' }}</div>
                                    @endif
                                </div>
                                <x-icon name="arrow-right" class="w-4 h-4 text-ink-400 shrink-0" />
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($property->leases->count())
            <div class="card p-6">
                <h3 class="font-heading font-bold text-ink-900 mb-4">Tenant / Lease Information</h3>
                <div class="space-y-3">
                    @foreach ($property->leases as $lease)
                        <div class="flex items-center justify-between rounded-lg bg-ink-50 p-4">
                            <div>
                                <div class="font-semibold text-sm text-ink-900">{{ $lease->tenant_name }}</div>
                                <div class="text-xs text-ink-500">{{ $lease->tenant_phone }} &middot; Since {{ $lease->start_date->format('M Y') }}</div>
                            </div>
                            <div class="text-right">
                                <div class="font-heading font-bold text-brand-600">PKR {{ number_format($lease->rent_amount) }}</div>
                                <span class="badge {{ $lease->status === 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-ink-100 text-ink-600' }}">{{ ucfirst($lease->status) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-heading font-bold text-ink-900">Recent Maintenance</h3>
                    <a href="{{ route('portal.maintenance.index') }}" class="text-xs font-semibold text-brand-600">View All</a>
                </div>
                @forelse ($property->maintenanceRequests->take(3) as $mr)
                    <a href="{{ route('portal.maintenance.show', $mr) }}" class="flex items-center justify-between py-2 border-b border-ink-100 last:border-0">
                        <span class="text-sm text-ink-800">{{ $mr->title }}</span>
                        <span class="badge bg-ink-100 text-ink-600">{{ ucfirst(str_replace('_',' ',$mr->status)) }}</span>
                    </a>
                @empty
                    <p class="text-sm text-ink-500">No maintenance requests for this property.</p>
                @endforelse
            </div>
        </div>

        <aside class="space-y-6">
            <div class="card p-6">
                <div class="badge {{ $property->status === 'occupied' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} mb-3">{{ ucfirst(str_replace('_',' ',$property->status)) }}</div>
                <div class="font-heading font-extrabold text-2xl text-brand-600">PKR {{ number_format($property->price) }}<span class="text-sm font-normal text-ink-400">/{{ $property->price_period }}</span></div>
                <a href="{{ route('portal.maintenance.create') }}" class="btn-primary w-full mt-5 justify-center">Request Maintenance</a>
                <a href="{{ route('portal.documents.index') }}" class="btn-outline w-full mt-3 justify-center">View Documents</a>
            </div>

            <div class="card p-6">
                <h3 class="font-heading font-bold text-ink-900 text-sm mb-3">Payment History</h3>
                @forelse ($property->payments->take(4) as $payment)
                    <div class="flex items-center justify-between py-2 border-b border-ink-100 last:border-0 text-sm">
                        <span class="text-ink-600">{{ $payment->due_date?->format('M Y') }}</span>
                        <span class="font-semibold {{ $payment->status === 'paid' ? 'text-emerald-600' : 'text-brand-600' }}">{{ ucfirst($payment->status) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-ink-500">No payment records yet.</p>
                @endforelse
            </div>
        </aside>
    </div>

@endsection
