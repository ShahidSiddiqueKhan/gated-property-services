<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $posts = BlogPost::latest()->paginate(15);

        return view('admin.blog.index', compact('posts'));
    }

    public function create(): View
    {
        return view('admin.blog.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePost($request);

        $validated['slug'] = Str::slug($validated['title']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blog', 'public');
        }

        if ($request->hasFile('resource_file')) {
            $validated['resource_file'] = $request->file('resource_file')->store('blog/resources', 'public');
        }

        $post = BlogPost::create($validated);

        AuditLog::record($request->user(), 'Published blog post', $post, "Created: {$post->title}");

        return redirect()->route('admin.blog.index')->with('success', 'Post created.');
    }

    public function edit(BlogPost $post): View
    {
        return view('admin.blog.edit', compact('post'));
    }

    public function update(Request $request, BlogPost $post): RedirectResponse
    {
        $validated = $this->validatePost($request);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('blog', 'public');
        }

        if ($request->hasFile('resource_file')) {
            $validated['resource_file'] = $request->file('resource_file')->store('blog/resources', 'public');
        }

        $post->update($validated);

        AuditLog::record($request->user(), 'Updated blog post', $post, "Updated: {$post->title}");

        return redirect()->route('admin.blog.index')->with('success', 'Post updated.');
    }

    public function destroy(Request $request, BlogPost $post): RedirectResponse
    {
        AuditLog::record($request->user(), 'Deleted blog post', null, "Deleted: {$post->title}");

        $post->delete();

        return back()->with('success', 'Post deleted.');
    }

    protected function validatePost(Request $request): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:255'],
            'resource_type' => ['required', 'in:article,guide,video,download'],
            'excerpt' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'image', 'max:5120'],
            'resource_file' => ['nullable', 'file', 'max:10240'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $validated['author'] = $validated['author'] ?: 'GATED Property Services';
        $validated['published_at'] = $request->boolean('is_published') ? now() : null;
        unset($validated['is_published']);

        return $validated;
    }
}
