@extends('layouts.admin')

@section('title', 'Promotions')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-ink-500">Seasonal offers and discounts shown on the homepage banner.</p>
        <a href="{{ route('admin.promotions.create') }}" class="btn-primary !py-2.5 !px-4 text-sm">Add Promotion</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3">Title</th>
                        <th class="text-left px-6 py-3">Discount</th>
                        <th class="text-left px-6 py-3">Valid Until</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-right px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($promotions as $promo)
                        <tr class="hover:bg-ink-50/50">
                            <td class="px-6 py-4 font-medium text-ink-900">{{ $promo->title }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $promo->discount_label ?? '—' }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $promo->valid_until?->format('M d, Y') ?? 'No expiry' }}</td>
                            <td class="px-6 py-4"><span class="badge {{ $promo->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-ink-100 text-ink-600' }}">{{ $promo->is_active ? 'Active' : 'Inactive' }}</span></td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.promotions.edit', $promo) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">Edit</a>
                                <form method="POST" action="{{ route('admin.promotions.destroy', $promo) }}" class="inline" onsubmit="return confirm('Delete this promotion?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-ink-400 font-semibold text-xs hover:text-brand-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-ink-500">No promotions yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $promotions->links() }}</div>

@endsection
