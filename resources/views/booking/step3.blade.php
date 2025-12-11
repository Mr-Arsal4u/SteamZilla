@extends('layouts.app')

@section('title', 'Select Date & Time - SteamZilla')

@section('content')
<div class="min-h-screen bg-white py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Indicator -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="ml-2 text-sm font-semibold text-[#45A247]">Address</span>
                </div>
                <div class="w-16 h-1 bg-[#45A247]"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold">
                        <i class="fas fa-check text-sm"></i>
                    </div>
                    <span class="ml-2 text-sm font-semibold text-[#45A247]">Order Info</span>
                </div>
                <div class="w-16 h-1 bg-[#45A247]"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold">3</div>
                    <span class="ml-2 text-sm font-semibold text-[#45A247]">Date/Time</span>
                </div>
                <div class="w-16 h-1 bg-gray-200"></div>
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold">4</div>
                    <span class="ml-2 text-sm font-semibold text-gray-600">Payment</span>
                </div>
            </div>
        </div>

        <!-- Step 3 Content -->
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Select Date & Time</h2>

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('booking.step3.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Calendar -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-900 mb-4">Select Date *</label>
                        <div class="border-2 border-gray-300 rounded-lg p-4">
                            <input 
                                type="date" 
                                name="booking_date" 
                                id="booking_date"
                                value="{{ old('booking_date', $bookingData['booking_date'] ?? '') }}"
                                min="{{ date('Y-m-d') }}"
                                required
                                class="w-full px-4 py-3 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]"
                                onchange="checkAvailability()">
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Select your preferred service date</p>
                    </div>

                    <!-- Time Slots -->
                    <div>
                        <label class="block text-lg font-semibold text-gray-900 mb-4">Select Time *</label>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach($timeSlots as $slot)
                                <label class="time-slot-card cursor-pointer">
                                    <input 
                                        type="radio" 
                                        name="booking_time" 
                                        value="{{ $slot['value'] }}"
                                        {{ old('booking_time', $bookingData['booking_time'] ?? '') === $slot['value'] ? 'checked' : '' }}
                                        required 
                                        class="hidden">
                                    <div class="border-2 border-gray-300 rounded-lg p-4 text-center hover:border-[#45A247] transition">
                                        <div class="font-semibold text-gray-900">{{ $slot['label'] }}</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                        <p class="text-sm text-gray-600 mt-2">Available time slots</p>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-10">
                    <a href="{{ route('booking.step2') }}" class="bg-gray-200 text-gray-700 px-8 py-4 rounded-full text-lg font-bold hover:bg-gray-300 transition">
                        Back
                    </a>
                    <button type="submit" class="bg-[#45A247] text-white px-10 py-4 rounded-full text-lg font-bold hover:bg-[#3a8a3c] transition transform hover:scale-105 shadow-lg">
                        Next
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function checkAvailability() {
        // In production, make AJAX call to check availability
        const selectedDate = document.getElementById('booking_date').value;
        // For now, just enable all time slots
    }

    // Style selected time slot
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.time-slot-card input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.time-slot-card > div').forEach(card => {
                    card.classList.remove('border-[#45A247]', 'bg-green-50');
                });
                if (this.checked) {
                    this.closest('.time-slot-card').querySelector('div').classList.add('border-[#45A247]', 'bg-green-50');
                }
            });
        });
    });
</script>
@endsection

