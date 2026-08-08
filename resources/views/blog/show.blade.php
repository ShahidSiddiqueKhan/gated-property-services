@extends('layouts.app')

@section('title', $post->title . ' | GATED Property Services Blog')
@section('meta_description', $post->excerpt)

@section('content')

    <section class="bg-ink-950 text-white py-16">
        <div class="max-w-3xl mx-auto px-6">
            <a href="{{ route('blog.index') }}" class="text-sm text-ink-400 hover:text-white">&larr; Back to Blog</a>
            @if ($post->category)
                <span class="section-eyebrow text-brand-500 block mt-4">{{ $post->category }}</span>
            @endif
            <h1 class="mt-2 text-3xl lg:text-4xl font-heading font-extrabold">{{ $post->title }}</h1>
            <p class="mt-3 text-sm text-ink-400">By {{ $post->author }} &middot; {{ optional($post->published_at)->format('F d, Y') }}</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-6" data-reveal>
            @if ($post->image)
                <img src="{{ \App\Support\Media::url($post->image) }}" class="rounded-2xl w-full aspect-video object-cover mb-10 shadow-lg" alt="{{ $post->title }}">
            @endif
            <div class="prose max-w-none prose-headings:font-heading text-ink-700 leading-relaxed">
                {!! $post->body !!}
            </div>

            @if ($post->resource_file)
                <a href="{{ \App\Support\Media::url($post->resource_file) }}" target="_blank" class="mt-8 card p-5 flex items-center gap-3 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 max-w-md">
                    <span class="w-11 h-11 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon name="document-arrow-down" class="w-5 h-5" /></span>
                    <div>
                        <div class="font-semibold text-sm text-ink-900">Download Resource</div>
                        <div class="text-xs text-ink-500">Click to download &rarr;</div>
                    </div>
                </a>
            @endif
        </div>

        @if ($related->count())
        <div class="max-w-5xl mx-auto px-6 mt-16 pt-10 border-t border-ink-100">
            <h3 class="font-heading font-bold text-ink-900 mb-6">More Resources</h3>
            <div class="grid sm:grid-cols-3 gap-6">
                @foreach ($related as $r)
                    <div data-reveal data-reveal-delay="{{ min($loop->iteration, 6) }}">
                        @include('blog.partials.card', ['post' => $r])
                    </div>
                @endforeach
            </div>
        </div>
        @endif
    </section>

@endsection
