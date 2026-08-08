@extends('layouts.admin')

@section('title', 'Packages')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-ink-500">Manage subscription packages (Basic, Premium, Full Valet, or custom) and their default rent commission.</p>
        <a href="{{ route('admin.packages.create') }}" class="btn-primary !py-2.5 !px-4 text-sm">Add Package</a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($packages as $package)
            <div class="card p-6">
                <div class="flex items-start justify-between">
                    <h3 class="font-heading font-bold text-ink-900">{{ $package->name }}</h3>
                    <span class="badge {{ $package->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-ink-100 text-ink-600' }}">{{ $package->is_active ? 'Active' : 'Hidden' }}</span>
                </div>
                <p class="mt-2 text-sm text-ink-500">{{ $package->description }}</p>
                <div class="mt-4 flex items-baseline gap-1">
                    <span class="text-2xl font-heading font-bold text-ink-900">PKR {{ number_format($package->monthly_price, 0) }}</span>
                    <span class="text-xs text-ink-400">/ month</span>
                </div>
                <p class="mt-1 text-xs text-ink-500">Rent commission: <span class="font-semibold text-ink-700">{{ rtrim(rtrim(number_format($package->rent_commission_percent, 2), '0'), '.') }}%</span></p>
                <p class="mt-1 text-xs text-ink-400">{{ $package->property_packages_count }} propert{{ $package->property_packages_count === 1 ? 'y' : 'ies' }} subscribed</p>
                <div class="mt-4 flex items-center justify-between pt-4 border-t border-ink-100">
                    <div class="flex gap-3">
                        <a href="{{ route('admin.packages.edit', $package) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">Edit</a>
                        <form method="POST" action="{{ route('admin.packages.destroy', $package) }}" onsubmit="return confirm('Delete this package?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-ink-400 font-semibold text-xs hover:text-brand-600">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-ink-500 col-span-3">No packages yet.</p>
        @endforelse
    </div>

@endsection
