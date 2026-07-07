@extends('layouts.admin')

@section('title', 'Assign Task')

@section('content')

    <a href="{{ route('admin.tasks.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Tasks</a>

    <div class="max-w-2xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.tasks.store') }}" class="space-y-4">
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
                <label class="text-sm font-semibold text-ink-700">Task Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            </div>
            <div>
                <label class="text-sm font-semibold text-ink-700">Description</label>
                <textarea name="description" rows="3" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('description') }}</textarea>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-ink-700">Assigned To (Staff)</label>
                    <input type="text" name="assigned_to" value="{{ old('assigned_to') }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label class="text-sm font-semibold text-ink-700">Due Date</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
            </div>
            <button type="submit" class="btn-primary">Create Task</button>
        </form>
    </div>

@endsection
