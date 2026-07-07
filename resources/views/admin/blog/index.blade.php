@extends('layouts.admin')

@section('title', 'Blog & Resources')

@section('content')

    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-ink-500">Manage articles, guides, videos and downloadable resources.</p>
        <a href="{{ route('admin.blog.create') }}" class="btn-primary !py-2.5 !px-4 text-sm">New Post</a>
    </div>

    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-ink-50 text-ink-500 text-xs uppercase">
                    <tr>
                        <th class="text-left px-6 py-3">Title</th>
                        <th class="text-left px-6 py-3">Type</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-left px-6 py-3">Published</th>
                        <th class="text-right px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100">
                    @forelse ($posts as $post)
                        <tr class="hover:bg-ink-50/50">
                            <td class="px-6 py-4 font-medium text-ink-900">{{ $post->title }}</td>
                            <td class="px-6 py-4 text-ink-600 capitalize">{{ $post->resource_type }}</td>
                            <td class="px-6 py-4">
                                <span class="badge {{ $post->published_at ? 'bg-emerald-100 text-emerald-700' : 'bg-ink-100 text-ink-600' }}">{{ $post->published_at ? 'Published' : 'Draft' }}</span>
                            </td>
                            <td class="px-6 py-4 text-ink-600">{{ $post->published_at?->format('M d, Y') ?? '—' }}</td>
                            <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                                <a href="{{ route('admin.blog.edit', $post) }}" class="text-brand-600 font-semibold text-xs hover:text-brand-700">Edit</a>
                                <form method="POST" action="{{ route('admin.blog.destroy', $post) }}" class="inline" onsubmit="return confirm('Delete this post?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-ink-400 font-semibold text-xs hover:text-brand-600">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-10 text-center text-ink-500">No posts yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">{{ $posts->links() }}</div>

@endsection
