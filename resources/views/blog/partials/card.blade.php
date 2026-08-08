@php
    $imageUrl = \App\Support\Media::url($post->image, 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=800&q=80');
@endphp
<a href="{{ route('blog.show', $post) }}" class="card overflow-hidden group hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
    <div class="aspect-[16/10] overflow-hidden relative">
        <img src="{{ $imageUrl }}" alt="{{ $post->title }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
        <div class="absolute inset-0 bg-gradient-to-t from-ink-950/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <span class="absolute top-3 left-3 badge bg-ink-950/90 text-white capitalize">{{ $post->resource_type }}</span>
    </div>
    <div class="p-6 flex flex-col flex-1">
        @if ($post->category)
            <span class="section-eyebrow">{{ $post->category }}</span>
        @endif
        <h3 class="mt-2 font-heading font-bold text-ink-900 group-hover:text-brand-600 transition leading-snug">{{ $post->title }}</h3>
        <p class="mt-2 text-sm text-ink-500 leading-relaxed line-clamp-2">{{ $post->excerpt }}</p>
        <div class="mt-4 pt-4 border-t border-ink-100 text-xs text-ink-400">
            {{ optional($post->published_at)->format('M d, Y') }}
        </div>
    </div>
</a>
