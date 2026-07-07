@extends('layouts.admin')

@section('title', $maintenanceRequest->title)
@section('subtitle', 'Ticket ' . $maintenanceRequest->ticket_no)

@section('content')

    <a href="{{ route('admin.maintenance.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Maintenance</a>

    <div class="grid lg:grid-cols-3 gap-6 mt-4">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                @php
                    $statusColors = ['submitted' => 'bg-ink-100 text-ink-600', 'acknowledged' => 'bg-blue-100 text-blue-700', 'in_progress' => 'bg-amber-100 text-amber-700', 'completed' => 'bg-emerald-100 text-emerald-700', 'cancelled' => 'bg-ink-100 text-ink-500'];
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-heading font-bold text-ink-900">{{ $maintenanceRequest->title }}</h2>
                    <span class="badge {{ $statusColors[$maintenanceRequest->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$maintenanceRequest->status)) }}</span>
                </div>
                <p class="text-sm text-ink-600 leading-relaxed">{{ $maintenanceRequest->description }}</p>

                <dl class="mt-6 grid sm:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-ink-500">Property</dt><dd class="font-semibold text-ink-900">{{ $maintenanceRequest->property->title }}</dd></div>
                    <div><dt class="text-ink-500">Client</dt><dd class="font-semibold text-ink-900">{{ $maintenanceRequest->user->name }}</dd></div>
                    <div><dt class="text-ink-500">Category</dt><dd class="text-ink-900 capitalize">{{ str_replace('_',' ',$maintenanceRequest->category) }}</dd></div>
                    <div><dt class="text-ink-500">Priority</dt><dd class="text-ink-900 capitalize">{{ $maintenanceRequest->priority }}</dd></div>
                </dl>

                @if ($maintenanceRequest->images->count())
                    <div class="mt-6 grid grid-cols-4 gap-2">
                        @foreach ($maintenanceRequest->images as $img)
                            <img src="{{ \App\Support\Media::url($img->path) }}" class="rounded-lg aspect-square object-cover">
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="card p-6">
                <h3 class="font-heading font-bold text-ink-900 mb-5">Activity Timeline</h3>
                <div class="space-y-4">
                    @foreach ($maintenanceRequest->updates as $update)
                        <div class="flex gap-3">
                            <span class="w-8 h-8 rounded-full bg-brand-50 text-brand-600 flex items-center justify-center shrink-0"><x-icon name="check-circle" class="w-4 h-4" /></span>
                            <div>
                                <div class="text-sm font-semibold text-ink-900 capitalize">{{ str_replace('_',' ',$update->status) }}</div>
                                @if ($update->note)<p class="text-sm text-ink-500">{{ $update->note }}</p>@endif
                                <div class="text-xs text-ink-400 mt-0.5">{{ $update->created_by }} &middot; {{ $update->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <aside class="card p-6 h-fit">
            <h3 class="font-heading font-bold text-ink-900 mb-4">Manage Request</h3>
            <form method="POST" action="{{ route('admin.maintenance.update', $maintenanceRequest) }}" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-xs font-semibold text-ink-700">Status</label>
                    <select name="status" required class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                        @foreach (['submitted' => 'Submitted', 'acknowledged' => 'Acknowledged', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $val => $label)
                            <option value="{{ $val }}" @selected($maintenanceRequest->status === $val)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="text-xs font-semibold text-ink-700">Assign To</label>
                    <input type="text" name="assigned_to" value="{{ old('assigned_to', $maintenanceRequest->assigned_to) }}" placeholder="e.g. Bilal (Plumbing Team)" class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label class="text-xs font-semibold text-ink-700">Estimated Completion</label>
                    <input type="date" name="estimated_completion" value="{{ old('estimated_completion', $maintenanceRequest->estimated_completion?->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                </div>
                <div>
                    <label class="text-xs font-semibold text-ink-700">Update Note</label>
                    <textarea name="note" rows="3" placeholder="Add a note visible to the client's timeline..." class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500"></textarea>
                </div>
                <button type="submit" class="btn-primary w-full justify-center text-sm">Save Update</button>
            </form>
        </aside>
    </div>

@endsection
