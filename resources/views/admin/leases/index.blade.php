@extends('layouts.admin')

@section('title', 'Leases & Tenants')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div class="flex gap-2">
            <a href="{{ route('admin.leases.index') }}" class="badge {{ !request('status') ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">All</a>
            <a href="{{ route('admin.leases.index', ['status' => 'active']) }}" class="badge {{ request('status') === 'active' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Active</a>
            <a href="{{ route('admin.leases.index', ['status' => 'ended']) }}" class="badge {{ request('status') === 'ended' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Ended</a>
        </div>
        <a href="{{ route('admin.leases.create') }}" class="btn-primary !py-2.5 !px-4 text-sm">Add Lease</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3">Tenant</th>
                        <th class="text-left px-6 py-3">Property</th>
                        <th class="text-left px-6 py-3">Rent</th>
                        <th class="text-left px-6 py-3">Start Date</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-right px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($leases as $lease)
                        <tr class="hover:bg-ink-50/50">
                            <td class="px-6 py-4 font-medium text-ink-900">{{ $lease->tenant_name }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $lease->property->title }}</td>
                            <td class="px-6 py-4 text-ink-600">PKR {{ number_format($lease->rent_amount) }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $lease->start_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4"><span class="badge bg-ink-100 text-ink-600">{{ ucfirst($lease->status) }}</span></td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.leases.edit', $lease) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">Edit</a>
                                <form method="POST" action="{{ route('admin.leases.destroy', $lease) }}" class="inline" onsubmit="return confirm('Delete this lease?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-ink-400 font-semibold text-xs hover:text-brand-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-ink-500">No leases found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $leases->links() }}</div>

@endsection
