@extends('layouts.admin')

@section('title', 'Edit Renovation Project')

@section('content')
    <a href="{{ route('admin.renovations.show', $project) }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Project</a>
    <div class="max-w-3xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.renovations.update', $project) }}">
            @csrf
            @method('PUT')
            @include('admin.renovations._form')
            <button type="submit" class="btn-primary mt-6">Save Changes</button>
        </form>
    </div>
@endsection
