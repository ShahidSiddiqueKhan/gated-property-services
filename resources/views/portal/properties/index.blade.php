@extends('layouts.portal')

@section('title', 'My Properties')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-ink-500">{{ $properties->total() }} total propert{{ $properties->total() === 1 ? 'y' : 'ies' }} under GATED management.</p>
        <a href="{{ route('property-registration.create') }}" class="btn-primary !py-2.5 !px-4 text-sm">Register New Property</a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($properties as $property)
            @php
                $cover = $property->coverImage;
                $imageUrl = \App\Support\Media::url($cover?->path, 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=600&q=80');
                $statusColors = ['occupied' => 'bg-emerald-100 text-emerald-700', 'vacant' => 'bg-amber-100 text-amber-700', 'maintenance' => 'bg-blue-100 text-blue-700', 'pending_review' => 'bg-ink-100 text-ink-600'];
            @endphp
            <a href="{{ route('portal.properties.show', $property) }}" class="card overflow-hidden group hover:shadow-lg transition">
                <div class="relative aspect-[4/3]">
                    <img src="{{ $imageUrl }}" class="w-full h-full object-cover" alt="{{ $property->title }}">
                    <span class="absolute top-3 right-3 badge {{ $statusColors[$property->status] ?? 'bg-ink-100 text-ink-600' }}">{{ ucfirst(str_replace('_',' ',$property->status)) }}</span>
                </div>
                <div class="p-5">
                    <h3 class="font-heading font-bold text-ink-900 group-hover:text-brand-600 transition">{{ $property->title }}</h3>
                    <p class="text-xs text-ink-500 mt-1">{{ $property->area_location }}, {{ $property->city }} &middot; Ref: {{ $property->reference_no }}</p>
                    <div class="mt-3 flex items-center justify-between text-sm">
                        <span class="font-heading font-bold text-brand-600">PKR {{ number_format($property->price) }}<span class="text-xs font-normal text-ink-400">/{{ $property->price_period }}</span></span>
                        @if ($property->activeLease)
                            <span class="text-xs text-ink-500">Tenant: {{ $property->activeLease->tenant_name }}</span>
                        @endif
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full card p-10 text-center">
                <p class="text-ink-500">You haven't registered any properties yet.</p>
                <a href="{{ route('property-registration.create') }}" class="btn-primary mt-4">Register Your First Property</a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $properties->links() }}</div>

@endsection
