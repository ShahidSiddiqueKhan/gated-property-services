@extends('layouts.app')

@section('title', 'Terms of Service | GATED Property Services')

@section('content')

    <section class="bg-ink-950 text-white py-16 text-center">
        <div class="max-w-3xl mx-auto px-6">
            <span class="section-eyebrow text-brand-500">Legal</span>
            <h1 class="mt-3 text-4xl font-heading font-extrabold">Terms of Service</h1>
            <p class="mt-4 text-ink-300">Last updated: {{ now()->format('F Y') }}</p>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="max-w-3xl mx-auto px-6 prose text-ink-700 leading-relaxed">
            <p>These Terms of Service govern your use of the GATED Property Services website and Client Portal. By registering a property or creating an account, you agree to these terms.</p>

            <h3>Services</h3>
            <p>GATED provides property management services including tenant placement, rent collection, maintenance coordination, marketing, and reporting, as selected by the client at registration or afterward.</p>

            <h3>Client Responsibilities</h3>
            <p>Clients agree to provide accurate property and ownership information and to keep login credentials confidential. Submitting fraudulent ownership documents may result in account termination.</p>

            <h3>Payments</h3>
            <p>Service fees and rent handling terms are agreed per property. Manual bank transfer is currently supported with confirmation reviewed by our finance team; live card payments will be enabled once integrated.</p>

            <h3>Limitation of Liability</h3>
            <p>GATED exercises reasonable care and diligence in managing properties but is not liable for events outside its reasonable control, including tenant default, force majeure, or third-party contractor performance beyond agreed service levels.</p>

            <h3>Termination</h3>
            <p>Either party may terminate the management agreement per the terms outlined in the individual property management contract signed at onboarding.</p>

            <h3>Contact</h3>
            <p>For questions about these terms, contact info@gatedpropertyservices.com.</p>
        </div>
    </section>

@endsection
