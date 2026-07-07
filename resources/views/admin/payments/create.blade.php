@extends('layouts.admin')

@section('title', 'Create Invoice')

@section('content')

    <a href="{{ route('admin.payments.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Payments</a>

    <div class="max-w-2xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.payments.store') }}" class="space-y-4">
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
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-ink-700">Type</label>
                    <select name="type" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        <option value="rent">Rent</option>
                        <option value="service">Service Fee</option>
                        <option value="invoice">General Invoice</option>
                        <option value="maintenance">Maintenance Charge</option>
                    </select>
                </div>
                <div>
                    <label class="text-sm font-semibold text-ink-700">Amount (PKR)</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
            </div>
            <div>
                <label class="text-sm font-semibold text-ink-700">Due Date</label>
                <input type="date" name="due_date" value="{{ old('due_date') }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            </div>
            <div>
                <label class="text-sm font-semibold text-ink-700">Notes</label>
                <textarea name="notes" rows="3" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('notes') }}</textarea>
            </div>
            <button type="submit" class="btn-primary">Create Invoice</button>
        </form>
    </div>

@endsection
