@extends('layouts.app')

@section('title', 'Contact Us | GATED Property Services')
@section('meta_description', 'Get in touch with GATED Property Services via phone, WhatsApp, live chat, email or our contact form. We respond within one hour.')

@section('content')

    <section class="bg-ink-950 text-white py-16 text-center relative overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 15% 20%, var(--color-brand-600) 0%, transparent 40%), radial-gradient(circle at 85% 80%, var(--color-brand-700) 0%, transparent 40%);"></div>
        <div class="max-w-3xl mx-auto px-6 relative" data-reveal>
            <span class="section-eyebrow text-brand-500">Communication Center</span>
            <h1 class="mt-3 text-4xl font-heading font-extrabold">Let's Talk About Your Property</h1>
            <p class="mt-4 text-ink-300">Multiple ways to reach us &mdash; pick whichever is most convenient.</p>
        </div>
    </section>

    <section class="py-6 bg-white border-b border-ink-100">
        <div class="max-w-7xl mx-auto px-6 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="tel:+923009558737" class="card p-5 flex items-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group" data-reveal data-reveal-delay="1">
                <span class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300"><x-icon name="phone" class="w-5 h-5" /></span>
                <div><div class="text-sm font-bold text-ink-900">Call Us</div><div class="text-xs text-ink-500">+92 300 9558737</div></div>
            </a>
            <a href="https://wa.me/923009558737" target="_blank" class="card p-5 flex items-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group" data-reveal data-reveal-delay="2">
                <span class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300"><x-icon name="chat" class="w-5 h-5" /></span>
                <div><div class="text-sm font-bold text-ink-900">WhatsApp</div><div class="text-xs text-ink-500">Usually replies in &lt;1 hr</div></div>
            </a>
            <a href="mailto:shahidjamil21@gmail.com" class="card p-5 flex items-center gap-3 hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group" data-reveal data-reveal-delay="3">
                <span class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center group-hover:scale-110 transition-transform duration-300"><x-icon name="envelope" class="w-5 h-5" /></span>
                <div><div class="text-sm font-bold text-ink-900">Email</div><div class="text-xs text-ink-500">shahidjamil21@gmail.com</div></div>
            </a>
            <div class="card p-5 flex items-center gap-3" data-reveal data-reveal-delay="4">
                <span class="w-10 h-10 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center"><x-icon name="video-camera" class="w-5 h-5" /></span>
                <div><div class="text-sm font-bold text-ink-900">Video Consultation</div><div class="text-xs text-ink-500">Book via the form &rarr;</div></div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-ink-50">
        <div class="max-w-7xl mx-auto px-6 grid lg:grid-cols-5 gap-10">
            <div class="lg:col-span-3 card p-8" data-reveal>
                <h2 class="font-heading font-bold text-xl text-ink-900">Send Us a Message</h2>

                @if (session('success'))
                    <div class="mt-4 rounded-lg bg-emerald-50 text-emerald-800 text-sm p-4">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="mt-6 space-y-4" x-data="{ type: '{{ old('type', 'general') }}' }">
                    @csrf
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                            @error('name') <p class="text-xs text-brand-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                            @error('email') <p class="text-xs text-brand-600 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        </div>
                        <div>
                            <label class="text-sm font-semibold text-ink-700">Inquiry Type</label>
                            <select name="type" x-model="type" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                                <option value="general">General Inquiry</option>
                                <option value="consultation">Request Video Consultation</option>
                                <option value="callback">Request a Call Back</option>
                            </select>
                        </div>
                    </div>
                    <div x-show="type === 'consultation'" x-cloak x-transition>
                        <label class="text-sm font-semibold text-ink-700">Preferred Date &amp; Time</label>
                        <input type="datetime-local" name="preferred_at" value="{{ old('preferred_at') }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                        <p class="text-xs text-ink-400 mt-1">We'll confirm your video consultation slot by email &mdash; times are in your local timezone.</p>
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Subject</label>
                        <input type="text" name="subject" value="{{ old('subject') }}" class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="text-sm font-semibold text-ink-700">Message</label>
                        <textarea name="message" rows="5" required class="mt-1 w-full rounded-lg border-ink-200 focus:border-brand-500 focus:ring-brand-500">{{ old('message') }}</textarea>
                        @error('message') <p class="text-xs text-brand-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="btn-primary w-full sm:w-auto">Send Message</button>
                </form>
            </div>

            <div class="lg:col-span-2 space-y-6" data-reveal data-reveal-delay="2">
                <div class="card p-6">
                    <h3 class="font-heading font-bold text-ink-900">Office</h3>
                    <p class="mt-2 text-sm text-ink-500 flex items-start gap-2"><x-icon name="map-pin" class="w-4 h-4 mt-0.5 text-brand-600 shrink-0" /> GATED Property Services HQ, Gulberg, Islamabad, Pakistan</p>
                    <p class="mt-2 text-sm text-ink-500 flex items-start gap-2"><x-icon name="clock" class="w-4 h-4 mt-0.5 text-brand-600 shrink-0" /> Mon &ndash; Sat: 9:00 AM &ndash; 8:00 PM &middot; 24/7 Client Support</p>
                </div>
                <div class="rounded-2xl overflow-hidden aspect-video shadow-sm">
                    <iframe src="https://www.google.com/maps?q=Gulberg,Islamabad,Pakistan&output=embed" class="w-full h-full border-0" loading="lazy" title="GATED Property Services location"></iframe>
                </div>
            </div>
        </div>
    </section>

@endsection
