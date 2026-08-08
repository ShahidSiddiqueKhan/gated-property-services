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

            @if ($payment->revenue_stream)
                <div class="mt-6 rounded-xl bg-ink-50 border border-ink-100 p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-heading font-bold text-ink-900 text-sm">Breakdown</h3>
                        <span class="badge bg-brand-50 text-brand-700">{{ $payment->streamLabel() }}</span>
                    </div>
                    <dl class="space-y-1.5 text-sm">
                        @if ($payment->revenue_stream === \App\Models\Payment::STREAM_RENT_COMMISSION)
                            <div class="flex justify-between"><dt class="text-ink-500">Rent collected</dt><dd class="text-ink-800">PKR {{ number_format($payment->base_amount, 0) }}</dd></div>
                            <div class="flex justify-between"><dt class="text-ink-500">GATED commission ({{ rtrim(rtrim(number_format($payment->fee_percent, 2), '0'), '.') }}%)</dt><dd class="text-ink-800">PKR {{ number_format($payment->amount - $payment->owner_amount, 0) }}</dd></div>
                            <div class="flex justify-between pt-1.5 border-t border-ink-200"><dt class="text-ink-700 font-semibold">You receive</dt><dd class="font-semibold text-emerald-600">PKR {{ number_format($payment->owner_amount, 0) }}</dd></div>
                        @elseif (in_array($payment->revenue_stream, [\App\Models\Payment::STREAM_MAINTENANCE_FEE, \App\Models\Payment::STREAM_RENOVATION_FEE]))
                            <div class="flex justify-between"><dt class="text-ink-500">Contractor cost</dt><dd class="text-ink-800">PKR {{ number_format($payment->base_amount, 0) }}</dd></div>
                            <div class="flex justify-between"><dt class="text-ink-500">GATED fee ({{ rtrim(rtrim(number_format($payment->fee_percent, 2), '0'), '.') }}%)</dt><dd class="text-ink-800">PKR {{ number_format($payment->amount - $payment->base_amount, 0) }}</dd></div>
                            <div class="flex justify-between pt-1.5 border-t border-ink-200"><dt class="text-ink-700 font-semibold">Total</dt><dd class="font-semibold text-ink-900">PKR {{ number_format($payment->amount, 0) }}</dd></div>
                        @endif
                    </dl>
                </div>
            @endif
        </div>

        <aside class="space-y-6" x-data="{ showManual: false }">
            @if (in_array($payment->status, ['due', 'overdue']))
                @php $isOverseas = auth()->user()->is_overseas; @endphp

                <div class="card p-6">
                    <h3 class="font-heading font-bold text-ink-900 text-sm mb-1">Pay This Invoice</h3>
                    <p class="text-xs text-ink-500 mb-4">Choose whichever option is most convenient &mdash; both are secure and confirm instantly.</p>

                    <div class="space-y-3">
                        <form method="POST" action="{{ route('portal.payments.stripe.checkout', $payment) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 rounded-xl border-2 {{ $isOverseas ? 'border-brand-500 bg-brand-50/40' : 'border-ink-200' }} p-4 text-left hover:border-brand-400 hover:bg-brand-50/40 transition-all duration-200">
                                <span class="w-10 h-10 rounded-lg bg-ink-900 text-white flex items-center justify-center shrink-0"><x-icon name="credit-card" class="w-5 h-5" /></span>
                                <span class="flex-1">
                                    <span class="flex items-center gap-2">
                                        <span class="font-semibold text-sm text-ink-900">Pay by Card (Stripe)</span>
                                        @if ($isOverseas)
                                            <span class="badge bg-brand-600 text-white text-[10px]">Recommended</span>
                                        @endif
                                    </span>
                                    <span class="block text-xs text-ink-500 mt-0.5">Best for overseas owners &mdash; Visa, Mastercard &amp; more.</span>
                                </span>
                                <x-icon name="arrow-right" class="w-4 h-4 text-ink-400 shrink-0" />
                            </button>
                        </form>

                        <a href="{{ route('portal.payments.jazzcash.checkout', $payment) }}" class="w-full flex items-center gap-3 rounded-xl border-2 {{ !$isOverseas ? 'border-emerald-500 bg-emerald-50/40' : 'border-ink-200' }} p-4 text-left hover:border-emerald-400 hover:bg-emerald-50/40 transition-all duration-200">
                            <span class="w-10 h-10 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0"><x-icon name="banknotes" class="w-5 h-5" /></span>
                            <span class="flex-1">
                                <span class="flex items-center gap-2">
                                    <span class="font-semibold text-sm text-ink-900">Pay via JazzCash</span>
                                    @if (!$isOverseas)
                                        <span class="badge bg-emerald-600 text-white text-[10px]">Recommended</span>
                                    @endif
                                </span>
                                <span class="block text-xs text-ink-500 mt-0.5">Mobile wallet &amp; local debit/credit cards.</span>
                            </span>
                            <x-icon name="arrow-right" class="w-4 h-4 text-ink-400 shrink-0" />
                        </a>

                        <form method="POST" action="{{ route('portal.payments.safepay.checkout', $payment) }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 rounded-xl border-2 border-ink-200 p-4 text-left hover:border-brand-400 hover:bg-brand-50/40 transition-all duration-200">
                                <span class="w-10 h-10 rounded-lg bg-blue-600 text-white flex items-center justify-center shrink-0"><x-icon name="shield-check" class="w-5 h-5" /></span>
                                <span class="flex-1">
                                    <span class="font-semibold text-sm text-ink-900">Pay via Safepay</span>
                                    <span class="block text-xs text-ink-500 mt-0.5">Cards, wallets &amp; bank rails, all in one checkout.</span>
                                </span>
                                <x-icon name="arrow-right" class="w-4 h-4 text-ink-400 shrink-0" />
                            </button>
                        </form>
                    </div>

                    <button type="button" @click="showManual = !showManual" class="mt-4 text-xs font-semibold text-ink-500 hover:text-brand-600 flex items-center gap-1">
                        <span x-text="showManual ? 'Hide manual bank transfer option' : 'Prefer a manual bank transfer instead?'"></span>
                        <x-icon name="chevron-down" class="w-3.5 h-3.5 transition" x-bind:class="showManual ? 'rotate-180' : ''" />
                    </button>

                    <div x-show="showManual" x-cloak x-transition class="mt-4 pt-4 border-t border-ink-100">
                        <div class="text-sm text-ink-600 space-y-1">
                            <p><span class="text-ink-400">Bank:</span> Meezan Bank</p>
                            <p><span class="text-ink-400">Account Title:</span> GATED Property Services</p>
                            <p><span class="text-ink-400">Account No:</span> 0123-4567-8901</p>
                            <p><span class="text-ink-400">IBAN:</span> PK00 MEZN 0000 0123 4567 8901</p>
                        </div>
                        <p class="mt-3 text-xs text-ink-400">Transfer the amount above, then confirm below. Our finance team verifies manual transfers within 24 hours (card and JazzCash payments confirm instantly).</p>

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
                            <button type="submit" class="btn-outline w-full justify-center text-sm">I've Made This Payment</button>
                        </form>
                    </div>
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
