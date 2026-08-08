@extends('layouts.admin')

@section('title', $project->title)
@section('subtitle', $project->property->title)

@section('content')

    @php
        $statusColors = ['proposed' => 'bg-ink-100 text-ink-600', 'approved' => 'bg-blue-100 text-blue-700', 'in_progress' => 'bg-amber-100 text-amber-700', 'completed' => 'bg-emerald-100 text-emerald-700', 'cancelled' => 'bg-ink-100 text-ink-500'];
    @endphp

    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.renovations.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Renovation Projects</a>
        <div class="flex gap-2">
            @if ($project->approval_status === 'pending')
                <form method="POST" action="{{ route('admin.renovations.reject', $project) }}" onsubmit="return confirm('Reject this project?')">
                    @csrf
                    <button type="submit" class="btn-outline !py-2 !px-4 text-sm">Reject</button>
                </form>
                <form method="POST" action="{{ route('admin.renovations.approve', $project) }}">
                    @csrf
                    <button type="submit" class="btn-primary !py-2 !px-4 text-sm">Approve &amp; Invoice Client</button>
                </form>
            @endif
            <a href="{{ route('admin.renovations.edit', $project) }}" class="btn-outline !py-2 !px-4 text-sm">Edit</a>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="card p-6">
                <div class="flex items-center gap-2 mb-3">
                    <span class="badge {{ $statusColors[$project->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$project->status)) }}</span>
                    <span class="badge {{ $project->approval_status === 'approved' ? 'bg-emerald-100 text-emerald-700' : ($project->approval_status === 'rejected' ? 'bg-brand-100 text-brand-700' : 'bg-amber-100 text-amber-700') }}">{{ ucfirst($project->approval_status) }}</span>
                </div>
                <h2 class="font-heading font-bold text-xl text-ink-900">{{ $project->title }}</h2>
                <p class="text-sm text-ink-500 mt-1">{{ $project->property->title }} &middot; Owner: {{ $project->property->owner?->name ?? 'Unassigned' }}</p>
                <p class="mt-4 text-sm text-ink-600 leading-relaxed">{{ $project->description }}</p>

                <dl class="mt-6 grid sm:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-ink-500">Contractor</dt><dd class="font-semibold text-ink-900">{{ $project->contractor_name ?? '—' }}</dd></div>
                    <div><dt class="text-ink-500">Contractor Contact</dt><dd class="text-ink-900">{{ $project->contractor_contact ?? '—' }}</dd></div>
                    <div><dt class="text-ink-500">Start Date</dt><dd class="text-ink-900">{{ $project->start_date?->format('M j, Y') ?? '—' }}</dd></div>
                    <div><dt class="text-ink-500">Expected Completion</dt><dd class="text-ink-900">{{ $project->expected_completion_date?->format('M j, Y') ?? '—' }}</dd></div>
                    @if ($project->actual_completion_date)
                        <div><dt class="text-ink-500">Actual Completion</dt><dd class="text-ink-900">{{ $project->actual_completion_date->format('M j, Y') }}</dd></div>
                    @endif
                    @if ($project->final_cost)
                        <div><dt class="text-ink-500">Final Cost</dt><dd class="font-semibold text-ink-900">PKR {{ number_format($project->final_cost, 0) }}</dd></div>
                    @endif
                </dl>

                <form method="POST" action="{{ route('admin.renovations.status', $project) }}" class="mt-6 pt-6 border-t border-ink-100 flex flex-wrap items-end gap-3">
                    @csrf @method('PUT')
                    <div>
                        <label class="text-xs font-semibold text-ink-700">Status</label>
                        <select name="status" class="mt-1 rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                            @foreach (['proposed' => 'Proposed', 'approved' => 'Approved', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $val => $label)
                                <option value="{{ $val }}" @selected($project->status === $val)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-ink-700">Final Cost (if different)</label>
                        <input type="number" step="0.01" min="0" name="final_cost" value="{{ $project->final_cost }}" class="mt-1 rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                    </div>
                    <button type="submit" class="btn-outline !py-2 !px-4 text-sm">Update Status</button>
                </form>
            </div>

            <div class="card p-6">
                <h3 class="font-heading font-bold text-ink-900 mb-4">Milestones</h3>
                <div class="space-y-2">
                    @forelse ($project->milestones as $milestone)
                        <div class="flex items-center justify-between py-2 border-b border-ink-100 last:border-0 text-sm">
                            <div>
                                <div class="font-medium text-ink-900">{{ $milestone->title }}</div>
                                @if ($milestone->due_date)<div class="text-xs text-ink-400">Due {{ $milestone->due_date->format('M j, Y') }}</div>@endif
                            </div>
                            <div class="flex items-center gap-3">
                                <form method="POST" action="{{ route('admin.renovations.milestones.update', [$project, $milestone]) }}">
                                    @csrf @method('PUT')
                                    <select name="status" onchange="this.form.submit()" class="rounded-lg border-ink-200 text-xs focus:border-brand-500 focus:ring-brand-500">
                                        @foreach (['pending' => 'Pending', 'in_progress' => 'In Progress', 'completed' => 'Completed'] as $val => $label)
                                            <option value="{{ $val }}" @selected($milestone->status === $val)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </form>
                                <form method="POST" action="{{ route('admin.renovations.milestones.destroy', [$project, $milestone]) }}" onsubmit="return confirm('Delete this milestone?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-ink-400 hover:text-brand-600 text-xs font-semibold">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-ink-500">No milestones yet.</p>
                    @endforelse
                </div>
                <form method="POST" action="{{ route('admin.renovations.milestones.store', $project) }}" class="mt-4 pt-4 border-t border-ink-100 flex flex-wrap items-end gap-3">
                    @csrf
                    <div class="flex-1 min-w-[160px]">
                        <label class="text-xs font-semibold text-ink-700">New Milestone</label>
                        <input type="text" name="title" required placeholder="e.g. Demolition complete" class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-ink-700">Due Date</label>
                        <input type="date" name="due_date" class="mt-1 rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                    </div>
                    <button type="submit" class="btn-outline !py-2 !px-4 text-sm">Add</button>
                </form>
            </div>

            <div class="card p-6">
                <h3 class="font-heading font-bold text-ink-900 mb-4">Photos, Videos &amp; Invoices</h3>
                @if ($project->media->count())
                    <div class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                        @foreach ($project->media as $item)
                            <div class="relative group">
                                @if ($item->type === 'photo')
                                    <img src="{{ \App\Support\Media::url($item->path) }}" class="rounded-lg aspect-square object-cover">
                                @else
                                    <div class="rounded-lg aspect-square bg-ink-100 flex items-center justify-center">
                                        <x-icon :name="$item->type === 'video' ? 'camera' : 'document-text'" class="w-6 h-6 text-ink-500" />
                                    </div>
                                @endif
                                <div class="mt-1 text-[10px] text-ink-500 capitalize">{{ $item->phase ?? $item->type }}</div>
                                <form method="POST" action="{{ route('admin.renovations.media.destroy', [$project, $item]) }}" onsubmit="return confirm('Remove this file?')" class="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-6 h-6 rounded-full bg-white/90 text-brand-600 flex items-center justify-center text-xs font-bold shadow">&times;</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-ink-500">No media uploaded yet.</p>
                @endif

                <form method="POST" action="{{ route('admin.renovations.media.store', $project) }}" enctype="multipart/form-data" class="mt-4 pt-4 border-t border-ink-100 grid sm:grid-cols-4 gap-3 items-end">
                    @csrf
                    <div>
                        <label class="text-xs font-semibold text-ink-700">Type</label>
                        <select name="type" class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                            <option value="photo">Photo</option>
                            <option value="video">Video</option>
                            <option value="invoice">Invoice</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-ink-700">Phase</label>
                        <select name="phase" class="mt-1 w-full rounded-lg border-ink-200 text-sm focus:border-brand-500 focus:ring-brand-500">
                            <option value="">—</option>
                            <option value="before">Before</option>
                            <option value="progress">Progress</option>
                            <option value="after">After</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="text-xs font-semibold text-ink-700">File</label>
                        <input type="file" name="file" required class="mt-1 w-full text-sm">
                    </div>
                    <div class="sm:col-span-4">
                        <button type="submit" class="btn-outline !py-2 !px-4 text-sm">Upload</button>
                    </div>
                </form>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="card p-6">
                <h3 class="font-heading font-bold text-ink-900 text-sm mb-3">Financial Summary</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-ink-500">Project value</dt><dd class="text-ink-800 font-semibold">PKR {{ number_format($project->project_value, 0) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-ink-500">GATED fee ({{ rtrim(rtrim(number_format($project->fee_percent, 2), '0'), '.') }}%)</dt><dd class="font-semibold text-brand-600">PKR {{ number_format($project->fee_amount, 0) }}</dd></div>
                    <div class="flex justify-between pt-2 border-t border-ink-100"><dt class="text-ink-700 font-semibold">Total client invoice</dt><dd class="font-heading font-bold text-ink-900">PKR {{ number_format($project->totalWithFee(), 0) }}</dd></div>
                </dl>
                <div class="mt-4">
                    <div class="flex justify-between text-xs text-ink-500 mb-1">
                        <span>Milestone progress</span>
                        <span>{{ $project->milestoneProgress() }}%</span>
                    </div>
                    <div class="h-2 rounded-full bg-ink-100 overflow-hidden">
                        <div class="h-full bg-brand-600" style="width: {{ $project->milestoneProgress() }}%"></div>
                    </div>
                </div>
            </div>

            @if ($project->payments->count())
                <div class="card p-6">
                    <h3 class="font-heading font-bold text-ink-900 text-sm mb-3">Linked Invoices</h3>
                    @foreach ($project->payments as $payment)
                        <a href="{{ route('admin.payments.show', $payment) }}" class="flex items-center justify-between py-2 border-b border-ink-100 last:border-0 text-sm hover:bg-ink-50/50">
                            <span>{{ $payment->invoice_no }}</span>
                            <span class="badge bg-ink-100 text-ink-600">{{ ucfirst($payment->status) }}</span>
                        </a>
                    @endforeach
                </div>
            @endif
        </aside>
    </div>

@endsection
