@php $lease = $lease ?? null; @endphp

<div class="grid sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Property</label>
        <select name="property_id" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            <option value="">Select property</option>
            @foreach ($properties as $p)
                <option value="{{ $p->id }}" @selected(old('property_id', $lease?->property_id) == $p->id)>{{ $p->title }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Tenant Name</label>
        <input type="text" name="tenant_name" value="{{ old('tenant_name', $lease?->tenant_name) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Tenant Phone</label>
        <input type="text" name="tenant_phone" value="{{ old('tenant_phone', $lease?->tenant_phone) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Tenant Email</label>
        <input type="email" name="tenant_email" value="{{ old('tenant_email', $lease?->tenant_email) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Monthly Rent (PKR)</label>
        <input type="number" name="rent_amount" value="{{ old('rent_amount', $lease?->rent_amount) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Start Date</label>
        <input type="date" name="start_date" value="{{ old('start_date', $lease?->start_date?->format('Y-m-d')) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">End Date</label>
        <input type="date" name="end_date" value="{{ old('end_date', $lease?->end_date?->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Status</label>
        <select name="status" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            <option value="active" @selected(old('status', $lease?->status) === 'active')>Active</option>
            <option value="pending" @selected(old('status', $lease?->status) === 'pending')>Pending</option>
            <option value="ended" @selected(old('status', $lease?->status) === 'ended')>Ended</option>
        </select>
    </div>
</div>

@unless ($lease)
    <div class="mt-4 pt-4 border-t border-ink-100" x-data="{ charge: false }">
        <label class="flex items-center gap-2 text-sm text-ink-700">
            <input type="checkbox" name="charge_placement_fee" value="1" x-model="charge" class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> Charge a tenant placement fee for this new tenant
        </label>
        <div x-show="charge" x-cloak class="mt-3 max-w-xs">
            <label class="text-sm font-semibold text-ink-700">Placement Fee % of Monthly Rent</label>
            <input type="number" step="1" min="50" max="100" name="placement_fee_percent" value="50" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            <p class="mt-1 text-xs text-ink-400">Standard range 50–100%, set per client agreement.</p>
        </div>
    </div>
@endunless
