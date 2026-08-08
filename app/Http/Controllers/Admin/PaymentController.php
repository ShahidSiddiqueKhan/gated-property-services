<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\Property;
use App\Models\User;
use App\Services\Billing\FeeCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(protected FeeCalculator $feeCalculator)
    {
    }

    public function index(Request $request): View
    {
        $query = Payment::with('user', 'property');

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        if ($request->filled('search')) {
            $search = $request->string('search');
            $query->where('invoice_no', 'like', "%{$search}%");
        }

        $payments = $query->latest('due_date')->paginate(15)->withQueryString();

        $counts = [
            'due' => Payment::where('status', 'due')->count(),
            'overdue' => Payment::where('status', 'overdue')->count(),
            'pending_review' => Payment::where('status', 'pending_review')->count(),
            'paid' => Payment::where('status', 'paid')->count(),
        ];

        return view('admin.payments.index', compact('payments', 'counts'));
    }

    public function create(): View
    {
        $clients = User::where('role', 'client')->orderBy('name')->get();
        $properties = Property::orderBy('title')->get();

        return view('admin.payments.create', compact('clients', 'properties'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'property_id' => ['nullable', 'exists:properties,id'],
            'type' => ['required', 'in:rent,service,invoice,maintenance'],
            'amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['required', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $revenueFields = [];

        // Rent invoices represent the full rent GATED collects on the owner's
        // behalf — split it into GATED's commission and the owner's net
        // using the property's active package commission (or override).
        if ($validated['type'] === 'rent' && $validated['property_id']) {
            $property = Property::with('activePackage')->find($validated['property_id']);
            $commissionPercent = (float) ($property?->activePackage?->commission_percent ?? 0);

            if ($commissionPercent > 0) {
                $split = $this->feeCalculator->rentCommission((float) $validated['amount'], $commissionPercent);

                $revenueFields = [
                    'revenue_stream' => Payment::STREAM_RENT_COMMISSION,
                    'base_amount' => $split['rent_amount'],
                    'fee_percent' => $split['commission_percent'],
                    'owner_amount' => $split['owner_amount'],
                ];
            }
        }

        $payment = Payment::create([
            ...$validated,
            ...$revenueFields,
            'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
            'status' => 'due',
        ]);

        AuditLog::record($request->user(), 'Created invoice', $payment, "Created invoice {$payment->invoice_no} for PKR " . number_format($payment->amount));

        return redirect()->route('admin.payments.index')->with('success', 'Invoice created.');
    }

    public function show(Payment $payment): View
    {
        $payment->load('user', 'property', 'lease');

        return view('admin.payments.show', compact('payment'));
    }

    public function confirm(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'method' => ['nullable', 'in:bank_transfer,cash,card,other'],
        ]);

        $payment->update([
            'status' => 'paid',
            'method' => $validated['method'] ?? $payment->method ?? 'bank_transfer',
            'paid_date' => now(),
        ]);

        AuditLog::record($request->user(), 'Confirmed payment', $payment, "Confirmed invoice {$payment->invoice_no} as paid");

        return back()->with('success', 'Invoice ' . $payment->invoice_no . ' marked as paid.');
    }

    public function markOverdue(Request $request, Payment $payment): RedirectResponse
    {
        $payment->update(['status' => 'overdue']);

        AuditLog::record($request->user(), 'Marked overdue', $payment, "Marked invoice {$payment->invoice_no} overdue");

        return back()->with('success', 'Invoice marked overdue.');
    }

    public function destroy(Request $request, Payment $payment): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted invoice', null, "Deleted invoice {$payment->invoice_no}");

        $payment->delete();

        return back()->with('success', 'Invoice deleted.');
    }
}
