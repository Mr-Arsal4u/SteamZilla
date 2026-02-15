@extends('layouts.app')

@section('title', 'Blog - SteamZilla')
@section('description', 'Tips, guides, and updates about steam car detailing and eco-friendly cleaning from SteamZilla.')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight">Blog</h1>
            <p class="mt-2 text-gray-600">Guides, tips, and updates from our steam detailing team.</p>
        </div>

        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('blog.index') }}" class="px-3 py-1.5 rounded-full text-sm border {{ empty($categorySlug) ? 'bg-[#45A247] text-white border-[#45A247]' : 'text-[#45A247] border-[#45A247] hover:bg-green-50' }}">All</a>
                @foreach($categories as $cat)
                    <a href="{{ route('blog.index', ['category' => $cat->slug]) }}" class="px-3 py-1.5 rounded-full text-sm border {{ $categorySlug === $cat->slug ? 'bg-[#45A247] text-white border-[#45A247]' : 'text-[#45A247] border-[#45A247] hover:bg-green-50' }}">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>

        <div>
                @if($posts->count() === 0)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center text-gray-600">
                        No posts available yet.
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                        @foreach($posts as $post)
                        <article class="group flex flex-col h-full rounded-2xl border border-gray-200 overflow-hidden bg-white shadow-md hover:shadow-xl hover:-translate-y-0.5 transition">
                            <a href="{{ route('blog.show', $post->slug) }}" class="block">
                                <div class="relative w-full h-64 bg-gray-100 overflow-hidden">
                                    @if($post->featured_image)
                                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105 rounded-t-2xl">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400 text-sm">No image</div>
                                    @endif
                                </div>
                            </a>
                            <div class="flex flex-col flex-1 p-6">
                                <div class="text-xs text-gray-500 mb-3 flex items-center flex-wrap gap-2">
                                    @if($post->category)
                                        <span class="px-2 py-1 bg-green-50 text-[#45A247] rounded">{{ $post->category->name }}</span>
                                    @endif
                                    @if($post->published_at)
                                        <span>{{ $post->published_at->format('M d, Y') }}</span>
                                    @endif
                                    @if($post->reading_time)
                                        <span>• {{ $post->reading_time }} min read</span>
                                    @endif
                                </div>
                                <h3 class="text-2xl font-semibold text-gray-900 mb-3 leading-snug">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-[#3a8a3c]">{{ $post->title }}</a>
                                </h3>
                                <p class="text-gray-600 text-base mb-5" style="-webkit-line-clamp:4; display:-webkit-box; -webkit-box-orient:vertical; overflow:hidden;">
                                    {{ $post->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 180) }}
                                </p>
                                <div class="mt-auto">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center text-[#45A247] hover:text-[#3a8a3c] font-semibold text-base">
                                        Read more <span class="ml-1">→</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                        @endforeach
                    </div>
                    <div class="mt-8">
                        {{ $posts->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
