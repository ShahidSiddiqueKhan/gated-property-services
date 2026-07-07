@extends('layouts.app')

@section('title', $service->name . ' | GATED Property Services')
@section('meta_description', $service->short_description)

@section('content')

    <section class="bg-ink-950 text-white py-16">
        <div class="max-w-5xl mx-auto px-6">
            <a href="{{ route('services.index') }}" class="text-sm text-ink-400 hover:text-white">&larr; All Services</a>
            <div class="mt-4 flex items-center gap-4">
                <div class="w-14 h-14 rounded-xl bg-brand-600 flex items-center justify-center shrink-0"><x-icon :name="$service->icon" class="w-7 h-7" /></div>
                <h1 class="text-3xl lg:text-4xl font-heading font-extrabold">{{ $service->name }}</h1>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-6 grid lg:grid-cols-3 gap-10">
            <div class="lg:col-span-2">
                <p class="text-ink-600 leading-relaxed">{{ $service->description }}</p>

                <h2 class="mt-8 font-heading font-bold text-lg text-ink-900">What's Included</h2>
                <div class="mt-4 grid sm:grid-cols-2 gap-3">
                    @foreach ($service->features ?? [] as $feature)
                        <div class="flex items-center gap-2 text-sm text-ink-700"><x-icon name="check-circle" class="w-5 h-5 text-brand-600 shrink-0" /> {{ $feature }}</div>
                    @endforeach
                </div>
            </div>

            <aside class="card p-6 h-fit">
                <h3 class="font-heading font-bold text-ink-900">Interested in this service?</h3>
                <p class="mt-2 text-sm text-ink-500">Register your property or speak with a specialist to get a tailored plan.</p>
                <a href="{{ route('property-registration.create') }}" class="btn-primary w-full mt-4 justify-center">Register Property</a>
                <a href="{{ route('contact.show') }}" class="btn-outline w-full mt-3 justify-center">Request Consultation</a>
            </aside>
        </div>

        @if ($related->count())
        <div class="max-w-5xl mx-auto px-6 mt-16 pt-10 border-t border-ink-100">
            <h3 class="font-heading font-bold text-ink-900 mb-6">Related Services</h3>
            <div class="grid sm:grid-cols-3 gap-6">
                @foreach ($related as $r)
                    <a href="{{ route('services.show', $r) }}" class="card p-5 hover:shadow-lg transition">
                        <div class="w-10 h-10 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center"><x-icon :name="$r->icon" class="w-5 h-5" /></div>
                        <h4 class="mt-3 font-heading font-bold text-sm text-ink-900">{{ $r->name }}</h4>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </section>

@endsection
