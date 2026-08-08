@php $item = $item ?? null; @endphp

<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-semibold text-ink-700">Category</label>
        <select name="category" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            @foreach (['advertising' => 'Property Advertising', 'emergency' => 'Emergency Services'] as $value => $label)
                <option value="{{ $value }}" @selected(old('category', $item?->category ?? request('category')) === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Name</label>
        <input type="text" name="name" value="{{ old('name', $item?->name) }}" required placeholder="e.g. Drone Video" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Description</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('description', $item?->description) }}</textarea>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Price (PKR)</label>
        <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $item?->price) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Price Max <span class="font-normal text-ink-400">(blank = fixed price)</span></label>
        <input type="number" step="0.01" min="0" name="price_max" value="{{ old('price_max', $item?->price_max) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $item?->sort_order ?? 0) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="flex items-end pb-2.5">
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item?->is_active ?? true)) class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> Active (shown to clients)
        </label>
    </div>
</div>
