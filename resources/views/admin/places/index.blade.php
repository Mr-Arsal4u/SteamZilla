@extends('layouts.admin')

@section('title', 'Manage Places - SteamZilla')
@section('page-title', 'Places Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-900">Places</h2>
    <a href="{{ route('admin.places.create') }}" class="bg-[#45A247] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#3a8a3c] transition">
        <i class="fas fa-plus mr-2"></i>Create New Place
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow p-4 mb-6">
    <form method="GET" action="{{ route('admin.places') }}" class="flex items-center space-x-4 flex-wrap">
        <div>
            <label for="country_id" class="text-sm font-medium text-gray-700">Filter by Country:</label>
            <select name="country_id" id="country_id" onchange="this.form.submit()" class="ml-2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <option value="">All Countries</option>
                @foreach($countries as $country)
                    <option value="{{ $country->id }}" {{ request('country_id') == $country->id ? 'selected' : '' }}>
                        {{ $country->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="city_id" class="text-sm font-medium text-gray-700">Filter by City:</label>
            <select name="city_id" id="city_id" onchange="this.form.submit()" class="ml-2 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <option value="">All Cities</option>
                @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                        {{ $city->name }}, {{ $city->country->name }}
                    </option>
                @endforeach
            </select>
        </div>
        @if(request('country_id') || request('city_id'))
            <a href="{{ route('admin.places') }}" class="text-sm text-gray-600 hover:text-[#45A247]">Clear Filters</a>
        @endif
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($places as $place)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $place->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $place->city->name }}, {{ $place->city->country->name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        <div class="max-w-xs truncate" title="{{ $place->address }}">
                            {{ $place->address ?? '-' }}
                        </div>
                        @if($place->zip_code)
                            <div class="text-xs text-gray-500">ZIP: {{ $place->zip_code }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $place->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $place->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $place->sort_order }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                        <a href="{{ route('admin.places.edit', $place->id) }}" class="text-[#45A247] hover:text-[#3a8a3c]">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.places.delete', $place->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
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
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No places found. <a href="{{ route('admin.places.create') }}" class="text-[#45A247] hover:underline">Create one</a></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

