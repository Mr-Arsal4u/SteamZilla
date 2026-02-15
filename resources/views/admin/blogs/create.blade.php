@extends('layouts.admin')

@section('title', 'Create Blog Post - SteamZilla')
@section('page-title', 'Create Blog Post')

@section('content')
<div class="mb-4 sm:mb-6">
    <a href="{{ route('admin.blogs') }}" class="text-[#45A247] hover:text-[#3a8a3c] text-sm sm:text-base">
        <i class="fas fa-arrow-left mr-2"></i>Back to Blog Posts
    </a>
    </div>

<div class="bg-white rounded-lg shadow p-4 sm:p-6 max-w-4xl mx-auto">
    <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Create New Blog Post</h2>
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">Slug</label>
                    <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]" placeholder="auto-generated from title if empty">
                </div>
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category_id" id="category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                        <option value="">— None —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div>
                <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">Excerpt</label>
                <textarea name="excerpt" id="excerpt" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]" placeholder="Short summary shown in listings">{{ old('excerpt') }}</textarea>
            </div>
            
            <div>
                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                <textarea name="content" id="content" rows="10"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ old('content') }}</textarea>
                <p class="text-xs text-gray-500 mt-1">Rich text editor enabled</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Featured Image</label>
                    <input type="file" name="featured_image" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gallery Images</label>
                    <input type="file" name="gallery_images[]" multiple accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                    <p class="text-xs text-gray-500 mt-1">Optional multiple images</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Publish</label>
                    <input type="hidden" name="is_published" value="0">
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="is_published" value="1" class="rounded border-gray-300 text-[#45A247]" {{ old('is_published') ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Published</span>
                    </label>
                </div>
                <div>
                    <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Publish Date</label>
                    <input type="datetime-local" name="published_at" id="published_at" value="{{ old('published_at') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Feature</label>
                    <input type="hidden" name="is_featured" value="0">
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="is_featured" value="1" class="rounded border-gray-300 text-[#45A247]" {{ old('is_featured') ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700">Featured</span>
                    </label>
                </div>
            </div>
            
            <div class="border-t pt-4">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">SEO</h3>
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                    </div>
                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                        <textarea name="meta_description" id="meta_description" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ old('meta_description') }}</textarea>
                    </div>
                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                        <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]" placeholder="comma-separated">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-6">
            <button type="submit" class="bg-[#45A247] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#3a8a3c] transition">
                Create Post
            </button>
        </div>
    </form>
</div>

<!-- CKEditor 5 Classic Build (no API key required) -->
<script src="https://cdn.ckeditor.com/ckeditor5/41.3.1/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (window.ClassicEditor) {
        ClassicEditor.create(document.querySelector('#content'), {
            toolbar: [
                'undo','redo','|','heading','|',
                'bold','italic','underline','link','|',
                'bulletedList','numberedList','outdent','indent','|',
                'blockQuote','insertTable','codeBlock','|','removeFormat'
            ],
            table: { contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells'] }
        }).catch(console.error);
    }
});
</script>
@endsection
