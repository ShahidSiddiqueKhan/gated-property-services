@extends('layouts.portal')

@section('title', 'Task Center')

@section('content')

    @php
        $statusColors = ['pending' => 'bg-ink-100 text-ink-600', 'in_progress' => 'bg-amber-100 text-amber-700', 'completed' => 'bg-emerald-100 text-emerald-700'];
    @endphp

    <div class="space-y-4">
        @forelse ($tasks as $task)
            <div class="card p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-start gap-4 min-w-0">
                    <span class="w-11 h-11 rounded-xl bg-ink-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon name="check-circle" class="w-5 h-5" /></span>
                    <div class="min-w-0">
                        <h3 class="font-heading font-bold text-ink-900">{{ $task->title }}</h3>
                        @if ($task->description)<p class="text-sm text-ink-500 mt-1">{{ $task->description }}</p>@endif
                        <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-ink-400">
                            @if ($task->property)<span>{{ $task->property->title }}</span>@endif
                            @if ($task->assigned_to)<span>Assigned: {{ $task->assigned_to }}</span>@endif
                            @if ($task->due_date)<span>Due: {{ $task->due_date->format('M d, Y') }}</span>@endif
                        </div>
                    </div>
                </div>
                <span class="badge {{ $statusColors[$task->status] ?? '' }} shrink-0">{{ ucfirst(str_replace('_',' ',$task->status)) }}</span>
            </div>
        @empty
            <div class="card p-10 text-center text-ink-500">No tasks to show right now.</div>
        @endforelse
    </div>

@endsection
