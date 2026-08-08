<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Services\Payments\JazzCashGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JazzCashController extends Controller
{
    public function __construct(protected JazzCashGateway $gateway)
    {
    }

    /**
     * Render an auto-submitting form that POSTs the client to JazzCash's
     * hosted payment page. GET request (not a redirect target) so the
     * client can land here from a plain "Pay with JazzCash" link/button.
     */
    public function checkout(Request $request, Payment $payment): View
    {
        abort_unless($payment->user_id === $request->user()->id, 403);
        abort_unless(in_array($payment->status, ['due', 'overdue']), 400, 'This invoice is not payable right now.');

        $fields = $this->gateway->buildFields($payment);

        $payment->update([
            'gateway' => 'jazzcash',
            'gateway_reference' => $fields['pp_TxnRefNo'],
            'gateway_currency' => 'PKR',
        ]);

        AuditLog::record($request->user(), 'Started JazzCash checkout', $payment, "Invoice {$payment->invoice_no}, ref {$fields['pp_TxnRefNo']}");

        return view('portal.payments.jazzcash-redirect', [
            'endpoint' => $this->gateway->endpoint(),
            'fields' => $fields,
        ]);
    }

    /**
     * JazzCash POSTs the transaction result back to this URL. This route is
     * intentionally public (no auth/CSRF) since the request originates from
     * JazzCash's servers/the customer's redirected browser, not a session we
     * control — we instead verify authenticity via the signed pp_SecureHash
     * and look the invoice up by the unique pp_TxnRefNo we generated.
     */
    public function return(Request $request): RedirectResponse
    {
        $data = $request->all();

        $payment = Payment::where('gateway', 'jazzcash')
            ->where('gateway_reference', $data['pp_TxnRefNo'] ?? '__none__')
            ->first();

        if (! $payment) {
            return redirect()->route('home')->with('error', 'We could not find this JazzCash transaction. Please contact support.');
        }

        if (! $this->gateway->verify($data)) {
            AuditLog::record($payment->user, 'JazzCash callback rejected', $payment, 'Secure hash verification failed on return POST.');

            return redirect()->route('portal.payments.show', $payment)
                ->with('error', 'We could not verify this JazzCash payment (signature mismatch). Please contact support before retrying.');
        }

        // Idempotent: JazzCash occasionally re-sends the same return POST.
        if ($payment->status === 'paid') {
            return redirect()->route('portal.payments.show', $payment);
        }

        $responseCode = $data['pp_ResponseCode'] ?? null;

        if ($responseCode === '000') {
            $payment->update([
                'status' => 'paid',
                'method' => 'card',
                'paid_date' => now(),
                'gateway_payload' => $data,
            ]);

            AuditLog::record(
                $payment->user,
                'JazzCash payment succeeded',
                $payment,
                "Invoice {$payment->invoice_no} paid via JazzCash (RRN: " . ($data['pp_RetreivalReferenceNo'] ?? 'n/a') . ')'
            );

            return redirect()->route('portal.payments.show', $payment)
                ->with('success', 'Payment received via JazzCash. Thank you!');
        }

        $payment->update(['gateway_payload' => $data]);

        AuditLog::record(
            $payment->user,
            'JazzCash payment failed',
            $payment,
            "Invoice {$payment->invoice_no}: " . ($data['pp_ResponseMessage'] ?? 'Unknown error') . " (code {$responseCode})"
        );

        return redirect()->route('portal.payments.show', $payment)
            ->with('error', 'JazzCash payment was not completed: ' . ($data['pp_ResponseMessage'] ?? 'Unknown error') . '. You have not been charged.');
    }
}
