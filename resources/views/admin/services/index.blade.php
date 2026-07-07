@extends('layouts.admin')

@section('title', 'Services')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-ink-500">Manage the services shown on your homepage and services page.</p>
        <a href="{{ route('admin.services.create') }}" class="btn-primary !py-2.5 !px-4 text-sm">Add Service</a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($services as $service)
            <div class="card p-6">
                <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center"><x-icon :name="$service->icon" class="w-6 h-6" /></div>
                <h3 class="mt-4 font-heading font-bold text-ink-900">{{ $service->name }}</h3>
                <p class="mt-2 text-sm text-ink-500">{{ $service->short_description }}</p>
                <div class="mt-4 flex items-center justify-between pt-4 border-t border-ink-100">
                    <span class="badge {{ $service->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-ink-100 text-ink-600' }}">{{ $service->is_active ? 'Active' : 'Hidden' }}</span>
                    <div class="flex gap-3">
                        <a href="{{ route('admin.services.edit', $service) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">Edit</a>
                        <form method="POST" action="{{ route('admin.services.destroy', $service) }}" onsubmit="return confirm('Delete this service?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-ink-400 font-semibold text-xs hover:text-brand-600">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-ink-500 col-span-3">No services yet.</p>
        @endforelse
    </div>

@endsection
