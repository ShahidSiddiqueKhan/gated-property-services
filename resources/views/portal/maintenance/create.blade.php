@extends('layouts.portal')

@section('title', 'New Maintenance Request')

@section('content')

    <a href="{{ route('portal.maintenance.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Maintenance</a>

    <div class="max-w-2xl mt-4">
        <div class="card p-6 sm:p-8">
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-brand-50 border border-brand-200 text-brand-800 text-sm p-4">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('portal.maintenance.store') }}" enctype="multipart/form-data" class="space-y-5">
                @csrf
                <div>
                    <label class="text-sm font-semibold text-ink-700">Property</label>
                    <select name="property_id" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        <option value="">Select a property</option>
                        @foreach ($properties as $property)
                            <option value="{{ $property->id }}" @selected(old('property_id') == $property->id)>{{ $property->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-sm font-semibold text-ink-700">Issue Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Kitchen tap leaking" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Category</label>
                        <select name="category" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                            @foreach (['plumbing' => 'Plumbing', 'electrical' => 'Electrical', 'structural' => 'Structural', 'appliance' => 'Appliance', 'pest_control' => 'Pest Control', 'painting' => 'Painting', 'other' => 'Other'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('category') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Priority</label>
                        <select name="priority" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                            @foreach (['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'emergency' => 'Emergency'] as $val => $label)
                                <option value="{{ $val }}" @selected(old('priority') === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="text-sm font-semibold text-ink-700">Description</label>
                    <textarea name="description" rows="5" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="text-sm font-semibold text-ink-700 block mb-2">Photos (optional)</label>
                    <label class="flex flex-col items-center justify-center gap-2 border-2 border-dashed border-ink-200 rounded-xl p-6 cursor-pointer hover:border-brand-400 transition text-center">
                        <x-icon name="camera" class="w-7 h-7 text-ink-400" />
                        <span class="text-sm font-semibold text-ink-700">Upload photos of the issue</span>
                        <input type="file" name="images[]" multiple accept="image/*" class="hidden">
                    </label>
                </div>

                <button type="submit" class="btn-primary w-full sm:w-auto justify-center">Submit Request</button>
            </form>
        </div>
    </div>

@endsection
