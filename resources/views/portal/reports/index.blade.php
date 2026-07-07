@extends('layouts.portal')

@section('title', 'Financial Reports')

@section('content')

    <div class="flex items-center justify-end mb-6">
        <button onclick="window.print()" class="btn-outline !py-2.5 !px-4 text-sm"><x-icon name="document-arrow-down" class="w-4 h-4" /> Print / Save as PDF</button>
    </div>

    <div class="grid sm:grid-cols-2 gap-5 mb-6">
        <div class="card p-5">
            <div class="text-xs text-ink-500">Total Income (This Year)</div>
            <div class="text-2xl font-heading font-extrabold text-emerald-600 mt-1">PKR {{ number_format($totalIncomeYear) }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs text-ink-500">Outstanding Balance</div>
            <div class="text-2xl font-heading font-extrabold text-brand-600 mt-1">PKR {{ number_format($totalDue) }}</div>
        </div>
    </div>

    <div class="card p-6 mb-6">
        <h2 class="font-heading font-bold text-ink-900 mb-6">Income Overview (Last 12 Months)</h2>
        @php $max = max(1, $months->max('income')); @endphp
        <div class="flex items-end gap-2 h-56 overflow-x-auto">
            @foreach ($months as $m)
                <div class="flex-1 min-w-[32px] flex flex-col items-center gap-2">
                    <div class="w-full bg-ink-100 rounded-t-md flex items-end" style="height: 100%">
                        <div class="w-full bg-gradient-to-t from-brand-600 to-brand-400 rounded-t-md" style="height: {{ $m['income'] > 0 ? max(6, ($m['income'] / $max) * 100) : 4 }}%"></div>
                    </div>
                    <div class="text-[10px] font-semibold text-ink-500 rotate-0 whitespace-nowrap">{{ substr($m['label'], 0, 3) }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="p-6 border-b border-ink-100"><h2 class="font-heading font-bold text-ink-900">Income by Property</h2></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3">Property</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-right px-6 py-3">Total Paid</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($properties as $property)
                        <tr>
                            <td class="px-6 py-4 font-medium text-ink-900">{{ $property->title }}</td>
                            <td class="px-6 py-4 text-ink-600 capitalize">{{ str_replace('_',' ',$property->status) }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-ink-900">PKR {{ number_format($property->paid_total ?? 0) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="px-6 py-10 text-center text-ink-500">No properties yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
