@extends('layouts.admin')

@section('title', 'Add Service')

@section('content')
    <a href="{{ route('admin.services.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Services</a>
    <div class="max-w-3xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.services.store') }}">
            @csrf
            @include('admin.services._form')
            <button type="submit" class="btn-primary mt-6">Create Service</button>
        </form>
    </div>
@endsection
