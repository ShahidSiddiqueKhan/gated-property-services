@extends('layouts.portal')

@section('title', 'Documents')

@section('content')

    @php
        $types = ['lease_agreement' => 'Lease Agreement', 'inspection_report' => 'Inspection Report', 'invoice' => 'Invoice', 'tax_document' => 'Tax Document', 'legal' => 'Legal', 'other' => 'Other'];
    @endphp

    <div class="flex flex-wrap items-center gap-2 mb-6">
        <a href="{{ route('portal.documents.index') }}" class="badge {{ !request('type') ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">All</a>
        @foreach ($types as $val => $label)
            <a href="{{ route('portal.documents.index', ['type' => $val]) }}" class="badge {{ request('type') === $val ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="card overflow-hidden">
        <div class="divide-y divide-ink-100">
            @forelse ($documents as $document)
                <div class="p-5 flex items-center justify-between gap-4 hover:bg-ink-50/50 transition">
                    <div class="flex items-center gap-4 min-w-0">
                        <span class="w-11 h-11 rounded-xl bg-ink-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon name="document-text" class="w-5 h-5" /></span>
                        <div class="min-w-0">
                            <div class="font-semibold text-sm text-ink-900 truncate">{{ $document->title }}</div>
                            <div class="text-xs text-ink-500">{{ $types[$document->type] ?? ucfirst($document->type) }} @if($document->property) &middot; {{ $document->property->title }} @endif &middot; {{ $document->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                    <a href="{{ \App\Support\Media::url($document->file_path) }}" target="_blank" class="btn-outline !py-2 !px-3 text-xs shrink-0">
                        <x-icon name="document-arrow-down" class="w-4 h-4" /> Download
                    </a>
                </div>
            @empty
                <div class="p-10 text-center text-ink-500">No documents available yet.</div>
            @endforelse
        </div>
    </div>

    <div class="mt-6">{{ $documents->links() }}</div>

@endsection
