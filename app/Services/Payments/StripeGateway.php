<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Thin wrapper around Stripe's Checkout Sessions REST API using Laravel's
 * built-in HTTP client (no stripe-php SDK dependency required). Aimed at
 * overseas property owners paying by card in a foreign currency.
 *
 * Requires STRIPE_KEY / STRIPE_SECRET / STRIPE_WEBHOOK_SECRET in .env.
 * Ships pointed at test-mode keys by default — swap in live keys (and a
 * live webhook secret) before accepting real payments.
 */
class StripeGateway
{
    protected string $base = 'https://api.stripe.com/v1';

    /**
     * Create a Stripe Checkout Session for a single invoice and return the
     * decoded JSON response (contains ['id' => ..., 'url' => ...]).
     */
    public function createCheckoutSession(Payment $payment, string $successUrl, string $cancelUrl): array
    {
        $currency = config('services.stripe.currency', 'usd');

        $response = Http::asForm()
            ->withToken(config('services.stripe.secret'))
            ->post("{$this->base}/checkout/sessions", [
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => "GATED Invoice {$payment->invoice_no}",
                            'description' => ucfirst($payment->type) . ' payment' . ($payment->property?->title ? ' for ' . $payment->property->title : ''),
                        ],
                        'unit_amount' => (int) round(((float) $payment->amount) * 100),
                    ],
                    'quantity' => 1,
                ]],
                'customer_email' => $payment->user->email,
                'success_url' => $successUrl,
                'cancel_url' => $cancelUrl,
                'metadata' => [
                    'payment_id' => (string) $payment->id,
                    'invoice_no' => $payment->invoice_no,
                ],
            ]);

        if ($response->failed()) {
            Log::error('Stripe checkout session creation failed', ['body' => $response->json()]);

            throw new RuntimeException($response->json('error.message') ?? 'Unable to start Stripe checkout.');
        }

        return $response->json();
    }

    /**
     * Retrieve a Checkout Session directly from Stripe's servers so we never
     * trust the client-supplied session_id/status without server-side proof.
     */
    public function retrieveSession(string $sessionId): array
    {
        $response = Http::withToken(config('services.stripe.secret'))
            ->get("{$this->base}/checkout/sessions/{$sessionId}");

        if ($response->failed()) {
            Log::error('Stripe session retrieval failed', ['session_id' => $sessionId, 'body' => $response->json()]);

            throw new RuntimeException('Unable to retrieve Stripe session.');
        }

        return $response->json();
    }

    /**
     * Verify a Stripe webhook's `Stripe-Signature` header by hand, following
     * Stripe's documented scheme, so we don't need the stripe-php SDK just
     * for signature checking. Returns the decoded event payload on success,
     * or null if the signature is missing/invalid/stale.
     */
    public function verifyWebhookSignature(string $payload, string $sigHeader, int $tolerance = 300): ?array
    {
        $secret = config('services.stripe.webhook_secret');

        if (! $secret || ! $sigHeader) {
            return null;
        }

        $parts = [];
        foreach (explode(',', $sigHeader) as $pair) {
            [$key, $value] = array_pad(explode('=', $pair, 2), 2, null);
            if ($key !== null && $value !== null) {
                $parts[$key][] = $value;
            }
        }

        $timestamp = $parts['t'][0] ?? null;
        $signatures = $parts['v1'] ?? [];

        if (! $timestamp || empty($signatures)) {
            return null;
        }

        if (abs(time() - (int) $timestamp) > $tolerance) {
            Log::warning('Stripe webhook rejected: timestamp outside tolerance window.');

            return null;
        }

        $expected = hash_hmac('sha256', "{$timestamp}.{$payload}", $secret);

        foreach ($signatures as $signature) {
            if (hash_equals($expected, $signature)) {
                return json_decode($payload, true);
            }
        }

        Log::warning('Stripe webhook rejected: signature mismatch.');

        return null;
    }
}
