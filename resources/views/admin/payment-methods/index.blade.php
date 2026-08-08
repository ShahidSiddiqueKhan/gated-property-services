@extends('layouts.admin')

@section('title', 'Payment Methods')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-ink-500 max-w-2xl">Manage every way clients can pay — local bank transfers, mobile wallets, cards, and overseas options. Live gateways (Stripe, JazzCash) are locked to their checkout code; everything else is a manual method you fully control.</p>
        <a href="{{ route('admin.payment-methods.create') }}" class="btn-primary !py-2.5 !px-4 text-sm shrink-0">Add Method</a>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-ink-50 text-ink-500 text-xs uppercase tracking-wide">
                <tr>
                    <th class="text-left px-5 py-3">Method</th>
                    <th class="text-left px-5 py-3">Type</th>
                    <th class="text-left px-5 py-3">Region</th>
                    <th class="text-left px-5 py-3">Status</th>
                    <th class="text-right px-5 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @forelse ($paymentMethods as $method)
                    <tr>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2 font-semibold text-ink-800">
                                <x-icon :name="$method->icon ?? 'banknotes'" class="w-4 h-4 text-brand-600" />
                                {{ $method->name }}
                            </div>
                        </td>
                        <td class="px-5 py-3">
                            <span class="badge {{ $method->isGateway() ? 'bg-brand-50 text-brand-700' : 'bg-ink-100 text-ink-600' }}">{{ $method->isGateway() ? 'Live Gateway' : 'Manual' }}</span>
                        </td>
                        <td class="px-5 py-3 text-ink-600 capitalize">{{ $method->region }}</td>
                        <td class="px-5 py-3">
                            <span class="badge {{ $method->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-ink-100 text-ink-600' }}">{{ $method->is_active ? 'Active' : 'Hidden' }}</span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex justify-end gap-3">
                                <a href="{{ route('admin.payment-methods.edit', $method) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">Edit</a>
                                @unless ($method->isGateway())
                                    <form method="POST" action="{{ route('admin.payment-methods.destroy', $method) }}" onsubmit="return confirm('Delete this payment method?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-ink-400 font-semibold text-xs hover:text-brand-600">Delete</button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-5 py-8 text-center text-ink-500">No payment methods yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

@endsection
