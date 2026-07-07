<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Client Portal') | GATED Property Services</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 24 24%22><rect width=%2224%22 height=%2224%22 rx=%224%22 fill=%22%23111%22/><text x=%2212%22 y=%2217%22 font-size=%2214%22 fill=%22%23e63946%22 text-anchor=%22middle%22 font-family=%22sans-serif%22 font-weight=%22bold%22>G</text></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.14.1/cdn.min.js"></script>
</head>
<body class="bg-ink-50 text-ink-800" x-data="{ sidebarOpen: false }">

    @php
        $navItems = [
            ['route' => 'portal.dashboard', 'icon' => 'chart-bar', 'label' => 'Dashboard'],
            ['route' => 'portal.properties.index', 'icon' => 'building-office', 'label' => 'My Properties'],
            ['route' => 'portal.payments.index', 'icon' => 'banknotes', 'label' => 'Rent & Payments'],
            ['route' => 'portal.maintenance.index', 'icon' => 'wrench-screwdriver', 'label' => 'Maintenance'],
            ['route' => 'portal.documents.index', 'icon' => 'document-text', 'label' => 'Documents'],
            ['route' => 'portal.reports.index', 'icon' => 'chart-bar', 'label' => 'Reports'],
            ['route' => 'portal.messages.index', 'icon' => 'chat', 'label' => 'Messages'],
            ['route' => 'portal.tasks.index', 'icon' => 'check-circle', 'label' => 'Tasks'],
        ];
    @endphp

    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed lg:sticky top-0 z-40 h-screen w-72 bg-ink-950 text-ink-300 flex flex-col transition-transform duration-200 lg:translate-x-0">
            <div class="px-6 py-6 flex items-center gap-3 border-b border-white/10">
                <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-600 text-white font-heading font-extrabold">G</span>
                <div class="leading-tight">
                    <div class="font-heading font-extrabold text-white text-sm">GATED</div>
                    <div class="text-[10px] uppercase tracking-widest text-ink-400">Client Portal</div>
                </div>
            </div>

            <div class="px-6 py-5 flex items-center gap-3 border-b border-white/10">
                <span class="w-11 h-11 rounded-full bg-brand-600 text-white flex items-center justify-center font-heading font-bold">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                <div>
                    <div class="text-xs text-ink-400">Welcome Back,</div>
                    <div class="font-semibold text-white text-sm">{{ auth()->user()->name }}</div>
                </div>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs($item['route'] === 'portal.dashboard' ? $item['route'] : str_replace('.index','',$item['route']).'*') ? 'bg-brand-600 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <x-icon :name="$item['icon']" class="w-5 h-5 shrink-0" />
                        {{ $item['label'] }}
                        @if ($item['label'] === 'Messages' && ($unreadMessagesCount ?? 0) > 0)
                            <span class="ml-auto bg-white text-brand-600 text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $unreadMessagesCount }}</span>
                        @endif
                    </a>
                @endforeach

                <div class="pt-4 mt-4 border-t border-white/10 space-y-1">
                    <a href="{{ route('portal.profile.edit') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium transition {{ request()->routeIs('portal.profile.*') ? 'bg-brand-600 text-white' : 'hover:bg-white/5 hover:text-white' }}">
                        <x-icon name="users" class="w-5 h-5 shrink-0" /> Profile Settings
                    </a>
                    <a href="{{ route('contact.show') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg text-sm font-medium hover:bg-white/5 hover:text-white transition">
                        <x-icon name="chat" class="w-5 h-5 shrink-0" /> Support
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
                        <a href="{{ route('home') }}" class="hidden sm:inline text-sm font-semibold text-ink-500 hover:text-brand-600 transition">&larr; Back to Website</a>
                        <a href="{{ route('portal.messages.index') }}" class="relative w-10 h-10 rounded-full bg-ink-50 flex items-center justify-center text-ink-600 hover:text-brand-600 transition">
                            <x-icon name="bell" class="w-5 h-5" />
                            @if (($unreadMessagesCount ?? 0) > 0)
                                <span class="absolute -top-1 -right-1 bg-brand-600 text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center">{{ $unreadMessagesCount }}</span>
                            @endif
                        </a>
                        <a href="{{ route('portal.profile.edit') }}" class="w-10 h-10 rounded-full bg-ink-900 text-white flex items-center justify-center font-heading font-bold">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</a>
                    </div>
                </div>
            </header>

            @if (session('success'))
                <div class="mx-6 mt-6 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm p-4 flex items-center gap-2">
                    <x-icon name="check-circle" class="w-5 h-5 shrink-0" /> {{ session('success') }}
                </div>
            @endif

            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>
</html>
