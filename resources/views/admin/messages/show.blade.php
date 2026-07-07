@extends('layouts.admin')

@section('title', $thread->subject)
@section('subtitle', 'From ' . $thread->user->name)

@section('content')

    <div class="flex items-center justify-between mb-4">
        <a href="{{ route('admin.messages.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Messages</a>
        @if ($thread->status === 'open')
            <form method="POST" action="{{ route('admin.messages.resolve', $thread) }}">
                @csrf
                <button type="submit" class="btn-outline !py-2 !px-4 text-sm">Mark Resolved</button>
            </form>
        @endif
    </div>

    <div class="max-w-2xl card p-6">
        <div>
            <div class="text-xs font-semibold text-ink-700">{{ $thread->user->name }} &middot; {{ $thread->created_at->diffForHumans() }}</div>
            <p class="text-sm text-ink-600 mt-1">{{ $thread->body }}</p>
        </div>

        @if ($thread->replies->count())
            <div class="mt-4 pl-4 border-l-2 border-ink-100 space-y-4">
                @foreach ($thread->replies as $reply)
                    <div>
                        <div class="text-xs font-semibold {{ $reply->sender === 'staff' ? 'text-brand-600' : 'text-ink-700' }}">{{ $reply->sender === 'staff' ? 'GATED Support (You)' : $thread->user->name }} &middot; {{ $reply->created_at->diffForHumans() }}</div>
                        <p class="text-sm text-ink-600 mt-1">{{ $reply->body }}</p>
                    </div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.messages.reply', $thread) }}" class="mt-6 pt-6 border-t border-ink-100 space-y-3">
            @csrf
            <label class="text-sm font-semibold text-ink-700">Reply as GATED Support</label>
            <textarea name="body" rows="4" required class="w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500"></textarea>
            <button type="submit" class="btn-primary text-sm">Send Reply</button>
        </form>
    </div>

@endsection
