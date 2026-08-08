@extends('layouts.admin')

@section('title', 'Payments & Invoices')

@section('content')

    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.payments.index') }}" class="badge {{ !request('status') ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">All</a>
            <a href="{{ route('admin.payments.index', ['status' => 'due']) }}" class="badge {{ request('status') === 'due' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Due ({{ $counts['due'] }})</a>
            <a href="{{ route('admin.payments.index', ['status' => 'pending_review']) }}" class="badge {{ request('status') === 'pending_review' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Awaiting Confirmation ({{ $counts['pending_review'] }})</a>
            <a href="{{ route('admin.payments.index', ['status' => 'overdue']) }}" class="badge {{ request('status') === 'overdue' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Overdue ({{ $counts['overdue'] }})</a>
            <a href="{{ route('admin.payments.index', ['status' => 'paid']) }}" class="badge {{ request('status') === 'paid' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Paid ({{ $counts['paid'] }})</a>
        </div>
        <a href="{{ route('admin.payments.create') }}" class="btn-primary !py-2.5 !px-4 text-sm">Create Invoice</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3">Invoice</th>
                        <th class="text-left px-6 py-3">Client</th>
                        <th class="text-left px-6 py-3">Property</th>
                        <th class="text-left px-6 py-3">Amount</th>
                        <th class="text-left px-6 py-3">Due Date</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-right px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($payments as $payment)
                        @php $statusColors = ['paid' => 'bg-emerald-100 text-emerald-700', 'due' => 'bg-amber-100 text-amber-700', 'overdue' => 'bg-brand-100 text-brand-700', 'pending_review' => 'bg-blue-100 text-blue-700']; @endphp
                        <tr class="hover:bg-ink-50/50">
                            <td class="px-6 py-4 font-medium text-ink-900">{{ $payment->invoice_no }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $payment->user->name }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $payment->property?->title ?? '—' }}</td>
                            <td class="px-6 py-4 font-semibold text-ink-900">PKR {{ number_format($payment->amount) }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $payment->due_date?->format('M d, Y') ?? '—' }}</td>
                            <td class="px-6 py-4">
                                <span class="badge {{ $statusColors[$payment->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$payment->status)) }}</span>
                                @if ($payment->gateway)
                                    <div class="text-[10px] text-ink-400 mt-1 capitalize">via {{ $payment->gateway }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                @if (in_array($payment->status, ['due', 'overdue', 'pending_review']))
                                    <form method="POST" action="{{ route('admin.payments.confirm', $payment) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-emerald-600 font-semibold text-xs hover:text-emerald-700">Mark Paid</button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.payments.show', $payment) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-6 py-10 text-center text-ink-500">No payments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $payments->links() }}</div>

@endsection
