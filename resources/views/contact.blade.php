@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Header -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold mb-4">Get in Touch</h1>
            <p class="text-xl text-gray-600">We're here to help with all your puja samagri needs</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->


            <form action="{{ route('contact.store') }}" method="POST" class="space-y-6">
                @csrf
                @if (session('success'))
                    <div class="p-4 mb-4 text-green-800 bg-green-100 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="first-name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                        <input type="text" id="first-name" name="first-name" value="{{ old('first-name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('first-name') border-red-500 @enderror"
                            required>
                        @error('first-name')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="last-name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                        <input type="text" id="last-name" name="last-name" value="{{ old('last-name') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('last-name') border-red-500 @enderror"
                            required>
                        @error('last-name')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('email') border-red-500 @enderror"
                        required>
                    @error('email')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <input type="tel" id="phone" name="phone" value="{{ old('phone') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg" pattern="[6-9][0-9]{9}"
                        title="Please enter a valid 10-digit Indian mobile number starting with 6, 7, 8, or 9"
                        minlength="10" maxlength="10" placeholder="9876543210" required>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>


                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                    <input type="text" id="subject" name="subject" value="{{ old('subject') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('subject') border-red-500 @enderror"
                        required>
                    @error('subject')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea id="message" name="message" rows="5" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-3 rounded-lg transition-colors">
                    Send Message
                </button>
            </form>

            <!-- Contact Information -->
            <div class="space-y-8">
                <!-- Contact Details -->
                <div class="bg-white rounded-2xl border border-gray-200 p-8">
                    <h2 class="text-2xl font-bold mb-6">Contact Information</h2>
                    <div class="space-y-6">
                        <div class="flex items-center gap-4">
                            <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center">
                                <i data-lucide="phone" class="w-6 h-6 text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">Phone</h3>
                                <p class="text-gray-600">+91 95798 09188</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center">
                                <i data-lucide="mail" class="w-6 h-6 text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">Email</h3>
                                <p class="text-gray-600">support@shreesamagri.com</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center">
                                <i data-lucide="map-pin" class="w-6 h-6 text-purple-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold">Address</h3>
                                <p class="text-gray-600">Bhalchandra nagari, Ravet, Pimpri Chinchwad<br>Pune,
                                    Maharashtra 412101</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Help -->
                {{-- <div class="bg-green-50 rounded-2xl p-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-green-600 w-12 h-12 rounded-full flex items-center justify-center">
                            <i data-lucide="message-circle" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-green-800">Need Quick Help?</h3>
                            <p class="text-green-700 text-sm">Quick responses guaranteed</p>
                        </div>
                    </div>
                    <p class="text-green-700 mb-4">Get instant help with your orders, queries, or puja guidance through
                        WhatsApp</p>
                    <button
                        class="bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-3 rounded-lg transition-colors flex items-center gap-2">
                        <i data-lucide="message-circle" class="w-5 h-5"></i>
                        Chat on WhatsApp
                    </button>
                </div> --}}

                <!-- Support Hours -->
                {{-- <div class="bg-blue-50 rounded-2xl p-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="bg-blue-600 w-12 h-12 rounded-full flex items-center justify-center">
                            <i data-lucide="headphones" class="w-6 h-6 text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-blue-800">Customer Support</h3>
                            <p class="text-blue-700 text-sm">24/7 Available</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-blue-700">
                        <div class="flex justify-between">
                            <span>Response Time:</span>
                            <span class="font-medium">Within 5 minutes</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Phone Support:</span>
                            <span class="font-medium">24/7</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Email Support:</span>
                            <span class="font-medium">24/7</span>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-16">
            <h2 class="text-3xl font-bold text-center mb-12">Frequently Asked Questions</h2>
            <div class="max-w-3xl mx-auto space-y-6">
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold mb-3">How fast is your delivery?</h3>
                    <p class="text-gray-600">We deliver authentic puja items within 1 day to all our serviceable areas.</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold mb-3">Are your puja items authentic?</h3>
                    <p class="text-gray-600">Yes, all our puja items are sourced directly from trusted suppliers and
                        artisans. We ensure authenticity and quality in every product we deliver.</p>
                </div>
                {{-- 
                <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold mb-3">Can I customize my puja kit?</h3>
                    <p class="text-gray-600">Absolutely! We offer custom puja kits tailored to your specific requirements.
                        Contact us with your needs and we'll create a personalized kit for you.</p>
                </div> --}}

                {{-- <div class="bg-white rounded-2xl border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold mb-3">Do you provide pandit services?</h3>
                    <p class="text-gray-600">Yes, we have verified and experienced pandits available for various puja
                        ceremonies. You can book them through our consult page.</p>
                </div> --}}
            </div>
        </div>
    </div>
@endsection
