@php $promotion = $promotion ?? null; @endphp

<div class="grid sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Title</label>
        <input type="text" name="title" value="{{ old('title', $promotion?->title) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Discount Label</label>
        <input type="text" name="discount_label" value="{{ old('discount_label', $promotion?->discount_label) }}" placeholder="e.g. 20% Off First Month" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Valid Until</label>
        <input type="date" name="valid_until" value="{{ old('valid_until', $promotion?->valid_until?->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Description</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('description', $promotion?->description) }}</textarea>
    </div>
    <label class="flex items-center gap-2 text-sm text-ink-600">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $promotion?->is_active ?? true)) class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> Active
    </label>
</div>
