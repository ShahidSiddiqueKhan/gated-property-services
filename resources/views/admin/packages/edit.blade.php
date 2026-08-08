@extends('layouts.admin')

@section('title', 'Edit Package')

@section('content')
    <a href="{{ route('admin.packages.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Packages</a>
    <div class="max-w-3xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.packages.update', $package) }}">
            @csrf
            @method('PUT')
            @include('admin.packages._form')
            <button type="submit" class="btn-primary mt-6">Save Changes</button>
        </form>
    </div>
@endsection
