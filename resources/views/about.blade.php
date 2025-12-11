@extends('layouts.app')

@section('title', 'The Zilla Method - SteamZilla Mobile Detailing')

@section('content')
@php
use App\Models\PageContent;
use Illuminate\Support\Facades\Storage;
@endphp

<!-- Hero Section -->
<section class="bg-gradient-to-br from-green-50 to-white py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1 class="text-4xl md:text-6xl font-black text-gray-900 mb-6">{{ $pageTitle ?: 'THE ZILLA METHOD: MORE THAN STEAM. IT\'S A SYSTEM.' }}</h1>
        @if($mainDescription)
            <p class="text-xl text-gray-600 leading-relaxed">
                {{ $mainDescription }}
            </p>
        @else
            <p class="text-xl text-gray-600 leading-relaxed">
                Other detailers use steam as a tool. At SteamZilla, it's our core DNA. Our process is engineered for results that ordinary cleaning can't touch.
            </p>
        @endif
    </div>
</section>

<!-- The Science Section -->
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold mb-8 text-gray-900">The Science of Our Strike</h2>
        <p class="text-lg text-gray-700 mb-8 leading-relaxed">
            Our industrial-grade steam generators produce a dry, high-temperature vapor (over 300°F). This dynamic duo of <strong>HEAT</strong> and <strong>PRESSURE</strong> performs a triple-action assault on dirt:
        </p>
        
        <div class="space-y-8">
            <!-- Step 1 -->
            <div class="bg-gray-50 rounded-2xl p-8 border-l-4 border-[#45A247]">
                <div class="flex items-start">
                    <div class="w-16 h-16 bg-[#45A247] rounded-full flex items-center justify-center flex-shrink-0 mr-6">
                        <span class="text-white text-2xl font-bold">1</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-3 text-gray-900">ANNIHILATE</h3>
                        <p class="text-gray-700 leading-relaxed">The intense heat dissolves grease, melts old spills, and breaks the molecular bonds of grime and stains.</p>
                    </div>
                </div>
            </div>
            
            <!-- Step 2 -->
            <div class="bg-gray-50 rounded-2xl p-8 border-l-4 border-[#45A247]">
                <div class="flex items-start">
                    <div class="w-16 h-16 bg-[#45A247] rounded-full flex items-center justify-center flex-shrink-0 mr-6">
                        <span class="text-white text-2xl font-bold">2</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-3 text-gray-900">SANITIZE</h3>
                        <p class="text-gray-700 leading-relaxed">It eliminates odors at the source—pet accidents, smoke, mildew—by destroying the microbes that cause them, not masking them.</p>
                    </div>
                </div>
            </div>
            
            <!-- Step 3 -->
            <div class="bg-gray-50 rounded-2xl p-8 border-l-4 border-[#45A247]">
                <div class="flex items-start">
                    <div class="w-16 h-16 bg-[#45A247] rounded-full flex items-center justify-center flex-shrink-0 mr-6">
                        <span class="text-white text-2xl font-bold">3</span>
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold mb-3 text-gray-900">EXTRACT</h3>
                        <p class="text-gray-700 leading-relaxed">Our powerful vacuum systems pull the loosened contaminants out completely, leaving surfaces truly clean—not just moved around.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Section -->
<section class="py-20 bg-[#F7FFF7]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl md:text-4xl font-bold mb-8 text-gray-900">Why Choose the Beast</h2>
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Eco-Friendly Excellence</h3>
                <p class="text-gray-700 leading-relaxed">
                    We use up to 90% less water than traditional car washes. Our steam process requires minimal chemicals, making it safe for your family, pets, and the environment.
                </p>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Deep Cleaning Power</h3>
                <p class="text-gray-700 leading-relaxed">
                    Steam penetrates deeper than any spray or wipe. It reaches into fabric fibers, leather pores, and hard-to-reach crevices that traditional methods miss.
                </p>
            </div>
            
            <div class="bg-white rounded-2xl p-8 shadow-lg">
                <h3 class="text-2xl font-bold mb-4 text-gray-900">Sanitization Guarantee</h3>
                <p class="text-gray-700 leading-relaxed">
                    Our high-temperature steam eliminates 99.9% of bacteria, viruses, and allergens. Your car becomes a healthier space for you and your passengers.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Mission Statement -->
@if($mission)
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-[#45A247]/10 rounded-2xl p-12 text-center border-2 border-[#45A247]">
            <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900">Our Mission</h2>
            <p class="text-lg text-gray-700 leading-relaxed">
                {{ $mission }}
            </p>
        </div>
    </div>
</section>
@endif

<!-- Additional Content Sections from Database -->
@if($aboutContents && $aboutContents->count() > 0)
    @foreach($aboutContents as $section => $contents)
        @if(!in_array($section, ['main']))
        <section class="py-20 {{ $loop->even ? 'bg-[#F7FFF7]' : 'bg-white' }}">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                @php
                    $sectionTitle = PageContent::getContent('about', $section, 'title', '');
                @endphp
                @if($sectionTitle)
                    <h2 class="text-3xl md:text-4xl font-bold mb-8 text-gray-900">{{ $sectionTitle }}</h2>
                @endif
                
                @foreach($contents as $content)
                    @if($content->type === 'html')
                        <div class="prose max-w-none mb-6">
                            {!! $content->value !!}
                        </div>
                    @elseif($content->type === 'image')
                        <div class="mb-6">
                            <img src="{{ Storage::url($content->value) }}" alt="{{ $content->key }}" class="rounded-lg shadow-lg w-full">
                        </div>
                    @else
                        <p class="text-lg text-gray-700 mb-6 leading-relaxed">{{ $content->value }}</p>
                    @endif
                @endforeach
            </div>
        </section>
        @endif
    @endforeach
@endif
@endsection
