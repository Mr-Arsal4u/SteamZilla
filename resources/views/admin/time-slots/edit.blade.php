@extends('layouts.admin')

@section('title', 'Edit Time Slot - SteamZilla')
@section('page-title', 'Edit Time Slot')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.time-slots') }}" class="text-[#45A247] hover:text-[#3a8a3c]">
        <i class="fas fa-arrow-left mr-2"></i>Back to Time Slots
    </a>
</div>

<div class="bg-white rounded-lg shadow p-6 max-w-3xl">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">Edit Time Slot</h2>
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('admin.time-slots.update', $timeSlot->id) }}" method="POST">
        @csrf
        
        <div class="space-y-6">
            <div>
                <label for="start_time" class="block text-sm font-medium text-gray-700 mb-2">Start Time *</label>
                <input type="time" name="start_time" id="start_time" value="{{ old('start_time', date('H:i', strtotime($timeSlot->start_time))) }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
            </div>
            
            <div>
                <label for="end_time" class="block text-sm font-medium text-gray-700 mb-2">End Time (Optional)</label>
                <input type="time" name="end_time" id="end_time" value="{{ old('end_time', $timeSlot->end_time ? date('H:i', strtotime($timeSlot->end_time)) : '') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <p class="mt-1 text-sm text-gray-500">Leave empty for single time slot (e.g., 8:00 AM only)</p>
            </div>
            
            <div>
                <label for="label" class="block text-sm font-medium text-gray-700 mb-2">Label (Optional)</label>
                <input type="text" name="label" id="label" value="{{ old('label', $timeSlot->label) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]"
                    placeholder="e.g., Morning, Afternoon, Evening">
            </div>
            
            <div>
                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                <input type="number" name="sort_order" id="sort_order" min="0" value="{{ old('sort_order', $timeSlot->sort_order) }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <p class="mt-1 text-sm text-gray-500">Lower numbers appear first in the list</p>
            </div>
            
            <div>
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $timeSlot->is_active) ? 'checked' : '' }} class="mr-2">
                    <span class="text-sm text-gray-700">Active (visible to customers)</span>
                </label>
            </div>
            
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.time-slots') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-[#45A247] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#3a8a3c] transition">
                    Update Time Slot
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

