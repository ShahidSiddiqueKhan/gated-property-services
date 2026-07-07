@extends('layouts.portal')

@section('title', $maintenanceRequest->title)
@section('subtitle', 'Ticket ' . $maintenanceRequest->ticket_no)

@section('content')

    <a href="{{ route('portal.maintenance.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Maintenance</a>

    @php
        $statusColors = ['submitted' => 'bg-ink-100 text-ink-600', 'acknowledged' => 'bg-blue-100 text-blue-700', 'in_progress' => 'bg-amber-100 text-amber-700', 'completed' => 'bg-emerald-100 text-emerald-700', 'cancelled' => 'bg-ink-100 text-ink-500'];
        $steps = ['submitted', 'acknowledged', 'in_progress', 'completed'];
        $currentIndex = array_search($maintenanceRequest->status, $steps);
    @endphp

    <div class="grid lg:grid-cols-3 gap-6 mt-4">
        <div class="lg:col-span-2 space-y-6">

            {{-- Progress tracker --}}
            <div class="card p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="font-heading font-bold text-ink-900">{{ $maintenanceRequest->title }}</h2>
                    <span class="badge {{ $statusColors[$maintenanceRequest->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$maintenanceRequest->status)) }}</span>
                </div>

                @if ($currentIndex !== false)
                <div class="flex items-center">
                    @foreach ($steps as $i => $step)
                        <div class="flex items-center flex-1 last:flex-none">
                            <div class="flex flex-col items-center">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-xs font-bold {{ $i <= $currentIndex ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-400' }}">
                                    @if ($i < $currentIndex)<x-icon name="check" class="w-4 h-4" />@else {{ $i + 1 }} @endif
                                </div>
                                <div class="text-[10px] text-ink-500 mt-1.5 text-center w-20 capitalize">{{ str_replace('_',' ',$step) }}</div>
                            </div>
                            @if (!$loop->last)
                                <div class="flex-1 h-0.5 {{ $i < $currentIndex ? 'bg-brand-600' : 'bg-ink-100' }} -mt-5"></div>
                            @endif
                        </div>
                    @endforeach
                </div>
                @endif

                <p class="mt-8 text-sm text-ink-600 leading-relaxed">{{ $maintenanceRequest->description }}</p>

                <dl class="mt-6 grid sm:grid-cols-2 gap-4 text-sm">
                    <div><dt class="text-ink-500">Property</dt><dd class="font-semibold text-ink-900">{{ $maintenanceRequest->property->title }}</dd></div>
                    <div><dt class="text-ink-500">Category</dt><dd class="font-semibold text-ink-900 capitalize">{{ str_replace('_',' ',$maintenanceRequest->category) }}</dd></div>
                    <div><dt class="text-ink-500">Assigned To</dt><dd class="font-semibold text-ink-900">{{ $maintenanceRequest->assigned_to ?? 'Not yet assigned' }}</dd></div>
                    <div><dt class="text-ink-500">Estimated Completion</dt><dd class="font-semibold text-ink-900">{{ $maintenanceRequest->estimated_completion?->format('M d, Y') ?? 'TBC' }}</dd></div>
                </dl>

                @if ($maintenanceRequest->images->count())
                <div class="mt-6">
                    <h3 class="text-sm font-semibold text-ink-700 mb-2">Photos</h3>
                    <div class="grid grid-cols-4 gap-2">
                        @foreach ($maintenanceRequest->images as $img)
                            <img src="{{ \App\Support\Media::url($img->path) }}" class="rounded-lg aspect-square object-cover">
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Timeline --}}
            <div class="card p-6">
                <h3 class="font-heading font-bold text-ink-900 mb-5">Activity Timeline</h3>
                <div class="space-y-5">
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
            <h3 class="font-heading font-bold text-ink-900 text-sm">Need urgent help?</h3>
            <p class="mt-2 text-sm text-ink-500">Contact our support team directly for emergency issues.</p>
            <a href="tel:+923001234567" class="btn-primary w-full mt-4 justify-center text-sm">Call Support</a>
            <a href="{{ route('portal.messages.index') }}" class="btn-outline w-full mt-3 justify-center text-sm">Send a Message</a>
        </aside>
    </div>

@endsection
