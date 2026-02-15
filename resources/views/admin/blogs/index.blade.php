@extends('layouts.admin')

@section('title', 'Manage Blog Posts - SteamZilla')
@section('page-title', 'Blog Posts Management')

@section('content')
<div class="mb-4 sm:mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Blog Posts</h2>
    <a href="{{ route('admin.blogs.create') }}" class="bg-[#45A247] text-white px-4 sm:px-6 py-2 rounded-lg font-semibold hover:bg-[#3a8a3c] transition text-sm sm:text-base w-full sm:w-auto text-center">
        <i class="fas fa-plus mr-2"></i>Create New Post
    </a>
    </div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="lg:hidden divide-y divide-gray-200">
        @forelse($blogs as $post)
        <div class="p-4">
            <div class="flex justify-between items-start mb-2">
                <h3 class="font-semibold text-gray-900">{{ $post->title }}</h3>
                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $post->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    {{ $post->is_published ? 'Published' : 'Draft' }}
                </span>
            </div>
            <div class="text-sm text-gray-600 space-y-1 mb-3">
                <p><span class="font-medium">Category:</span> {{ $post->category?->name ?? '—' }}</p>
                <p><span class="font-medium">Slug:</span> {{ $post->slug }}</p>
            </div>
            <div class="flex space-x-3 text-sm">
                <a href="{{ route('admin.blogs.edit', $post->id) }}" class="text-[#45A247] hover:text-[#3a8a3c]">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form action="{{ route('admin.blogs.delete', $post->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="p-6 text-center text-gray-500">No blog posts found</div>
        @endforelse
    </div>

    <div class="hidden lg:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Published</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($blogs as $post)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $post->title }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $post->category?->name ?? '—' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $post->is_published ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $post->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $post->published_at ? $post->published_at->format('M d, Y') : '—' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                        <a href="{{ route('admin.blogs.edit', $post->id) }}" class="text-[#45A247] hover:text-[#3a8a3c]">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.blogs.delete', $post->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No blog posts found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

