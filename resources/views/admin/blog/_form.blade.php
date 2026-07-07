@php $post = $post ?? null; @endphp

<div class="grid sm:grid-cols-2 gap-4">
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Title</label>
        <input type="text" name="title" value="{{ old('title', $post?->title) }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Category</label>
        <input type="text" name="category" value="{{ old('category', $post?->category) }}" placeholder="e.g. Market Updates" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Resource Type</label>
        <select name="resource_type" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
            <option value="article" @selected(old('resource_type', $post?->resource_type) === 'article')>Article</option>
            <option value="guide" @selected(old('resource_type', $post?->resource_type) === 'guide')>Guide</option>
            <option value="video" @selected(old('resource_type', $post?->resource_type) === 'video')>Video Tutorial</option>
            <option value="download" @selected(old('resource_type', $post?->resource_type) === 'download')>Downloadable Resource</option>
        </select>
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Excerpt</label>
        <input type="text" name="excerpt" value="{{ old('excerpt', $post?->excerpt) }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="sm:col-span-2">
        <label class="text-sm font-semibold text-ink-700">Body (HTML allowed)</label>
        <textarea name="body" rows="8" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500 font-mono text-sm">{{ old('body', $post?->body) }}</textarea>
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700 block mb-2">Cover Image</label>
        <input type="file" name="image" accept="image/*" class="w-full text-sm">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700 block mb-2">Downloadable File (optional)</label>
        <input type="file" name="resource_file" class="w-full text-sm">
    </div>
    <div>
        <label class="text-sm font-semibold text-ink-700">Author</label>
        <input type="text" name="author" value="{{ old('author', $post?->author) }}" placeholder="GATED Property Services" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
    </div>
    <div class="flex items-end pb-2.5">
        <label class="flex items-center gap-2 text-sm text-ink-600">
            <input type="checkbox" name="is_published" value="1" @checked(old('is_published', $post?->published_at)) class="rounded border-ink-300 text-brand-600 focus:ring-brand-500"> Published
        </label>
    </div>
</div>
