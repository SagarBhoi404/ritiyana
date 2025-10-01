@extends('layouts.app')

@section('title', 'Refund & Cancellation Policy - Shree Samagri')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Refund & Cancellation Policy</h1>
    <p class="text-sm text-gray-600 mb-8">Last Updated: October 1, 2025</p>

    <div class="space-y-6 text-gray-700">
        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">1. Overview</h3>
            <p>At Shree Samagri, we strive to provide authentic and quality puja samagri products. Due to the religious and sacred nature of our products, we have specific policies regarding cancellations and refunds. Please read this policy carefully before placing your order.</p>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">2. Order Cancellation</h3>
            
            <h4 class="font-semibold mt-4 mb-2">Before Shipment:</h4>
            <ul class="list-disc pl-6 space-y-2">
                <li>Orders can be cancelled within 24 hours of placement</li>
                <li>Cancellation requests must be made via email or customer support</li>
                <li>Full refund will be processed if cancelled before shipment</li>
                <li>Refund will be credited to the original payment method within 5-7 business days</li>
            </ul>

            <h4 class="font-semibold mt-4 mb-2">After Shipment:</h4>
            <ul class="list-disc pl-6 space-y-2">
                <li>Orders cannot be cancelled once shipped</li>
                <li>You may refuse delivery, and return shipping charges will apply</li>
                <li>Refund will be processed after deducting shipping costs both ways</li>
            </ul>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">3. Return Policy</h3>
            
            <h4 class="font-semibold mt-4 mb-2">Eligible for Return:</h4>
            <ul class="list-disc pl-6 space-y-2">
                <li>Damaged or defective products upon delivery</li>
                <li>Wrong items shipped</li>
                <li>Incomplete orders or missing items</li>
                <li>Products not matching description</li>
            </ul>

            <h4 class="font-semibold mt-4 mb-2">Not Eligible for Return:</h4>
            <ul class="list-disc pl-6 space-y-2">
                <li>Opened or used puja samagri items (due to hygiene and sanctity)</li>
                <li>Customized or personalized puja kits</li>
                <li>Products damaged due to misuse or negligence</li>
                <li>Items returned after 7 days of delivery</li>
            </ul>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">4. Return Process</h3>
            <ol class="list-decimal pl-6 space-y-2">
                <li>Contact our customer support within 48 hours of delivery</li>
                <li>Provide order number, photos of the damaged/wrong item, and reason for return</li>
                <li>Wait for return approval from our team</li>
                <li>Pack the item securely in original packaging with all accessories</li>
                <li>Ship the product to our return address (provided after approval)</li>
                <li>Share the return tracking details with us</li>
            </ol>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">5. Refund Process</h3>
            
            <h4 class="font-semibold mt-4 mb-2">Refund Timeline:</h4>
            <ul class="list-disc pl-6 space-y-2">
                <li>Quality check performed within 2-3 business days of receiving returned product</li>
                <li>Refund initiated within 5-7 business days after quality check</li>
                <li>Refund credited to original payment method within 7-10 business days</li>
                <li>Bank processing time may vary (3-5 additional days)</li>
            </ul>

            <h4 class="font-semibold mt-4 mb-2">Refund Amount:</h4>
            <ul class="list-disc pl-6 space-y-2">
                <li>Full refund for damaged/defective products (including shipping charges)</li>
                <li>Full product amount for wrong items shipped</li>
                <li>Shipping charges deducted for customer-initiated returns</li>
                <li>Refunds processed in INR only</li>
            </ul>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">6. Exchange Policy</h3>
            <ul class="list-disc pl-6 space-y-2">
                <li>Exchanges available only for damaged or defective products</li>
                <li>Replacement shipped within 3-5 business days after receiving returned item</li>
                <li>No exchange for size, color, or variant changes</li>
                <li>Subject to product availability</li>
            </ul>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">7. Non-Delivery Issues</h3>
            
            <h4 class="font-semibold mt-4 mb-2">If Order Not Received:</h4>
            <ul class="list-disc pl-6 space-y-2">
                <li>Wait for the estimated delivery time plus 2 extra days</li>
                <li>Check with neighbors or building security</li>
                <li>Contact our customer support with order details</li>
                <li>We will investigate with courier partner</li>
                <li>Full refund or replacement if confirmed lost in transit</li>
            </ul>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">8. Damaged During Transit</h3>
            <ul class="list-disc pl-6 space-y-2">
                <li>Inspect package at the time of delivery</li>
                <li>Refuse delivery if package is visibly damaged</li>
                <li>Take photos/video if damage discovered after opening</li>
                <li>Report within 48 hours for claim processing</li>
                <li>Full refund or replacement for transit-damaged items</li>
            </ul>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">9. Special Circumstances</h3>
            
            <h4 class="font-semibold mt-4 mb-2">Festivals and Peak Season:</h4>
            <p>During festival seasons, cancellation and refund processing may take additional 2-3 business days due to high order volumes.</p>

            <h4 class="font-semibold mt-4 mb-2">Bulk Orders:</h4>
            <p>For bulk orders above ₹10,000, please contact us for custom cancellation and refund terms before placing the order.</p>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">10. Refund Methods</h3>
            <ul class="list-disc pl-6 space-y-2">
                <li><strong>Credit/Debit Card:</strong> Refunded to the same card</li>
                <li><strong>Net Banking:</strong> Credited to source bank account</li>
                <li><strong>UPI:</strong> Refunded to linked account</li>
                <li><strong>Wallets:</strong> Credited back to wallet</li>
                <li>Cash on Delivery returns: Bank transfer (provide account details)</li>
            </ul>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">11. Important Notes</h3>
            <ul class="list-disc pl-6 space-y-2">
                <li>All returns must include original invoice and packaging</li>
                <li>Shipping charges for returns are customer's responsibility unless product is defective</li>
                <li>We recommend insured shipping for valuable items</li>
                <li>Keep tracking information until refund is processed</li>
                <li>Partial refunds may apply if product is not in original condition</li>
            </ul>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">12. Contact for Returns & Refunds</h3>
            <p>For any queries or assistance regarding cancellations, returns, or refunds:</p>
            <p class="mt-2">
                <strong>Email:</strong> [Your Email]<br>
                <strong>Phone:</strong> [Your Phone Number]<br>
                <strong>WhatsApp:</strong> [Your WhatsApp Number]<br>
                <strong>Support Hours:</strong> Monday to Saturday, 10:00 AM - 6:00 PM IST
            </p>
        </section>

        <section>
            <h3 class="text-xl font-semibold text-gray-900 mb-3">13. Dispute Resolution</h3>
            <p>In case of any disputes, we encourage customers to contact our support team first. We are committed to resolving issues amicably. All disputes are subject to the jurisdiction of courts in [Your City], India.</p>
        </section>
    </div>
</div>
@endsection
