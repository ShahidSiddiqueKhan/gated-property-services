@extends('layouts.admin')

@section('title', 'Properties')

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.properties.index') }}" class="badge {{ !request('status') ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">All ({{ $counts['all'] }})</a>
            <a href="{{ route('admin.properties.index', ['status' => 'pending_review']) }}" class="badge {{ request('status') === 'pending_review' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Pending Review ({{ $counts['pending_review'] }})</a>
            <a href="{{ route('admin.properties.index', ['status' => 'occupied']) }}" class="badge {{ request('status') === 'occupied' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Occupied ({{ $counts['occupied'] }})</a>
            <a href="{{ route('admin.properties.index', ['status' => 'vacant']) }}" class="badge {{ request('status') === 'vacant' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Vacant ({{ $counts['vacant'] }})</a>
        </div>
        <a href="{{ route('admin.properties.create') }}" class="btn-primary !py-2.5 !px-4 text-sm">Add Property</a>
    </div>

    <form method="GET" class="mb-6 flex gap-3">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title or reference..." class="w-full max-w-sm rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
        <button type="submit" class="btn-outline !py-2.5 !px-4 text-sm"><x-icon name="magnifying-glass" class="w-4 h-4" /></button>
    </form>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3">Property</th>
                        <th class="text-left px-6 py-3">Owner</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-left px-6 py-3">Price</th>
                        <th class="text-left px-6 py-3">Featured</th>
                        <th class="text-right px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($properties as $property)
                        @php
                            $statusColors = ['occupied' => 'bg-emerald-100 text-emerald-700', 'vacant' => 'bg-amber-100 text-amber-700', 'maintenance' => 'bg-blue-100 text-blue-700', 'pending_review' => 'bg-brand-100 text-brand-700'];
                        @endphp
                        <tr class="hover:bg-ink-50/50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-ink-900">{{ $property->title }}</div>
                                <div class="text-xs text-ink-400">{{ $property->reference_no }}</div>
                            </td>
                            <td class="px-6 py-4 text-ink-600">{{ $property->owner?->name ?? 'Unassigned' }}</td>
                            <td class="px-6 py-4"><span class="badge {{ $statusColors[$property->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$property->status)) }}</span></td>
                            <td class="px-6 py-4 font-semibold text-ink-900">PKR {{ number_format($property->price) }}</td>
                            <td class="px-6 py-4">
                                <form method="POST" action="{{ route('admin.properties.toggle-featured', $property) }}">
                                    @csrf
                                    <button type="submit" class="badge {{ $property->is_featured ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-500' }}">{{ $property->is_featured ? 'Featured' : 'Standard' }}</button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                @if ($property->status === 'pending_review')
                                    <form method="POST" action="{{ route('admin.properties.approve', $property) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-emerald-600 font-semibold text-xs hover:text-emerald-700">Approve</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.properties.show', $property) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">View</a>
                                <a href="{{ route('admin.properties.edit', $property) }}" class="text-ink-500 font-semibold text-xs hover:text-ink-700">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-ink-500">No properties found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $properties->links() }}</div>

@endsection
