@extends('layouts.admin')

@section('title', $property->title)
@section('subtitle', 'Ref: ' . $property->reference_no)

@section('content')

    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.properties.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Properties</a>
        <div class="flex gap-2">
            @if ($property->status === 'pending_review')
                <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                    @csrf
                    <button type="submit" class="btn-primary !py-2 !px-4 text-sm">Approve &amp; Publish</button>
                </form>
            @endif
            <a href="{{ route('admin.properties.edit', $property) }}" class="btn-outline !py-2 !px-4 text-sm">Edit</a>
            <form method="POST" action="{{ route('admin.properties.destroy', $property) }}" onsubmit="return confirm('Delete this property permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-outline !py-2 !px-4 text-sm !border-brand-600 !text-brand-600 hover:!bg-brand-600 hover:!text-white">Delete</button>
            </form>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <div class="flex items-center gap-2 mb-4">
                    @php $statusColors = ['occupied' => 'bg-emerald-100 text-emerald-700', 'vacant' => 'bg-amber-100 text-amber-700', 'maintenance' => 'bg-blue-100 text-blue-700', 'pending_review' => 'bg-brand-100 text-brand-700']; @endphp
                    <span class="badge {{ $statusColors[$property->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$property->status)) }}</span>
                    @if ($property->is_featured)<span class="badge bg-brand-600 text-white">Featured</span>@endif
                    <span class="badge bg-ink-100 text-ink-600">{{ $property->published_at ? 'Published' : 'Not Published' }}</span>
                </div>
                <h2 class="font-heading font-bold text-xl text-ink-900">{{ $property->title }}</h2>
                <p class="text-sm text-ink-500 mt-1">{{ $property->address }} &middot; Owner: {{ $property->owner?->name ?? 'Unassigned' }}</p>
                <p class="mt-4 text-sm text-ink-600 leading-relaxed">{{ $property->description }}</p>

                @if ($property->images->count())
                    <div class="mt-5 grid grid-cols-4 gap-2">
                        @foreach ($property->images as $img)
                            <img src="{{ \App\Support\Media::url($img->path) }}" class="rounded-lg aspect-square object-cover">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="card p-6">
                <h3 class="font-heading font-bold text-ink-900 mb-4">Leases</h3>
                @forelse ($property->leases as $lease)
                    <div class="flex items-center justify-between py-2 border-b border-ink-100 last:border-0 text-sm">
                        <span>{{ $lease->tenant_name }} &middot; PKR {{ number_format($lease->rent_amount) }}</span>
                        <span class="badge bg-ink-100 text-ink-600">{{ ucfirst($lease->status) }}</span>
                    </div>
                @empty
                    <p class="text-sm text-ink-500">No lease records.</p>
                @endforelse
                <a href="{{ route('admin.leases.create') }}" class="btn-outline mt-4 text-sm !py-2 !px-4">Add Lease</a>
            </div>

            <div class="card p-6">
                <h3 class="font-heading font-bold text-ink-900 mb-4">Maintenance History</h3>
                @forelse ($property->maintenanceRequests as $mr)
                    <a href="{{ route('admin.maintenance.show', $mr) }}" class="flex items-center justify-between py-2 border-b border-ink-100 last:border-0 text-sm hover:bg-ink-50/50">
                        <span>{{ $mr->title }}</span>
                        <span class="badge bg-ink-100 text-ink-600">{{ ucfirst(str_replace('_',' ',$mr->status)) }}</span>
                    </a>
                @empty
                    <p class="text-sm text-ink-500">No maintenance history.</p>
                @endforelse
            </div>
        </div>

        <aside class="space-y-6">
            <div class="card p-6">
                <div class="font-heading font-extrabold text-2xl text-brand-600">PKR {{ number_format($property->price) }}<span class="text-sm font-normal text-ink-400">/{{ $property->price_period }}</span></div>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-ink-500">Type</dt><dd class="text-ink-800 capitalize">{{ str_replace('_',' ',$property->type) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink-500">Bedrooms</dt><dd class="text-ink-800">{{ $property->bedrooms ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink-500">Bathrooms</dt><dd class="text-ink-800">{{ $property->bathrooms ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink-500">Size</dt><dd class="text-ink-800">{{ $property->size_label ?? '—' }}</dd></div>
                </dl>
                @if ($property->published_at)
                    <a href="{{ route('properties.show', $property) }}" target="_blank" class="btn-outline w-full mt-4 justify-center text-sm">View Public Listing</a>
                @endif
            </div>

            @if (!empty($property->legal_documents))
                <div class="card p-6">
                    <h3 class="font-heading font-bold text-ink-900 text-sm mb-3">Legal Documents</h3>
                    @foreach ($property->legal_documents as $doc)
                        <a href="{{ \App\Support\Media::url($doc) }}" target="_blank" class="block text-sm text-brand-600 hover:text-brand-700 py-1">Document {{ $loop->iteration }}</a>
                    @endforeach
                </div>
            @endif

            @if (!empty($property->services_requested))
                <div class="card p-6">
                    <h3 class="font-heading font-bold text-ink-900 text-sm mb-3">Services Requested</h3>
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($property->services_requested as $service)
                            <span class="badge bg-ink-100 text-ink-600">{{ ucwords(str_replace('-',' ',$service)) }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>

@endsection
