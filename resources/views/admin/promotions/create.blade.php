@extends('layouts.admin')

@section('title', 'Add Promotion')

@section('content')
    <a href="{{ route('admin.promotions.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Promotions</a>
    <div class="max-w-2xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.promotions.store') }}">
            @csrf
            @include('admin.promotions._form')
            <button type="submit" class="btn-primary mt-6">Create Promotion</button>
        </form>
    </div>
@endsection
