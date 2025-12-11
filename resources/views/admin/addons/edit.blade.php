@extends('layouts.admin')

@section('title', 'Edit Add-On - SteamZilla')
@section('page-title', 'Edit Add-On')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.addons') }}" class="text-[#45A247] hover:text-[#3a8a3c]">
        <i class="fas fa-arrow-left mr-2"></i>Back to Add-Ons
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Add-On</h2>
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('admin.addons.update', $addon->id) }}" method="POST">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Add-On Name *</label>
                <input type="text" name="name" id="name" value="{{ old('name', $addon->name) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price *</label>
                <input type="number" name="price" id="price" step="0.01" min="0" value="{{ old('price', $addon->price) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                <textarea name="description" id="description" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ old('description', $addon->description) }}</textarea>
            </div>
            
            <div>
                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <input type="text" name="category" id="category" value="{{ old('category', $addon->category) }}" placeholder="e.g., Interior, Exterior, Protection"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="has_quantity" value="1" {{ old('has_quantity', $addon->has_quantity) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm text-gray-700">Allow quantity selection</span>
                </label>
            </div>
            
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $addon->is_active) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm text-gray-700">Active (visible to customers)</span>
                </label>
            </div>
            
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.addons') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-[#45A247] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#3a8a3c] transition">
                    Update Add-On
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
