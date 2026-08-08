@extends('layouts.admin')

@section('title', 'Invoice ' . $payment->invoice_no)

@section('content')

    <a href="{{ route('admin.payments.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Payments</a>

    <div class="max-w-2xl mt-4 card p-6 sm:p-8">
        @php $statusColors = ['paid' => 'bg-emerald-100 text-emerald-700', 'due' => 'bg-amber-100 text-amber-700', 'overdue' => 'bg-brand-100 text-brand-700', 'pending_review' => 'bg-blue-100 text-blue-700']; @endphp
        <div class="flex items-center justify-between mb-6">
            <h2 class="font-heading font-bold text-xl text-ink-900">{{ $payment->invoice_no }}</h2>
            <span class="badge {{ $statusColors[$payment->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$payment->status)) }}</span>
        </div>

        <dl class="grid sm:grid-cols-2 gap-4 text-sm">
            <div><dt class="text-ink-500">Client</dt><dd class="font-semibold text-ink-900">{{ $payment->user->name }}</dd></div>
            <div><dt class="text-ink-500">Property</dt><dd class="font-semibold text-ink-900">{{ $payment->property?->title ?? '—' }}</dd></div>
            <div><dt class="text-ink-500">Type</dt><dd class="font-semibold text-ink-900 capitalize">{{ $payment->type }}</dd></div>
            <div><dt class="text-ink-500">Amount</dt><dd class="font-heading font-extrabold text-brand-600 text-lg">PKR {{ number_format($payment->amount) }}</dd></div>
            <div><dt class="text-ink-500">Due Date</dt><dd class="text-ink-900">{{ $payment->due_date?->format('M d, Y') ?? '—' }}</dd></div>
            @if ($payment->paid_date)<div><dt class="text-ink-500">Paid Date</dt><dd class="text-ink-900">{{ $payment->paid_date->format('M d, Y') }}</dd></div>@endif
            @if ($payment->method)<div><dt class="text-ink-500">Method</dt><dd class="text-ink-900 capitalize">{{ str_replace('_',' ',$payment->method) }}</dd></div>@endif
            @if ($payment->gateway)<div><dt class="text-ink-500">Gateway</dt><dd class="text-ink-900 capitalize">{{ $payment->gateway }} @if($payment->gateway_currency) ({{ strtoupper($payment->gateway_currency) }}) @endif</dd></div>@endif
            @if ($payment->gateway_reference)<div><dt class="text-ink-500">Gateway Reference</dt><dd class="text-ink-900 font-mono text-xs">{{ $payment->gateway_reference }}</dd></div>@endif
        </dl>
        @if ($payment->notes)
            <p class="mt-4 text-sm text-ink-600"><span class="text-ink-400">Notes:</span> {{ $payment->notes }}</p>
        @endif

        @if ($payment->revenue_stream)
            <div class="mt-6 rounded-xl bg-ink-50 border border-ink-100 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-heading font-bold text-ink-900 text-sm">Revenue Breakdown</h3>
                    <span class="badge bg-brand-50 text-brand-700">{{ $payment->streamLabel() }}</span>
                </div>
                <dl class="space-y-1.5 text-sm">
                    @if ($payment->revenue_stream === \App\Models\Payment::STREAM_RENT_COMMISSION)
                        <div class="flex justify-between"><dt class="text-ink-500">Rent collected</dt><dd class="text-ink-800">PKR {{ number_format($payment->base_amount, 0) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-ink-500">GATED commission ({{ rtrim(rtrim(number_format($payment->fee_percent, 2), '0'), '.') }}%)</dt><dd class="font-semibold text-brand-600">PKR {{ number_format($payment->amount - $payment->owner_amount, 0) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-ink-500">Owner receives</dt><dd class="font-semibold text-emerald-600">PKR {{ number_format($payment->owner_amount, 0) }}</dd></div>
                    @elseif (in_array($payment->revenue_stream, [\App\Models\Payment::STREAM_MAINTENANCE_FEE, \App\Models\Payment::STREAM_RENOVATION_FEE]))
                        <div class="flex justify-between"><dt class="text-ink-500">Contractor cost</dt><dd class="text-ink-800">PKR {{ number_format($payment->base_amount, 0) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-ink-500">GATED fee ({{ rtrim(rtrim(number_format($payment->fee_percent, 2), '0'), '.') }}%)</dt><dd class="font-semibold text-brand-600">PKR {{ number_format($payment->amount - $payment->base_amount, 0) }}</dd></div>
                        <div class="flex justify-between"><dt class="text-ink-500">Total billed</dt><dd class="font-semibold text-ink-900">PKR {{ number_format($payment->amount, 0) }}</dd></div>
                    @else
                        <div class="flex justify-between"><dt class="text-ink-500">Amount</dt><dd class="font-semibold text-ink-900">PKR {{ number_format($payment->amount, 0) }}</dd></div>
                    @endif
                </dl>
            </div>
        @endif

        <div class="flex gap-2 mt-6">
            @if (in_array($payment->status, ['due', 'overdue', 'pending_review']))
                <form method="POST" action="{{ route('admin.payments.confirm', $payment) }}">
                    @csrf
                    <button type="submit" class="btn-primary !py-2 !px-4 text-sm">Mark as Paid</button>
                </form>
                @if ($payment->status === 'due')
                    <form method="POST" action="{{ route('admin.payments.overdue', $payment) }}">
                        @csrf
                        <button type="submit" class="btn-outline !py-2 !px-4 text-sm">Mark Overdue</button>
                    </form>
                @endif
            @endif
            <form method="POST" action="{{ route('admin.payments.destroy', $payment) }}" onsubmit="return confirm('Delete this invoice?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-outline !py-2 !px-4 text-sm !border-brand-600 !text-brand-600 hover:!bg-brand-600 hover:!text-white">Delete</button>
            </form>
        </div>
    </div>

@endsection
