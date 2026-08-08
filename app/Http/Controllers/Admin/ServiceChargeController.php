<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Payment;
use App\Models\Property;
use App\Models\ServiceCatalogItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Bills a property's owner for a standalone catalog service — property
 * advertising add-ons (listing, photography, drone video, marketing
 * packages) or emergency call-outs (lockout, emergency inspection, night
 * visit) — outside the recurring package fee. Prices default from the
 * admin-configurable service_catalog but can be adjusted per invoice.
 */
class ServiceChargeController extends Controller
{
    public function store(Request $request, Property $property): RedirectResponse
    {
        $validated = $request->validate([
            'service_catalog_id' => ['required', 'exists:service_catalog,id'],
            'amount' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $item = ServiceCatalogItem::findOrFail($validated['service_catalog_id']);
        $stream = $item->category === 'emergency' ? Payment::STREAM_EMERGENCY_SERVICE : Payment::STREAM_ADVERTISING;

        $payment = Payment::create([
            'invoice_no' => 'INV-' . strtoupper(Str::random(8)),
            'user_id' => $property->user_id,
            'property_id' => $property->id,
            'type' => 'service',
            'revenue_stream' => $stream,
            'amount' => $validated['amount'],
            'status' => 'due',
            'due_date' => now()->addDays(7),
            'notes' => $validated['notes'] ?: $item->name,
        ]);

        AuditLog::record($request->user(), 'Billed service charge', $payment, "Billed {$item->name} (PKR " . number_format($validated['amount']) . ") to {$property->title}");

        return back()->with('success', $item->name . ' billed to ' . $property->title . '.');
    }
}
