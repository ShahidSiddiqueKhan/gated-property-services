@extends('layouts.portal')

@section('title', 'Financials')

@section('content')

    @if ($properties->isEmpty())
        <div class="card p-10 text-center text-ink-500">No properties on your account yet.</div>
    @else
        <div class="flex flex-wrap items-center gap-2 mb-6">
            <a href="{{ route('portal.finances.index') }}" class="badge {{ !request('property') ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">All Properties (Combined)</a>
            @foreach ($properties as $property)
                <a href="{{ route('portal.finances.index', ['property' => $property->id]) }}" class="badge {{ (int) request('property') === $property->id ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">{{ $property->title }}</a>
            @endforeach
        </div>

        @php
            $isAll = ! request('property');
            $summary = $isAll ? $overall : $summaries[$selectedProperty->id];
            $property = $isAll ? null : $selectedProperty;
        @endphp

        @if ($property)
            <p class="text-sm text-ink-500 mb-4">Showing isolated financial records for <span class="font-semibold text-ink-800">{{ $property->title }}</span> — figures never mix with your other properties.</p>
        @else
            <p class="text-sm text-ink-500 mb-4">Combined totals across all your properties. Select a property above to see its isolated breakdown.</p>
        @endif

        @if ($property?->activePackage)
            <div class="card p-6 mb-6">
                <div class="flex items-center justify-between">
                    <h3 class="font-heading font-bold text-ink-900">{{ $property->activePackage->package->name }} Package</h3>
                    <span class="badge bg-emerald-100 text-emerald-700 capitalize">{{ $property->activePackage->frequency }}</span>
                </div>
                <dl class="mt-3 grid sm:grid-cols-4 gap-4 text-sm">
                    <div><dt class="text-ink-500">Billed amount</dt><dd class="font-semibold text-ink-900">PKR {{ number_format($property->activePackage->final_price, 0) }}</dd></div>
                    <div><dt class="text-ink-500">Discount</dt><dd class="text-emerald-600">{{ $property->activePackage->discount_percent > 0 ? rtrim(rtrim(number_format($property->activePackage->discount_percent, 2), '0'), '.') . '%' : 'None' }}</dd></div>
                    <div><dt class="text-ink-500">Rent commission</dt><dd class="text-ink-900">{{ rtrim(rtrim(number_format($property->activePackage->commission_percent, 2), '0'), '.') }}%</dd></div>
                    <div><dt class="text-ink-500">Renews</dt><dd class="text-ink-900">{{ $property->activePackage->renews_at?->format('M j, Y') ?? '—' }}</dd></div>
                </dl>
            </div>
        @endif

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
            <div class="card p-5">
                <div class="text-xs text-ink-500">Total Paid</div>
                <div class="text-2xl font-heading font-extrabold text-emerald-600 mt-1">PKR {{ number_format($summary['total_paid']) }}</div>
            </div>
            <div class="card p-5">
                <div class="text-xs text-ink-500">Outstanding</div>
                <div class="text-2xl font-heading font-extrabold text-brand-600 mt-1">PKR {{ number_format($summary['total_outstanding']) }}</div>
            </div>
            <div class="card p-5">
                <div class="text-xs text-ink-500">Rent Collected</div>
                <div class="text-2xl font-heading font-extrabold text-ink-900 mt-1">PKR {{ number_format($summary['rent_collected']) }}</div>
            </div>
            <div class="card p-5">
                <div class="text-xs text-ink-500">Net to You (after commission)</div>
                <div class="text-2xl font-heading font-extrabold text-ink-900 mt-1">PKR {{ number_format($summary['owner_net_from_rent']) }}</div>
            </div>
        </div>

        <div class="card overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-ink-100"><h3 class="font-heading font-bold text-ink-900 text-sm">GATED Revenue &amp; Service Charges Breakdown</h3></div>
            <table class="w-full text-sm">
                <tbody class="divide-y divide-ink-100">
                    <tr><td class="px-6 py-3 text-ink-600">Package fees paid</td><td class="px-6 py-3 text-right font-semibold text-ink-900">PKR {{ number_format($summary['package_fees']) }}</td></tr>
                    <tr><td class="px-6 py-3 text-ink-600">Rent collection commission (GATED)</td><td class="px-6 py-3 text-right font-semibold text-ink-900">PKR {{ number_format($summary['rent_commission']) }}</td></tr>
                    <tr><td class="px-6 py-3 text-ink-600">Maintenance — contractor invoices</td><td class="px-6 py-3 text-right text-ink-900">PKR {{ number_format($summary['maintenance_contractor_cost']) }}</td></tr>
                    <tr><td class="px-6 py-3 text-ink-600">Maintenance — GATED coordination fee</td><td class="px-6 py-3 text-right font-semibold text-ink-900">PKR {{ number_format($summary['maintenance_gated_fee']) }}</td></tr>
                    <tr><td class="px-6 py-3 text-ink-600">Renovation — project value</td><td class="px-6 py-3 text-right text-ink-900">PKR {{ number_format($summary['renovation_project_value']) }}</td></tr>
                    <tr><td class="px-6 py-3 text-ink-600">Renovation — GATED management fee</td><td class="px-6 py-3 text-right font-semibold text-ink-900">PKR {{ number_format($summary['renovation_gated_fee']) }}</td></tr>
                    <tr><td class="px-6 py-3 text-ink-600">Tenant placement fees</td><td class="px-6 py-3 text-right font-semibold text-ink-900">PKR {{ number_format($summary['tenant_placement_fees']) }}</td></tr>
                    <tr><td class="px-6 py-3 text-ink-600">Property advertising</td><td class="px-6 py-3 text-right font-semibold text-ink-900">PKR {{ number_format($summary['advertising_fees']) }}</td></tr>
                    <tr><td class="px-6 py-3 text-ink-600">Emergency call-outs</td><td class="px-6 py-3 text-right font-semibold text-ink-900">PKR {{ number_format($summary['emergency_fees']) }}</td></tr>
                </tbody>
            </table>
        </div>

        <div class="card overflow-hidden">
            <div class="px-6 py-4 border-b border-ink-100 flex items-center justify-between">
                <h3 class="font-heading font-bold text-ink-900 text-sm">Complete Payment History</h3>
                @if ($property)<span class="text-xs text-ink-400">{{ $property->title }} only</span>@endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-ink-50 text-ink-500 text-xs uppercase">
                        <tr>
                            <th class="text-left px-6 py-3">Invoice</th>
                            @unless ($property)<th class="text-left px-6 py-3">Property</th>@endunless
                            <th class="text-left px-6 py-3">Category</th>
                            <th class="text-left px-6 py-3">Due Date</th>
                            <th class="text-left px-6 py-3">Amount</th>
                            <th class="text-left px-6 py-3">Status</th>
                            <th class="text-right px-6 py-3">Receipt</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-ink-100">
                        @forelse ($summary['payments'] as $payment)
                            <tr class="hover:bg-ink-50/50">
                                <td class="px-6 py-3 font-medium text-ink-900">{{ $payment->invoice_no }}</td>
                                @unless ($property)<td class="px-6 py-3 text-ink-600">{{ $payment->property?->title ?? '—' }}</td>@endunless
                                <td class="px-6 py-3 text-ink-600">{{ $payment->streamLabel() }}</td>
                                <td class="px-6 py-3 text-ink-600">{{ $payment->due_date?->format('M d, Y') ?? '—' }}</td>
                                <td class="px-6 py-3 font-semibold text-ink-900">PKR {{ number_format($payment->amount) }}</td>
                                <td class="px-6 py-3">
                                    @php $statusColors = ['paid' => 'bg-emerald-100 text-emerald-700', 'due' => 'bg-amber-100 text-amber-700', 'overdue' => 'bg-brand-100 text-brand-700', 'pending_review' => 'bg-blue-100 text-blue-700']; @endphp
                                    <span class="badge {{ $statusColors[$payment->status] ?? 'bg-ink-100 text-ink-600' }}">{{ ucfirst(str_replace('_',' ',$payment->status)) }}</span>
                                </td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('portal.payments.show', $payment) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">View &rarr;</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="px-6 py-10 text-center text-ink-500">No payment records yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection
