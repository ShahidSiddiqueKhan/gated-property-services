@extends('layouts.portal')

@section('title', 'Messages')

@section('content')

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-4">
            @forelse ($threads as $thread)
                <div class="card p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h3 class="font-heading font-bold text-ink-900">{{ $thread->subject }}</h3>
                            <p class="text-xs text-ink-400 mt-0.5">{{ $thread->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="badge {{ $thread->status === 'open' ? 'bg-emerald-100 text-emerald-700' : 'bg-ink-100 text-ink-600' }}">{{ ucfirst($thread->status) }}</span>
                    </div>
                    <p class="mt-3 text-sm text-ink-600">{{ $thread->body }}</p>

                    @if ($thread->replies->count())
                        <div class="mt-4 pl-4 border-l-2 border-ink-100 space-y-3">
                            @foreach ($thread->replies as $reply)
                                <div>
                                    <div class="text-xs font-semibold {{ $reply->sender === 'staff' ? 'text-brand-600' : 'text-ink-700' }}">{{ $reply->sender === 'staff' ? 'GATED Support' : 'You' }} &middot; {{ $reply->created_at->diffForHumans() }}</div>
                                    <p class="text-sm text-ink-600 mt-1">{{ $reply->body }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('portal.messages.store') }}" class="mt-4 flex gap-2">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $thread->id }}">
                        <input type="hidden" name="subject" value="Re: {{ $thread->subject }}">
                        <input type="text" name="body" required placeholder="Write a reply..." class="flex-1 rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                        <button type="submit" class="btn-primary !px-4 text-sm"><x-icon name="paper-airplane" class="w-4 h-4" /></button>
                    </form>
                </div>
            @empty
                <div class="card p-10 text-center text-ink-500">No messages yet. Start a new conversation below.</div>
            @endforelse
        </div>

        <aside class="card p-6 h-fit">
            <h3 class="font-heading font-bold text-ink-900 mb-4">New Message</h3>
            <form method="POST" action="{{ route('portal.messages.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="text-xs font-semibold text-ink-700">Subject</label>
                    <input type="text" name="subject" required class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label class="text-xs font-semibold text-ink-700">Message</label>
                    <textarea name="body" rows="4" required class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500"></textarea>
                </div>
                <button type="submit" class="btn-primary w-full justify-center text-sm">Send to Support</button>
            </form>
            <div class="mt-5 pt-5 border-t border-ink-100 text-xs text-ink-500 space-y-2">
                <p class="flex items-center gap-2"><x-icon name="phone" class="w-4 h-4 text-brand-600" /> +92 300 1234567</p>
                <p class="flex items-center gap-2"><x-icon name="envelope" class="w-4 h-4 text-brand-600" /> info@gatedpropertyservices.com</p>
                <p class="flex items-center gap-2"><x-icon name="clock" class="w-4 h-4 text-brand-600" /> Avg. response time: under 1 hour</p>
            </div>
        </aside>
    </div>

@endsection
