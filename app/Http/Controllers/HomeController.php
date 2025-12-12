<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Addon;
use App\Models\Setting;
use App\Models\PageContent;
use App\Models\GalleryImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        $packages = Package::where('is_active', true)->get();
        $addons = Addon::where('is_active', true)->get();
        
        // Get page content from database
        $heroTitle = PageContent::getContent('home', 'hero', 'title', 'UNLEASH THE POWER OF CLEAN.');
        $heroSubtitle = PageContent::getContent('home', 'hero', 'subtitle', 'SteamZilla Mobile Detailing - Where Deep-Clean Science Meets Monster Results.');
        $heroDescription = PageContent::getContent('home', 'hero', 'description', 'Tired of surface-level washes and chemical smells? It\'s time for an evolution in car care. SteamZilla roars into your driveway with the raw power of professional steam cleaning.');
        $heroBackground = PageContent::getContent('home', 'hero', 'background_image');
        
        $benefitsTitle = PageContent::getContent('home', 'benefits', 'title', 'WHY STEAMZILLA?');
        $benefitsDescription = PageContent::getContent('home', 'benefits', 'description', '');
        
        $howItWorksTitle = PageContent::getContent('home', 'how_it_works', 'title', 'How It Works');
        $howItWorksDescription = PageContent::getContent('home', 'how_it_works', 'description', '');
        
        // Get gallery images
        $galleryImages = GalleryImage::where('is_active', true)
            ->where('category', 'gallery')
            ->orderBy('order')
            ->limit(8)
            ->get();
        
        // Fallback to home category if no gallery images
        if ($galleryImages->isEmpty()) {
            $galleryImages = GalleryImage::where('is_active', true)
                ->where('category', 'home')
                ->orderBy('order')
                ->limit(8)
                ->get();
        }
        
        return view('home', compact(
            'packages', 
            'addons', 
            'heroTitle', 
            'heroSubtitle', 
            'heroDescription',
            'heroBackground',
            'benefitsTitle',
            'benefitsDescription',
            'howItWorksTitle',
            'howItWorksDescription',
            'galleryImages'
        ));
    }

    public function contact()
    {
        $contactTitle = Setting::get('contact_title', 'Contact Us');
        $contactAddress = Setting::get('contact_address', '');
        $contactHours = Setting::get('contact_hours', '');
        $contactEmail = Setting::get('contact_email', '');
        $contactPhone = Setting::get('contact_phone', '');
        
        return view('contact', compact(
            'contactTitle',
            'contactAddress',
            'contactHours',
            'contactEmail',
            'contactPhone'
        ));
    }

    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'message' => 'required|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('contact-submissions', 'public');
        }

        // Store contact submission in database
        \App\Models\ContactSubmission::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'message' => $validated['message'],
            'image_path' => $imagePath,
        ]);

        // Send email notification to admin (optional)
        // You can uncomment this if you want email notifications
        /*
        try {
            \Mail::to(Setting::get('contact_email', 'mrzilla89@thesteamzilla.com'))->send(
                new \App\Mail\ContactSubmissionNotification($validated, $imagePath)
            );
        } catch (\Exception $e) {
            // Log error but don't fail the submission
            \Log::error('Failed to send contact submission email: ' . $e->getMessage());
        }
        */

        return redirect()->route('contact')->with('success', 'Thank you for contacting us! We will get back to you soon.');
    }

    public function termsAndConditions()
    {
        return view('terms-and-conditions');
    }

    public function privacyPolicy()
    {
        return view('privacy-policy');
    }
}
