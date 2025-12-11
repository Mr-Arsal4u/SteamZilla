@extends('layouts.app')

@section('title', 'Privacy Policy - SteamZilla')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-8 md:p-12">
            <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-6">Privacy Policy</h1>
            <p class="text-gray-600 mb-8">Last updated: {{ date('F d, Y') }}</p>

            <div class="prose prose-lg max-w-none">
                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">1. Introduction</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        SteamZilla ("we," "our," or "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you use our website and mobile steam cleaning services.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">2. Information We Collect</h2>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Personal Information</h3>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We may collect personal information that you provide to us, including:
                    </p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>Name and contact information (email, phone number, address)</li>
                        <li>Vehicle information (make, model, type)</li>
                        <li>Payment information (processed securely through third-party payment processors)</li>
                        <li>Service preferences and booking history</li>
                        <li>Account credentials (if you create an account)</li>
                    </ul>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3 mt-6">Automatically Collected Information</h3>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>IP address and browser type</li>
                        <li>Device information</li>
                        <li>Website usage data and analytics</li>
                        <li>Cookies and similar tracking technologies</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">3. How We Use Your Information</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">We use the information we collect to:</p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>Process and fulfill your service bookings</li>
                        <li>Communicate with you about your bookings and services</li>
                        <li>Send you service confirmations, reminders, and updates</li>
                        <li>Process payments and prevent fraud</li>
                        <li>Improve our services and website experience</li>
                        <li>Send marketing communications (with your consent)</li>
                        <li>Respond to your inquiries and provide customer support</li>
                        <li>Comply with legal obligations</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">4. Information Sharing and Disclosure</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">We do not sell your personal information. We may share your information with:</p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li><strong>Service Providers:</strong> Third-party companies that help us operate our business (payment processors, email services, analytics providers)</li>
                        <li><strong>Legal Requirements:</strong> When required by law or to protect our rights and safety</li>
                        <li><strong>Business Transfers:</strong> In connection with a merger, acquisition, or sale of assets</li>
                        <li><strong>With Your Consent:</strong> When you explicitly authorize us to share your information</li>
                    </ul>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">5. Data Security</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We implement appropriate technical and organizational security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet or electronic storage is 100% secure.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">6. Cookies and Tracking Technologies</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We use cookies and similar tracking technologies to enhance your experience on our website. You can control cookie preferences through your browser settings. Note that disabling cookies may affect website functionality.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">7. Your Rights and Choices</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">You have the right to:</p>
                    <ul class="list-disc list-inside text-gray-700 space-y-2 mb-4">
                        <li>Access and receive a copy of your personal information</li>
                        <li>Correct inaccurate or incomplete information</li>
                        <li>Request deletion of your personal information</li>
                        <li>Opt-out of marketing communications</li>
                        <li>Object to processing of your personal information</li>
                        <li>Request data portability</li>
                    </ul>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        To exercise these rights, please contact us using the information provided in the Contact section.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">8. Data Retention</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We retain your personal information for as long as necessary to fulfill the purposes outlined in this Privacy Policy, unless a longer retention period is required or permitted by law. When we no longer need your information, we will securely delete or anonymize it.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">9. Children's Privacy</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Our services are not directed to individuals under the age of 18. We do not knowingly collect personal information from children. If you believe we have collected information from a child, please contact us immediately.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">10. Third-Party Links</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        Our website may contain links to third-party websites. We are not responsible for the privacy practices of these external sites. We encourage you to review the privacy policies of any third-party sites you visit.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">11. Changes to This Privacy Policy</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        We may update this Privacy Policy from time to time. We will notify you of any material changes by posting the new Privacy Policy on this page and updating the "Last updated" date. Your continued use of our services after changes become effective constitutes acceptance of the updated policy.
                    </p>
                </section>

                <section class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">12. Contact Us</h2>
                    <p class="text-gray-700 leading-relaxed mb-4">
                        If you have questions, concerns, or requests regarding this Privacy Policy or our data practices, please contact us:
                    </p>
                    <p class="text-gray-700 leading-relaxed">
                        <strong>Email:</strong> {{ \App\Models\Setting::get('contact_email', 'info@steamzila.com') }}<br>
                        <strong>Phone:</strong> {{ \App\Models\Setting::get('contact_phone', '(555) 123-4567') }}<br>
                        <strong>Address:</strong> {{ \App\Models\Setting::get('contact_address', 'Mobile Service Area') }}
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection

