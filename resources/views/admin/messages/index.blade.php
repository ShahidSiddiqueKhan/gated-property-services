@extends('layouts.admin')

@section('title', 'Messages')

@section('content')

    <div class="flex flex-wrap items-center gap-2 mb-6">
        <a href="{{ route('admin.messages.index') }}" class="badge {{ !request('status') ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">All</a>
        <a href="{{ route('admin.messages.index', ['status' => 'open']) }}" class="badge {{ request('status') === 'open' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Open</a>
        <a href="{{ route('admin.messages.index', ['status' => 'resolved']) }}" class="badge {{ request('status') === 'resolved' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Resolved</a>
    </div>

    <div class="space-y-3">
        @forelse ($threads as $thread)
            <a href="{{ route('admin.messages.show', $thread) }}" class="card p-5 flex items-center justify-between gap-4 hover:shadow-lg transition">
                <div class="min-w-0">
                    <div class="font-semibold text-sm text-ink-900 truncate">{{ $thread->subject }}</div>
                    <div class="text-xs text-ink-500">{{ $thread->user->name }} &middot; {{ $thread->replies->count() }} repl{{ $thread->replies->count() === 1 ? 'y' : 'ies' }} &middot; {{ $thread->created_at->diffForHumans() }}</div>
                </div>
                <span class="badge {{ $thread->status === 'open' ? 'bg-emerald-100 text-emerald-700' : 'bg-ink-100 text-ink-600' }} shrink-0">{{ ucfirst($thread->status) }}</span>
            </a>
        @empty
            <div class="card p-10 text-center text-ink-500">No messages found.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $threads->links() }}</div>

@endsection
