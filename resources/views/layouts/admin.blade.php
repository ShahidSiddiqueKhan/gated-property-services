<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal') | GATED Property Services</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><rect width=%2224%22 height=%2224%22 rx=%224%22 fill=%22%23111%22/><text x=%2212%22 y=%2217%22 font-size=%2214%22 fill=%22%23e63946%22 text-anchor=%22middle%22 font-family=%22sans-serif%22 font-weight=%22bold%22>G</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.1/cdn.min.js"></script>
</head>
<body class="bg-ink-50 text-ink-800" x-data="{ sidebarOpen: false }">

    @php
        $adminNav = [
            ['route' => 'admin.dashboard', 'icon' => 'chart-bar', 'label' => 'Dashboard'],
            ['route' => 'admin.clients.index', 'icon' => 'users', 'label' => 'Clients'],
            ['route' => 'admin.properties.index', 'icon' => 'building-office', 'label' => 'Properties', 'badge' => $adminPendingApprovals ?? 0],
            ['route' => 'admin.leases.index', 'icon' => 'document-text', 'label' => 'Leases & Tenants'],
            ['route' => 'admin.payments.index', 'icon' => 'banknotes', 'label' => 'Payments'],
            ['route' => 'admin.maintenance.index', 'icon' => 'wrench-screwdriver', 'label' => 'Maintenance'],
            ['route' => 'admin.documents.index', 'icon' => 'document-arrow-down', 'label' => 'Documents'],
            ['route' => 'admin.messages.index', 'icon' => 'chat', 'label' => 'Messages', 'badge' => $adminNewMessages ?? 0],
            ['route' => 'admin.tasks.index', 'icon' => 'check-circle', 'label' => 'Tasks'],
            ['route' => 'admin.leads.index', 'icon' => 'megaphone', 'label' => 'Leads', 'badge' => $adminNewLeads ?? 0],
        ];
        $financeNav = [
            ['route' => 'admin.packages.index', 'icon' => 'banknotes', 'label' => 'Packages'],
            ['route' => 'admin.payment-methods.index', 'icon' => 'globe-alt', 'label' => 'Payment Methods'],
            ['route' => 'admin.fee-tiers.index', 'icon' => 'chart-bar', 'label' => 'Fee Tiers'],
            ['route' => 'admin.service-catalog.index', 'icon' => 'megaphone', 'label' => 'Service Catalog'],
            ['route' => 'admin.renovations.index', 'icon' => 'wrench-screwdriver', 'label' => 'Renovation Projects'],
        ];
        $contentNav = [
            ['route' => 'admin.testimonials.index', 'icon' => 'star', 'label' => 'Testimonials'],
            ['route' => 'admin.blog.index', 'icon' => 'document-text', 'label' => 'Blog & Resources'],
            ['route' => 'admin.services.index', 'icon' => 'globe-alt', 'label' => 'Services'],
            ['route' => 'admin.promotions.index', 'icon' => 'megaphone', 'label' => 'Promotions'],
        ];
        $systemNav = [
            ['route' => 'admin.reports.index', 'icon' => 'chart-bar', 'label' => 'Reports'],
            ['route' => 'admin.audit-log.index', 'icon' => 'shield-check', 'label' => 'Audit Log'],
        ];
    @endphp

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:sticky top-0 z-40 h-screen w-72 bg-ink-950 text-ink-300 flex flex-col transition-transform duration-200 lg:translate-x-0 overflow-y-auto">
            <div class="px-6 py-6 flex items-center gap-3 border-b border-white/10">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-600 text-white font-heading font-extrabold">G</span>
                <div class="leading-tight">
                    <div class="font-heading font-extrabold text-white text-sm">GATED</div>
                    <div class="text-[10px] uppercase tracking-widest text-ink-400">Admin Portal</div>
                </div>
            </div>

            <div class="px-6 py-5 flex items-center gap-3 border-b border-white/10">
                <span class="w-11 h-11 rounded-full bg-brand-600 text-white flex items-center justify-center font-heading font-bold">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</span>
                <div>
                    <div class="text-xs text-ink-400">Signed in as</div>
                    <div class="font-semibold text-white text-sm">{{ auth()->user()->name }}</div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-6">
                <div class="space-y-1">
                    @foreach ($adminNav as $item)
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs(str_replace('.index','',$item['route']).'*') || request()->routeIs($item['route']) ? 'bg-brand-600 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            <x-icon :name="$item['icon']" class="w-5 h-5 shrink-0" />
                            <span class="flex-1">{{ $item['label'] }}</span>
                            @if (($item['badge'] ?? 0) > 0)
                                <span class="bg-white text-brand-600 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $item['badge'] }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-white/10 space-y-1">
                    <div class="px-4 text-[10px] uppercase tracking-widest text-ink-500 mb-1">Finance &amp; Packages</div>
                    @foreach ($financeNav as $item)
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs(str_replace('.index','',$item['route']).'*') ? 'bg-brand-600 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            <x-icon :name="$item['icon']" class="w-5 h-5 shrink-0" />
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-white/10 space-y-1">
                    <div class="px-4 text-[10px] uppercase tracking-widest text-ink-500 mb-1">Content &amp; Marketing</div>
                    @foreach ($contentNav as $item)
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs(str_replace('.index','',$item['route']).'*') ? 'bg-brand-600 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            <x-icon :name="$item['icon']" class="w-5 h-5 shrink-0" />
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>

                <div class="pt-4 border-t border-white/10 space-y-1">
                    <div class="px-4 text-[10px] uppercase tracking-widest text-ink-500 mb-1">System</div>
                    @foreach ($systemNav as $item)
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition {{ request()->routeIs(str_replace('.index','',$item['route']).'*') ? 'bg-brand-600 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                            <x-icon :name="$item['icon']" class="w-5 h-5 shrink-0" />
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                    <a href="{{ route('portal.profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-white/5 hover:text-white transition">
                        <x-icon name="users" class="w-5 h-5 shrink-0" /> Profile Settings
                    </a>
                </div>
            </nav>

            <div class="p-4 border-t border-white/10">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium text-ink-300 hover:bg-brand-600 hover:text-white transition">
                        <x-icon name="arrow-right" class="w-5 h-5 shrink-0" /> Logout
                    </button>
                </form>
            </div>
        </aside>

        <div @click="sidebarOpen = false" x-show="sidebarOpen" x-cloak class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

        {{-- Main --}}
        <div class="flex-1 min-w-0">
            <header class="sticky top-0 z-20 bg-white border-b border-ink-100">
                <div class="px-6 h-20 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 text-ink-700"><x-icon name="bars-3" class="w-6 h-6" /></button>
                        <div>
                            <h1 class="font-heading font-extrabold text-xl text-ink-900">@yield('title', 'Dashboard')</h1>
                            @hasSection('subtitle')<p class="text-sm text-ink-500">@yield('subtitle')</p>@endif
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <a href="{{ route('home') }}" class="hidden sm:inline text-sm font-semibold text-ink-500 hover:text-brand-600 transition">&larr; View Website</a>
                        <span class="badge bg-brand-50 text-brand-700">Admin</span>
                    </div>
                </div>
            </header>

            @if (session('success'))
                <div class="mx-6 mt-6 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 flex items-center gap-2">
                    <x-icon name="check-circle" class="w-5 h-5 shrink-0" /> {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="mx-6 mt-6 rounded-lg bg-brand-50 border border-brand-200 text-brand-800 text-sm p-4">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
