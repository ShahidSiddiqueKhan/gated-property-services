<?php

namespace App\Services\Payments;

use App\Models\Payment;
use Illuminate\Support\Collection;

/**
 * JazzCash "Page Redirection" (HTTP POST) integration — the standard hosted
 * checkout flow for Pakistani mobile-wallet / debit card payments. The
 * merchant's browser POSTs a signed set of pp_* fields to JazzCash, the
 * customer completes payment on JazzCash's own page, and JazzCash POSTs
 * back to our return URL with the result (also signed).
 *
 * Requires JAZZCASH_MERCHANT_ID / JAZZCASH_PASSWORD / JAZZCASH_INTEGRITY_SALT
 * in .env. Ships pointed at the sandbox endpoint by default — switch
 * JAZZCASH_ENV=live and supply live credentials before accepting real payments.
 *
 * Hash algorithm verified against JazzCash's documented Page Redirection spec
 * (pp_Version 1.1): sort all non-empty pp_ and ppmpf_ prefixed fields
 * alphabetically by field name, join their values with '&', prefix with the integrity salt,
 * then HMAC-SHA256 the result using the integrity salt as the key.
 */
class JazzCashGateway
{
    public function endpoint(): string
    {
        return config('services.jazzcash.environment') === 'live'
            ? 'https://payments.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform/'
            : 'https://sandbox.jazzcash.com.pk/CustomerPortal/transactionmanagement/merchantform/';
    }

    /**
     * Build the full signed field set to auto-POST to JazzCash for a given
     * invoice. The unique pp_TxnRefNo is also returned so the caller can
     * store it against the Payment to reconcile the eventual return POST.
     */
    public function buildFields(Payment $payment): array
    {
        $now = now();
        $expiry = $now->copy()->addHours(1);
        $txnRefNo = 'GATED' . $now->format('YmdHis') . $payment->id;

        $fields = [
            'pp_Version' => '1.1',
            'pp_TxnType' => '',
            'pp_Language' => 'EN',
            'pp_MerchantID' => (string) config('services.jazzcash.merchant_id'),
            'pp_SubMerchantID' => '',
            'pp_Password' => (string) config('services.jazzcash.password'),
            'pp_BankID' => '',
            'pp_ProductID' => '',
            'pp_TxnRefNo' => $txnRefNo,
            'pp_Amount' => (string) (int) round(((float) $payment->amount) * 100),
            'pp_TxnCurrency' => 'PKR',
            'pp_TxnDateTime' => $now->format('YmdHis'),
            'pp_BillReference' => preg_replace('/[^A-Za-z0-9]/', '', $payment->invoice_no) ?: 'GATEDINV',
            'pp_Description' => 'GATED Invoice ' . $payment->invoice_no,
            'pp_TxnExpiryDateTime' => $expiry->format('YmdHis'),
            'pp_ReturnURL' => route('payments.jazzcash.return'),
        ];

        $fields['pp_SecureHash'] = $this->generateHash($fields);

        return $fields;
    }

    /**
     * Recompute the secure hash for a set of pp_/ppmpf_ fields (excluding
     * pp_SecureHash itself), for either signing an outgoing request or
     * verifying JazzCash's incoming return POST.
     */
    public function generateHash(array $fields): string
    {
        $salt = (string) config('services.jazzcash.integrity_salt');

        $sorted = $this->relevantFields($fields)->sortKeys();

        $string = $salt . '&' . $sorted->implode('&');

        return hash_hmac('sha256', $string, $salt);
    }

    /**
     * Verify the pp_SecureHash on a return/callback payload from JazzCash.
     */
    public function verify(array $responseFields): bool
    {
        $incomingHash = $responseFields['pp_SecureHash'] ?? '';

        if (! $incomingHash || ! config('services.jazzcash.integrity_salt')) {
            return false;
        }

        $expected = $this->generateHash($responseFields);

        return hash_equals($expected, $incomingHash);
    }

    /**
     * Only non-empty pp_ / ppmpf_ fields participate in the hash, per
     * JazzCash's spec — this mirrors real-world working integrations.
     */
    protected function relevantFields(array $fields): Collection
    {
        return collect($fields)
            ->filter(fn ($value, $key) => $key !== 'pp_SecureHash'
                && (str_starts_with($key, 'pp_') || str_starts_with($key, 'ppmpf_'))
                && $value !== null
                && $value !== '')
            ->map(fn ($value) => (string) $value);
    }
}
