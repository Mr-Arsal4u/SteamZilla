@extends('layouts.app')

@section('title', 'Select Date & Time - SteamZilla')

@section('content')
<div class="min-h-screen bg-white py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Progress Indicator -->
        <div class="mb-6 sm:mb-8">
            <div class="flex items-center justify-center space-x-2 sm:space-x-4 overflow-x-auto pb-2">
                <div class="flex items-center flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold text-xs sm:text-sm">
                        <i class="fas fa-check text-xs sm:text-sm"></i>
                    </div>
                    <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-semibold text-[#45A247] hidden sm:inline">Address</span>
                </div>
                <div class="w-8 sm:w-16 h-1 bg-[#45A247] flex-shrink-0"></div>
                <div class="flex items-center flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold text-xs sm:text-sm">
                        <i class="fas fa-check text-xs sm:text-sm"></i>
                    </div>
                    <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-semibold text-[#45A247] hidden sm:inline">Order Info</span>
                </div>
                <div class="w-8 sm:w-16 h-1 bg-[#45A247] flex-shrink-0"></div>
                <div class="flex items-center flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-[#45A247] text-white flex items-center justify-center font-bold text-xs sm:text-sm">3</div>
                    <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-semibold text-[#45A247] hidden sm:inline">Date/Time</span>
                </div>
                <div class="w-8 sm:w-16 h-1 bg-gray-200 flex-shrink-0"></div>
                <div class="flex items-center flex-shrink-0">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-xs sm:text-sm">4</div>
                    <span class="ml-1 sm:ml-2 text-xs sm:text-sm font-semibold text-gray-600 hidden sm:inline">Payment</span>
                </div>
            </div>
        </div>

        <!-- Step 3 Content -->
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 md:p-8 lg:p-12">
            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6 sm:mb-8">Select Date & Time</h2>

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
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 sm:gap-3">
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

