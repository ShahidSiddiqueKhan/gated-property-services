@extends('layouts.admin')

@section('title', 'Clients')

@section('content')

    <form method="GET" class="mb-6 flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email..." class="w-full max-w-sm rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
        <button type="submit" class="btn-outline !py-2.5 !px-4 text-sm"><x-icon name="magnifying-glass" class="w-4 h-4" /> Search</button>
    </form>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3">Name</th>
                        <th class="text-left px-6 py-3">Email</th>
                        <th class="text-left px-6 py-3">Phone</th>
                        <th class="text-left px-6 py-3">Properties</th>
                        <th class="text-left px-6 py-3">Type</th>
                        <th class="text-right px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($clients as $client)
                        <tr class="hover:bg-ink-50/50">
                            <td class="px-6 py-4 font-medium text-ink-900">{{ $client->name }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $client->email }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $client->phone ?? '—' }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $client->properties_count }}</td>
                            <td class="px-6 py-4">
                                @if ($client->is_overseas)
                                    <span class="badge bg-blue-100 text-blue-700">Overseas</span>
                                @else
                                    <span class="badge bg-ink-100 text-ink-600">Local</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.clients.show', $client) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">View &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-ink-500">No clients found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $clients->links() }}</div>

@endsection
