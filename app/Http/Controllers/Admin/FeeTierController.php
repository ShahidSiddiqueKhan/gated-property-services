<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\FeeTier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FeeTierController extends Controller
{
    public function index(): View
    {
        $tiersByCategory = [
            'maintenance' => FeeTier::category('maintenance')->get(),
            'renovation' => FeeTier::category('renovation')->get(),
        ];

        return view('admin.fee-tiers.index', compact('tiersByCategory'));
    }

    public function create(): View
    {
        return view('admin.fee-tiers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateFeeTier($request);

        $tier = FeeTier::create($validated);

        AuditLog::record($request->user(), 'Created fee tier', $tier, "Added {$tier->category} fee tier: {$tier->fee_percent}%");

        return redirect()->route('admin.fee-tiers.index')->with('success', 'Fee tier added.');
    }

    public function edit(FeeTier $feeTier): View
    {
        return view('admin.fee-tiers.edit', ['tier' => $feeTier]);
    }

    public function update(Request $request, FeeTier $feeTier): RedirectResponse
    {
        $validated = $this->validateFeeTier($request);

        $feeTier->update($validated);

        AuditLog::record($request->user(), 'Updated fee tier', $feeTier, "Updated {$feeTier->category} fee tier: {$feeTier->fee_percent}%");

        return redirect()->route('admin.fee-tiers.index')->with('success', 'Fee tier updated.');
    }

    public function destroy(Request $request, FeeTier $feeTier): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted fee tier', null, "Deleted {$feeTier->category} fee tier: {$feeTier->fee_percent}%");

        $feeTier->delete();

        return back()->with('success', 'Fee tier deleted.');
    }

    protected function validateFeeTier(Request $request): array
    {
        $validated = $request->validate([
            'category' => ['required', 'in:maintenance,renovation'],
            'min_amount' => ['required', 'numeric', 'min:0'],
            'max_amount' => ['nullable', 'numeric', 'gt:min_amount'],
            'fee_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'sort_order' => ['nullable', 'integer'],
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        return $validated;
    }
}
