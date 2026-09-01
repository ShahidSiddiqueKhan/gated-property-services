@extends('layouts.app')

@section('title', 'Privacy Policy | GATED Property Services')

@section('content')

    <section class="bg-ink-950 text-white py-16 text-center">
        <div class="max-w-3xl mx-auto px-6">
            <span class="section-eyebrow text-brand-500">Legal</span>
            <h1 class="mt-3 text-4xl font-heading font-extrabold">Privacy Policy</h1>
            <p class="mt-4 text-ink-300">Last updated: {{ now()->format('F Y') }}</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-6 prose text-ink-700 leading-relaxed">
            <p>GATED Property Services ("GATED", "we", "us") is committed to protecting the privacy and security of our clients' personal and financial information, in line with data protection principles similar to the GDPR and applicable Pakistani law.</p>

            <h3>Information We Collect</h3>
            <p>We collect information you provide directly, such as your name, contact details, property information, financial and tenancy details, and documents you upload through the Client Portal or property registration form.</p>

            <h3>How We Use Your Information</h3>
            <p>We use your information to manage your property, process payments, coordinate maintenance, communicate updates, and comply with legal and regulatory obligations. We do not sell your personal information to third parties.</p>

            <h3>Data Security</h3>
            <p>Client accounts support two-factor authentication, all data is transmitted over encrypted connections, and access to sensitive records is role-restricted and logged in our internal audit trail.</p>

            <h3>Data Retention</h3>
            <p>We retain your information for as long as your account is active or as needed to provide services, and as required by tax, legal, and regulatory obligations.</p>

            <h3>Your Rights</h3>
            <p>You may request a copy of the personal data we hold about you, ask us to correct inaccurate information, or request deletion of your account and associated data, subject to our legal retention obligations. To make a request, contact us via the Messages center in your Client Portal or email rafayshahid0890@gmail.com.</p>

            <h3>Contact</h3>
            <p>Questions about this policy can be directed to rafayshahid0890@gmail.com.</p>
        </div>
    </section>

@endsection
