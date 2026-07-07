<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = BlogPost::published();

        if ($request->filled('type')) {
            $query->where('resource_type', $request->string('type'));
        }

        $posts = $query->latest('published_at')->paginate(6)->withQueryString();

        return view('blog.index', compact('posts'));
    }

    public function show(BlogPost $post): View
    {
        $related = BlogPost::published()->where('id', '!=', $post->id)->latest('published_at')->take(3)->get();

        return view('blog.show', compact('post', 'related'));
    }
}
