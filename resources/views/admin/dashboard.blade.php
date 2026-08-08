@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('subtitle', 'Portfolio-wide overview of clients, properties and operations.')

@section('content')

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5" data-reveal>
        <a href="{{ route('admin.clients.index') }}" class="card p-5 flex items-center gap-4 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <span class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0"><x-icon name="users" class="w-6 h-6" /></span>
            <div><div class="text-xs text-ink-500">Total Clients</div><div class="text-2xl font-heading font-extrabold text-ink-900"><span data-counter="{{ $totalClients }}">0</span></div></div>
        </a>
        <a href="{{ route('admin.properties.index') }}" class="card p-5 flex items-center gap-4 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <span class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0"><x-icon name="building-office" class="w-6 h-6" /></span>
            <div><div class="text-xs text-ink-500">Properties ({{ $occupied }} occupied)</div><div class="text-2xl font-heading font-extrabold text-ink-900"><span data-counter="{{ $totalProperties }}">0</span></div></div>
        </a>
        <a href="{{ route('admin.properties.index', ['status' => 'pending_review']) }}" class="card p-5 flex items-center gap-4 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <span class="w-12 h-12 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon name="exclamation-triangle" class="w-6 h-6" /></span>
            <div><div class="text-xs text-ink-500">Pending Approvals</div><div class="text-2xl font-heading font-extrabold text-ink-900"><span data-counter="{{ $pendingApprovals }}">0</span></div></div>
        </a>
        <a href="{{ route('admin.payments.index') }}" class="card p-5 flex items-center gap-4 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <span class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0"><x-icon name="banknotes" class="w-6 h-6" /></span>
            <div><div class="text-xs text-ink-500">Revenue This Month</div><div class="text-2xl font-heading font-extrabold text-ink-900">PKR <span data-counter="{{ (int) $revenueThisMonth }}">0</span></div></div>
        </a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-6" data-reveal>
        <a href="{{ route('admin.payments.index', ['status' => 'due']) }}" class="card p-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-xs text-ink-500">Outstanding Payments</div>
            <div class="text-xl font-heading font-extrabold text-brand-600 mt-1">PKR <span data-counter="{{ (int) $pendingPaymentsAmount }}">0</span></div>
            <div class="text-xs text-ink-400 mt-1">{{ $pendingPayments }} invoice(s)</div>
        </a>
        <a href="{{ route('admin.maintenance.index') }}" class="card p-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-xs text-ink-500">Open Maintenance</div>
            <div class="text-xl font-heading font-extrabold text-amber-600 mt-1"><span data-counter="{{ $openMaintenance }}">0</span></div>
            <div class="text-xs text-ink-400 mt-1">{{ $emergencyMaintenance }} emergency</div>
        </a>
        <a href="{{ route('admin.leads.index') }}" class="card p-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-xs text-ink-500">New Leads</div>
            <div class="text-xl font-heading font-extrabold text-blue-600 mt-1"><span data-counter="{{ $newLeads }}">0</span></div>
            <div class="text-xs text-ink-400 mt-1">Awaiting response</div>
        </a>
        <a href="{{ route('admin.messages.index') }}" class="card p-5 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="text-xs text-ink-500">New Messages</div>
            <div class="text-xl font-heading font-extrabold text-ink-900 mt-1"><span data-counter="{{ $newMessages }}">0</span></div>
            <div class="text-xs text-ink-400 mt-1">Last 7 days</div>
        </a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6 mt-6">
        <div class="lg:col-span-1 card p-6 flex flex-col" data-reveal data-reveal-delay="2">
            <h2 class="font-heading font-bold text-ink-900 mb-4">Portfolio Status</h2>
            <div class="flex-1 flex flex-col items-center justify-center">
                <div class="donut-chart w-36" style="--value: 0;" x-data x-init="setTimeout(() => $el.style.setProperty('--value', {{ $occupancyRate }}), 200)">
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

        <div class="lg:col-span-1 card p-6" data-reveal data-reveal-delay="3">
            <div class="flex items-center justify-between mb-6">
                <h2 class="font-heading font-bold text-ink-900">Revenue Overview</h2>
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-semibold text-brand-600">Full Reports</a>
            </div>
            @php $max = max(1, $monthlyRevenue->max('total')); @endphp
            <div class="flex items-end gap-3 h-48">
                @foreach ($monthlyRevenue as $month)
                    <div class="flex-1 flex flex-col items-center gap-2">
                        <div class="w-full bg-ink-100 rounded-t-md flex items-end" style="height: 100%">
                            <div class="w-full bg-gradient-to-t from-brand-600 to-brand-400 rounded-t-md transition-all duration-700 ease-out" style="height: {{ $month['total'] > 0 ? max(6, ($month['total'] / $max) * 100) : 4 }}%"></div>
                        </div>
                        <div class="text-xs font-semibold text-ink-500">{{ $month['label'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="lg:col-span-1 card p-6" data-reveal data-reveal-delay="4">
            <h2 class="font-heading font-bold text-ink-900 mb-4">Recent Activity</h2>
            <div class="space-y-4">
                @forelse ($recentActivity as $log)
                    <div class="flex gap-3">
                        <span class="w-8 h-8 rounded-full bg-ink-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon name="check-circle" class="w-4 h-4" /></span>
                        <div class="min-w-0">
                            <div class="text-sm font-medium text-ink-800">{{ $log->action }}</div>
                            <div class="text-xs text-ink-400 truncate">{{ $log->description }}</div>
                            <div class="text-xs text-ink-400">{{ $log->user?->name ?? 'System' }} &middot; {{ $log->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-ink-500">No activity recorded yet.</p>
                @endforelse
            </div>
        </div>
    </div>

@endsection
