@extends('layouts.admin')

@section('title', 'Renovation Projects')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.renovations.index') }}" class="badge {{ !request('status') ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">All</a>
            <a href="{{ route('admin.renovations.index', ['status' => 'proposed']) }}" class="badge {{ request('status') === 'proposed' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Proposed ({{ $counts['proposed'] }})</a>
            <a href="{{ route('admin.renovations.index', ['status' => 'in_progress']) }}" class="badge {{ request('status') === 'in_progress' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">In Progress ({{ $counts['in_progress'] }})</a>
            <a href="{{ route('admin.renovations.index', ['status' => 'completed']) }}" class="badge {{ request('status') === 'completed' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Completed ({{ $counts['completed'] }})</a>
        </div>
        <a href="{{ route('admin.renovations.create') }}" class="btn-primary !py-2.5 !px-4 text-sm shrink-0">New Project</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3">Project</th>
                        <th class="text-left px-6 py-3">Property</th>
                        <th class="text-left px-6 py-3">Value</th>
                        <th class="text-left px-6 py-3">GATED Fee</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-right px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @php
                        $statusColors = ['proposed' => 'bg-ink-100 text-ink-600', 'approved' => 'bg-blue-100 text-blue-700', 'in_progress' => 'bg-amber-100 text-amber-700', 'completed' => 'bg-emerald-100 text-emerald-700', 'cancelled' => 'bg-ink-100 text-ink-500'];
                    @endphp
                    @forelse ($projects as $project)
                        <tr class="hover:bg-ink-50/50">
                            <td class="px-6 py-4">
                                <div class="font-medium text-ink-900">{{ $project->title }}</div>
                                @if ($project->approval_status === 'pending')
                                    <div class="text-xs text-amber-600">Awaiting approval</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-ink-600">{{ $project->property->title }}</td>
                            <td class="px-6 py-4 text-ink-800">PKR {{ number_format($project->project_value, 0) }}</td>
                            <td class="px-6 py-4 font-semibold text-brand-600">PKR {{ number_format($project->fee_amount, 0) }} ({{ rtrim(rtrim(number_format($project->fee_percent, 2), '0'), '.') }}%)</td>
                            <td class="px-6 py-4"><span class="badge {{ $statusColors[$project->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$project->status)) }}</span></td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.renovations.show', $project) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">Manage &rarr;</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-ink-500">No renovation projects yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $projects->links() }}</div>

@endsection
