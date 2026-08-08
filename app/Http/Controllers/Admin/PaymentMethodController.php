<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\PaymentMethod;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    public function index(): View
    {
        $paymentMethods = PaymentMethod::orderBy('sort_order')->get();

        return view('admin.payment-methods.index', compact('paymentMethods'));
    }

    public function create(): View
    {
        return view('admin.payment-methods.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePaymentMethod($request);
        $validated['code'] = $validated['code'] ?: Str::slug($validated['name'], '_');
        // New methods added from the admin UI are always manual/instructions-based —
        // live gateway checkout requires real code wiring (see StripeGateway/JazzCashGateway)
        // and is only ever added by the development team, not toggled on here.
        $validated['type'] = 'manual';

        $method = PaymentMethod::create($validated);

        AuditLog::record($request->user(), 'Created payment method', $method, "Added payment method: {$method->name}");

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method added.');
    }

    public function edit(PaymentMethod $paymentMethod): View
    {
        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        $validated = $this->validatePaymentMethod($request, $paymentMethod);

        $paymentMethod->update($validated);

        AuditLog::record($request->user(), 'Updated payment method', $paymentMethod, "Updated payment method: {$paymentMethod->name}");

        return redirect()->route('admin.payment-methods.index')->with('success', 'Payment method updated.');
    }

    public function destroy(Request $request, PaymentMethod $paymentMethod): RedirectResponse
    {
        if ($paymentMethod->isGateway()) {
            return back()->with('error', 'Live gateway methods are wired into checkout code and cannot be deleted — deactivate it instead.');
        }

        AuditLog::record($request->user(), 'Deleted payment method', null, "Deleted payment method: {$paymentMethod->name}");

        $paymentMethod->delete();

        return back()->with('success', 'Payment method deleted.');
    }

    protected function validatePaymentMethod(Request $request, ?PaymentMethod $paymentMethod = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:100', 'alpha_dash', 'unique:payment_methods,code,' . ($paymentMethod?->id)],
            'type' => ['required', 'in:manual,gateway'],
            'region' => ['required', 'in:local,overseas,both'],
            'icon' => ['nullable', 'string', 'max:100'],
            'instructions' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // Gateway methods are wired directly to checkout code by their `code`
        // (e.g. stripe/jazzcash/safepay) — never allow that to be edited once set.
        if ($paymentMethod?->isGateway()) {
            $validated['code'] = $paymentMethod->code;
            $validated['type'] = 'gateway';
        }

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        return $validated;
    }
}
