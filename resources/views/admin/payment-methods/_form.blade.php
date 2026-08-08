@php $paymentMethod = $paymentMethod ?? null; @endphp

@php
    $iconOptions = ['banknotes', 'building-office', 'globe-alt', 'shield-check', 'chart-bar', 'document-text'];
@endphp

<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-semibold text-ink-700">Method Name</label>
        <input type="text" name="name" value="{{ old('name', $paymentMethod?->name) }}" required placeholder="e.g. Meezan Bank Transfer" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Region</label>
        <select name="region" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            @foreach (['local' => 'Local (Pakistan)', 'overseas' => 'Overseas', 'both' => 'Both'] as $value => $label)
                <option value="{{ $value }}" @selected(old('region', $paymentMethod?->region ?? 'both') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>

    @if ($paymentMethod?->isGateway())
        <div class="sm:col-span-2 rounded-lg bg-brand-50 border border-brand-100 px-4 py-3 text-sm text-brand-700">
            This is a live gateway method wired to checkout code (code: <span class="font-mono font-semibold">{{ $paymentMethod->code }}</span>). Only its display details can be edited here — it cannot be deleted or converted to manual.
        </div>
    @else
        <input type="hidden" name="type" value="manual">
        <div>
            <label class="text-sm font-semibold text-ink-700">Internal Code <span class="font-normal text-ink-400">(optional)</span></label>
            <input type="text" name="code" value="{{ old('code', $paymentMethod?->code) }}" placeholder="auto-generated from name" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
        </div>
    @endif

    <div>
        <label class="text-sm font-semibold text-ink-700">Icon</label>
        <select name="icon" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            @foreach ($iconOptions as $icon)
                <option value="{{ $icon }}" @selected(old('icon', $paymentMethod?->icon) === $icon)>{{ ucwords(str_replace('-',' ',$icon)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Instructions shown to clients</label>
        <textarea name="instructions" rows="4" placeholder="e.g. Account Title: GATED Property Services, IBAN: ..., include your invoice number as the transfer reference." class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('instructions', $paymentMethod?->instructions) }}</textarea>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Sort Order</label>
        <input type="number" name="sort_order" value="{{ old('sort_order', $paymentMethod?->sort_order ?? 0) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="flex items-end pb-2.5">
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $paymentMethod?->is_active ?? true)) class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> Active (shown to clients at checkout)
        </label>
    </div>
</div>
