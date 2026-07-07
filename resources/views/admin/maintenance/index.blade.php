@extends('layouts.admin')

@section('title', 'Maintenance Requests')

@section('content')

    <div class="flex flex-wrap items-center gap-2 mb-6">
        <a href="{{ route('admin.maintenance.index') }}" class="badge {{ !request('status') ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">All</a>
        <a href="{{ route('admin.maintenance.index', ['status' => 'submitted']) }}" class="badge {{ request('status') === 'submitted' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">New ({{ $counts['submitted'] }})</a>
        <a href="{{ route('admin.maintenance.index', ['status' => 'in_progress']) }}" class="badge {{ request('status') === 'in_progress' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">In Progress ({{ $counts['in_progress'] }})</a>
        <a href="{{ route('admin.maintenance.index', ['priority' => 'emergency']) }}" class="badge {{ request('priority') === 'emergency' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Emergency ({{ $counts['emergency'] }})</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3">Ticket</th>
                        <th class="text-left px-6 py-3">Property</th>
                        <th class="text-left px-6 py-3">Client</th>
                        <th class="text-left px-6 py-3">Priority</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-right px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($requests as $request)
                        @php
                            $statusColors = ['submitted' => 'bg-ink-100 text-ink-600', 'acknowledged' => 'bg-blue-100 text-blue-700', 'in_progress' => 'bg-amber-100 text-amber-700', 'completed' => 'bg-emerald-100 text-emerald-700', 'cancelled' => 'bg-ink-100 text-ink-500'];
                            $priorityColors = ['low' => 'bg-ink-100 text-ink-600', 'medium' => 'bg-blue-100 text-blue-700', 'high' => 'bg-amber-100 text-amber-700', 'emergency' => 'bg-brand-100 text-brand-700'];
                        @endphp
                        <tr class="hover:bg-ink-50/50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-ink-900">{{ $request->title }}</div>
                                <div class="text-xs text-ink-400">{{ $request->ticket_no }}</div>
                            </td>
                            <td class="px-6 py-4 text-ink-600">{{ $request->property->title }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $request->user->name }}</td>
                            <td class="px-6 py-4"><span class="badge {{ $priorityColors[$request->priority] ?? '' }}">{{ ucfirst($request->priority) }}</span></td>
                            <td class="px-6 py-4"><span class="badge {{ $statusColors[$request->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$request->status)) }}</span></td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.maintenance.show', $request) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">Manage &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-ink-500">No maintenance requests found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $requests->links() }}</div>

@endsection
