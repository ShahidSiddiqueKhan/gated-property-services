@extends('layouts.admin')

@section('title', 'Upload Document')

@section('content')

    <a href="{{ route('admin.documents.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Documents</a>

    <div class="max-w-2xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.documents.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label class="text-sm font-semibold text-ink-700">Client</label>
                <select name="user_id" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    <option value="">Select client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" @selected(old('user_id') == $client->id)>{{ $client->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold text-ink-700">Property (optional)</label>
                <select name="property_id" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    <option value="">None</option>
                    @foreach ($properties as $property)
                        <option value="{{ $property->id }}" @selected(old('property_id') == $property->id)>{{ $property->title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold text-ink-700">Document Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            </div>
            <div>
                <label class="text-sm font-semibold text-ink-700">Document Type</label>
                <select name="type" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    <option value="lease_agreement">Lease Agreement</option>
                    <option value="inspection_report">Inspection Report</option>
                    <option value="invoice">Invoice</option>
                    <option value="tax_document">Tax Document</option>
                    <option value="legal">Legal</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div>
                <label class="text-sm font-semibold text-ink-700 block mb-2">File</label>
                <input type="file" name="file" required class="w-full text-sm">
            </div>
            <button type="submit" class="btn-primary">Upload Document</button>
        </form>
    </div>

@endsection
