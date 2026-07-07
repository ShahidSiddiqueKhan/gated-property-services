<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Promotion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PromotionController extends Controller
{
    public function index(): View
    {
        $promotions = Promotion::latest()->paginate(15);

        return view('admin.promotions.index', compact('promotions'));
    }

    public function create(): View
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePromotion($request);

        $promotion = Promotion::create($validated);

        AuditLog::record($request->user(), 'Created promotion', $promotion, "Added promotion: {$promotion->title}");

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion created.');
    }

    public function edit(Promotion $promotion): View
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion): RedirectResponse
    {
        $validated = $this->validatePromotion($request);

        $promotion->update($validated);

        AuditLog::record($request->user(), 'Updated promotion', $promotion, "Updated promotion: {$promotion->title}");

        return redirect()->route('admin.promotions.index')->with('success', 'Promotion updated.');
    }

    public function destroy(Request $request, Promotion $promotion): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted promotion', null, "Deleted promotion: {$promotion->title}");

        $promotion->delete();

        return back()->with('success', 'Promotion deleted.');
    }

    protected function validatePromotion(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'discount_label' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string'],
            'valid_until' => ['nullable', 'date'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        return $validated;
    }
}
