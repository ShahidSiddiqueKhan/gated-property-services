@extends('layouts.portal')

@section('title', $property->title)
@section('subtitle', 'Ref: ' . $property->reference_no)

@section('content')

    <a href="{{ route('portal.properties.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to My Properties</a>

    <div class="grid lg:grid-cols-3 gap-6 mt-4">
        <div class="lg:col-span-2 space-y-6">
            <div class="card overflow-hidden">
                @php
                    $cover = $property->images->firstWhere('is_cover', true) ?? $property->images->first();
                    $imageUrl = \App\Support\Media::url($cover?->path, 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1000&q=80');
                @endphp
                <img src="{{ $imageUrl }}" class="w-full aspect-video object-cover" alt="{{ $property->title }}">
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
