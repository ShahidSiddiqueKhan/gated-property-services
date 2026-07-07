@extends('layouts.admin')

@section('title', 'Edit Client')

@section('content')

    <a href="{{ route('admin.clients.show', $client) }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to {{ $client->name }}</a>

    <div class="max-w-2xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.clients.update', $client) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-semibold text-ink-700">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $client->name) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label class="text-sm font-semibold text-ink-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $client->email) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label class="text-sm font-semibold text-ink-700">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $client->phone) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label class="text-sm font-semibold text-ink-700">Company Name</label>
                    <input type="text" name="company_name" value="{{ old('company_name', $client->company_name) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label class="text-sm font-semibold text-ink-700">Country</label>
                    <input type="text" name="country" value="{{ old('country', $client->country) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div class="flex items-end pb-2.5">
                    <label class="flex items-center gap-2 text-sm text-ink-600">
                        <input type="checkbox" name="is_overseas" value="1" @checked($client->is_overseas) class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> Overseas owner
                    </label>
                </div>
                <div class="sm:col-span-2">
                    <label class="text-sm font-semibold text-ink-700">Reset Password <span class="font-normal text-ink-400">(leave blank to keep current)</span></label>
                    <input type="password" name="new_password" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                </div>
            </div>
            <button type="submit" class="btn-primary">Save Changes</button>
        </form>
    </div>

@endsection
