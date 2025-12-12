@extends('layouts.admin')

@section('title', 'Manage Countries - SteamZilla')
@section('page-title', 'Countries Management')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-900">Countries</h2>
    <a href="{{ route('admin.countries.create') }}" class="bg-[#45A247] text-white px-6 py-2 rounded-lg font-semibold hover:bg-[#3a8a3c] transition">
        <i class="fas fa-plus mr-2"></i>Create New Country
    </a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cities</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sort Order</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($countries as $country)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $country->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $country->code ?? '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <a href="{{ route('admin.cities', ['country_id' => $country->id]) }}" class="text-[#45A247] hover:text-[#3a8a3c]">
                            {{ $country->cities()->count() }} cities
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $country->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $country->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $country->sort_order }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                        <a href="{{ route('admin.countries.edit', $country->id) }}" class="text-[#45A247] hover:text-[#3a8a3c]">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <form action="{{ route('admin.countries.delete', $country->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure? This will also delete all cities and places in this country.')">
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
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No countries found. <a href="{{ route('admin.countries.create') }}" class="text-[#45A247] hover:underline">Create one</a></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

