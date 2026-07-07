@extends('layouts.admin')

@section('title', 'Leads & Inquiries')

@section('content')

    <div class="flex flex-wrap items-center gap-2 mb-6">
        <a href="{{ route('admin.leads.index') }}" class="badge {{ !request('type') && !request('unhandled_only') ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">All</a>
        <a href="{{ route('admin.leads.index', ['unhandled_only' => 1]) }}" class="badge {{ request('unhandled_only') ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Unhandled</a>
        <a href="{{ route('admin.leads.index', ['type' => 'consultation']) }}" class="badge {{ request('type') === 'consultation' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Consultations</a>
        <a href="{{ route('admin.leads.index', ['type' => 'callback']) }}" class="badge {{ request('type') === 'callback' ? 'bg-brand-600 text-white' : 'bg-ink-100 text-ink-600' }}">Call Backs</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3">Name</th>
                        <th class="text-left px-6 py-3">Contact</th>
                        <th class="text-left px-6 py-3">Type</th>
                        <th class="text-left px-6 py-3">Message</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-right px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($submissions as $submission)
                        <tr class="hover:bg-ink-50/50">
                            <td class="px-6 py-4 font-medium text-ink-900">{{ $submission->name }}</td>
                            <td class="px-6 py-4 text-ink-600">{{ $submission->email }}<br><span class="text-xs text-ink-400">{{ $submission->phone }}</span></td>
                            <td class="px-6 py-4">
                                <span class="badge bg-ink-100 text-ink-600 capitalize">{{ $submission->type }}</span>
                                @if ($submission->preferred_at)
                                    <div class="text-xs text-ink-400 mt-1">{{ $submission->preferred_at->format('M d, Y g:i A') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-ink-600 max-w-xs truncate">{{ $submission->message }}</td>
                            <td class="px-6 py-4"><span class="badge {{ $submission->is_handled ? 'bg-emerald-100 text-emerald-700' : 'bg-brand-100 text-brand-700' }}">{{ $submission->is_handled ? 'Handled' : 'New' }}</span></td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <form method="POST" action="{{ route('admin.leads.toggle-handled', $submission) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-brand-600 font-semibold text-xs hover:text-brand-700">{{ $submission->is_handled ? 'Reopen' : 'Mark Handled' }}</button>
                                </form>
                                <form method="POST" action="{{ route('admin.leads.destroy', $submission) }}" class="inline" onsubmit="return confirm('Delete this lead?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-ink-400 font-semibold text-xs hover:text-brand-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-ink-500">No leads found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $submissions->links() }}</div>

@endsection
