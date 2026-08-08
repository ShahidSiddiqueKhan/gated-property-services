@extends('layouts.admin')

@section('title', 'Fee Tiers')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-ink-500 max-w-2xl">Tiered percentage fees GATED charges on maintenance contractor invoices and renovation project values. FeeCalculator picks the row whose amount range matches automatically.</p>
        <a href="{{ route('admin.fee-tiers.create') }}" class="btn-primary !py-2.5 !px-4 text-sm shrink-0">Add Tier</a>
    </div>

    @foreach ($tiersByCategory as $category => $tiers)
        <div class="card overflow-hidden mb-6">
            <div class="px-5 py-3 bg-ink-50 border-b border-ink-100 font-heading font-bold text-ink-900 text-sm">
                {{ $category === 'maintenance' ? 'Maintenance Coordination' : 'Renovation Project Management' }}
            </div>
            <table class="w-full text-sm">
                <thead class="text-ink-500 text-xs uppercase tracking-wide">
                    <tr>
                        <th class="text-left px-5 py-2">Range (PKR)</th>
                        <th class="text-left px-5 py-2">Fee %</th>
                        <th class="text-right px-5 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($tiers as $tier)
                        <tr>
                            <td class="px-5 py-3 text-ink-700">
                                {{ number_format($tier->min_amount, 0) }} &ndash; {{ $tier->max_amount ? number_format($tier->max_amount, 0) : 'no limit' }}
                            </td>
                            <td class="px-5 py-3 font-semibold text-ink-900">{{ rtrim(rtrim(number_format($tier->fee_percent, 2), '0'), '.') }}%</td>
                            <td class="px-5 py-3 text-right">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.fee-tiers.edit', $tier) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">Edit</a>
                                    <form method="POST" action="{{ route('admin.fee-tiers.destroy', $tier) }}" onsubmit="return confirm('Delete this tier?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-ink-400 font-semibold text-xs hover:text-brand-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-5 py-6 text-center text-ink-500">No tiers set — falls back to 0% until added.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @endforeach

@endsection
