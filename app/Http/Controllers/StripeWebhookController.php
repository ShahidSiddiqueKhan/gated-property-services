<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Payment;
use App\Services\Payments\StripeGateway;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Asynchronous confirmation channel for Stripe payments, independent of
 * whether the client's browser makes it back to our success_url (they might
 * close the tab, lose connectivity, etc.). This is the source of truth
 * Stripe recommends relying on in production, in addition to the immediate
 * browser-redirect confirmation handled by StripePaymentController::return().
 *
 * Configure this URL (yourapp.com/webhooks/stripe) as an endpoint in the
 * Stripe Dashboard, listening for the checkout.session.completed event, and
 * copy its signing secret into STRIPE_WEBHOOK_SECRET.
 */
class StripeWebhookController extends Controller
{
    public function __construct(protected StripeGateway $gateway)
    {
    }

    public function handle(Request $request): Response
    {
        $event = $this->gateway->verifyWebhookSignature(
            $request->getContent(),
            $request->header('Stripe-Signature', '')
        );

        if (! $event) {
            return response('Invalid signature', 400);
        }

        if (($event['type'] ?? null) === 'checkout.session.completed') {
            $session = $event['data']['object'] ?? [];
            $paymentId = $session['metadata']['payment_id'] ?? null;

            if ($paymentId && ($payment = Payment::find($paymentId)) && $payment->status !== 'paid') {
                $payment->update([
                    'status' => 'paid',
                    'method' => 'card',
                    'gateway' => 'stripe',
                    'gateway_reference' => $session['id'] ?? $payment->gateway_reference,
                    'paid_date' => now(),
                    'gateway_payload' => $session,
                ]);

                AuditLog::record(
                    $payment->user,
                    'Stripe webhook confirmed payment',
                    $payment,
                    "Invoice {$payment->invoice_no} confirmed paid via Stripe webhook"
                );
            }
        }

        return response('OK', 200);
    }
}
