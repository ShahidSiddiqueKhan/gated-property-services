@extends('layouts.admin')

@section('title', 'Testimonials')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-ink-500">Manage client testimonials shown across the public site.</p>
        <a href="{{ route('admin.testimonials.create') }}" class="btn-primary !py-2.5 !px-4 text-sm">Add Testimonial</a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($testimonials as $t)
            <div class="card p-6">
                <div class="flex gap-0.5 text-brand-500 mb-2">
                    @for ($i = 0; $i < $t->rating; $i++)<x-icon name="star" class="w-4 h-4 fill-current" />@endfor
                </div>
                <p class="text-sm text-ink-600">&ldquo;{{ \Illuminate\Support\Str::limit($t->content, 120) }}&rdquo;</p>
                <div class="mt-4 flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-sm text-ink-900">{{ $t->name }}</div>
                        <div class="text-xs text-ink-500">{{ $t->role }}</div>
                    </div>
                    @if ($t->is_featured)<span class="badge bg-emerald-100 text-emerald-700">Featured</span>@endif
                </div>
                <div class="flex gap-3 mt-4 pt-4 border-t border-ink-100">
                    <a href="{{ route('admin.testimonials.edit', $t) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">Edit</a>
                    <form method="POST" action="{{ route('admin.testimonials.destroy', $t) }}" onsubmit="return confirm('Delete this testimonial?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-ink-400 font-semibold text-xs hover:text-brand-600">Delete</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-ink-500 col-span-3">No testimonials yet.</p>
        @endforelse
    </div>

    <div class="mt-6">{{ $testimonials->links() }}</div>

@endsection
