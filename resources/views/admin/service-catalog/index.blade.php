@extends('layouts.admin')

@section('title', 'Service Catalog')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-ink-500 max-w-2xl">Standalone, add-on services billed outside package fees — advertising/marketing add-ons and emergency call-outs. Prices are fully admin-editable.</p>
        <a href="{{ route('admin.service-catalog.create') }}" class="btn-primary !py-2.5 !px-4 text-sm shrink-0">Add Item</a>
    </div>

    @foreach ($itemsByCategory as $category => $items)
        <div class="card overflow-hidden mb-6">
            <div class="px-5 py-3 bg-ink-50 border-b border-ink-100 font-heading font-bold text-ink-900 text-sm">
                {{ $category === 'advertising' ? 'Property Advertising' : 'Emergency Services' }}
            </div>
            <table class="w-full text-sm">
                <thead class="text-ink-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-5 py-2">Name</th>
                        <th class="text-left px-5 py-2">Price</th>
                        <th class="text-left px-5 py-2">Status</th>
                        <th class="text-right px-5 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($items as $item)
                        <tr>
                            <td class="px-5 py-3">
                                <div class="font-semibold text-ink-800">{{ $item->name }}</div>
                                @if ($item->description)
                                    <div class="text-xs text-ink-500">{{ $item->description }}</div>
                                @endif
                            </td>
                            <td class="px-5 py-3 font-semibold text-ink-900">{{ $item->priceLabel() }}</td>
                            <td class="px-5 py-3">
                                <span class="badge {{ $item->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-ink-100 text-ink-600' }}">{{ $item->is_active ? 'Active' : 'Hidden' }}</span>
                            </td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.service-catalog.edit', $item) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">Edit</a>
                                    <form method="POST" action="{{ route('admin.service-catalog.destroy', $item) }}" onsubmit="return confirm('Delete this item?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-ink-400 font-semibold text-xs hover:text-brand-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-5 py-6 text-center text-ink-500">No items yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach

@endsection
