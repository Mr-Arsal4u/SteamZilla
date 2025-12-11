@extends('layouts.app')

@section('title', 'Terms and Conditions - SteamZilla')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">Terms and Conditions</h1>
            <p class="text-gray-600 mb-8">Last updated: {{ date('F d, Y') }}</p>

            <div class="prose prose-lg max-w-none">
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Acceptance of Terms</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        By accessing and using SteamZilla's mobile steam cleaning services, you accept and agree to be bound by the terms and provision of this agreement. If you do not agree to abide by the above, please do not use this service.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Service Description</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        SteamZilla provides professional mobile steam cleaning services for vehicles. Our services include interior steam sanitization, exterior cleaning, engine bay degreasing, and related add-on services as described on our website.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">3. Booking and Cancellation</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>Bookings must be made through our website or authorized channels.</li>
                        <li>We require at least 24 hours notice for cancellations or rescheduling.</li>
                        <li>Cancellations made less than 24 hours before the scheduled service may be subject to a cancellation fee.</li>
                        <li>No-shows will be charged the full service amount.</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Payment Terms</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>Payment is due upon completion of service unless otherwise arranged.</li>
                        <li>We accept cash, credit/debit cards, and gift cards.</li>
                        <li>Prices are subject to change without notice, but confirmed bookings will be honored at the quoted price.</li>
                        <li>Additional charges may apply for services not included in the selected package.</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Service Limitations</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>SteamZilla is not responsible for pre-existing damage to your vehicle.</li>
                        <li>We recommend removing personal items and valuables before service.</li>
                        <li>Some materials may be sensitive to steam cleaning - please inform us of any concerns.</li>
                        <li>Engine bay services are performed on cool engines only for safety.</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Customer Responsibilities</h2>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>Provide accurate vehicle information and service address.</li>
                        <li>Ensure access to a standard outdoor electrical outlet for our equipment.</li>
                        <li>Ensure the vehicle is in a safe location for service.</li>
                        <li>Disclose any known issues or concerns about your vehicle before service begins.</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Warranty and Guarantee</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We guarantee our workmanship. If you are not satisfied with the service provided, please contact us within 24 hours of service completion. We will work with you to resolve any issues. This warranty does not cover normal wear and tear or pre-existing conditions.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Limitation of Liability</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        SteamZilla's liability is limited to the cost of the service provided. We are not liable for any indirect, incidental, or consequential damages. Our insurance covers standard service operations, but customers are responsible for their vehicle's insurance.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Weather Conditions</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Services may be rescheduled due to severe weather conditions (heavy rain, snow, extreme temperatures). We will contact you to reschedule at no additional charge.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Changes to Terms</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        SteamZilla reserves the right to modify these terms and conditions at any time. Continued use of our services after changes constitutes acceptance of the new terms.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Contact Information</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        For questions about these Terms and Conditions, please contact us at:
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        <strong>Email:</strong> {{ \App\Models\Setting::get('contact_email', 'info@steamzila.com') }}<br>
                        <strong>Phone:</strong> {{ \App\Models\Setting::get('contact_phone', '(555) 123-4567') }}
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

