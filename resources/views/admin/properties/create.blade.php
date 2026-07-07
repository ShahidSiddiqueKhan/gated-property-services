@extends('layouts.admin')

@section('title', 'Add Property')

@section('content')

    <a href="{{ route('admin.properties.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Properties</a>

    <div class="max-w-4xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.properties.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.properties._form')
            <button type="submit" class="btn-primary mt-6">Create Property</button>
        </form>
    </div>

@endsection
