@php $service = $service ?? null; @endphp

@php
    $iconOptions = ['home', 'building-office', 'building-storefront', 'banknotes', 'wrench-screwdriver', 'globe-alt', 'megaphone', 'users', 'shield-check', 'document-text', 'chart-bar', 'camera'];
@endphp

<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-semibold text-ink-700">Service Name</label>
        <input type="text" name="name" value="{{ old('name', $service?->name) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Icon</label>
        <select name="icon" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            @foreach ($iconOptions as $icon)
                <option value="{{ $icon }}" @selected(old('icon', $service?->icon) === $icon)>{{ ucwords(str_replace('-',' ',$icon)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Short Description</label>
        <input type="text" name="short_description" value="{{ old('short_description', $service?->short_description) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Full Description</label>
        <textarea name="description" rows="4" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('description', $service?->description) }}</textarea>
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Features <span class="font-normal text-ink-400">(one per line)</span></label>
        <textarea name="features" rows="4" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('features', $service ? implode("\n", $service->features ?? []) : '') }}</textarea>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $service?->sort_order ?? 0) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="flex items-end pb-2.5">
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $service?->is_active ?? true)) class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> Active (visible on site)
        </label>
    </div>
</div>
