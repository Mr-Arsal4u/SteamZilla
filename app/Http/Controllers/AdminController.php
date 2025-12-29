<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use App\Models\Addon;
use App\Models\Setting;
use App\Models\GalleryImage;
use App\Models\PageContent;
use App\Models\GiftCard;
use App\Models\GiftCardTransaction;
use App\Models\ContactSubmission;
use App\Models\Country;
use App\Models\City;
use App\Models\Place;
use App\Models\VehicleType;
use App\Models\TimeSlot;
use App\Models\SocialLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // Middleware is applied via route middleware in web.php

    // ==================== DASHBOARD ====================
    public function dashboard()
    {
        // Analytics Stats
        $stats = [
            'total_bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'confirmed_bookings' => Booking::where('status', 'confirmed')->count(),
            'completed_bookings' => Booking::where('status', 'completed')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            'total_revenue' => Booking::where('status', '!=', 'cancelled')->sum('total_price'),
            'monthly_revenue' => Booking::where('status', '!=', 'cancelled')
                ->whereMonth('created_at', now()->month)
                ->sum('total_price'),
            'total_packages' => Package::where('is_active', true)->count(),
            'total_addons' => Addon::where('is_active', true)->count(),
            'active_gift_cards' => GiftCard::when(
                Schema::hasColumn('gift_cards', 'status'),
                fn($q) => $q->where('status', 'active'),
                fn($q) => $q->where('is_active', true)
            )->count(),
            'total_contact_submissions' => ContactSubmission::count(),
            'unread_contact_submissions' => ContactSubmission::where('is_read', false)->count(),
        ];

        // Recent Bookings
        $recentBookings = Booking::with(['package', 'bookingAddons.addon'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent Contact Submissions
        $recentContactSubmissions = ContactSubmission::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Revenue Chart Data (Last 6 months)
        $revenueData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenueData[] = [
                'month' => $month->format('M Y'),
                'revenue' => Booking::where('status', '!=', 'cancelled')
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total_price'),
            ];
        }

        // Popular Packages
        $popularPackages = Package::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentContactSubmissions', 'revenueData', 'popularPackages'));
    }

    // ==================== BOOKINGS MANAGEMENT ====================
    public function bookings(Request $request)
    {
        $query = Booking::with(['package', 'bookingAddons.addon', 'giftCard']);

        // Filters
        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->date_from) {
            $query->whereDate('booking_date', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('booking_date', '<=', $request->date_to);
        }
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('user_name', 'like', '%' . $request->search . '%')
                  ->orWhere('user_email', 'like', '%' . $request->search . '%')
                  ->orWhere('user_phone', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }

        $bookings = $query->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.bookings.index', compact('bookings'));
    }

    public function showBooking($id)
    {
        $booking = Booking::with(['package', 'bookingAddons.addon', 'giftCard'])->findOrFail($id);
        return view('admin.bookings.show', compact('booking'));
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $booking = Booking::findOrFail($id);
        $booking->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Booking status updated successfully.');
    }

    public function deleteBooking($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();
        return redirect()->route('admin.bookings')->with('success', 'Booking deleted successfully.');
    }

    // ==================== PAYMENTS MANAGEMENT ====================
    public function payments(Request $request)
    {
        $query = Booking::with(['package', 'giftCard'])
            ->where('status', '!=', 'cancelled');

        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);
        
        $paymentStats = [
            'total_revenue' => Booking::where('status', '!=', 'cancelled')->sum('total_price'),
            'card_payments' => Booking::where('payment_method', 'card')->where('status', '!=', 'cancelled')->sum('total_price'),
            'gift_card_payments' => Booking::where('payment_method', 'gift_card')->where('status', '!=', 'cancelled')->sum('total_price'),
            'gift_card_discounts' => Booking::where('payment_method', 'gift_card')->sum('gift_card_discount'),
        ];

        return view('admin.payments.index', compact('payments', 'paymentStats'));
    }

    // ==================== PACKAGES MANAGEMENT ====================
    public function packages()
    {
        $packages = Package::orderBy('created_at', 'desc')->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function createPackage()
    {
        return view('admin.packages.create');
    }

    public function storePackage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $features = $request->features ? json_encode(array_filter(array_map('trim', explode("\n", $request->features)))) : json_encode([]);

        Package::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'description' => $request->description,
            'features' => $features,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.packages')->with('success', 'Package created successfully.');
    }

    public function editPackage($id)
    {
        $package = Package::findOrFail($id);
        return view('admin.packages.edit', compact('package'));
    }

    public function updatePackage(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|string|max:255',
            'description' => 'nullable|string',
            'features' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $package = Package::findOrFail($id);
        $features = $request->features ? json_encode(array_filter(array_map('trim', explode("\n", $request->features)))) : json_encode([]);

        $package->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration' => $request->duration,
            'description' => $request->description,
            'features' => $features,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.packages')->with('success', 'Package updated successfully.');
    }

    public function deletePackage($id)
    {
        $package = Package::findOrFail($id);
        $package->delete();
        return redirect()->route('admin.packages')->with('success', 'Package deleted successfully.');
    }

    // ==================== ADDONS MANAGEMENT ====================
    public function addons()
    {
        $addons = Addon::orderBy('created_at', 'desc')->get();
        return view('admin.addons.index', compact('addons'));
    }

    public function createAddon()
    {
        return view('admin.addons.create');
    }

    public function storeAddon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Addon::create([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category' => $request->category,
            'has_quantity' => $request->has('has_quantity'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.addons')->with('success', 'Addon created successfully.');
    }

    public function editAddon($id)
    {
        $addon = Addon::findOrFail($id);
        return view('admin.addons.edit', compact('addon'));
    }

    public function updateAddon(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $addon = Addon::findOrFail($id);
        $addon->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'category' => $request->category,
            'has_quantity' => $request->has('has_quantity'),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.addons')->with('success', 'Addon updated successfully.');
    }

    public function deleteAddon($id)
    {
        $addon = Addon::findOrFail($id);
        $addon->delete();
        return redirect()->route('admin.addons')->with('success', 'Addon deleted successfully.');
    }

    // ==================== SETTINGS MANAGEMENT ====================
    public function settings()
    {
        $settings = Setting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        // Ensure all groups exist even if empty
        $allGroups = ['general', 'home', 'about', 'contact', 'seo'];
        foreach ($allGroups as $group) {
            if (!isset($settings[$group])) {
                $settings[$group] = collect([]);
            }
        }
        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        foreach ($request->except(['_token']) as $key => $value) {
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $path = $file->store('settings', 'public');
                Setting::set($key, $path, 'image', $request->get('group_' . $key, 'general'));
            } else {
                $group = $request->get('group_' . $key, 'general');
                $type = $request->get('type_' . $key, 'text');
                Setting::set($key, $value, $type, $group);
            }
        }

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    // ==================== GALLERY MANAGEMENT ====================
    public function gallery()
    {
        $images = GalleryImage::orderBy('order')->orderBy('created_at', 'desc')->get();
        return view('admin.gallery.index', compact('images'));
    }

    public function storeGalleryImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|max:5120',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $path = $request->file('image')->store('gallery', 'public');

        GalleryImage::create([
            'title' => $request->title,
            'description' => $request->description,
            'image_path' => $path,
            'category' => $request->category,
            'order' => GalleryImage::max('order') + 1,
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Image uploaded successfully.');
    }

    public function updateGalleryImage(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $image = GalleryImage::findOrFail($id);
        
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($image->image_path);
            $path = $request->file('image')->store('gallery', 'public');
            $image->image_path = $path;
        }

        $image->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
            'order' => $request->order ?? $image->order,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Image updated successfully.');
    }

    public function deleteGalleryImage($id)
    {
        $image = GalleryImage::findOrFail($id);
        Storage::disk('public')->delete($image->image_path);
        $image->delete();
        return redirect()->back()->with('success', 'Image deleted successfully.');
    }

    // ==================== PAGE CONTENT MANAGEMENT ====================
    public function pageContent($page)
    {
        $contents = PageContent::where('page', $page)->orderBy('section')->orderBy('order')->get()->groupBy('section');
        return view('admin.pages.content', compact('page', 'contents'));
    }

    public function updatePageContent(Request $request, $page)
    {
        foreach ($request->except(['_token', 'page']) as $key => $value) {
            $parts = explode('_', $key, 2);
            if (count($parts) === 2) {
                $section = $parts[0];
                $fieldKey = $parts[1];
                
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $path = $file->store('pages', 'public');
                    PageContent::setContent($page, $section, $fieldKey, $path, 'image');
                } else {
                    $type = strpos($key, 'html') !== false ? 'html' : 'text';
                    PageContent::setContent($page, $section, $fieldKey, $value, $type);
                }
            }
        }

        return redirect()->back()->with('success', 'Page content updated successfully.');
    }

    // ==================== CONTACT SUBMISSIONS MANAGEMENT ====================
    public function contactSubmissions()
    {
        $submissions = ContactSubmission::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.contact-submissions.index', compact('submissions'));
    }

    public function showContactSubmission($id)
    {
        $submission = ContactSubmission::findOrFail($id);
        
        // Mark as read if not already read
        if (!$submission->is_read) {
            $submission->update(['is_read' => true]);
        }
        
        return view('admin.contact-submissions.show', compact('submission'));
    }

    public function markContactSubmissionRead($id)
    {
        $submission = ContactSubmission::findOrFail($id);
        $submission->update(['is_read' => true]);
        
        return redirect()->back()->with('success', 'Submission marked as read.');
    }

    public function deleteContactSubmission($id)
    {
        $submission = ContactSubmission::findOrFail($id);
        
        // Delete image if exists
        if ($submission->image_path) {
            Storage::disk('public')->delete($submission->image_path);
        }
        
        $submission->delete();
        
        return redirect()->route('admin.contact-submissions')->with('success', 'Contact submission deleted successfully.');
    }

    // ==================== COUNTRIES MANAGEMENT ====================
    public function countries()
    {
        $countries = Country::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.countries.index', compact('countries'));
    }

    public function createCountry()
    {
        return view('admin.countries.create');
    }

    public function storeCountry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:3|unique:countries,code',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Country::create([
            'name' => $request->name,
            'code' => $request->code ? strtoupper($request->code) : null,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.countries')->with('success', 'Country created successfully.');
    }

    public function editCountry($id)
    {
        $country = Country::findOrFail($id);
        return view('admin.countries.edit', compact('country'));
    }

    public function updateCountry(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:3|unique:countries,code,' . $id,
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $country = Country::findOrFail($id);
        $country->update([
            'name' => $request->name,
            'code' => $request->code ? strtoupper($request->code) : null,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.countries')->with('success', 'Country updated successfully.');
    }

    public function deleteCountry($id)
    {
        $country = Country::findOrFail($id);
        $country->delete();
        return redirect()->route('admin.countries')->with('success', 'Country deleted successfully.');
    }

    // ==================== CITIES MANAGEMENT ====================
    public function cities(Request $request)
    {
        $query = City::with('country')->orderBy('sort_order')->orderBy('name');
        
        if ($request->country_id) {
            $query->where('country_id', $request->country_id);
        }

        $cities = $query->get();
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.cities.index', compact('cities', 'countries'));
    }

    public function createCity()
    {
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        return view('admin.cities.create', compact('countries'));
    }

    public function storeCity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        City::create([
            'country_id' => $request->country_id,
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.cities')->with('success', 'City created successfully.');
    }

    public function editCity($id)
    {
        $city = City::with('country')->findOrFail($id);
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        return view('admin.cities.edit', compact('city', 'countries'));
    }

    public function updateCity(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required|exists:countries,id',
            'name' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $city = City::findOrFail($id);
        $city->update([
            'country_id' => $request->country_id,
            'name' => $request->name,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.cities')->with('success', 'City updated successfully.');
    }

    public function deleteCity($id)
    {
        $city = City::findOrFail($id);
        $city->delete();
        return redirect()->route('admin.cities')->with('success', 'City deleted successfully.');
    }

    // ==================== PLACES MANAGEMENT ====================
    public function places(Request $request)
    {
        $query = Place::with(['city.country'])->orderBy('sort_order')->orderBy('name');
        
        if ($request->city_id) {
            $query->where('city_id', $request->city_id);
        }
        if ($request->country_id) {
            $query->whereHas('city', function($q) use ($request) {
                $q->where('country_id', $request->country_id);
            });
        }

        $places = $query->get();
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        $cities = City::where('is_active', true)->with('country')->orderBy('name')->get();
        
        return view('admin.places.index', compact('places', 'countries', 'cities'));
    }

    public function createPlace()
    {
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        $cities = City::where('is_active', true)->with('country')->orderBy('name')->get();
        return view('admin.places.create', compact('countries', 'cities'));
    }

    public function storePlace(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'zip_code' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Place::create([
            'city_id' => $request->city_id,
            'name' => $request->name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'zip_code' => $request->zip_code,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.places')->with('success', 'Place created successfully.');
    }

    public function editPlace($id)
    {
        $place = Place::with(['city.country'])->findOrFail($id);
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        $cities = City::where('is_active', true)->with('country')->orderBy('name')->get();
        return view('admin.places.edit', compact('place', 'countries', 'cities'));
    }

    public function updatePlace(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'zip_code' => 'nullable|string|max:20',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $place = Place::findOrFail($id);
        $place->update([
            'city_id' => $request->city_id,
            'name' => $request->name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'zip_code' => $request->zip_code,
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return redirect()->route('admin.places')->with('success', 'Place updated successfully.');
    }

    public function deletePlace($id)
    {
        $place = Place::findOrFail($id);
        $place->delete();
        return redirect()->route('admin.places')->with('success', 'Place deleted successfully.');
    }

    // ==================== VEHICLE TYPES MANAGEMENT ====================
    public function vehicleTypes()
    {
        $vehicleTypes = VehicleType::orderBy('sort_order')->orderBy('name')->get();
        return view('admin.vehicle-types.index', compact('vehicleTypes'));
    }

    public function createVehicleType()
    {
        return view('admin.vehicle-types.create');
    }

    public function storeVehicleType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        VehicleType::create([
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.vehicle-types')->with('success', 'Vehicle type created successfully.');
    }

    public function editVehicleType($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        return view('admin.vehicle-types.edit', compact('vehicleType'));
    }

    public function updateVehicleType(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $vehicleType = VehicleType::findOrFail($id);
        $vehicleType->update([
            'name' => $request->name,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.vehicle-types')->with('success', 'Vehicle type updated successfully.');
    }

    public function deleteVehicleType($id)
    {
        $vehicleType = VehicleType::findOrFail($id);
        $vehicleType->delete();
        return redirect()->route('admin.vehicle-types')->with('success', 'Vehicle type deleted successfully.');
    }

    // ==================== TIME SLOTS MANAGEMENT ====================
    public function timeSlots()
    {
        $timeSlots = TimeSlot::orderBy('sort_order')->orderBy('start_time')->get();
        return view('admin.time-slots.index', compact('timeSlots'));
    }

    public function createTimeSlot()
    {
        return view('admin.time-slots.create');
    }

    public function storeTimeSlot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'label' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        TimeSlot::create([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'label' => $request->label,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.time-slots')->with('success', 'Time slot created successfully.');
    }

    public function editTimeSlot($id)
    {
        $timeSlot = TimeSlot::findOrFail($id);
        return view('admin.time-slots.edit', compact('timeSlot'));
    }

    public function updateTimeSlot(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'label' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $timeSlot = TimeSlot::findOrFail($id);
        $timeSlot->update([
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'label' => $request->label,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.time-slots')->with('success', 'Time slot updated successfully.');
    }

    public function deleteTimeSlot($id)
    {
        $timeSlot = TimeSlot::findOrFail($id);
        $timeSlot->delete();
        return redirect()->route('admin.time-slots')->with('success', 'Time slot deleted successfully.');
    }

    // ==================== SOCIAL LINKS MANAGEMENT ====================
    public function socialLinks()
    {
        $socialLinks = SocialLink::orderBy('sort_order')->orderBy('platform')->get();
        return view('admin.social-links.index', compact('socialLinks'));
    }

    public function createSocialLink()
    {
        return view('admin.social-links.create');
    }

    public function storeSocialLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'platform' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        SocialLink::create([
            'platform' => $request->platform,
            'url' => $request->url,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.social-links')->with('success', 'Social link created successfully.');
    }

    public function editSocialLink($id)
    {
        $socialLink = SocialLink::findOrFail($id);
        return view('admin.social-links.edit', compact('socialLink'));
    }

    public function updateSocialLink(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'platform' => 'required|string|max:255',
            'url' => 'required|url|max:500',
            'icon' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $socialLink = SocialLink::findOrFail($id);
        $socialLink->update([
            'platform' => $request->platform,
            'url' => $request->url,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.social-links')->with('success', 'Social link updated successfully.');
    }

    public function deleteSocialLink($id)
    {
        $socialLink = SocialLink::findOrFail($id);
        $socialLink->delete();
        return redirect()->route('admin.social-links')->with('success', 'Social link deleted successfully.');
    }
}
