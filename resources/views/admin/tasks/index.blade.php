@extends('layouts.admin')

@section('title', 'Task Center')

@section('content')

    @php $statusColors = ['pending' => 'bg-ink-100 text-ink-600', 'in_progress' => 'bg-amber-100 text-amber-700', 'completed' => 'bg-emerald-100 text-emerald-700']; @endphp

    <div class="flex items-center justify-between mb-6">
        <div class="flex gap-2">
            <a href="{{ route('admin.tasks.index') }}" class="badge {{ !request('status') ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">All</a>
            <a href="{{ route('admin.tasks.index', ['status' => 'pending']) }}" class="badge {{ request('status') === 'pending' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Pending</a>
            <a href="{{ route('admin.tasks.index', ['status' => 'in_progress']) }}" class="badge {{ request('status') === 'in_progress' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">In Progress</a>
        </div>
        <a href="{{ route('admin.tasks.create') }}" class="btn-primary !py-2.5 !px-4 text-sm">Assign Task</a>
    </div>

    <div class="space-y-3">
        @forelse ($tasks as $task)
            <div class="card p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="min-w-0">
                    <h3 class="font-heading font-bold text-sm text-ink-900">{{ $task->title }}</h3>
                    <div class="text-xs text-ink-500 mt-1">{{ $task->user->name }} @if($task->property) &middot; {{ $task->property->title }} @endif @if($task->assigned_to) &middot; Assigned: {{ $task->assigned_to }} @endif</div>
                </div>
                <form method="POST" action="{{ route('admin.tasks.update', $task) }}" class="flex items-center gap-2 shrink-0">
                    @csrf @method('PUT')
                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-ink-200 text-xs focus:border-brand-500 focus:ring-brand-500">
                        <option value="pending" @selected($task->status === 'pending')>Pending</option>
                        <option value="in_progress" @selected($task->status === 'in_progress')>In Progress</option>
                        <option value="completed" @selected($task->status === 'completed')>Completed</option>
                    </select>
                    <input type="hidden" name="assigned_to" value="{{ $task->assigned_to }}">
                </form>
            </div>
        @empty
            <div class="card p-10 text-center text-ink-500">No tasks yet.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $tasks->links() }}</div>

@endsection
