<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Services\Payments\StripeGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use RuntimeException;

class StripePaymentController extends Controller
{
    public function __construct(protected StripeGateway $gateway)
    {
    }

    /**
     * Start a Stripe Checkout session for the given invoice and redirect the
     * client to Stripe's hosted payment page.
     */
    public function checkout(Request $request, Payment $payment): RedirectResponse
    {
        abort_unless($payment->user_id === $request->user()->id, 403);
        abort_unless(in_array($payment->status, ['due', 'overdue']), 400, 'This invoice is not payable right now.');

        try {
            $session = $this->gateway->createCheckoutSession(
                $payment,
                route('portal.payments.stripe.return', $payment) . '?session_id={CHECKOUT_SESSION_ID}',
                route('portal.payments.show', $payment),
            );
        } catch (RuntimeException $e) {
            return redirect()->route('portal.payments.show', $payment)
                ->with('error', 'Could not start Stripe checkout: ' . $e->getMessage());
        }

        $payment->update([
            'gateway' => 'stripe',
            'gateway_reference' => $session['id'] ?? null,
            'gateway_currency' => config('services.stripe.currency', 'usd'),
        ]);

        return redirect()->away($session['url']);
    }

    /**
     * Stripe redirects the client's browser back here after checkout
     * (success or cancel). We re-verify the session directly with Stripe's
     * servers rather than trusting the query string alone.
     */
    public function return(Request $request, Payment $payment): RedirectResponse
    {
        abort_unless($payment->user_id === $request->user()->id, 403);

        if ($payment->status === 'paid') {
            return redirect()->route('portal.payments.show', $payment);
        }

        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return redirect()->route('portal.payments.show', $payment)
                ->with('error', 'Missing Stripe session reference.');
        }

        try {
            $session = $this->gateway->retrieveSession($sessionId);
        } catch (RuntimeException) {
            return redirect()->route('portal.payments.show', $payment)
                ->with('error', 'We could not confirm this payment with Stripe. If you were charged, contact support.');
        }

        if (($session['payment_status'] ?? null) === 'paid') {
            $payment->update([
                'status' => 'paid',
                'method' => 'card',
                'paid_date' => now(),
                'gateway_reference' => $session['id'] ?? $payment->gateway_reference,
                'gateway_payload' => $session,
            ]);

            AuditLog::record(
                $payment->user,
                'Stripe payment succeeded',
                $payment,
                "Invoice {$payment->invoice_no} paid via Stripe (payment intent: " . ($session['payment_intent'] ?? 'n/a') . ')'
            );

            return redirect()->route('portal.payments.show', $payment)
                ->with('success', 'Payment received via Stripe. Thank you!');
        }

        return redirect()->route('portal.payments.show', $payment)
            ->with('error', 'Your Stripe payment was not completed, so you have not been charged. You can try again anytime.');
    }
}
