@extends('layouts.admin')

@section('title', 'Edit Service Catalog Item')

@section('content')
    <a href="{{ route('admin.service-catalog.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Service Catalog</a>
    <div class="max-w-3xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.service-catalog.update', $item) }}">
            @csrf
            @method('PUT')
            @include('admin.service-catalog._form')
            <button type="submit" class="btn-primary mt-6">Save Changes</button>
        </form>
    </div>
@endsection
