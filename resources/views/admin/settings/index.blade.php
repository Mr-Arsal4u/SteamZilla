@extends('layouts.admin')

@section('title', 'Settings - SteamZilla')
@section('page-title', 'General Settings')

@section('content')
@php
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
@endphp

<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <!-- General Settings -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">General Settings</h3>
        </div>
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Logo</label>
                @if(Setting::get('site_logo'))
                    <div class="mb-2">
                        <img src="{{ Storage::url(Setting::get('site_logo')) }}" alt="Site Logo" class="h-20 w-auto rounded border border-gray-300">
                    </div>
                @endif
                <input type="file" name="site_logo" accept="image/*" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <input type="hidden" name="group_site_logo" value="general">
                <input type="hidden" name="type_site_logo" value="image">
                <p class="mt-1 text-sm text-gray-500">Upload your site logo</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                <input type="text" name="site_name" value="{{ Setting::get('site_name', 'SteamZilla') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <input type="hidden" name="group_site_name" value="general">
                <input type="hidden" name="type_site_name" value="text">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Site Description</label>
                <textarea name="site_description" rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ Setting::get('site_description', '') }}</textarea>
                <input type="hidden" name="group_site_description" value="general">
                <input type="hidden" name="type_site_description" value="textarea">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                <input type="email" name="contact_email" value="{{ Setting::get('contact_email', '') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <input type="hidden" name="group_contact_email" value="general">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                <input type="text" name="contact_phone" value="{{ Setting::get('contact_phone', '') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <input type="hidden" name="group_contact_phone" value="general">
            </div>
        </div>
    </div>

    <!-- Home Settings -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Home Page Settings</h3>
        </div>
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Background Image</label>
                @if(Setting::get('home_hero_background'))
                    <div class="mb-2">
                        <img src="{{ Storage::url(Setting::get('home_hero_background')) }}" alt="Hero Background" class="h-32 w-auto rounded border border-gray-300">
                    </div>
                @endif
                <input type="file" name="home_hero_background" accept="image/*" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <input type="hidden" name="group_home_hero_background" value="home">
                <input type="hidden" name="type_home_hero_background" value="image">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Title</label>
                <input type="text" name="home_hero_title" value="{{ Setting::get('home_hero_title', 'UNLEASH THE POWER OF CLEAN.') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <input type="hidden" name="group_home_hero_title" value="home">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Hero Subtitle</label>
                <textarea name="home_hero_subtitle" rows="2" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ Setting::get('home_hero_subtitle', '') }}</textarea>
                <input type="hidden" name="group_home_hero_subtitle" value="home">
                <input type="hidden" name="type_home_hero_subtitle" value="textarea">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Benefits Section Title</label>
                <input type="text" name="home_benefits_title" value="{{ Setting::get('home_benefits_title', 'WHY STEAMZILLA?') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <input type="hidden" name="group_home_benefits_title" value="home">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Benefits Section Description</label>
                <textarea name="home_benefits_description" rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ Setting::get('home_benefits_description', '') }}</textarea>
                <input type="hidden" name="group_home_benefits_description" value="home">
                <input type="hidden" name="type_home_benefits_description" value="textarea">
            </div>
        </div>
    </div>

    <!-- About Settings -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">About Page Settings</h3>
        </div>
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Page Title</label>
                <input type="text" name="about_title" value="{{ Setting::get('about_title', 'About SteamZilla') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <input type="hidden" name="group_about_title" value="about">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Main Description</label>
                <textarea name="about_description" rows="6" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ Setting::get('about_description', '') }}</textarea>
                <input type="hidden" name="group_about_description" value="about">
                <input type="hidden" name="type_about_description" value="textarea">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Mission Statement</label>
                <textarea name="about_mission" rows="4" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ Setting::get('about_mission', '') }}</textarea>
                <input type="hidden" name="group_about_mission" value="about">
                <input type="hidden" name="type_about_mission" value="textarea">
            </div>
        </div>
    </div>

    <!-- Contact Settings -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Contact Settings</h3>
        </div>
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Page Title</label>
                <input type="text" name="contact_title" value="{{ Setting::get('contact_title', 'Contact Us') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <input type="hidden" name="group_contact_title" value="contact">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Contact Address</label>
                <textarea name="contact_address" rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ Setting::get('contact_address', '') }}</textarea>
                <input type="hidden" name="group_contact_address" value="contact">
                <input type="hidden" name="type_contact_address" value="textarea">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Business Hours</label>
                <textarea name="contact_hours" rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ Setting::get('contact_hours', '') }}</textarea>
                <input type="hidden" name="group_contact_hours" value="contact">
                <input type="hidden" name="type_contact_hours" value="textarea">
            </div>
        </div>
    </div>

    <!-- SEO Settings -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">SEO Settings</h3>
        </div>
        <div class="p-6 space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                <input type="text" name="seo_meta_title" value="{{ Setting::get('seo_meta_title', 'SteamZilla - Professional Car Steam Cleaning') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <input type="hidden" name="group_seo_meta_title" value="seo">
                <p class="mt-1 text-sm text-gray-500">Title for search engines (50-60 characters recommended)</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                <textarea name="seo_meta_description" rows="3" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ Setting::get('seo_meta_description', '') }}</textarea>
                <input type="hidden" name="group_seo_meta_description" value="seo">
                <input type="hidden" name="type_seo_meta_description" value="textarea">
                <p class="mt-1 text-sm text-gray-500">Description for search engines (150-160 characters recommended)</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                <input type="text" name="seo_meta_keywords" value="{{ Setting::get('seo_meta_keywords', '') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]"
                    placeholder="car cleaning, steam cleaning, mobile detailing">
                <input type="hidden" name="group_seo_meta_keywords" value="seo">
                <p class="mt-1 text-sm text-gray-500">Comma-separated keywords</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">OG Image (Social Media)</label>
                @if(Setting::get('seo_og_image'))
                    <div class="mb-2">
                        <img src="{{ Storage::url(Setting::get('seo_og_image')) }}" alt="OG Image" class="h-32 w-auto rounded border border-gray-300">
                    </div>
                @endif
                <input type="file" name="seo_og_image" accept="image/*" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                <input type="hidden" name="group_seo_og_image" value="seo">
                <input type="hidden" name="type_seo_og_image" value="image">
                <p class="mt-1 text-sm text-gray-500">Image shown when sharing on social media (1200x630px recommended)</p>
            </div>
        </div>
    </div>

    <!-- Existing Settings from Database -->
    @if($settings->count() > 0)
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Additional Settings</h3>
        </div>
        <div class="p-6 space-y-6">
            @foreach($settings as $group => $groupSettings)
                @if(!in_array($group, ['general', 'home', 'about', 'contact', 'seo']))
                    @foreach($groupSettings as $setting)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ $setting->key }}</label>
                        @if($setting->type === 'image')
                            <div class="mb-2">
                                @if($setting->value)
                                    <img src="{{ Storage::url($setting->value) }}" alt="{{ $setting->key }}" class="h-20 w-auto rounded border border-gray-300">
                                @endif
                            </div>
                            <input type="file" name="{{ $setting->key }}" accept="image/*" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                        @elseif($setting->type === 'textarea')
                            <textarea name="{{ $setting->key }}" rows="4" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">{{ $setting->value }}</textarea>
                        @else
                            <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#45A247] focus:border-[#45A247]">
                        @endif
                        @if($setting->description)
                            <p class="mt-1 text-sm text-gray-500">{{ $setting->description }}</p>
                        @endif
                        <input type="hidden" name="group_{{ $setting->key }}" value="{{ $setting->group }}">
                        <input type="hidden" name="type_{{ $setting->key }}" value="{{ $setting->type }}">
                    </div>
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <div class="flex justify-end">
        <button type="submit" class="bg-[#45A247] text-white px-8 py-3 rounded-lg font-semibold hover:bg-[#3a8a3c] transition">
            Save Settings
        </button>
    </div>
</form>
@endsection
