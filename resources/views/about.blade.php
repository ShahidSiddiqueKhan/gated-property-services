@extends('layouts.app')

@section('title', 'About Us | GATED Property Services')
@section('meta_description', 'Learn about GATED Property Services\' mission, vision, values, and the team dedicated to managing your property like our own.')

@section('content')

    <section class="bg-ink-950 text-white py-20 relative overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 15% 20%, var(--color-brand-600) 0%, transparent 40%), radial-gradient(circle at 85% 80%, var(--color-brand-700) 0%, transparent 40%);"></div>
        <div class="max-w-7xl mx-auto px-6 text-center relative" data-reveal>
            <span class="section-eyebrow text-brand-500">About GATED</span>
            <h1 class="mt-3 text-4xl lg:text-5xl font-heading font-extrabold">We Manage Your Property Like It's Our Own</h1>
            <p class="mt-4 max-w-2xl mx-auto text-ink-300">GATED Property Services is a leading property management company providing reliable, transparent and result-driven solutions for local and overseas property owners.</p>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-3 gap-8">
            <div class="card p-8 hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-reveal data-reveal-delay="1">
                <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center"><x-icon name="chart-bar" class="w-6 h-6" /></div>
                <h3 class="mt-4 font-heading font-bold text-lg text-ink-900">Our Mission</h3>
                <p class="mt-2 text-sm text-ink-500 leading-relaxed">To give property owners complete peace of mind through transparent, technology-driven management that protects and grows the value of every asset in our care.</p>
            </div>
            <div class="card p-8 hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-reveal data-reveal-delay="2">
                <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center"><x-icon name="globe-alt" class="w-6 h-6" /></div>
                <h3 class="mt-4 font-heading font-bold text-lg text-ink-900">Our Vision</h3>
                <p class="mt-2 text-sm text-ink-500 leading-relaxed">To become Pakistan's most trusted property management brand &mdash; the preferred choice for local and overseas owners alike.</p>
            </div>
            <div class="card p-8 hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-reveal data-reveal-delay="3">
                <div class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center"><x-icon name="shield-check" class="w-6 h-6" /></div>
                <h3 class="mt-4 font-heading font-bold text-lg text-ink-900">Our Core Values</h3>
                <p class="mt-2 text-sm text-ink-500 leading-relaxed">Transparency, credibility, communication, and co-creation with every client &mdash; the foundation of the 7 Cs that guide our work.</p>
            </div>
        </div>
    </section>

    {{-- Why choose GATED --}}
    <section class="py-20 bg-ink-50">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-2 gap-12 items-center">
            <div data-reveal>
                <span class="section-eyebrow">Why Choose GATED</span>
                <h2 class="mt-3 text-3xl font-heading font-extrabold text-ink-900">Built On Trust, Backed By Results</h2>
                <div class="mt-8 space-y-5">
                    @foreach ([
                        ['title' => 'Transparent Process', 'desc' => 'Every fee, report, and update is shared clearly &mdash; no surprises.'],
                        ['title' => 'Real-Time Updates', 'desc' => 'Track your property performance from anywhere via the Client Portal.'],
                        ['title' => 'Dedicated Team', 'desc' => 'A named point of contact for every property we manage.'],
                        ['title' => 'Maximum ROI', 'desc' => 'Marketing, pricing, and maintenance strategies built to protect your returns.'],
                    ] as $item)
                        <div class="flex gap-4">
                            <span class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center shrink-0"><x-icon name="check" class="w-5 h-5" /></span>
                            <div>
                                <h4 class="font-heading font-bold text-ink-900">{{ $item['title'] }}</h4>
                                <p class="text-sm text-ink-500 mt-1">{!! $item['desc'] !!}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="relative" data-reveal="zoom" data-reveal-delay="2">
                <img src="{{ asset('images/site/villa-facade-day.jpg') }}" class="rounded-2xl shadow-xl w-full aspect-[4/3] object-cover" alt="GATED managed luxury villa">
                <div class="absolute -left-8 -bottom-8 card p-4 shadow-2xl w-52 animate-float hidden sm:block">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 rounded-full bg-brand-600 text-white flex items-center justify-center shrink-0"><x-icon name="shield-check" class="w-5 h-5" /></span>
                        <div>
                            <div class="font-heading font-bold text-ink-900 text-sm">7+ Years</div>
                            <div class="text-xs text-ink-500">Trusted Experience</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="py-16 bg-ink-950 text-white">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 lg:grid-cols-4 gap-8 text-center" data-reveal>
            <div><div class="text-3xl font-heading font-extrabold text-brand-500"><span data-counter="500" data-counter-suffix="+">0</span></div><div class="text-xs uppercase tracking-wide text-ink-400 mt-1">Properties Managed</div></div>
            <div><div class="text-3xl font-heading font-extrabold text-brand-500"><span data-counter="98" data-counter-suffix="%">0</span></div><div class="text-xs uppercase tracking-wide text-ink-400 mt-1">Client Satisfaction</div></div>
            <div><div class="text-3xl font-heading font-extrabold text-brand-500"><span data-counter="7" data-counter-suffix="+">0</span></div><div class="text-xs uppercase tracking-wide text-ink-400 mt-1">Years of Excellence</div></div>
            <div><div class="text-3xl font-heading font-extrabold text-brand-500">24/7</div><div class="text-xs uppercase tracking-wide text-ink-400 mt-1">Support Available</div></div>
        </div>
    </section>

    {{-- Testimonials --}}
    @if ($testimonials->count())
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center max-w-2xl mx-auto" data-reveal>
                <span class="section-eyebrow">Credibility</span>
                <h2 class="mt-3 text-3xl font-heading font-extrabold text-ink-900">What Our Clients Say</h2>
            </div>
            <div class="mt-12 grid sm:grid-cols-3 gap-6">
                @foreach ($testimonials as $t)
                    <div class="card p-6 hover:shadow-lg hover:-translate-y-1 transition-all duration-300" data-reveal data-reveal-delay="{{ min($loop->iteration, 6) }}">
                        <div class="flex gap-0.5 text-brand-500 mb-3">
                            @for ($i = 0; $i < $t->rating; $i++)<x-icon name="star" class="w-4 h-4 fill-current" />@endfor
                        </div>
                        <p class="text-sm text-ink-600">&ldquo;{{ $t->content }}&rdquo;</p>
                        <div class="mt-4 font-semibold text-ink-900 text-sm">{{ $t->name }} <span class="font-normal text-ink-500">&middot; {{ $t->role }}</span></div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section class="py-16 bg-brand-600 text-center">
        <div class="max-w-3xl mx-auto px-6" data-reveal>
            <h2 class="text-2xl lg:text-3xl font-heading font-extrabold text-white">Ready to experience stress-free property management?</h2>
            <div class="mt-6 flex flex-wrap justify-center gap-3">
                <a href="{{ route('property-registration.create') }}" class="btn-dark">Register Your Property</a>
                <a href="{{ route('contact.show') }}" class="btn-outline-white">Talk to Our Team</a>
            </div>
        </div>
    </section>

@endsection
