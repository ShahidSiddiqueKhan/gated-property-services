@php $tier = $tier ?? null; @endphp

<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-semibold text-ink-700">Category</label>
        <select name="category" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            @foreach (['maintenance' => 'Maintenance Coordination', 'renovation' => 'Renovation Project Management'] as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $tier?->category ?? request('category')) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div></div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Min Amount (PKR)</label>
        <input type="number" step="0.01" min="0" name="min_amount" value="{{ old('min_amount', $tier?->min_amount ?? 0) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Max Amount <span class="font-normal text-ink-400">(blank = no upper limit)</span></label>
        <input type="number" step="0.01" min="0" name="max_amount" value="{{ old('max_amount', $tier?->max_amount) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Fee Percent</label>
        <input type="number" step="0.01" min="0" max="100" name="fee_percent" value="{{ old('fee_percent', $tier?->fee_percent) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $tier?->sort_order ?? 0) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
</div>
