@extends('layouts.app')

@section('title', 'Privacy Policy - Shree Samagri')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Privacy Policy</h1>
    <p class="text-sm text-gray-600 mb-8">Last Updated: October 1, 2025</p>

    <div class="space-y-6 text-gray-700">
        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">1. Introduction</h3>
            <p>Shree Samagri is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website and purchase our puja samagri products.</p>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">2. Information We Collect</h3>
            
            <h4 class="font-semibold mt-4 mb-2">Personal Information:</h4>
            <ul class="list-disc pl-6 space-y-2">
                <li>Name, email address, and phone number</li>
                <li>Shipping and billing addresses</li>
                <li>Payment information (processed securely through payment gateway)</li>
                <li>Order history and preferences</li>
            </ul>

            <h4 class="font-semibold mt-4 mb-2">Technical Information:</h4>
            <ul class="list-disc pl-6 space-y-2">
                <li>IP address and browser type</li>
                <li>Device information and operating system</li>
                <li>Cookies and usage data</li>
                <li>Pages visited and time spent on site</li>
            </ul>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">3. How We Use Your Information</h3>
            <ul class="list-disc pl-6 space-y-2">
                <li>Process and fulfill your orders</li>
                <li>Send order confirmations, invoices, and shipping updates</li>
                <li>Respond to customer service requests and support needs</li>
                <li>Improve our website and services</li>
                <li>Send promotional emails about new products and offers (with your consent)</li>
                <li>Prevent fraudulent transactions and ensure security</li>
                <li>Comply with legal obligations and regulations</li>
            </ul>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">4. Payment Information Security</h3>
            <p>We use secure payment gateways that comply with PCI-DSS standards. Your payment information is encrypted and transmitted securely. We do not store complete credit/debit card details on our servers. Payment processing is handled by certified third-party payment processors.</p>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">5. Information Sharing and Disclosure</h3>
            <p>We do not sell, trade, or rent your personal information to third parties. We may share your information with:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li><strong>Service Providers:</strong> Shipping companies, payment processors, and email service providers</li>
                <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
                <li><strong>Business Transfers:</strong> In case of merger, acquisition, or sale of assets</li>
            </ul>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">6. Data Retention</h3>
            <p>We retain your personal information for as long as necessary to fulfill the purposes outlined in this Privacy Policy, comply with legal obligations, resolve disputes, and enforce our agreements. Order history is maintained for accounting and tax purposes as per Indian regulations.</p>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">7. Cookies and Tracking</h3>
            <p>We use cookies to enhance your browsing experience, remember your preferences, and analyze site traffic. You can disable cookies in your browser settings, but this may affect website functionality.</p>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">8. Your Rights</h3>
            <p>You have the right to:</p>
            <ul class="list-disc pl-6 space-y-2">
                <li>Access your personal information</li>
                <li>Correct inaccurate data</li>
                <li>Request deletion of your data (subject to legal requirements)</li>
                <li>Opt-out of marketing communications</li>
                <li>Withdraw consent for data processing</li>
            </ul>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">9. Children's Privacy</h3>
            <p>Our services are not directed to individuals under 18 years of age. We do not knowingly collect personal information from children. If you are a parent and believe your child has provided us with information, please contact us.</p>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">10. Security Measures</h3>
            <p>We implement industry-standard security measures including SSL encryption, secure servers, and regular security audits. However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.</p>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">11. Third-Party Links</h3>
            <p>Our website may contain links to third-party websites. We are not responsible for the privacy practices of these external sites. Please review their privacy policies before providing any information.</p>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">12. Changes to Privacy Policy</h3>
            <p>We reserve the right to update this Privacy Policy at any time. Changes will be posted on this page with an updated revision date. Continued use of our services after changes constitutes acceptance of the modified policy.</p>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">13. Contact Us</h3>
            <p>If you have questions about this Privacy Policy or wish to exercise your rights, please contact us at:</p>
            <div class="mt-4 bg-gray-100 p-4 rounded-lg">
                <p><strong>Email:</strong> support@shreesamagri.com</p>
                {{-- <p><strong>Phone:</strong> +91 XXXXX XXXXX</p>
                <p><strong>Address:</strong> [Your Business Address, City, State, PIN Code]</p> --}}
            </div>
        </section>
    </div>
</div>
@endsection
