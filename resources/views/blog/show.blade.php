@extends('layouts.app')

@section('title', $post->meta_title ?: $post->title . ' - SteamZilla')
@section('description', $post->meta_description ?: ($post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 150)))

@section('content')
<div class="min-h-screen bg-white py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <nav class="text-sm mb-4">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-[#45A247]">Home</a>
            <span class="text-gray-400 mx-2">/</span>
            <a href="{{ route('blog.index') }}" class="text-gray-500 hover:text-[#45A247]">Blog</a>
            <span class="text-gray-400 mx-2">/</span>
            <span class="text-gray-700">{{ $post->title }}</span>
        </nav>

        <article class="bg-white">
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-3">{{ $post->title }}</h1>
            <div class="text-sm text-gray-500 mb-6">
                @if($post->category)
                    <span class="px-2 py-1 bg-green-50 text-[#45A247] rounded">{{ $post->category->name }}</span>
                @endif
                @if($post->published_at)
                    <span class="ml-2">{{ $post->published_at->format('M d, Y') }}</span>
                @endif
                @if($post->reading_time)
                    <span class="ml-2">• {{ $post->reading_time }} min read</span>
                @endif
            </div>

            @if($post->featured_image)
                <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-64 object-cover rounded-lg border mb-6">
            @endif

            <div class="prose max-w-none">
                {!! $post->content !!}
            </div>
        </article>

        @if(is_array($post->gallery_images) && count($post->gallery_images))
        <div class="mt-10">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Gallery</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @foreach($post->gallery_images as $img)
                    <a href="{{ Storage::url($img) }}" target="_blank" rel="noopener noreferrer" class="block group">
                        <img src="{{ Storage::url($img) }}" alt="{{ $post->title }} image" class="w-full h-40 sm:h-48 object-cover rounded-lg border transition-transform duration-300 group-hover:scale-[1.01]">
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($related->count())
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Related Posts</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach($related as $item)
                <article class="border border-gray-200 rounded-lg overflow-hidden hover:shadow transition">
                    @if($item->featured_image)
                        <img src="{{ Storage::url($item->featured_image) }}" alt="{{ $item->title }}" class="w-full h-36 object-cover">
                    @endif
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">
                            <a href="{{ route('blog.show', $item->slug) }}" class="hover:text-[#3a8a3c]">{{ $item->title }}</a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-3">{{ $item->excerpt ?? Str::limit(strip_tags($item->content), 100) }}</p>
                        <a href="{{ route('blog.show', $item->slug) }}" class="inline-block text-[#45A247] hover:text-[#3a8a3c] font-semibold text-sm">
                            Read more →
                        </a>
                    </div>
                </article>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
