<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Addon;
use App\Models\GiftCard;
use App\Models\Country;
use App\Models\City;
use App\Models\Place;
use App\Models\VehicleType;
use App\Models\TimeSlot;
use App\Mail\BookingConfirmation;
use App\Mail\NewBookingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Step 1: Address Collection
    public function step1(Request $request)
    {
        // Clear session if starting fresh
        if ($request->get('reset')) {
            Session::forget('booking_data');
        }

        $bookingData = Session::get('booking_data', []);
        $countries = Country::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $cities = [];
        $places = [];
        
        // If a country is selected, load its cities
        if (isset($bookingData['country_id'])) {
            $cities = City::where('country_id', $bookingData['country_id'])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        }
        
        // If a city is selected, load its places
        if (isset($bookingData['city_id'])) {
            $places = Place::where('city_id', $bookingData['city_id'])
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get();
        }
        
        return view('booking.step1', compact('bookingData', 'countries', 'cities', 'places'));
    }

    public function step1Store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required|exists:countries,id',
            'city_id' => 'required|exists:cities,id',
            'place_id' => 'required|exists:places,id',
            'address' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->route('booking.step1')
                ->withErrors($validator)
                ->withInput();
        }

        $place = Place::with(['city.country'])->findOrFail($request->place_id);
        $city = City::with('country')->findOrFail($request->city_id);
        $country = Country::findOrFail($request->country_id);
        
        $bookingData = Session::get('booking_data', []);
        $bookingData['country_id'] = $request->country_id;
        $bookingData['country_name'] = $country->name;
        $bookingData['city_id'] = $request->city_id;
        $bookingData['city_name'] = $city->name;
        $bookingData['place_id'] = $request->place_id;
        $bookingData['place_name'] = $place->name;
        
        // Build address from place data
        $addressParts = [];
        if ($place->address) {
            $addressParts[] = $place->address;
        }
        if ($place->name) {
            $addressParts[] = $place->name;
        }
        if ($place->city) {
            $addressParts[] = $place->city->name;
        }
        if ($place->city && $place->city->country) {
            $addressParts[] = $place->city->country->name;
        }
        if ($place->zip_code) {
            $addressParts[] = $place->zip_code;
        }
        
        $bookingData['address'] = $request->address ?: implode(', ', $addressParts);
        $bookingData['latitude'] = $place->latitude;
        $bookingData['longitude'] = $place->longitude;
        
        Session::put('booking_data', $bookingData);

        return redirect()->route('booking.step2');
    }

    // API endpoints for dynamic dropdowns
    public function getCities($countryId)
    {
        $cities = City::where('country_id', $countryId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name']);
        
        return response()->json($cities);
    }

    public function getPlaces($cityId)
    {
        $places = Place::where('city_id', $cityId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'address', 'zip_code']);
        
        return response()->json($places);
    }

    // Step 2: Order Info (Vehicle + Services)
    public function step2()
    {
        $bookingData = Session::get('booking_data', []);
        
        if (empty($bookingData['address'])) {
            return redirect()->route('booking.step1')
                ->with('error', 'Please provide an address first.');
        }

        $packages = Package::where('is_active', true)->get();
        $addons = Addon::where('is_active', true)->get();
        $vehicleTypes = VehicleType::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();
        
        return view('booking.step2', compact('packages', 'addons', 'vehicleTypes', 'bookingData'));
    }

    public function step2Store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'package_id' => 'required|exists:packages,id',
            'addons' => 'nullable|array',
            'addons.*' => 'exists:addons,id',
            'addon_quantities' => 'nullable|array',
            'addon_quantities.*' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return redirect()->route('booking.step2')
                ->withErrors($validator)
                ->withInput();
        }

        $package = Package::findOrFail($request->package_id);
        $totalPrice = $package->price;

        // Calculate addon prices
        $selectedAddons = [];
        if ($request->has('addons')) {
            foreach ($request->addons as $addonId) {
                $addon = Addon::findOrFail($addonId);
                $quantity = isset($request->addon_quantities[$addonId]) ? (int)$request->addon_quantities[$addonId] : 1;
                $addonTotal = $addon->price * $quantity;
                $totalPrice += $addonTotal;
                $selectedAddons[] = [
                    'id' => $addon->id,
                    'name' => $addon->name,
                    'price' => $addon->price,
                    'quantity' => $quantity,
                ];
            }
        }

        $vehicleType = VehicleType::findOrFail($request->vehicle_type_id);
        
        $bookingData = Session::get('booking_data', []);
        $bookingData['vehicle_type_id'] = $request->vehicle_type_id;
        $bookingData['vehicle_type'] = $vehicleType->name;
        $bookingData['package_id'] = $request->package_id;
        $bookingData['package_name'] = $package->name;
        $bookingData['package_price'] = $package->price;
        $bookingData['addons'] = $selectedAddons;
        $bookingData['total_price'] = $totalPrice;
        Session::put('booking_data', $bookingData);

        return redirect()->route('booking.step3');
    }

    // Step 3: Date/Time Selection
    public function step3()
    {
        $bookingData = Session::get('booking_data', []);
        
        if (empty($bookingData['package_id'])) {
            return redirect()->route('booking.step2')
                ->with('error', 'Please select a service package first.');
        }

        // Get available time slots from database
        $timeSlots = TimeSlot::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('start_time')
            ->get()
            ->map(function ($slot) {
                return [
                    'value' => $slot->time_value,
                    'label' => $slot->formatted_time,
                ];
            })
            ->toArray();

        return view('booking.step3', compact('timeSlots', 'bookingData'));
    }

    public function step3Store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('booking.step3')
                ->withErrors($validator)
                ->withInput();
        }

        $bookingData = Session::get('booking_data', []);
        $bookingData['booking_date'] = $request->booking_date;
        $bookingData['booking_time'] = $request->booking_time;
        Session::put('booking_data', $bookingData);

        return redirect()->route('booking.step4');
    }

    // Step 4: Payment Method
    public function step4()
    {
        $bookingData = Session::get('booking_data', []);
        
        if (empty($bookingData['booking_date'])) {
            return redirect()->route('booking.step3')
                ->with('error', 'Please select a date and time first.');
        }

        return view('booking.step4', compact('bookingData'));
    }

    public function step4Store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'user_phone' => 'required|string|max:20',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('booking.step4')
                ->withErrors($validator)
                ->withInput();
        }

        $bookingData = Session::get('booking_data', []);
        
        // Validate that we have the required booking data from previous steps
        if (empty($bookingData) || 
            !isset($bookingData['address']) || 
            !isset($bookingData['package_id']) || 
            !isset($bookingData['total_price'])) {
            return redirect()->route('booking.step1')
                ->with('error', 'Your booking session expired. Please start over.');
        }
        
        // Store user info in session for payment processing
        $bookingData['user_name'] = $request->user_name;
        $bookingData['user_email'] = $request->user_email;
        $bookingData['user_phone'] = $request->user_phone;
        $bookingData['notes'] = $request->notes;
        $bookingData['payment_method'] = 'square'; // Always use Square payment
        Session::put('booking_data', $bookingData);

        // Redirect to Square payment page
        return redirect()->route('booking.payment');
    }

    // Legacy methods for backward compatibility
    public function create(Request $request)
    {
        // Redirect to new multi-step flow
        return redirect()->route('booking.step1');
    }

    public function store(Request $request)
    {
        // Legacy method - redirect to step 1
        return redirect()->route('booking.step1');
    }

    public function success($id)
    {
        $booking = Booking::with(['package', 'bookingAddons.addon'])->findOrFail($id);
        return view('booking.success', compact('booking'));
    }
}
