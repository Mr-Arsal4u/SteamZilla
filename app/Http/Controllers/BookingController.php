<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Addon;
use App\Models\GiftCard;
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
        return view('booking.step1', compact('bookingData'));
    }

    public function step1Store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'place_id' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('booking.step1')
                ->withErrors($validator)
                ->withInput();
        }

        $bookingData = Session::get('booking_data', []);
        $bookingData['address'] = trim($request->address);
        // Latitude, longitude, and place_id are optional - user can enter address manually
        $bookingData['latitude'] = $request->latitude ?: null;
        $bookingData['longitude'] = $request->longitude ?: null;
        $bookingData['place_id'] = $request->place_id ?: null;
        Session::put('booking_data', $bookingData);

        return redirect()->route('booking.step2');
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
        
        return view('booking.step2', compact('packages', 'addons', 'bookingData'));
    }

    public function step2Store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'required|string|max:255',
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

        $bookingData = Session::get('booking_data', []);
        $bookingData['vehicle_type'] = $request->vehicle_type;
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

        // Generate available time slots (8 AM to 6 PM, hourly)
        $timeSlots = [];
        for ($hour = 8; $hour <= 18; $hour++) {
            $timeSlots[] = [
                'value' => str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00',
                'label' => date('g:i A', mktime($hour, 0, 0)),
            ];
        }

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
        
        // Create booking
        $booking = Booking::create([
            'user_name' => $request->user_name,
            'user_email' => $request->user_email,
            'user_phone' => $request->user_phone,
            'address' => $bookingData['address'],
            'latitude' => $bookingData['latitude'] ?? null,
            'longitude' => $bookingData['longitude'] ?? null,
            'place_id' => $bookingData['place_id'] ?? null,
            'vehicle_type' => $bookingData['vehicle_type'],
            'booking_date' => $bookingData['booking_date'],
            'booking_time' => $bookingData['booking_time'],
            'package_id' => $bookingData['package_id'],
            'status' => 'pending',
            'notes' => $request->notes,
            'total_price' => $bookingData['total_price'],
            'payment_method' => null, // Payment will be collected by employee
            'gift_card_id' => null,
            'gift_card_discount' => 0,
        ]);

        // Attach addons
        if (!empty($bookingData['addons'])) {
            foreach ($bookingData['addons'] as $addonData) {
                $booking->bookingAddons()->create([
                    'addon_id' => $addonData['id'],
                    'quantity' => $addonData['quantity'],
                    'price_at_booking' => $addonData['price'],
                ]);
            }
        }

        // Send email notifications
        try {
            Mail::to($booking->user_email)->send(new BookingConfirmation($booking));
            if (config('mail.admin_email')) {
                Mail::to(config('mail.admin_email'))->send(new NewBookingNotification($booking));
            }
        } catch (\Exception $e) {
            Log::error('Failed to send booking emails: ' . $e->getMessage());
        }

        // Clear session
        Session::forget('booking_data');

        return redirect()->route('booking.success', $booking->id)
            ->with('success', 'Your booking has been confirmed! Our employee will contact you for payment.');
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
