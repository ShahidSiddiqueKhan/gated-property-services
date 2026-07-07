@extends('layouts.portal')

@section('title', 'Invoice ' . $payment->invoice_no)

@section('content')

    <a href="{{ route('portal.payments.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Rent & Payments</a>

    <div class="grid lg:grid-cols-3 gap-6 mt-4">
        <div class="lg:col-span-2 card p-6">
            <div class="flex items-center justify-between">
                <h2 class="font-heading font-bold text-xl text-ink-900">Invoice {{ $payment->invoice_no }}</h2>
                @php $statusColors = ['paid' => 'bg-emerald-100 text-emerald-700', 'due' => 'bg-amber-100 text-amber-700', 'overdue' => 'bg-brand-100 text-brand-700', 'pending_review' => 'bg-blue-100 text-blue-700']; @endphp
                <span class="badge {{ $statusColors[$payment->status] ?? 'bg-ink-100 text-ink-600' }}">{{ ucfirst(str_replace('_',' ',$payment->status)) }}</span>
            </div>

            <dl class="mt-6 grid sm:grid-cols-2 gap-4 text-sm">
                <div><dt class="text-ink-500">Property</dt><dd class="font-semibold text-ink-900">{{ $payment->property?->title ?? '—' }}</dd></div>
                <div><dt class="text-ink-500">Type</dt><dd class="font-semibold text-ink-900 capitalize">{{ $payment->type }}</dd></div>
                <div><dt class="text-ink-500">Due Date</dt><dd class="font-semibold text-ink-900">{{ $payment->due_date?->format('F d, Y') ?? '—' }}</dd></div>
                <div><dt class="text-ink-500">Amount</dt><dd class="font-heading font-extrabold text-brand-600 text-lg">PKR {{ number_format($payment->amount) }}</dd></div>
                @if ($payment->paid_date)
                    <div><dt class="text-ink-500">Paid Date</dt><dd class="font-semibold text-ink-900">{{ $payment->paid_date->format('F d, Y') }}</dd></div>
                @endif
                @if ($payment->notes)
                    <div class="sm:col-span-2"><dt class="text-ink-500">Notes</dt><dd class="text-ink-700">{{ $payment->notes }}</dd></div>
                @endif
            </dl>
        </div>

        <aside class="space-y-6">
            @if (in_array($payment->status, ['due', 'overdue']))
                <div class="card p-6">
                    <h3 class="font-heading font-bold text-ink-900 text-sm mb-3">Bank Transfer Details</h3>
                    <div class="text-sm text-ink-600 space-y-1">
                        <p><span class="text-ink-400">Bank:</span> Meezan Bank</p>
                        <p><span class="text-ink-400">Account Title:</span> GATED Property Services</p>
                        <p><span class="text-ink-400">Account No:</span> 0123-4567-8901</p>
                        <p><span class="text-ink-400">IBAN:</span> PK00 MEZN 0000 0123 4567 8901</p>
                    </div>
                    <p class="mt-3 text-xs text-ink-400">A live card payment gateway is coming soon. For now, please transfer the amount above and confirm below.</p>

                    <form method="POST" action="{{ route('portal.payments.confirm', $payment) }}" class="mt-5 space-y-3">
                        @csrf
                        <div>
                            <label class="text-xs font-semibold text-ink-700">Payment Method Used</label>
                            <select name="method" required class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cash">Cash</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-ink-700">Reference / Notes (optional)</label>
                            <input type="text" name="notes" placeholder="Transaction ID, date, etc." class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <button type="submit" class="btn-primary w-full justify-center text-sm">I've Made This Payment</button>
                    </form>
                </div>
            @elseif ($payment->status === 'pending_review')
                <div class="card p-6 text-center">
                    <x-icon name="clock" class="w-8 h-8 text-blue-500 mx-auto" />
                    <p class="mt-3 text-sm text-ink-600">Your payment is awaiting confirmation from our finance team. This usually takes less than 24 hours.</p>
                </div>
            @else
                <div class="card p-6 text-center">
                    <x-icon name="check-circle" class="w-8 h-8 text-emerald-500 mx-auto" />
                    <p class="mt-3 text-sm text-ink-600">This invoice has been paid in full. Thank you!</p>
                </div>
            @endif
        </aside>
    </div>

@endsection
