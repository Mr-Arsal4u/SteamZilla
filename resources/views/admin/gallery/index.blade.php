@extends('layouts.admin')

@section('title', 'Gallery Management - SteamZilla')
@section('page-title', 'Gallery Management')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp

<!-- Upload Form -->
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Upload New Image</h3>
    <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Image *</label>
                <input type="file" name="image" accept="image/*" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                <input type="text" name="title" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select name="category" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                    <option value="home">Home</option>
                    <option value="gallery">Gallery</option>
                    <option value="hero">Hero Background</option>
                </select>
            </div>
        </div>
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
            <textarea name="description" rows="2" 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]"></textarea>
        </div>
        <div class="mt-4">
            <button type="submit" class="bg-[#45A247] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#3a8a3c] transition">
                Upload Image
            </button>
        </div>
    </form>
</div>

<!-- Gallery Grid -->
@if($images && $images->count() > 0)
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">Gallery Images ({{ $images->count() }})</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($images as $image)
        <div class="bg-gray-50 rounded-lg shadow overflow-hidden">
            <div class="relative">
                <img src="{{ Storage::url($image->image_path) }}" alt="{{ $image->title }}" class="w-full h-48 object-cover">
                @if(!$image->is_active)
                    <div class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-xs">Inactive</div>
                @endif
            </div>
            <div class="p-4">
                <h4 class="font-semibold text-gray-900 mb-1">{{ $image->title ?: 'Untitled' }}</h4>
                @if($image->description)
                    <p class="text-sm text-gray-600 mb-2">{{ Str::limit($image->description, 50) }}</p>
                @endif
                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                    <span class="px-2 py-1 bg-gray-200 rounded">{{ $image->category }}</span>
                    <span>Order: {{ $image->order }}</span>
                </div>
                <form action="{{ route('admin.gallery.update', $image->id) }}" method="POST" enctype="multipart/form-data" class="mb-2">
                    @csrf
                    <input type="text" name="title" value="{{ $image->title }}" placeholder="Title" 
                        class="w-full px-2 py-1 text-sm border border-gray-300 rounded mb-2">
                    <input type="number" name="order" value="{{ $image->order }}" placeholder="Order" 
                        class="w-full px-2 py-1 text-sm border border-gray-300 rounded mb-2">
                    <div class="flex items-center mb-2">
                        <input type="checkbox" name="is_active" value="1" {{ $image->is_active ? 'checked' : '' }} class="mr-2">
                        <label class="text-sm text-gray-700">Active</label>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                        Update
                    </button>
                </form>
                <form action="{{ route('admin.gallery.delete', $image->id) }}" method="POST" onsubmit="return confirm('Delete this image?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@else
<div class="bg-white rounded-lg shadow p-12 text-center">
    <i class="fas fa-images text-6xl text-gray-400 mb-4"></i>
    <p class="text-gray-500 text-lg">No images in gallery yet</p>
    <p class="text-gray-400 text-sm mt-2">Upload your first image above</p>
</div>
@endif
@endsection
