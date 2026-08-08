@php $package = $package ?? null; @endphp

<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-semibold text-ink-700">Package Name</label>
        <input type="text" name="name" value="{{ old('name', $package?->name) }}" required placeholder="e.g. Premium" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Monthly Price (PKR)</label>
        <input type="number" step="0.01" min="0" name="monthly_price" value="{{ old('monthly_price', $package?->monthly_price) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Description</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('description', $package?->description) }}</textarea>
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Features <span class="font-normal text-ink-400">(one per line)</span></label>
        <textarea name="features" rows="5" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('features', $package ? implode("\n", $package->features ?? []) : '') }}</textarea>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Default Rent Commission %</label>
        <input type="number" step="0.01" min="0" max="100" name="rent_commission_percent" value="{{ old('rent_commission_percent', $package?->rent_commission_percent ?? 0) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
        <p class="mt-1 text-xs text-ink-400">Applied when GATED collects rent on this package. Admins can override up to 12% per property.</p>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $package?->sort_order ?? 0) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="flex items-end pb-2.5">
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $package?->is_active ?? true)) class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> Active (assignable to properties)
        </label>
    </div>
</div>
