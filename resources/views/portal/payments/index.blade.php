@extends('layouts.portal')

@section('title', 'Rent & Payments')

@section('content')

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 mb-6">
        <div class="card p-5">
            <div class="text-xs text-ink-500">Outstanding Balance</div>
            <div class="text-2xl font-heading font-extrabold text-brand-600 mt-1">PKR {{ number_format($totalDue) }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs text-ink-500">Total Paid This Year</div>
            <div class="text-2xl font-heading font-extrabold text-emerald-600 mt-1">PKR {{ number_format($totalPaidThisYear) }}</div>
        </div>
        <div class="card p-5">
            <div class="text-xs text-ink-500">Ways to Pay</div>
            <div class="text-sm font-semibold text-ink-800 mt-2 flex items-center gap-1.5"><x-icon name="credit-card" class="w-4 h-4 text-ink-400" /> Card (Stripe) &middot; JazzCash &middot; Safepay &middot; Bank Transfer</div>
            <div class="text-xs text-ink-400 mt-1">Card, JazzCash &amp; Safepay confirm instantly</div>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="p-6 border-b border-ink-100">
            <h2 class="font-heading font-bold text-ink-900">Invoices & Rent History</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3">Invoice</th>
                        <th class="text-left px-6 py-3">Property</th>
                        <th class="text-left px-6 py-3">Type</th>
                        <th class="text-left px-6 py-3">Due Date</th>
                        <th class="text-left px-6 py-3">Amount</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-right px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($payments as $payment)
                        <tr class="hover:bg-ink-50/50">
                            <td class="px-6 py-4 font-medium text-ink-900">{{ $payment->invoice_no }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $payment->property?->title ?? '—' }}</td>
                            <td class="px-6 py-4 text-ink-600 capitalize">{{ $payment->type }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $payment->due_date?->format('M d, Y') ?? '—' }}</td>
                            <td class="px-6 py-4 font-semibold text-ink-900">PKR {{ number_format($payment->amount) }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = ['paid' => 'bg-emerald-100 text-emerald-700', 'due' => 'bg-amber-100 text-amber-700', 'overdue' => 'bg-brand-100 text-brand-700', 'pending_review' => 'bg-blue-100 text-blue-700'];
                                @endphp
                                <span class="badge {{ $statusColors[$payment->status] ?? 'bg-ink-100 text-ink-600' }}">{{ ucfirst(str_replace('_',' ',$payment->status)) }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
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

    <div class="mt-6">{{ $payments->links() }}</div>

@endsection
