<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'GATED Property Services | Your Property. Our Responsibility.')</title>
    <meta name="description" content="@yield('meta_description', 'GATED Property Services is a trusted, technology-driven property management company serving local and overseas owners across Pakistan — residential, commercial, and Airbnb management.')">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:title" content="@yield('title', 'GATED Property Services')">
    <meta property="og:description" content="@yield('meta_description', 'Professional property management you can trust.')">
    <meta property="og:type" content="website">

    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><rect width=%2224%22 height=%2224%22 rx=%224%22 fill=%22%23111%22/><text x=%2212%22 y=%2217%22 font-size=%2214%22 fill=%22%23e63946%22 text-anchor=%22middle%22 font-family=%22sans-serif%22 font-weight=%22bold%22>G</text></svg>">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.1/cdn.min.js"></script>
</head>
<body class="bg-white text-ink-800">

    {{-- Utility bar --}}
    <div class="hidden lg:block bg-ink-950 text-ink-200 text-xs">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between py-2">
            <div class="flex items-center gap-6">
                <a href="tel:+923001234567" class="flex items-center gap-1.5 hover:text-white transition">
                    <x-icon name="phone" class="w-3.5 h-3.5" /> +92 300 1234567
                </a>
                <a href="mailto:info@gatedpropertyservices.com" class="flex items-center gap-1.5 hover:text-white transition">
                    <x-icon name="envelope" class="w-3.5 h-3.5" /> info@gatedpropertyservices.com
                </a>
                <span class="flex items-center gap-1.5">
                    <x-icon name="map-pin" class="w-3.5 h-3.5" /> Lahore, Pakistan
                </span>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-ink-400">Response time: usually within 1 hour</span>
                <a href="https://wa.me/923001234567" target="_blank" rel="noopener" class="hover:text-white transition">WhatsApp</a>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <header x-data="{ open: false }" class="sticky top-0 z-40 bg-white/95 backdrop-blur border-b border-ink-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex items-center justify-between h-20">
                <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                    <span class="flex h-11 w-11 items-center justify-center rounded-lg bg-ink-950 text-brand-500 font-heading font-extrabold text-xl">G</span>
                    <span class="leading-tight">
                        <span class="block font-heading font-extrabold text-lg tracking-tight text-ink-900">GATED</span>
                        <span class="block text-[10px] font-semibold tracking-widest text-brand-600 uppercase -mt-1">Property Services</span>
                    </span>
                </a>

                <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold text-ink-700">
                    <a href="{{ route('home') }}" class="hover:text-brand-600 transition {{ request()->routeIs('home') ? 'text-brand-600' : '' }}">Home</a>
                    <a href="{{ route('about') }}" class="hover:text-brand-600 transition {{ request()->routeIs('about') ? 'text-brand-600' : '' }}">About Us</a>
                    <a href="{{ route('services.index') }}" class="hover:text-brand-600 transition {{ request()->routeIs('services.*') ? 'text-brand-600' : '' }}">Services</a>
                    <a href="{{ route('properties.index') }}" class="hover:text-brand-600 transition {{ request()->routeIs('properties.*') ? 'text-brand-600' : '' }}">Properties</a>
                    <a href="{{ route('blog.index') }}" class="hover:text-brand-600 transition {{ request()->routeIs('blog.*') ? 'text-brand-600' : '' }}">Resources</a>
                    <a href="{{ route('contact.show') }}" class="hover:text-brand-600 transition {{ request()->routeIs('contact.*') ? 'text-brand-600' : '' }}">Contact Us</a>
                </nav>

                <div class="hidden lg:flex items-center gap-3">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('portal.dashboard') }}" class="btn-dark !py-2.5 !px-5 text-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline !py-2.5 !px-5 text-sm">Client Portal Login</a>
                        <a href="{{ route('property-registration.create') }}" class="btn-primary !py-2.5 !px-5 text-sm">Register Property</a>
                    @endauth
                </div>

                <button @click="open = !open" class="lg:hidden p-2 text-ink-900" aria-label="Toggle menu">
                    <x-icon name="bars-3" x-show="!open" class="w-7 h-7" />
                    <x-icon name="x-mark" x-show="open" x-cloak class="w-7 h-7" />
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div x-show="open" x-cloak x-transition class="lg:hidden border-t border-ink-100 bg-white">
            <div class="px-6 py-4 flex flex-col gap-3 text-sm font-semibold text-ink-700">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('about') }}">About Us</a>
                <a href="{{ route('services.index') }}">Services</a>
                <a href="{{ route('properties.index') }}">Properties</a>
                <a href="{{ route('blog.index') }}">Resources</a>
                <a href="{{ route('contact.show') }}">Contact Us</a>
                <div class="flex flex-col gap-2 pt-3 border-t border-ink-100">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('portal.dashboard') }}" class="btn-dark justify-center">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline justify-center">Client Portal Login</a>
                        <a href="{{ route('property-registration.create') }}" class="btn-primary justify-center">Register Property</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    {{-- Flash messages --}}
    @if (session('success'))
        <div class="bg-emerald-50 border-b border-emerald-200 text-emerald-800 text-sm">
            <div class="max-w-7xl mx-auto px-6 py-3 flex items-center gap-2">
                <x-icon name="check-circle" class="w-5 h-5 shrink-0" />
                <span>{{ session('success') }}</span>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-brand-50 border-b border-brand-200 text-brand-800 text-sm">
            <div class="max-w-7xl mx-auto px-6 py-3 flex items-center gap-2">
                <x-icon name="exclamation-triangle" class="w-5 h-5 shrink-0" />
                <span>{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-ink-950 text-ink-300">
        <div class="max-w-7xl mx-auto px-6 py-16 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
            <div>
                <a href="{{ route('home') }}" class="flex items-center gap-2 mb-4">
                    <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-brand-500 font-heading font-extrabold text-lg">G</span>
                    <span class="font-heading font-extrabold text-white text-lg">GATED</span>
                </a>
                <p class="text-sm leading-relaxed mb-4">Your Property. Our Responsibility. Professional, technology-driven property management for local and overseas owners across Pakistan.</p>
                <div class="flex items-center gap-3">
                    @foreach (['facebook','instagram','linkedin','youtube'] as $network)
                        <a href="#" class="w-9 h-9 rounded-full bg-white/5 hover:bg-brand-600 flex items-center justify-center transition" aria-label="{{ ucfirst($network) }}">
                            <span class="text-xs font-bold uppercase">{{ substr($network, 0, 2) }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <div>
                <h4 class="font-heading font-bold text-white mb-4">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                    <li><a href="{{ route('services.index') }}" class="hover:text-white transition">Services</a></li>
                    <li><a href="{{ route('properties.index') }}" class="hover:text-white transition">Properties</a></li>
                    <li><a href="{{ route('blog.index') }}" class="hover:text-white transition">Blog</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-white transition">FAQs</a></li>
                    <li><a href="{{ route('contact.show') }}" class="hover:text-white transition">Contact Us</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-heading font-bold text-white mb-4">Our Services</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('services.index') }}" class="hover:text-white transition">Residential Management</a></li>
                    <li><a href="{{ route('services.index') }}" class="hover:text-white transition">Commercial Management</a></li>
                    <li><a href="{{ route('services.index') }}" class="hover:text-white transition">Airbnb Management</a></li>
                    <li><a href="{{ route('services.index') }}" class="hover:text-white transition">Overseas Owner Services</a></li>
                    <li><a href="{{ route('services.index') }}" class="hover:text-white transition">Maintenance Management</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-heading font-bold text-white mb-4">Get In Touch</h4>
                <ul class="space-y-3 text-sm">
                    <li class="flex items-start gap-2"><x-icon name="phone" class="w-4 h-4 mt-0.5 text-brand-500 shrink-0" /> +92 300 1234567</li>
                    <li class="flex items-start gap-2"><x-icon name="envelope" class="w-4 h-4 mt-0.5 text-brand-500 shrink-0" /> info@gatedpropertyservices.com</li>
                    <li class="flex items-start gap-2"><x-icon name="map-pin" class="w-4 h-4 mt-0.5 text-brand-500 shrink-0" /> Lahore, Pakistan</li>
                    <li class="flex items-start gap-2"><x-icon name="clock" class="w-4 h-4 mt-0.5 text-brand-500 shrink-0" /> 24/7 Client Support</li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10">
            <div class="max-w-7xl mx-auto px-6 py-5 flex flex-wrap items-center justify-center gap-x-8 gap-y-2 text-xs text-ink-400">
                <span class="flex items-center gap-1.5"><x-icon name="shield-check" class="w-4 h-4 text-brand-500" /> Secure &amp; Reliable</span>
                <span class="flex items-center gap-1.5"><x-icon name="document-arrow-down" class="w-4 h-4 text-brand-500" /> Daily Backups</span>
                <span class="flex items-center gap-1.5"><x-icon name="lock-closed" class="w-4 h-4 text-brand-500" /> 20 Free SSL</span>
                <span class="flex items-center gap-1.5"><x-icon name="chart-bar" class="w-4 h-4 text-brand-500" /> 24/7 Monitoring</span>
            </div>
        </div>

        <div class="border-t border-white/10">
            <div class="max-w-7xl mx-auto px-6 py-5 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-ink-500">
                <span>&copy; {{ date('Y') }} GATED Property Services. All Rights Reserved.</span>
                <div class="flex items-center gap-4">
                    <a href="{{ route('privacy') }}" class="hover:text-white transition">Privacy Policy</a>
                    <a href="{{ route('terms') }}" class="hover:text-white transition">Terms of Service</a>
                    <span>Lahore &middot; Islamabad &middot; Karachi</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- Floating WhatsApp / Live Chat widgets --}}
    <div x-data="{ chatOpen: false }" class="fixed bottom-6 right-6 z-50 flex flex-col items-end gap-3">

        {{-- Live chat panel --}}
        <div x-show="chatOpen" x-cloak x-transition @click.outside="chatOpen = false" class="w-80 max-w-[calc(100vw-3rem)] card shadow-2xl overflow-hidden">
            <div class="bg-ink-950 text-white p-4 flex items-center justify-between">
                <div>
                    <div class="font-heading font-bold text-sm">GATED Live Chat</div>
                    <div class="text-xs text-emerald-400 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Usually replies in under 1 hour</div>
                </div>
                <button @click="chatOpen = false" class="text-ink-400 hover:text-white"><x-icon name="x-mark" class="w-5 h-5" /></button>
            </div>

            <div class="p-4">
                <form method="POST" action="{{ route('contact.store') }}" class="space-y-3">
                    @csrf
                    <input type="hidden" name="subject" value="Live Chat">
                    <input type="hidden" name="type" value="general">
                    <p class="text-sm text-ink-600">Send us a quick message and our team will get back to you shortly.</p>
                    <input type="text" name="name" placeholder="Your name" required class="w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                    <input type="email" name="email" placeholder="Your email" required class="w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                    <textarea name="message" rows="3" placeholder="How can we help?" required class="w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500"></textarea>
                    <button type="submit" class="btn-primary w-full justify-center text-sm">Send Message</button>
                </form>
            </div>
        </div>

        <div class="flex flex-col gap-3 items-end">
            <button @click="chatOpen = !chatOpen" class="flex items-center gap-2 rounded-full bg-ink-950 hover:bg-ink-900 text-white pl-4 pr-5 py-3.5 shadow-lg transition">
                <x-icon name="chat" class="w-5 h-5" />
                <span class="text-sm font-semibold hidden sm:inline">Live Chat</span>
            </button>
            <a href="https://wa.me/923001234567" target="_blank" rel="noopener"
               class="flex items-center gap-2 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white pl-4 pr-5 py-3.5 shadow-lg shadow-emerald-500/30 transition">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6 fill-current"><path d="M12.04 2c-5.52 0-10 4.48-10 10 0 1.77.46 3.45 1.27 4.9L2 22l5.25-1.38A9.94 9.94 0 0 0 12.04 22c5.52 0 10-4.48 10-10s-4.48-10-10-10Zm0 18.2a8.2 8.2 0 0 1-4.18-1.15l-.3-.18-3.12.82.83-3.04-.2-.31A8.2 8.2 0 1 1 12.04 20.2Zm4.5-6.13c-.25-.12-1.46-.72-1.69-.8-.23-.08-.39-.12-.56.13-.17.25-.64.8-.79.96-.14.17-.29.19-.54.06-.25-.12-1.04-.38-1.98-1.22-.73-.65-1.23-1.46-1.37-1.71-.14-.25-.02-.38.11-.51.11-.11.25-.29.37-.43.12-.14.16-.25.25-.41.08-.17.04-.31-.02-.44-.06-.12-.56-1.35-.77-1.85-.2-.48-.4-.42-.56-.42-.14 0-.31-.02-.48-.02-.17 0-.44.06-.67.31-.23.25-.87.85-.87 2.08 0 1.22.89 2.4 1.02 2.57.12.17 1.75 2.67 4.24 3.74.59.26 1.05.41 1.41.52.59.19 1.13.16 1.56.1.48-.07 1.46-.6 1.66-1.17.21-.58.21-1.08.14-1.18-.06-.1-.23-.16-.48-.28Z"/></svg>
                <span class="text-sm font-semibold hidden sm:inline">WhatsApp</span>
            </a>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
