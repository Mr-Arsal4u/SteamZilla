<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $categorySlug = $request->query('category');
        $query = BlogPost::with('category')->where('is_published', true)->orderBy('published_at', 'desc')->orderBy('created_at', 'desc');

        if ($categorySlug) {
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)->where('is_active', true);
            });
        }

        $posts = $query->paginate(9);
        $categories = BlogCategory::where('is_active', true)->orderBy('name')->get();

        return view('blog.index', compact('posts', 'categories', 'categorySlug'));
    }

    public function show($slug)
    {
        $post = BlogPost::with('category')->where('slug', $slug)->where('is_published', true)->firstOrFail();
        $related = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->when($post->category_id, fn($q) => $q->where('category_id', $post->category_id))
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();
        return view('blog.show', compact('post', 'related'));
    }
}

