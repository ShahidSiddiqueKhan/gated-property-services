@extends('layouts.admin')

@section('title', $client->name)
@section('subtitle', $client->email)

@section('content')

    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.clients.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Clients</a>
        <a href="{{ route('admin.clients.edit', $client) }}" class="btn-outline !py-2 !px-4 text-sm">Edit Client</a>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <h2 class="font-heading font-bold text-ink-900 mb-4">Properties ({{ $client->properties->count() }})</h2>
                <div class="space-y-3">
                    @forelse ($client->properties as $property)
                        <a href="{{ route('admin.properties.show', $property) }}" class="flex items-center justify-between rounded-lg bg-ink-50 p-4 hover:bg-ink-100 transition">
                            <div>
                                <div class="font-semibold text-sm text-ink-900">{{ $property->title }}</div>
                                <div class="text-xs text-ink-500">{{ $property->reference_no }} &middot; {{ ucfirst(str_replace('_',' ',$property->status)) }}</div>
                            </div>
                            <span class="font-heading font-bold text-brand-600 text-sm">PKR {{ number_format($property->price) }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-ink-500">No properties registered yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="card p-6">
                <h2 class="font-heading font-bold text-ink-900 mb-4">Payment History</h2>
                <div class="space-y-2">
                    @forelse ($client->payments->take(8) as $payment)
                        <div class="flex items-center justify-between py-2 border-b border-ink-100 last:border-0 text-sm">
                            <span class="text-ink-600">{{ $payment->invoice_no }} &middot; {{ $payment->due_date?->format('M d, Y') }}</span>
                            <span class="font-semibold text-ink-900">PKR {{ number_format($payment->amount) }} <span class="text-xs text-ink-400">({{ ucfirst(str_replace('_',' ',$payment->status)) }})</span></span>
                        </div>
                    @empty
                        <p class="text-sm text-ink-500">No payment history.</p>
                    @endforelse
                </div>
            </div>

            <div class="card p-6">
                <h2 class="font-heading font-bold text-ink-900 mb-4">Maintenance Requests</h2>
                <div class="space-y-2">
                    @forelse ($client->maintenanceRequests->take(8) as $mr)
                        <a href="{{ route('admin.maintenance.show', $mr) }}" class="flex items-center justify-between py-2 border-b border-ink-100 last:border-0 text-sm hover:bg-ink-50/50">
                            <span class="text-ink-600">{{ $mr->title }}</span>
                            <span class="badge bg-ink-100 text-ink-600">{{ ucfirst(str_replace('_',' ',$mr->status)) }}</span>
                        </a>
                    @empty
                        <p class="text-sm text-ink-500">No maintenance requests.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="card p-6">
                <span class="w-14 h-14 rounded-full bg-ink-900 text-white flex items-center justify-center font-heading font-bold text-xl mb-4">{{ substr($client->name, 0, 1) }}</span>
                <h3 class="font-heading font-bold text-ink-900">{{ $client->name }}</h3>
                <dl class="mt-4 space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-ink-500">Email</dt><dd class="text-ink-800">{{ $client->email }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink-500">Phone</dt><dd class="text-ink-800">{{ $client->phone ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink-500">Country</dt><dd class="text-ink-800">{{ $client->country ?? '—' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink-500">Joined</dt><dd class="text-ink-800">{{ $client->created_at->format('M d, Y') }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink-500">2FA</dt><dd class="text-ink-800">{{ $client->two_factor_enabled ? 'Enabled' : 'Disabled' }}</dd></div>
                </dl>
            </div>

            <div class="card p-6">
                <h3 class="font-heading font-bold text-ink-900 text-sm mb-3">Documents</h3>
                @forelse ($client->documents as $doc)
                    <div class="text-sm text-ink-600 py-1.5 border-b border-ink-100 last:border-0">{{ $doc->title }}</div>
                @empty
                    <p class="text-sm text-ink-500">No documents on file.</p>
                @endforelse
                <a href="{{ route('admin.documents.create') }}" class="btn-outline w-full mt-4 justify-center text-sm">Upload Document</a>
            </div>
        </aside>
    </div>

@endsection
