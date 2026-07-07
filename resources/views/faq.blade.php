@extends('layouts.app')

@section('title', 'FAQs | GATED Property Services')
@section('meta_description', 'Answers to common questions about GATED\'s property management services, client portal, payments and maintenance process.')

@section('content')

    <section class="bg-ink-950 text-white py-16 text-center">
        <div class="max-w-3xl mx-auto px-6">
            <span class="section-eyebrow text-brand-500">Knowledge Center</span>
            <h1 class="mt-3 text-4xl font-heading font-extrabold">Frequently Asked Questions</h1>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-6" x-data="{ open: 0 }">
            @php
                $faqs = [
                    ['q' => 'What areas does GATED Property Services cover?', 'a' => 'We currently manage residential, commercial and Airbnb properties across Lahore, Islamabad and Karachi, with a growing network of vetted contractors and field teams in each city.'],
                    ['q' => 'How do I register my property with GATED?', 'a' => 'Use the "Register Property" button anywhere on the site to submit owner information, property details, legal documents and photos. Our team reviews every submission within 24 hours.'],
                    ['q' => 'How can I track my rent and property performance?', 'a' => 'Every client receives access to the Client Portal, a secure dashboard showing occupancy status, rent history, maintenance progress, documents and messages in real time.'],
                    ['q' => 'How are maintenance requests handled?', 'a' => 'Submit a request through the Client Portal with a description, priority and photos. You can track every status update &mdash; submitted, acknowledged, in progress, completed &mdash; from the same ticket.'],
                    ['q' => 'How do I make rent or service payments?', 'a' => 'Invoices appear in your Client Portal under Rent & Payments. Bank transfer details are provided on each invoice; once you\'ve sent payment, confirm it in the portal and our finance team verifies within 24 hours. Card payments are coming soon.'],
                    ['q' => 'Can overseas owners use GATED\'s services?', 'a' => 'Yes &mdash; overseas owner services are one of our core offerings, including full property oversight, monthly reporting, financial tracking and video consultations.'],
                    ['q' => 'What documents will I have access to?', 'a' => 'Lease agreements, inspection reports, invoices and tax documents are all stored securely in your Documents Center within the Client Portal.'],
                ];
            @endphp

            <div class="space-y-3">
                @foreach ($faqs as $i => $faq)
                    <div class="card overflow-hidden">
                        <button @click="open = open === {{ $i }} ? null : {{ $i }}" class="w-full flex items-center justify-between gap-4 p-5 text-left font-semibold text-ink-900">
                            {{ $faq['q'] }}
                            <x-icon name="chevron-down" class="w-5 h-5 text-brand-600 shrink-0 transition" x-bind:class="open === {{ $i }} ? 'rotate-180' : ''" />
                        </button>
                        <div x-show="open === {{ $i }}" x-transition x-cloak class="px-5 pb-5 text-sm text-ink-500 leading-relaxed">
                            {!! $faq['a'] !!}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-16 bg-ink-50 text-center">
        <div class="max-w-2xl mx-auto px-6">
            <h2 class="text-2xl font-heading font-extrabold text-ink-900">Still have questions?</h2>
            <p class="mt-2 text-ink-500">Our support team is available around the clock.</p>
            <a href="{{ route('contact.show') }}" class="btn-primary mt-5">Contact Us</a>
        </div>
    </section>

@endsection
