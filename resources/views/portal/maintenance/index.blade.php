@extends('layouts.portal')

@section('title', 'Maintenance')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-ink-500">Submit and track maintenance requests for your properties.</p>
        <a href="{{ route('portal.maintenance.create') }}" class="btn-primary !py-2.5 !px-4 text-sm">New Request</a>
    </div>

    <div class="grid gap-4">
        @forelse ($requests as $request)
            @php
                $statusColors = ['submitted' => 'bg-ink-100 text-ink-600', 'acknowledged' => 'bg-blue-100 text-blue-700', 'in_progress' => 'bg-amber-100 text-amber-700', 'completed' => 'bg-emerald-100 text-emerald-700', 'cancelled' => 'bg-ink-100 text-ink-500'];
                $priorityColors = ['low' => 'bg-ink-100 text-ink-600', 'medium' => 'bg-blue-100 text-blue-700', 'high' => 'bg-amber-100 text-amber-700', 'emergency' => 'bg-brand-100 text-brand-700'];
            @endphp
            <a href="{{ route('portal.maintenance.show', $request) }}" class="card p-5 flex items-center justify-between gap-4 hover:shadow-lg transition">
                <div class="flex items-center gap-4 min-w-0">
                    <span class="w-11 h-11 rounded-xl bg-ink-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon name="wrench-screwdriver" class="w-5 h-5" /></span>
                    <div class="min-w-0">
                        <div class="font-semibold text-ink-900 text-sm truncate">{{ $request->title }}</div>
                        <div class="text-xs text-ink-500">{{ $request->ticket_no }} &middot; {{ $request->property->title }}</div>
                    </div>
                </div>
                <div class="flex items-center gap-2 shrink-0">
                    <span class="badge {{ $priorityColors[$request->priority] ?? '' }}">{{ ucfirst($request->priority) }}</span>
                    <span class="badge {{ $statusColors[$request->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$request->status)) }}</span>
                </div>
            </a>
        @empty
            <div class="card p-10 text-center">
                <p class="text-ink-500">No maintenance requests yet.</p>
                <a href="{{ route('portal.maintenance.create') }}" class="btn-primary mt-4">Submit a Request</a>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $requests->links() }}</div>

@endsection
