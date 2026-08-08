@php
    $cover = $property->coverImage ?? $property->images->first();
    $imageUrl = \App\Support\Media::url($cover?->path, 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80');
    $statusColors = [
        'occupied' => 'bg-emerald-100 text-emerald-700',
        'vacant' => 'bg-amber-100 text-amber-700',
        'maintenance' => 'bg-blue-100 text-blue-700',
        'pending_review' => 'bg-ink-100 text-ink-600',
    ];
@endphp

<a href="{{ route('properties.show', $property) }}" class="card overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
    <div class="relative aspect-[4/3] overflow-hidden">
        <img src="{{ $imageUrl }}" alt="{{ $property->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <span class="absolute top-3 left-3 badge bg-ink-950/90 text-white">{{ $property->listing_type === 'sale' ? 'For Sale' : 'For Rent' }}</span>
        <span class="absolute top-3 right-3 badge {{ $statusColors[$property->status] ?? 'bg-ink-100 text-ink-600' }}">{{ ucfirst(str_replace('_', ' ', $property->status)) }}</span>
    </div>
    <div class="p-6 flex flex-col flex-1">
        <h3 class="font-heading font-bold text-ink-900 group-hover:text-brand-600 transition">{{ $property->title }}</h3>
        <p class="text-sm text-ink-500 mt-1 flex items-center gap-1"><x-icon name="map-pin" class="w-4 h-4" /> {{ $property->area_location }}, {{ $property->city }}</p>

        <div class="mt-4 flex items-center gap-4 text-xs text-ink-500">
            @if ($property->bedrooms)
                <span>{{ $property->bedrooms }} Bed</span>
            @endif
            @if ($property->bathrooms)
                <span>{{ $property->bathrooms }} Bath</span>
            @endif
            @if ($property->size_label)
                <span>{{ $property->size_label }}</span>
            @endif
        </div>

        <div class="mt-5 pt-4 border-t border-ink-100 flex items-center justify-between">
            <span class="font-heading font-extrabold text-brand-600">
                PKR {{ number_format($property->price) }}
                @if ($property->price_period === 'month')<span class="text-xs font-normal text-ink-400">/ month</span>@endif
                @if ($property->price_period === 'night')<span class="text-xs font-normal text-ink-400">/ night</span>@endif
            </span>
            <span class="text-sm font-semibold text-ink-900 group-hover:text-brand-600 transition">View &rarr;</span>
        </div>
    </div>
</a>
