@extends('layouts.app')

@section('title', 'Our Services | GATED Property Services')
@section('meta_description', 'Explore GATED\'s full range of property management services: residential, commercial, Airbnb, overseas owner services, maintenance and more.')

@section('content')

    <section class="bg-ink-950 text-white py-20 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 15% 20%, var(--color-brand-600) 0%, transparent 40%), radial-gradient(circle at 85% 80%, var(--color-brand-700) 0%, transparent 40%);"></div>
        <div class="max-w-3xl mx-auto px-6 relative" data-reveal>
            <span class="section-eyebrow text-brand-500">Our Premium Services</span>
            <h1 class="mt-3 text-4xl lg:text-5xl font-heading font-extrabold">Comprehensive Property Management Solutions</h1>
            <p class="mt-4 text-ink-300">Tailored to your needs &mdash; whether you own a single apartment or a growing portfolio.</p>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($services as $service)
                <div class="card p-8 flex flex-col hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-reveal data-reveal-delay="{{ min($loop->iteration, 6) }}">
                    <div class="w-14 h-14 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center">
                        <x-icon :name="$service->icon" class="w-7 h-7" />
                    </div>
                    <h3 class="mt-5 font-heading font-bold text-lg text-ink-900">{{ $service->name }}</h3>
                    <p class="mt-2 text-sm text-ink-500 leading-relaxed flex-1">{{ $service->short_description }}</p>
                    <ul class="mt-4 space-y-1.5">
                        @foreach (array_slice($service->features ?? [], 0, 3) as $feature)
                            <li class="flex items-center gap-2 text-xs text-ink-600"><x-icon name="check" class="w-3.5 h-3.5 text-brand-600 shrink-0" /> {{ $feature }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('services.show', $service) }}" class="mt-5 inline-flex items-center gap-1 text-sm font-semibold text-brand-600 hover:text-brand-700">Learn More <x-icon name="arrow-right" class="w-4 h-4" /></a>
                </div>
            @endforeach
        </div>
    </section>

    <section class="py-16 bg-ink-50">
        <div class="max-w-4xl mx-auto px-6 text-center" data-reveal>
            <span class="section-eyebrow">Cost &amp; Transparency</span>
            <h2 class="mt-3 text-3xl font-heading font-extrabold text-ink-900">Simple, Transparent Pricing</h2>
            <p class="mt-4 text-ink-500">Every service package includes clear pricing with no hidden fees. Request a personalised quote and we'll walk you through exactly what's included.</p>
            <a href="{{ route('contact.show') }}" class="btn-primary mt-6">Request a Quote</a>
        </div>
    </section>

@endsection
