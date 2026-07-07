@extends('layouts.admin')

@section('title', 'Add Testimonial')

@section('content')
    <a href="{{ route('admin.testimonials.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Testimonials</a>
    <div class="max-w-2xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.testimonials.store') }}">
            @csrf
            @include('admin.testimonials._form')
            <button type="submit" class="btn-primary mt-6">Add Testimonial</button>
        </form>
    </div>
@endsection
