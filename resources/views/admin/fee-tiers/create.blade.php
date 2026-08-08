@extends('layouts.admin')

@section('title', 'Add Fee Tier')

@section('content')
    <a href="{{ route('admin.fee-tiers.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Fee Tiers</a>
    <div class="max-w-3xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.fee-tiers.store') }}">
            @csrf
            @include('admin.fee-tiers._form')
            <button type="submit" class="btn-primary mt-6">Add Fee Tier</button>
        </form>
    </div>
@endsection
