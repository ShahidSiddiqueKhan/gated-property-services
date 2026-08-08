@extends('layouts.admin')

@section('title', 'New Renovation Project')

@section('content')
    <a href="{{ route('admin.renovations.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Renovation Projects</a>
    <div class="max-w-3xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.renovations.store') }}">
            @csrf
            @include('admin.renovations._form')
            <button type="submit" class="btn-primary mt-6">Create Project</button>
        </form>
    </div>
@endsection
