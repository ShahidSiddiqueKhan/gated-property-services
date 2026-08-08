@extends('layouts.app')

@section('title', 'Blog & Resources | GATED Property Services')
@section('meta_description', 'Real estate trends, property investment guidance, rental management tips, market updates and legal guidance from GATED Property Services.')

@section('content')

    <section class="bg-ink-950 text-white py-16 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 15% 20%, var(--color-brand-600) 0%, transparent 40%), radial-gradient(circle at 85% 80%, var(--color-brand-700) 0%, transparent 40%);"></div>
        <div class="max-w-3xl mx-auto px-6 relative" data-reveal>
            <span class="section-eyebrow text-brand-500">Blog &amp; Resources</span>
            <h1 class="mt-3 text-4xl font-heading font-extrabold">Insights for Property Owners</h1>
            <p class="mt-4 text-ink-300">Real estate trends, investment guidance, rental management and legal advice.</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-wrap gap-2 mb-10" data-reveal>
                <a href="{{ route('blog.index') }}" class="badge {{ !request('type') ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }} transition-colors duration-200">All</a>
                <a href="{{ route('blog.index', ['type' => 'article']) }}" class="badge {{ request('type') === 'article' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }} transition-colors duration-200">Articles</a>
                <a href="{{ route('blog.index', ['type' => 'guide']) }}" class="badge {{ request('type') === 'guide' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }} transition-colors duration-200">Guides</a>
                <a href="{{ route('blog.index', ['type' => 'video']) }}" class="badge {{ request('type') === 'video' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }} transition-colors duration-200">Video Tutorials</a>
                <a href="{{ route('blog.index', ['type' => 'download']) }}" class="badge {{ request('type') === 'download' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }} transition-colors duration-200">Downloadable Resources</a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($posts as $post)
                    <div data-reveal data-reveal-delay="{{ min($loop->iteration, 6) }}">
                        @include('blog.partials.card', ['post' => $post])
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        </div>
    </section>

@endsection
