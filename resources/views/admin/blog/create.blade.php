@extends('layouts.admin')

@section('title', 'New Post')

@section('content')
    <a href="{{ route('admin.blog.index') }}" class="text-sm text-ink-500 hover:text-brand-600">&larr; Back to Blog</a>
    <div class="max-w-3xl mt-4 card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.blog.store') }}" enctype="multipart/form-data">
            @csrf
            @include('admin.blog._form')
            <button type="submit" class="btn-primary mt-6">Create Post</button>
        </form>
    </div>
@endsection
