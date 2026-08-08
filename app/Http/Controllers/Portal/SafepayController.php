<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Services\Payments\SafepayGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SafepayController extends Controller
{
    public function __construct(protected SafepayGateway $gateway)
    {
    }

    /**
     * Open a Safepay payment tracker for this invoice and redirect the
     * client to Safepay's hosted checkout page.
     */
    public function checkout(Request $request, Payment $payment): RedirectResponse
    {
        abort_unless($payment->user_id === $request->user()->id, 403);
        abort_unless(in_array($payment->status, ['due', 'overdue']), 400, 'This invoice is not payable right now.');

        try {
            $token = $this->gateway->createTracker($payment);
        } catch (\Throwable $e) {
            AuditLog::record($request->user(), 'Safepay checkout failed to start', $payment, $e->getMessage());

            return redirect()->route('portal.payments.show', $payment)
                ->with('error', 'We could not start Safepay checkout right now. Please try another payment method or contact support.');
        }

        $payment->update([
            'gateway' => 'safepay',
            'gateway_reference' => $token,
            'gateway_currency' => 'PKR',
        ]);

        AuditLog::record($request->user(), 'Started Safepay checkout', $payment, "Invoice {$payment->invoice_no}, tracker {$token}");

        return redirect()->away($this->gateway->checkoutUrl($token, $payment));
    }

    /**
     * Safepay POSTs the customer's browser back here (per the official SDK's
     * verification example, which reads $_POST['tracker'] / $_POST['sig'])
     * after checkout completes or fails. Public route (no auth/CSRF) since
     * this originates from Safepay/the customer's redirected browser, not a
     * session we control — we authorize via the signed tracker instead and
     * look the invoice up by gateway_reference.
     */
    public function return(Request $request): RedirectResponse
    {
        $tracker = (string) $request->input('tracker', '');
        $signature = (string) $request->input('sig', '');

        $payment = Payment::where('gateway', 'safepay')
            ->where('gateway_reference', $tracker ?: '__none__')
            ->first();

        if (! $payment) {
            return redirect()->route('home')->with('error', 'We could not find this Safepay transaction. Please contact support.');
        }

        if (! $this->gateway->verifySignature($tracker, $signature)) {
            AuditLog::record($payment->user, 'Safepay callback rejected', $payment, 'Signature verification failed on return redirect.');

            return redirect()->route('portal.payments.show', $payment)
                ->with('error', 'We could not verify this Safepay payment (signature mismatch). Please contact support before retrying.');
        }

        // Idempotent: avoid double-processing if the customer refreshes.
        if ($payment->status === 'paid') {
            return redirect()->route('portal.payments.show', $payment);
        }

        $payment->update([
            'status' => 'paid',
            'method' => 'card',
            'paid_date' => now(),
            'gateway_payload' => $request->all(),
        ]);

        AuditLog::record($payment->user, 'Safepay payment succeeded', $payment, "Invoice {$payment->invoice_no} paid via Safepay (tracker: {$tracker})");

        return redirect()->route('portal.payments.show', $payment)
            ->with('success', 'Payment received via Safepay. Thank you!');
    }
}
