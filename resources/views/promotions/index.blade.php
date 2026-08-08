@extends('layouts.app')

@section('title', 'Offers & Promotions | GATED Property Services')
@section('meta_description', 'Current seasonal offers and service discounts from GATED Property Services for property owners and overseas investors.')

@section('content')

    <section class="bg-ink-950 text-white py-16 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 15% 20%, var(--color-brand-600) 0%, transparent 40%), radial-gradient(circle at 85% 80%, var(--color-brand-700) 0%, transparent 40%);"></div>
        <div class="max-w-3xl mx-auto px-6 relative" data-reveal>
            <span class="section-eyebrow text-brand-500">Offers &amp; Promotions</span>
            <h1 class="mt-3 text-4xl font-heading font-extrabold">Current Deals for Property Owners</h1>
            <p class="mt-4 text-ink-300">Seasonal offers and service discounts available right now &mdash; register your property or upgrade your service package to take advantage.</p>
        </div>
    </section>

    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            @if ($promotions->count())
                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($promotions as $promotion)
                        <div class="card overflow-hidden flex flex-col hover:shadow-xl hover:-translate-y-1 transition-all duration-300" data-reveal data-reveal-delay="{{ min($loop->iteration, 6) }}">
                            <div class="bg-gradient-to-br from-brand-600 to-brand-700 p-6 text-white relative overflow-hidden">
                                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 80% 20%, white 0%, transparent 45%);"></div>
                                <div class="relative">
                                    <x-icon name="megaphone" class="w-8 h-8 text-white/80" />
                                    @if ($promotion->discount_label)
                                        <div class="mt-4 text-3xl font-heading font-extrabold">{{ $promotion->discount_label }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="p-6 flex flex-col flex-1">
                                <h3 class="font-heading font-bold text-lg text-ink-900">{{ $promotion->title }}</h3>
                                @if ($promotion->description)
                                    <p class="mt-2 text-sm text-ink-500 leading-relaxed flex-1">{{ $promotion->description }}</p>
                                @endif
                                <div class="mt-5 pt-4 border-t border-ink-100 flex items-center justify-between">
                                    @if ($promotion->valid_until)
                                        <span class="text-xs text-ink-400 flex items-center gap-1.5"><x-icon name="clock" class="w-4 h-4" /> Valid until {{ $promotion->valid_until->format('M d, Y') }}</span>
                                    @else
                                        <span class="text-xs text-ink-400">Ongoing offer</span>
                                    @endif
                                    <a href="{{ route('contact.show') }}" class="text-sm font-semibold text-brand-600 hover:text-brand-700">Claim Offer &rarr;</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12">{{ $promotions->links() }}</div>
            @else
                <div class="text-center py-16" data-reveal>
                    <x-icon name="megaphone" class="w-10 h-10 text-ink-300 mx-auto" />
                    <p class="mt-4 text-ink-500">No active promotions right now &mdash; check back soon, or contact us to ask about current packages.</p>
                    <a href="{{ route('contact.show') }}" class="btn-primary mt-6">Contact Us</a>
                </div>
            @endif
        </div>
    </section>

    <section class="py-16 bg-ink-50 text-center">
        <div class="max-w-2xl mx-auto px-6" data-reveal>
            <h2 class="text-2xl font-heading font-extrabold text-ink-900">Want a custom quote for your property?</h2>
            <p class="mt-2 text-ink-500">Speak with our team to combine a service package with any current promotion.</p>
            <a href="{{ route('property-registration.create') }}" class="btn-primary mt-6">Register Your Property</a>
        </div>
    </section>

@endsection
