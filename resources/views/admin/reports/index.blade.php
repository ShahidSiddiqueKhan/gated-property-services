@extends('layouts.admin')

@section('title', 'Portfolio Reports')

@section('content')

    <div class="flex justify-end mb-6">
        <button onclick="window.print()" class="btn-outline !py-2.5 !px-4 text-sm"><x-icon name="document-arrow-down" class="w-4 h-4" /> Print / Save as PDF</button>
    </div>

    <div class="grid sm:grid-cols-3 gap-5 mb-6">
        <div class="card p-5">
            <div class="text-xs text-ink-500">Total Income (This Year)</div>
            <div class="text-2xl font-heading font-extrabold text-emerald-600 mt-1">PKR {{ number_format($totalIncomeYear) }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs text-ink-500">Outstanding Balance</div>
            <div class="text-2xl font-heading font-extrabold text-brand-600 mt-1">PKR {{ number_format($totalOutstanding) }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs text-ink-500">Occupancy Rate</div>
            <div class="text-2xl font-heading font-extrabold text-ink-900 mt-1">{{ $occupancyRate }}%</div>
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
                    <div class="text-[10px] font-semibold text-ink-500 whitespace-nowrap">{{ substr($m['label'], 0, 3) }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
        <div class="card overflow-hidden">
            <div class="p-6 border-b border-ink-100"><h2 class="font-heading font-bold text-ink-900">Top Earning Properties</h2></div>
            <div class="divide-y divide-ink-100">
                @forelse ($topProperties as $property)
                    <div class="px-6 py-3 flex items-center justify-between text-sm">
                        <span class="text-ink-800">{{ $property->title }}</span>
                        <span class="font-semibold text-ink-900">PKR {{ number_format($property->paid_total ?? 0) }}</span>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-ink-500">No data yet.</div>
                @endforelse
            </div>
        </div>

        <div class="card overflow-hidden">
            <div class="p-6 border-b border-ink-100"><h2 class="font-heading font-bold text-ink-900">Properties by City</h2></div>
            <div class="divide-y divide-ink-100">
                @forelse ($propertiesByCity as $row)
                    <div class="px-6 py-3 flex items-center justify-between text-sm">
                        <span class="text-ink-800">{{ $row->city ?? 'Unspecified' }}</span>
                        <span class="font-semibold text-ink-900">{{ $row->total }}</span>
                    </div>
                @empty
                    <div class="px-6 py-10 text-center text-ink-500">No data yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    <div class="card overflow-hidden mt-6">
        <div class="p-6 border-b border-ink-100"><h2 class="font-heading font-bold text-ink-900">Maintenance by Category</h2></div>
        <div class="grid sm:grid-cols-3 lg:grid-cols-6 divide-x divide-ink-100">
            @forelse ($maintenanceByCategory as $row)
                <div class="p-4 text-center">
                    <div class="text-xl font-heading font-extrabold text-ink-900">{{ $row->total }}</div>
                    <div class="text-xs text-ink-500 capitalize">{{ str_replace('_',' ',$row->category) }}</div>
                </div>
            @empty
                <div class="p-10 text-center text-ink-500 col-span-full">No maintenance data yet.</div>
            @endforelse
        </div>
    </div>

@endsection
