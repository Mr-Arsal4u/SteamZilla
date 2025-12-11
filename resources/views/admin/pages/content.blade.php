@extends('layouts.admin')

@section('title', ucfirst($page) . ' Page Content - SteamZilla')
@section('page-title', ucfirst($page) . ' Page Content Management')

@section('content')
@php
use App\Models\PageContent;
use Illuminate\Support\Facades\Storage;
@endphp

<div class="mb-4">
    <div class="flex space-x-2">
        <a href="{{ route('admin.pages.content', 'home') }}" 
            class="px-4 py-2 rounded-lg {{ $page === 'home' ? 'bg-[#45A247] text-white' : 'bg-gray-200 text-gray-700' }}">
            Home
        </a>
        <a href="{{ route('admin.pages.content', 'about') }}" 
            class="px-4 py-2 rounded-lg {{ $page === 'about' ? 'bg-[#45A247] text-white' : 'bg-gray-200 text-gray-700' }}">
            About
        </a>
    </div>
</div>

<form action="{{ route('admin.pages.update', $page) }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    @if($page === 'home')
    <!-- Hero Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Hero Section</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Background Image</label>
                @php
                    $heroBg = PageContent::getContent('home', 'hero', 'background_image');
                @endphp
                @if($heroBg)
                    <div class="mb-2">
                        <img src="{{ Storage::url($heroBg) }}" alt="Hero Background" class="h-40 w-auto rounded border border-gray-300">
                    </div>
                @endif
                <input type="file" name="hero_background_image" accept="image/*" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <p class="mt-1 text-sm text-gray-500">Current: {{ $heroBg ? 'Image uploaded' : 'No image' }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
                <input type="text" name="hero_title" value="{{ PageContent::getContent('home', 'hero', 'title', 'UNLEASH THE POWER OF CLEAN.') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Subtitle</label>
                <textarea name="hero_subtitle" rows="2" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ PageContent::getContent('home', 'hero', 'subtitle', '') }}</textarea>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Benefits Section</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Section Title</label>
                <input type="text" name="benefits_title" value="{{ PageContent::getContent('home', 'benefits', 'title', 'WHY STEAMZILLA?') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Section Description</label>
                <textarea name="benefits_description" rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ PageContent::getContent('home', 'benefits', 'description', '') }}</textarea>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">How It Works Section</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Section Title</label>
                <input type="text" name="how_it_works_title" value="{{ PageContent::getContent('home', 'how_it_works', 'title', 'HOW IT WORKS') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Section Description</label>
                <textarea name="how_it_works_description" rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ PageContent::getContent('home', 'how_it_works', 'description', '') }}</textarea>
            </div>
        </div>
    </div>
    @endif

    @if($page === 'about')
    <!-- About Content -->
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">About Page Content</h3>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Page Title</label>
                <input type="text" name="about_title" value="{{ PageContent::getContent('about', 'main', 'title', 'About SteamZilla') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Main Description</label>
                <textarea name="about_description" rows="6" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ PageContent::getContent('about', 'main', 'description', '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mission Statement</label>
                <textarea name="about_mission" rows="4" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ PageContent::getContent('about', 'main', 'mission', '') }}</textarea>
            </div>
        </div>
    </div>
    @endif

    <!-- Show existing content from database -->
    @if($contents->count() > 0)
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Content Sections</h3>
        @foreach($contents as $section => $sectionContents)
            @if(!in_array($section, ['hero', 'benefits', 'how_it_works', 'main']))
            <div class="mb-6 pb-6 border-b border-gray-200 last:border-0">
                <h4 class="font-semibold text-gray-900 mb-4 capitalize">{{ str_replace('_', ' ', $section) }}</h4>
                @foreach($sectionContents as $content)
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ str_replace('_', ' ', $content->key) }}</label>
                    @if($content->type === 'image')
                        @if($content->value)
                            <div class="mb-2">
                                <img src="{{ Storage::url($content->value) }}" alt="{{ $content->key }}" class="h-32 w-auto rounded border border-gray-300">
                            </div>
                        @endif
                        <input type="file" name="{{ $section }}_{{ $content->key }}" accept="image/*" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                    @elseif($content->type === 'html')
                        <textarea name="{{ $section }}_{{ $content->key }}" rows="6" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247] font-mono text-sm">{{ $content->value }}</textarea>
                    @else
                        <textarea name="{{ $section }}_{{ $content->key }}" rows="3" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ $content->value }}</textarea>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
        @endforeach
    </div>
    @endif

    <div class="flex justify-end">
        <button type="submit" class="bg-[#45A247] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#3a8a3c] transition">
            Save Changes
        </button>
    </div>
</form>
@endsection
