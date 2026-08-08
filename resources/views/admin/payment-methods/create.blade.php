@extends('layouts.admin')

@section('title', 'Add Payment Method')

@section('content')
    <a href="{{ route('admin.payment-methods.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Payment Methods</a>
    <div class="max-w-3xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.payment-methods.store') }}">
            @csrf
            @include('admin.payment-methods._form')
            <button type="submit" class="btn-primary mt-6">Add Payment Method</button>
        </form>
    </div>
@endsection
