@extends('layouts.portal')

@section('title', 'Dashboard')
@section('subtitle', 'Here\'s what\'s happening with your properties today.')

@section('content')

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5" data-reveal>
        <div class="card p-5 flex items-center gap-4 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <span class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0"><x-icon name="building-office" class="w-6 h-6" /></span>
            <div>
                <div class="text-xs text-ink-500">Total Properties</div>
                <div class="text-2xl font-heading font-extrabold text-ink-900"><span data-counter="{{ $totalProperties }}">0</span></div>
            </div>
        </div>
        <div class="card p-5 flex items-center gap-4 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <span class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0"><x-icon name="check-circle" class="w-6 h-6" /></span>
            <div>
                <div class="text-xs text-ink-500">Occupied</div>
                <div class="text-2xl font-heading font-extrabold text-ink-900"><span data-counter="{{ $occupied }}">0</span></div>
            </div>
        </div>
        <div class="card p-5 flex items-center gap-4 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <span class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center shrink-0"><x-icon name="banknotes" class="w-6 h-6" /></span>
            <div>
                <div class="text-xs text-ink-500">Monthly Rent</div>
                <div class="text-2xl font-heading font-extrabold text-ink-900">PKR <span data-counter="{{ (int) $monthlyRent }}">0</span></div>
            </div>
        </div>
        <div class="card p-5 flex items-center gap-4 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <span class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon name="chart-bar" class="w-6 h-6" /></span>
            <div>
                <div class="text-xs text-ink-500">This Month Earning</div>
                <div class="text-2xl font-heading font-extrabold text-ink-900">PKR <span data-counter="{{ (int) $thisMonthEarning }}">0</span></div>
            </div>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mt-6">
        {{-- My Properties --}}
        <div class="lg:col-span-1 card p-6" data-reveal>
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-heading font-bold text-ink-900">My Properties</h2>
                <a href="{{ route('portal.properties.index') }}" class="text-xs font-semibold text-brand-600 hover:text-brand-700">View All</a>
            </div>
            <div class="space-y-4">
                @forelse ($properties->take(3) as $property)
                    @php
                        $cover = $property->coverImage;
                        $imageUrl = \App\Support\Media::url($cover?->path, 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=200&q=80');
                        $statusColors = ['occupied' => 'text-emerald-600', 'vacant' => 'text-amber-600', 'maintenance' => 'text-blue-600', 'pending_review' => 'text-ink-500'];
                    @endphp
                    <a href="{{ route('portal.properties.show', $property) }}" class="flex items-center gap-3 hover:bg-ink-50 rounded-lg p-2 -m-2 transition-colors duration-200">
                        <img src="{{ $imageUrl }}" class="w-14 h-14 rounded-lg object-cover shrink-0" alt="{{ $property->title }}">
                        <div class="min-w-0">
                            <div class="font-semibold text-sm text-ink-900 truncate">{{ $property->title }}</div>
                            <div class="text-xs {{ $statusColors[$property->status] ?? 'text-ink-500' }}">Status: {{ ucfirst(str_replace('_',' ',$property->status)) }}</div>
                            <div class="text-xs text-ink-500">Monthly Rent: PKR {{ number_format($property->price) }}</div>
                        </div>
                    </a>
                @empty
                    <p class="text-sm text-ink-500">No properties yet. <a href="{{ route('property-registration.create') }}" class="text-brand-600 font-semibold">Register one</a>.</p>
                @endforelse
            </div>
        </div>

        {{-- Occupancy donut --}}
        <div class="lg:col-span-1 card p-6 flex flex-col" data-reveal data-reveal-delay="2">
            <h2 class="font-heading font-bold text-ink-900 mb-4">Portfolio Status</h2>
            <div class="flex-1 flex flex-col items-center justify-center">
                <div class="donut-chart w-40" style="--value: 0;" x-data x-init="setTimeout(() => $el.style.setProperty('--value', {{ $occupancyRate }}), 200)">
                    <span class="text-2xl font-heading font-extrabold text-ink-900">{{ $occupancyRate }}%</span>
                </div>
                <div class="mt-6 w-full space-y-2">
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 text-ink-600"><span class="w-2.5 h-2.5 rounded-full bg-brand-600"></span> Occupied</span>
                        <span class="font-semibold text-ink-900">{{ $occupied }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 text-ink-600"><span class="w-2.5 h-2.5 rounded-full bg-ink-200"></span> Vacant</span>
                        <span class="font-semibold text-ink-900">{{ $vacant }}</span>
                    </div>
                    <div class="flex items-center justify-between text-xs">
                        <span class="flex items-center gap-2 text-ink-600"><span class="w-2.5 h-2.5 rounded-full bg-blue-400"></span> Maintenance</span>
                        <span class="font-semibold text-ink-900">{{ $underMaintenance }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Monthly overview --}}
        <div class="lg:col-span-1 card p-6" data-reveal data-reveal-delay="3">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-heading font-bold text-ink-900">Monthly Overview</h2>
                <span class="text-xs text-ink-400">Last 6 months</span>
            </div>
            @php $max = max(1, $monthlyOverview->max('total')); @endphp
            <div class="flex items-end gap-3 h-48">
                @foreach ($monthlyOverview as $month)
                    <div class="flex-1 flex flex-col items-center gap-2 group">
                        <div class="text-[10px] font-semibold text-ink-500 opacity-0 group-hover:opacity-100 transition">PKR {{ number_format($month['total']) }}</div>
                        <div class="w-full bg-ink-100 rounded-t-md relative flex items-end" style="height: 100%">
                            <div class="w-full bg-gradient-to-t from-brand-600 to-brand-400 rounded-t-md transition-all duration-700 ease-out" style="height: {{ $month['total'] > 0 ? max(6, ($month['total'] / $max) * 100) : 4 }}%"></div>
                        </div>
                        <div class="text-xs font-semibold text-ink-500">{{ $month['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-6" data-reveal>
        <a href="{{ route('portal.maintenance.index') }}" class="card p-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-xs text-ink-500">Maintenance Requests</div>
            <div class="text-2xl font-heading font-extrabold text-ink-900 mt-1"><span data-counter="{{ $maintenanceInProgress }}">0</span></div>
            <div class="text-xs text-ink-400 mt-1">In Progress</div>
        </a>
        <a href="{{ route('portal.payments.index') }}" class="card p-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-xs text-ink-500">Rent Received</div>
            <div class="text-2xl font-heading font-extrabold text-emerald-600 mt-1">PKR <span data-counter="{{ (int) $rentReceived }}">0</span></div>
            <div class="text-xs text-ink-400 mt-1">This Month</div>
        </a>
        <a href="{{ route('portal.payments.index') }}" class="card p-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-xs text-ink-500">Invoices Due</div>
            <div class="text-2xl font-heading font-extrabold text-brand-600 mt-1"><span data-counter="{{ $invoicesDue }}">0</span></div>
            <div class="text-xs text-ink-400 mt-1">Payment Due</div>
        </a>
        <a href="{{ route('portal.messages.index') }}" class="card p-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-xs text-ink-500">Messages</div>
            <div class="text-2xl font-heading font-extrabold text-ink-900 mt-1"><span data-counter="{{ $unreadMessages }}">0</span></div>
            <div class="text-xs text-ink-400 mt-1">Unread</div>
        </a>
    </div>

    {{-- Recent activity --}}
    <div class="card p-6 mt-6" data-reveal>
        <h2 class="font-heading font-bold text-ink-900 mb-4">Recent Activity</h2>
        <div class="space-y-4">
            @forelse ($recentActivities as $activity)
                <div class="flex items-center gap-3">
                    <span class="w-9 h-9 rounded-full bg-ink-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon :name="$activity['icon']" class="w-4 h-4" /></span>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-ink-800 truncate">{{ $activity['title'] }}</div>
                        <div class="text-xs text-ink-400">{{ \Illuminate\Support\Carbon::parse($activity['time'])->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-ink-500">No recent activity yet.</p>
            @endforelse
        </div>
    </div>

@endsection
