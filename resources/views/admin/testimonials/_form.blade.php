@php $testimonial = $testimonial ?? null; @endphp

<div class="grid sm:grid-cols-2 gap-4">
    <div>
        <label class="text-sm font-semibold text-ink-700">Name</label>
        <input type="text" name="name" value="{{ old('name', $testimonial?->name) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Role / Title</label>
        <input type="text" name="role" value="{{ old('role', $testimonial?->role) }}" placeholder="e.g. Property Owner" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Location</label>
        <input type="text" name="location" value="{{ old('location', $testimonial?->location) }}" placeholder="e.g. Overseas Client" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Rating</label>
        <select name="rating" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            @for ($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" @selected(old('rating', $testimonial?->rating ?? 5) == $i)>{{ $i }} Stars</option>
            @endfor
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Testimonial</label>
        <textarea name="content" rows="4" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('content', $testimonial?->content) }}</textarea>
    </div>
    <label class="flex items-center gap-2 text-sm text-ink-600">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $testimonial?->is_featured ?? true)) class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> Feature on homepage
    </label>
</div>
