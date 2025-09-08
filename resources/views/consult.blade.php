@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-3xl md:text-4xl font-bold mb-4">Book a Pandit</h1>
        <p class="text-xl text-gray-600">Expert pandits for authentic puja ceremonies at your doorstep</p>
    </div>

    <!-- Pandits Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
        <!-- Pandit 1 -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-orange-400 to-orange-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                        PS
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold">Pandit Sharma</h3>
                        <div class="flex items-center gap-1 text-yellow-500">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <span class="text-gray-600 text-sm ml-1">(4.9)</span>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="text-gray-600 mb-2"><strong>Experience:</strong> 15+ years</p>
                    <p class="text-gray-600 mb-2"><strong>Specialization:</strong> Ganesh Puja, Satyanarayan Puja, Griha Pravesh</p>
                    <p class="text-gray-600 mb-4"><strong>Languages:</strong> Hindi, English, Marathi</p>
                    <div class="text-2xl font-bold text-vibrant-pink">₹2,500</div>
                </div>
                
                <button onclick="bookPandit('pandit-1', 'Pandit Sharma', 2500)" class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-3 rounded-lg transition-colors">
                    Book Now
                </button>
            </div>
        </div>

        <!-- Pandit 2 -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                        AG
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold">Acharya Gupta</h3>
                        <div class="flex items-center gap-1 text-yellow-500">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <span class="text-gray-600 text-sm ml-1">(4.8)</span>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="text-gray-600 mb-2"><strong>Experience:</strong> 20+ years</p>
                    <p class="text-gray-600 mb-2"><strong>Specialization:</strong> Lakshmi Puja, Durga Puja, Navratri</p>
                    <p class="text-gray-600 mb-4"><strong>Languages:</strong> Hindi, English, Sanskrit</p>
                    <div class="text-2xl font-bold text-vibrant-pink">₹3,000</div>
                </div>
                
                <button onclick="bookPandit('pandit-2', 'Acharya Gupta', 3000)" class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-3 rounded-lg transition-colors">
                    Book Now
                </button>
            </div>
        </div>

        <!-- Pandit 3 -->
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                        PM
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold">Pandit Mishra</h3>
                        <div class="flex items-center gap-1 text-yellow-500">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <span class="text-gray-600 text-sm ml-1">(4.7)</span>
                        </div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="text-gray-600 mb-2"><strong>Experience:</strong> 12+ years</p>
                    <p class="text-gray-600 mb-2"><strong>Specialization:</strong> Wedding Ceremonies, Housewarming, Festival Pujas</p>
                    <p class="text-gray-600 mb-4"><strong>Languages:</strong> Hindi, English</p>
                    <div class="text-2xl font-bold text-vibrant-pink">₹2,200</div>
                </div>
                
                <button onclick="bookPandit('pandit-3', 'Pandit Mishra', 2200)" class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-3 rounded-lg transition-colors">
                    Book Now
                </button>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div class="bg-gray-50 rounded-2xl p-8 mb-12">
        <h2 class="text-2xl font-bold text-center mb-8">Why Book Through PujaKit?</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="bg-blue-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="shield-check" class="w-6 h-6 text-blue-600"></i>
                </div>
                <h3 class="font-semibold mb-2">Verified Pandits</h3>
                <p class="text-gray-600 text-sm">All pandits are background verified and experienced</p>
            </div>
            <div class="text-center">
                <div class="bg-green-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="clock" class="w-6 h-6 text-green-600"></i>
                </div>
                <h3 class="font-semibold mb-2">Quick Booking</h3>
                <p class="text-gray-600 text-sm">Book in minutes, confirm within 30 minutes</p>
            </div>
            <div class="text-center">
                <div class="bg-purple-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="heart" class="w-6 h-6 text-purple-600"></i>
                </div>
                <h3 class="font-semibold mb-2">Authentic Rituals</h3>
                <p class="text-gray-600 text-sm">Traditional ceremonies as per Vedic customs</p>
            </div>
            <div class="text-center">
                <div class="bg-orange-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="home" class="w-6 h-6 text-orange-600"></i>
                </div>
                <h3 class="font-semibold mb-2">At Your Doorstep</h3>
                <p class="text-gray-600 text-sm">Pandits come to your location at scheduled time</p>
            </div>
        </div>
    </div>
</div>

<!-- Booking Confirmation Modal -->
<div id="booking-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-md mx-4">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check-circle" class="w-8 h-8 text-green-600"></i>
            </div>
            <h3 class="text-xl font-bold mb-2">Booking Confirmed!</h3>
            <p class="text-gray-600">Your pandit booking has been confirmed. You will receive a confirmation call within 30 minutes.</p>
        </div>
        
        <div class="space-y-3 mb-6">
            <div class="flex justify-between">
                <span class="text-gray-600">Booking ID:</span>
                <span class="font-medium" id="booking-id">#RIT123456</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Pandit:</span>
                <span class="font-medium" id="pandit-name">-</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">Amount:</span>
                <span class="font-medium" id="booking-amount">₹-</span>
            </div>
        </div>
        
        <div class="flex gap-3">
            <button onclick="closeBookingModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium py-3 rounded-lg transition-colors">
                Close
            </button>
            <button class="flex-1 bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-3 rounded-lg transition-colors">
                View Details
            </button>
        </div>
    </div>
</div>

<script>
function bookPandit(panditId, panditName, price) {
    // Generate random booking ID
    const bookingId = '#RIT' + Math.floor(Math.random() * 900000 + 100000);
    
    // Update modal content
    document.getElementById('booking-id').textContent = bookingId;
    document.getElementById('pandit-name').textContent = panditName;
    document.getElementById('booking-amount').textContent = '₹' + price;
    
    // Show modal
    document.getElementById('booking-modal').classList.remove('hidden');
}

function closeBookingModal() {
    document.getElementById('booking-modal').classList.add('hidden');
}
</script>
@endsection
