@extends('layouts.admin')

@section('title', 'Add Lease')

@section('content')
    <a href="{{ route('admin.leases.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Leases</a>

    <div class="max-w-2xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.leases.store') }}">
            @csrf
            @include('admin.leases._form')
            <button type="submit" class="btn-primary mt-6">Create Lease</button>
        </form>
    </div>
@endsection
