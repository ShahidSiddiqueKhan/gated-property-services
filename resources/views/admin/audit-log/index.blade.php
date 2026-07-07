@extends('layouts.admin')

@section('title', 'Audit Log')
@section('subtitle', 'A record of key actions taken across the Admin Portal for security and compliance.')

@section('content')

    <form method="GET" class="mb-6 flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search actions..." class="w-full max-w-sm rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
        <button type="submit" class="btn-outline !py-2.5 !px-4 text-sm"><x-icon name="magnifying-glass" class="w-4 h-4" /></button>
    </form>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3">Action</th>
                        <th class="text-left px-6 py-3">Description</th>
                        <th class="text-left px-6 py-3">User</th>
                        <th class="text-left px-6 py-3">IP Address</th>
                        <th class="text-left px-6 py-3">When</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($logs as $log)
                        <tr class="hover:bg-ink-50/50">
                            <td class="px-6 py-4 font-medium text-ink-900">{{ $log->action }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $log->description }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $log->user?->name ?? 'System' }}</td>
                            <td class="px-6 py-4 text-ink-400 text-xs">{{ $log->ip_address }}</td>
                            <td class="px-6 py-4 text-ink-400 text-xs">{{ $log->created_at->format('M d, Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-ink-500">No activity recorded yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $logs->links() }}</div>

@endsection
