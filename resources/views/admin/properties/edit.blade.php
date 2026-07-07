@extends('layouts.admin')

@section('title', 'Edit Property')

@section('content')

    <a href="{{ route('admin.properties.show', $property) }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to {{ $property->title }}</a>

    <div class="max-w-4xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.properties.update', $property) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('admin.properties._form')
            <button type="submit" class="btn-primary mt-6">Save Changes</button>
        </form>
    </div>

@endsection
