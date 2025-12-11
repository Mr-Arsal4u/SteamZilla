<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ContactSubmission;
use App\Models\Package;
use App\Models\Addon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        // User's bookings
        $bookings = Booking::where('user_email', $user->email)
            ->with(['package', 'bookingAddons.addon'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // User's contact submissions
        $contactSubmissions = ContactSubmission::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get() ?? collect([]);

        // Statistics
        $stats = [
            'total_bookings' => Booking::where('user_email', $user->email)->count(),
            'pending_bookings' => Booking::where('user_email', $user->email)->where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('user_email', $user->email)->where('status', 'confirmed')->count(),
            'completed_bookings' => Booking::where('user_email', $user->email)->where('status', 'completed')->count(),
            'total_spent' => Booking::where('user_email', $user->email)
                ->where('status', '!=', 'cancelled')
                ->sum('total_price'),
        ];

        return view('user.dashboard', compact('user', 'bookings', 'contactSubmissions', 'stats'));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check current password if changing password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->withErrors(['current_password' => 'Current password is incorrect.'])
                    ->withInput();
            }
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->address = $request->address;

        // Handle password change
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->save();

        return redirect()->route('user.profile')
            ->with('success', 'Profile updated successfully!');
    }

    public function bookings()
    {
        $user = Auth::user();
        $bookings = Booking::where('user_email', $user->email)
            ->with(['package', 'bookingAddons.addon', 'giftCard'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('user.bookings', compact('bookings'));
    }

    public function showBooking($id)
    {
        $user = Auth::user();
        $booking = Booking::where('id', $id)
            ->where('user_email', $user->email)
            ->with(['package', 'bookingAddons.addon', 'giftCard'])
            ->firstOrFail();

        return view('user.booking-details', compact('booking'));
    }

    public function contact()
    {
        $user = Auth::user();
        return view('user.contact', compact('user'));
    }

    public function submitContact(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('contact-submissions', 'public');
        }

        // Store contact submission
        ContactSubmission::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'message' => $validated['message'],
            'image_path' => $imagePath,
        ]);

        return redirect()->route('user.contact')
            ->with('success', 'Your query has been submitted successfully! We will get back to you soon.');
    }

    public function activity()
    {
        $user = Auth::user();
        
        // Get all user activities
        $bookings = Booking::where('user_email', $user->email)
            ->orderBy('created_at', 'desc')
            ->get() ?? collect([]);

        $contactSubmissions = ContactSubmission::where('email', $user->email)
            ->orderBy('created_at', 'desc')
            ->get() ?? collect([]);

        // Combine and sort by date
        $activities = collect();
        
        foreach ($bookings as $booking) {
            $activities->push([
                'type' => 'booking',
                'title' => 'Booking Created',
                'description' => 'Booked ' . $booking->package->name,
                'date' => $booking->created_at,
                'data' => $booking,
            ]);
        }

        foreach ($contactSubmissions as $submission) {
            $activities->push([
                'type' => 'contact',
                'title' => 'Contact Query Submitted',
                'description' => 'Submitted a query',
                'date' => $submission->created_at,
                'data' => $submission,
            ]);
        }

        $activities = $activities->sortByDesc('date')->values();

        return view('user.activity', compact('activities'));
    }
}
