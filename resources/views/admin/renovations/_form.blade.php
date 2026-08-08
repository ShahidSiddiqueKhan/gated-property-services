@php $project = $project ?? null; @endphp

<div class="grid sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Property</label>
        <select name="property_id" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            <option value="">Select property</option>
            @foreach ($properties as $p)
                <option value="{{ $p->id }}" @selected(old('property_id', $project?->property_id ?? request('property_id')) == $p->id)>{{ $p->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Project Title</label>
        <input type="text" name="title" value="{{ old('title', $project?->title) }}" required placeholder="e.g. Kitchen & Bathroom Renovation" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Description</label>
        <textarea name="description" rows="3" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('description', $project?->description) }}</textarea>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Contractor Name</label>
        <input type="text" name="contractor_name" value="{{ old('contractor_name', $project?->contractor_name) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Contractor Contact</label>
        <input type="text" name="contractor_contact" value="{{ old('contractor_contact', $project?->contractor_contact) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Project Value (PKR)</label>
        <input type="number" step="0.01" min="0" name="project_value" value="{{ old('project_value', $project?->project_value) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
        <p class="mt-1 text-xs text-ink-400">GATED's fee is auto-calculated from renovation fee tiers based on this value.</p>
    </div>
    <div></div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Start Date</label>
        <input type="date" name="start_date" value="{{ old('start_date', $project?->start_date?->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Expected Completion</label>
        <input type="date" name="expected_completion_date" value="{{ old('expected_completion_date', $project?->expected_completion_date?->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
</div>
