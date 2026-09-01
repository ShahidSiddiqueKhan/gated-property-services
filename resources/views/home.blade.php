@extends('layouts.app')

@section('title', 'GATED Property Services | Your Property. Our Responsibility.')
@section('meta_description', 'Professional property management, tenant services, rent collection, maintenance management and real-time client updates for local and overseas owners across Pakistan.')

@section('content')

    {{-- HERO --}}
    <section class="relative bg-ink-950 overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('images/site/villa-aerial-dusk.jpg') }}" class="w-full h-full object-cover opacity-30" alt="GATED managed luxury villa">
            <div class="absolute inset-0 bg-gradient-to-r from-ink-950 via-ink-950/95 to-ink-950/40"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 pt-20 pb-24 lg:pt-28 lg:pb-32 grid lg:grid-cols-2 gap-12 items-center">
            <div data-reveal>
                <span class="section-eyebrow text-brand-500">Trusted Property Management in Pakistan</span>
                <h1 class="mt-4 text-4xl sm:text-5xl lg:text-6xl font-heading font-extrabold text-white leading-[1.1]">
                    Your Property. <span class="text-brand-500">Our Responsibility.</span>
                </h1>
                <p class="mt-6 text-lg text-ink-300 max-w-xl">
                    Professional Property Management, Tenant Services, Rent Collection, Maintenance Management, and Real-Time Client Updates &mdash; for local and overseas owners alike.
                </p>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('property-registration.create') }}" class="btn-primary">Register Property</a>
                    <a href="{{ route('login') }}" class="btn-outline-white">Client Portal Login</a>
                    <a href="{{ route('contact.show') }}" class="btn-outline-white">Request Consultation</a>
                </div>

                <ul class="mt-8 grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-2 text-sm text-ink-200 max-w-lg">
                    @foreach (['Property Management','Tenant Services','Rent Collection','Maintenance Management','Real-Time Updates','Overseas Services'] as $item)
                        <li class="flex items-center gap-2"><x-icon name="check" class="w-4 h-4 text-brand-500 shrink-0" /> {{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="hidden lg:block relative" data-reveal="zoom" data-reveal-delay="2">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl aspect-[4/5]">
                    <img src="{{ asset('images/site/villa-facade-sunset.jpg') }}" class="w-full h-full object-cover" alt="GATED managed villa at sunset">
                </div>

                <div class="absolute -right-8 bottom-16 card p-4 shadow-2xl w-52 animate-float" style="animation-delay: 1.2s">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon name="check-circle" class="w-5 h-5" /></span>
                        <div>
                            <div class="text-[11px] text-ink-500">Occupancy Rate</div>
                            <div class="font-heading font-bold text-ink-900 text-sm">98% Satisfied</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats strip --}}
        <div class="relative border-t border-white/10 bg-ink-950/80 backdrop-blur">
            <div class="max-w-7xl mx-auto px-6 py-8 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center" data-reveal>
                <div>
                    <div class="text-3xl font-heading font-extrabold text-white"><span data-counter="{{ $stats['properties_managed'] }}" data-counter-suffix="+">0</span></div>
                    <div class="text-xs uppercase tracking-wide text-ink-400 mt-1">Properties Managed</div>
                </div>
                <div>
                    <div class="text-3xl font-heading font-extrabold text-white"><span data-counter="{{ $stats['client_satisfaction'] }}" data-counter-suffix="%">0</span></div>
                    <div class="text-xs uppercase tracking-wide text-ink-400 mt-1">Client Satisfaction</div>
                </div>
                <div>
                    <div class="text-3xl font-heading font-extrabold text-white"><span data-counter="{{ $stats['years_experience'] }}" data-counter-suffix="+">0</span></div>
                    <div class="text-xs uppercase tracking-wide text-ink-400 mt-1">Years of Excellence</div>
                </div>
                <div>
                    <div class="text-3xl font-heading font-extrabold text-white">{{ $stats['support_available'] }}</div>
                    <div class="text-xs uppercase tracking-wide text-ink-400 mt-1">Support Available</div>
                </div>
            </div>
        </div>
    </section>

    @if ($promotion)
    {{-- PROMOTION BANNER --}}
    <section class="bg-ink-900">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-wrap items-center justify-center gap-3 text-center">
            <span class="badge bg-brand-600 text-white shrink-0">{{ $promotion->discount_label ?? 'Limited Time Offer' }}</span>
            <p class="text-sm text-ink-200">
                <span class="font-semibold text-white">{{ $promotion->title }}</span>
                @if ($promotion->description) &mdash; {{ $promotion->description }} @endif
                @if ($promotion->valid_until) <span class="text-ink-400">(valid until {{ $promotion->valid_until->format('M d, Y') }})</span> @endif
            </p>
            <a href="{{ route('promotions.index') }}" class="text-sm font-semibold text-brand-500 hover:text-brand-400 shrink-0">View All Offers &rarr;</a>
        </div>
    </section>
    @endif

    {{-- SERVICES --}}
    <section class="py-20 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-2xl mx-auto text-center" data-reveal>
                <span class="section-eyebrow">Our Premium Services</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-heading font-extrabold text-ink-900">Everything Your Property Needs, In One Place</h2>
                <p class="mt-4 text-ink-500">From residential and commercial management to Airbnb hosting and overseas owner support &mdash; GATED covers it all.</p>
            </div>

            <div class="mt-14 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($services as $service)
                    <a href="{{ route('services.show', $service) }}" data-reveal data-reveal-delay="{{ min($loop->iteration, 6) }}" class="card p-6 hover:shadow-lg hover:-translate-y-1 transition group">
                        <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center group-hover:bg-brand-600 group-hover:text-white group-hover:scale-110 transition-all duration-300">
                            <x-icon :name="$service->icon" class="w-6 h-6" />
                        </div>
                        <h3 class="mt-4 font-heading font-bold text-ink-900">{{ $service->name }}</h3>
                        <p class="mt-2 text-sm text-ink-500 leading-relaxed">{{ $service->short_description }}</p>
                    </a>
                @endforeach
            </div>

            <div class="mt-10 text-center">
                <a href="{{ route('services.index') }}" class="btn-outline">View All Services <x-icon name="arrow-right" class="w-4 h-4" /></a>
            </div>
        </div>
    </section>

    {{-- ABOUT strip --}}
    <section class="py-20 lg:py-24 bg-ink-50">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div class="relative" data-reveal="zoom">
                <img src="{{ asset('images/site/villa-aerial-day.jpg') }}" class="rounded-2xl shadow-xl w-full aspect-[4/3] object-cover" alt="GATED managed luxury villa, aerial view">
                <div class="absolute -bottom-6 -right-6 bg-ink-950 text-white rounded-2xl px-6 py-4 shadow-xl hidden sm:block">
                    <div class="text-2xl font-heading font-extrabold text-brand-500">7+ Years</div>
                    <div class="text-xs text-ink-300">of Excellence</div>
                </div>
            </div>
            <div data-reveal data-reveal-delay="2">
                <span class="section-eyebrow">About GATED</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-heading font-extrabold text-ink-900">We Manage Your Property Like It's Our Own</h2>
                <p class="mt-4 text-ink-500 leading-relaxed">
                    GATED Property Services is a leading property management company providing reliable, transparent and result-driven solutions for local and overseas property owners across Pakistan. Our mission is simple: give owners complete peace of mind and maximum return on their investment.
                </p>
                <div class="mt-8 grid sm:grid-cols-2 gap-4">
                    @foreach ([
                        ['icon' => 'shield-check', 'label' => 'Transparent Process'],
                        ['icon' => 'chart-bar', 'label' => 'Real-Time Updates'],
                        ['icon' => 'lock-closed', 'label' => 'Secure &amp; Reliable'],
                        ['icon' => 'users', 'label' => 'Dedicated Team'],
                    ] as $point)
                        <div class="flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-white shadow-sm flex items-center justify-center text-brand-600 shrink-0"><x-icon :name="$point['icon']" class="w-5 h-5" /></span>
                            <span class="font-semibold text-ink-800 text-sm">{!! $point['label'] !!}</span>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('about') }}" class="btn-primary mt-8">Learn More About Us</a>
            </div>
        </div>
    </section>

    {{-- 7 Cs FRAMEWORK --}}
    <section class="py-20 lg:py-24 bg-ink-950 text-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-2xl mx-auto text-center">
                <span class="section-eyebrow text-brand-500">Our Philosophy</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-heading font-extrabold">The 7 Cs of GATED</h2>
                <p class="mt-4 text-ink-300">Every decision we make is guided by these seven commitments to our clients.</p>
            </div>

            <div class="mt-14 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ([
                    ['title' => 'Customer', 'desc' => 'Everything designed around client needs.'],
                    ['title' => 'Cost', 'desc' => 'Transparent pricing and service packages.'],
                    ['title' => 'Convenience', 'desc' => 'Simple navigation and easy access to information.'],
                    ['title' => 'Communication', 'desc' => 'Multiple channels and real-time updates.'],
                    ['title' => 'Credibility', 'desc' => 'Testimonials, certifications, and trust badges.'],
                    ['title' => 'Connection', 'desc' => 'Strong relationship-building with every client.'],
                    ['title' => 'Co-Creation', 'desc' => 'Feedback, surveys, and collaborative decisions.'],
                ] as $i => $c)
                    <div data-reveal data-reveal-delay="{{ min($i + 1, 6) }}" class="rounded-2xl border border-white/10 bg-white/5 p-6 hover:border-brand-600/60 hover:bg-white/10 hover:-translate-y-1 transition-all duration-300">
                        <div class="text-3xl font-heading font-extrabold text-brand-500">{{ $i + 1 }}</div>
                        <h3 class="mt-2 font-heading font-bold">{{ $c['title'] }}</h3>
                        <p class="mt-2 text-sm text-ink-300 leading-relaxed">{{ $c['desc'] }}</p>
                    </div>
                @endforeach
                <div class="rounded-2xl bg-brand-600 p-6 flex flex-col justify-center">
                    <h3 class="font-heading font-bold text-lg">See it in action</h3>
                    <p class="mt-2 text-sm text-brand-100">Explore your personalized Client Portal.</p>
                    <a href="{{ route('login') }}" class="mt-4 inline-flex text-sm font-semibold items-center gap-1 text-white">Client Portal Login <x-icon name="arrow-right" class="w-4 h-4" /></a>
                </div>
            </div>
        </div>
    </section>

    {{-- FEATURED PROPERTIES --}}
    <section class="py-20 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-10" data-reveal>
                <div>
                    <span class="section-eyebrow">Featured Properties</span>
                    <h2 class="mt-3 text-3xl lg:text-4xl font-heading font-extrabold text-ink-900">Available &amp; Managed Properties</h2>
                </div>
                <a href="{{ route('properties.index') }}" class="btn-outline">View All Properties <x-icon name="arrow-right" class="w-4 h-4" /></a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($featuredProperties as $property)
                    <div data-reveal data-reveal-delay="{{ min($loop->iteration, 6) }}">
                        @include('properties.partials.card', ['property' => $property])
                    </div>
                @empty
                    <p class="text-ink-500 col-span-3">Featured properties will appear here shortly.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- TESTIMONIALS --}}
    <section class="py-20 lg:py-24 bg-ink-50">
        <div class="max-w-7xl mx-auto px-6">
            <div class="max-w-2xl mx-auto text-center" data-reveal>
                <span class="section-eyebrow">What Our Clients Say</span>
                <h2 class="mt-3 text-3xl lg:text-4xl font-heading font-extrabold text-ink-900">Trusted by Property Owners</h2>
            </div>

            <div class="mt-14 grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($testimonials as $t)
                    <div data-reveal data-reveal-delay="{{ min($loop->iteration, 6) }}" class="card p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                        <div class="flex gap-0.5 text-brand-500 mb-3">
                            @for ($i = 0; $i < $t->rating; $i++)
                                <x-icon name="star" class="w-4 h-4 fill-current" />
                            @endfor
                        </div>
                        <p class="text-sm text-ink-600 leading-relaxed">&ldquo;{{ $t->content }}&rdquo;</p>
                        <div class="mt-5 flex items-center gap-3">
                            <span class="w-10 h-10 rounded-full bg-ink-900 text-white flex items-center justify-center font-heading font-bold text-sm">{{ substr($t->name, 0, 1) }}</span>
                            <div>
                                <div class="font-semibold text-ink-900 text-sm">{{ $t->name }}</div>
                                <div class="text-xs text-ink-500">{{ $t->role }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-10 flex flex-wrap items-center justify-center gap-x-10 gap-y-4 text-ink-400 text-sm font-semibold">
                <span>Trusted by property owners in</span>
                @foreach (['DHA Islamabad','Bahria Town','Emaar Pakistan','Lake City','Gulberg Islamabad'] as $partner)
                    <span class="text-ink-500">{{ $partner }}</span>
                @endforeach
            </div>
        </div>
    </section>

    {{-- BLOG --}}
    @if ($posts->count())
    <section class="py-20 lg:py-24 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-10" data-reveal>
                <div>
                    <span class="section-eyebrow">Resources</span>
                    <h2 class="mt-3 text-3xl lg:text-4xl font-heading font-extrabold text-ink-900">Latest Insights &amp; Guidance</h2>
                </div>
                <a href="{{ route('blog.index') }}" class="btn-outline">Visit Blog <x-icon name="arrow-right" class="w-4 h-4" /></a>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($posts as $post)
                    <div data-reveal data-reveal-delay="{{ min($loop->iteration, 6) }}">
                        @include('blog.partials.card', ['post' => $post])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- CTA --}}
    <section class="py-16 bg-brand-600 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 30%, white 1px, transparent 1px), radial-gradient(circle at 80% 70%, white 1px, transparent 1px); background-size: 48px 48px;"></div>
        <div class="max-w-7xl mx-auto px-6 flex flex-col lg:flex-row items-center justify-between gap-8 relative" data-reveal>
            <div class="text-center lg:text-left">
                <h2 class="text-2xl lg:text-3xl font-heading font-extrabold text-white">Let's Manage Your Property the Smart Way</h2>
                <p class="mt-2 text-brand-100">Register today and get a free consultation with our property management specialists.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="{{ route('property-registration.create') }}" class="btn-dark">Get Started Today</a>
                <a href="tel:+923215381128" class="btn-outline-white">Call Now: +92 321 5381128</a>
            </div>
        </div>
    </section>

@endsection
