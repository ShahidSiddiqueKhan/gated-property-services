@extends('layouts.admin')

@section('title', 'Documents')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-ink-500">All documents shared with clients across the portfolio.</p>
        <a href="{{ route('admin.documents.create') }}" class="btn-primary !py-2.5 !px-4 text-sm">Upload Document</a>
    </div>

    <div class="card overflow-hidden">
        <div class="divide-y divide-ink-100">
            @forelse ($documents as $document)
                <div class="p-5 flex items-center justify-between gap-4 hover:bg-ink-50/50 transition">
                    <div class="flex items-center gap-4 min-w-0">
                        <span class="w-11 h-11 rounded-xl bg-ink-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon name="document-text" class="w-5 h-5" /></span>
                        <div class="min-w-0">
                            <div class="font-semibold text-sm text-ink-900 truncate">{{ $document->title }}</div>
                            <div class="text-xs text-ink-500">{{ $document->user->name }} @if($document->property) &middot; {{ $document->property->title }} @endif &middot; {{ $document->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 shrink-0">
                        <a href="{{ \App\Support\Media::url($document->file_path) }}" target="_blank" class="text-brand-600 font-semibold text-xs hover:text-brand-700">View</a>
                        <form method="POST" action="{{ route('admin.documents.destroy', $document) }}" onsubmit="return confirm('Delete this document?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-ink-400 font-semibold text-xs hover:text-brand-600">Delete</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="p-10 text-center text-ink-500">No documents uploaded yet.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-6">{{ $documents->links() }}</div>

@endsection
