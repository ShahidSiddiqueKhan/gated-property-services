<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $payments = Payment::where('user_id', $user->id)
            ->with('property')
            ->latest('due_date')
            ->paginate(10);

        $totalDue = Payment::where('user_id', $user->id)->whereIn('status', ['due', 'overdue'])->sum('amount');
        $totalPaidThisYear = Payment::where('user_id', $user->id)->where('status', 'paid')->whereYear('paid_date', now()->year)->sum('amount');

        return view('portal.payments.index', compact('payments', 'totalDue', 'totalPaidThisYear'));
    }

    public function show(Request $request, Payment $payment): View
    {
        abort_unless($payment->user_id === $request->user()->id, 403);

        return view('portal.payments.show', compact('payment'));
    }

    /**
     * Client confirms they've sent a manual bank transfer for a due invoice.
     * No live payment gateway is connected yet; this marks the invoice as
     * "pending review" for the GATED finance team to confirm receipt.
     */
    public function confirm(Request $request, Payment $payment): RedirectResponse
    {
        abort_unless($payment->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'method' => ['required', 'in:bank_transfer,cash,card,other'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $payment->update([
            'status' => 'pending_review',
            'method' => $validated['method'],
            'notes' => $validated['notes'] ?? $payment->notes,
        ]);

        return back()->with('success', 'Thanks! We\'ve marked invoice ' . $payment->invoice_no . ' as awaiting confirmation. Our finance team will verify and update it within 24 hours.');
    }
}
