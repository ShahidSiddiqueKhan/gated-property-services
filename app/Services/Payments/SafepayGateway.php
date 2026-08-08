<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Facades\Http;

/**
 * Safepay hosted-checkout integration — GATED's second live gateway
 * (cards, wallets & local bank rails via a single Pakistani aggregator),
 * built with Laravel's HTTP client rather than the `getsafepay/safepay-php`
 * package to keep this project dependency-free, mirroring the Stripe and
 * JazzCash integrations.
 *
 * Endpoint paths, payload shape, and hash algorithms below are taken
 * directly from the official Safepay PHP SDK source
 * (github.com/getsafepay/safepay-php — src/Payments.php, Checkout.php,
 * Base.php, Verify.php), not guessed, since this is a financial integration:
 * - POST {api}/order/v1/init to open a payment "tracker" and get a token.
 * - Redirect to {base}/checkout/pay?env=&beacon={token}&source=&order_id=&
 *   redirect_url=&cancel_url=&webhooks= to collect payment.
 * - Safepay redirects back with `tracker` + `sig` fields; verify with
 *   hash_hmac('sha256', $tracker, $secret) === $sig.
 * - Webhooks are verified separately via X-SFPY-SIGNATURE using
 *   hash_hmac('sha512', ...) with the webhook secret (see verifyWebhook()).
 */
class SafepayGateway
{
    protected function isSandbox(): bool
    {
        return config('services.safepay.environment') !== 'production';
    }

    protected function apiBaseUrl(): string
    {
        return $this->isSandbox() ? 'https://sandbox.api.getsafepay.com' : 'https://api.getsafepay.com';
    }

    protected function checkoutBaseUrl(): string
    {
        return $this->isSandbox() ? 'https://sandbox.api.getsafepay.com' : 'https://getsafepay.com';
    }

    /**
     * Open a payment tracker for this invoice's amount and return the
     * tracker token used to build the checkout URL.
     *
     * @throws \RuntimeException if Safepay rejects the request
     */
    public function createTracker(Payment $payment): string
    {
        $request = Http::acceptJson();

        // Sandbox uses a self-signed cert on some environments, matching the
        // official SDK's behavior of skipping SSL verification in sandbox only.
        if ($this->isSandbox()) {
            $request = $request->withoutVerifying();
        }

        $response = $request->post($this->apiBaseUrl() . '/order/v1/init', [
            'environment' => $this->isSandbox() ? 'sandbox' : 'production',
            'client' => config('services.safepay.api_key'),
            'amount' => (float) $payment->amount,
            'currency' => 'PKR',
        ])->json();

        $token = $response['data']['token'] ?? null;

        if (! $token || ($response['status']['message'] ?? null) !== 'success') {
            throw new \RuntimeException('Safepay did not return a payment tracker: ' . ($response['status']['message'] ?? 'unknown error'));
        }

        return $token;
    }

    /**
     * Build the hosted checkout URL for a previously created tracker token.
     */
    public function checkoutUrl(string $trackerToken, Payment $payment): string
    {
        $params = [
            'env' => $this->isSandbox() ? 'sandbox' : 'production',
            'beacon' => $trackerToken,
            'source' => 'custom',
            'order_id' => $payment->invoice_no,
            'redirect_url' => route('payments.safepay.return'),
            'cancel_url' => route('portal.payments.show', $payment),
            'webhooks' => 'true',
        ];

        return $this->checkoutBaseUrl() . '/checkout/pay?' . http_build_query($params);
    }

    /**
     * Verify the `tracker` + `sig` pair Safepay appends to the return
     * redirect after checkout completes.
     */
    public function verifySignature(string $tracker, string $signature): bool
    {
        $secret = (string) config('services.safepay.secret');

        if (! $tracker || ! $signature || ! $secret) {
            return false;
        }

        return hash_equals(hash_hmac('sha256', $tracker, $secret), $signature);
    }

    /**
     * Verify an async webhook payload using the X-SFPY-SIGNATURE header.
     * Per the SDK, the signature covers the JSON-encoded `data` object only
     * (not the full raw body).
     */
    public function verifyWebhookSignature(string $rawBody, string $signature): bool
    {
        $secret = (string) config('services.safepay.webhook_secret');

        if (! $rawBody || ! $signature || ! $secret) {
            return false;
        }

        $payload = json_decode($rawBody, true);

        if (empty($payload['data'])) {
            return false;
        }

        $expected = hash_hmac('sha512', json_encode($payload['data'], JSON_UNESCAPED_SLASHES), $secret);

        return hash_equals($expected, $signature);
    }
}
