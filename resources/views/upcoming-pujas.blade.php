@extends('layouts.app')

@section('content')
    <style>
        /* Mobile-specific adjustments */
        @media (max-width: 640px) {
            .newsletter-input {
                min-height: 44px;
                /* iOS recommended touch target */
            }

            .newsletter-button {
                min-height: 44px;
                /* iOS recommended touch target */
                font-size: 14px;
            }
        }

        /* Prevent horizontal overflow */
        .newsletter-container {
            overflow-x: hidden;
            width: 100%;
            box-sizing: border-box;
        }
    </style>
    <style>
        /* Mobile-specific adjustments */
        @media (max-width: 640px) {
            .newsletter-input {
                min-height: 44px;
                /* iOS recommended touch target */
            }

            .newsletter-button {
                min-height: 44px;
                /* iOS recommended touch target */
                font-size: 14px;
            }
        }

        /* Prevent horizontal overflow */
        .newsletter-container {
            overflow-x: hidden;
            width: 100%;
            box-sizing: border-box;
        }

        .festival-event {
            transition: all 0.2s ease;
        }

        .festival-event:hover {
            transform: translateY(-1px);
        }

        .border-orange-500 {
            border-color: #f97316;
        }

        .border-purple-500 {
            border-color: #a855f7;
        }

        .border-yellow-500 {
            border-color: #eab308;
        }

        .border-pink-500 {
            border-color: #ec4899;
        }

        .border-gold-500 {
            border-color: #d4af37;
        }

        .border-red-500 {
            border-color: #ef4444;
        }

        .min-h-32 {
            min-height: 8rem;
        }

        @media (max-width: 768px) {
            .min-h-32 {
                min-height: 6rem;
            }

            .festival-event {
                font-size: 10px;
                padding: 4px;
            }

            /* Adjust calendar header for tablets */
            #currentMonth {
                font-size: 1.1rem;
            }
        }

        @media (max-width: 640px) {
            .min-h-32 {
                min-height: 5rem;
            }

            #calendarGrid {
                gap: 2px;
            }

            .festival-event {
                font-size: 9px;
                padding: 3px;
            }

            /* Make day labels smaller */
            .grid.grid-cols-7 .text-sm {
                font-size: 0.75rem;
            }
        }

        /* Extra small devices (phones in portrait) */
        @media (max-width: 480px) {
            .min-h-32 {
                min-height: 4rem;
            }

            #calendarGrid {
                gap: 1px;
            }

            .festival-event {
                font-size: 8px;
                padding: 2px;
                margin-bottom: 1px;
            }

            /* Adjust calendar header */
            #currentMonth {
                font-size: 1rem;
            }

            /* Make navigation buttons smaller */
            #prevMonth,
            #nextMonth {
                padding: 0.5rem;
            }

            #prevMonth svg,
            #nextMonth svg {
                width: 1rem;
                height: 1rem;
            }

            /* Adjust day number font size */
            .min-h-32 .text-sm {
                font-size: 0.7rem;
            }

            /* Hide popular badge on very small screens */
            .festival-event .bg-orange-100 {
                display: none;
            }
        }

        /* Very small devices (phones < 375px) */
        @media (max-width: 375px) {
            .min-h-32 {
                min-height: 3.5rem;
            }

            .festival-event {
                font-size: 7px;
                padding: 1px;
            }

            /* Stack festival name on new line if needed */
            .festival-event .truncate {
                white-space: normal;
                overflow: visible;
                text-overflow: clip;
                line-height: 1.1;
            }
        }
    </style>


    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">Upcoming Pujas</h1>
            <p class="text-xl text-gray-600">Prepare for upcoming festivals with our specially curated puja kits</p>
        </div>

        <!-- View Toggle -->
        <div class="flex justify-center mb-8">
            <div class="bg-gray-100 p-1 rounded-lg inline-flex">
                <button id="calendarViewBtn"
                    class="px-4 py-2 text-sm font-medium rounded-md bg-white text-vibrant-pink shadow-sm">Calendar
                    View</button>
                <button id="gridViewBtn"
                    class="px-4 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700">Grid View</button>
            </div>
        </div>

        <!-- Calendar View (Default) -->
        <div id="calendarView" class="mb-12">
            <!-- Calendar Header -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
                <div
                    class="bg-gradient-to-r from-vibrant-pink to-purple-600 text-white p-4 flex items-center justify-between">
                    <button id="prevMonth" class="p-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                    </button>
                    <h2 id="currentMonth" class="text-xl font-semibold">September 2025</h2>
                    <button id="nextMonth" class="p-2 rounded-lg hover:bg-white hover:bg-opacity-20 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Calendar Grid -->
                <div class="p-4">
                    <!-- Day Labels -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Sun</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Mon</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Tue</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Wed</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Thu</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Fri</div>
                        <div class="text-center text-sm font-medium text-gray-500 py-2">Sat</div>
                    </div>

                    <!-- Calendar Days -->
                    <div id="calendarGrid" class="grid grid-cols-7 gap-1">
                        <!-- Days will be populated by JavaScript -->
                    </div>
                </div>
            </div>

            <!-- Festival Details Panel -->
            <div id="festivalDetails" class="mt-6 hidden">
                <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                    <div id="festivalContent">
                        <!-- Festival details will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Grid View (Hidden by default) -->
        <div id="gridView" class="hidden grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            <!-- Your existing festival cards remain here -->
            <!-- Ganesh Chaturthi -->
            <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                <div class="relative">
                    <img src="{{ asset('images/ganesh-chaturthi.jpg') }}" alt="Ganesh Chaturthi"
                        class="w-full h-48 object-cover">
                    <div class="absolute top-4 left-4 bg-orange-500 text-white text-sm font-medium px-3 py-1 rounded-full">
                        POPULAR
                    </div>
                    <div class="absolute top-4 right-4 bg-white text-orange-600 text-sm font-bold px-3 py-1 rounded-full">
                        4 days left
                    </div>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-2 mb-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-gray-400"></i>
                        <span class="text-sm text-gray-600">September 7, 2025</span>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Ganesh Chaturthi</h3>
                    <p class="text-gray-600 mb-4">Celebrate the birth of Lord Ganesha with our complete puja kit</p>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="text-2xl font-bold text-vibrant-pink">₹599</span>
                        <span class="text-lg text-gray-500 line-through">₹799</span>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">25% OFF</span>
                    </div>
                    <button
                        onclick="addToCart({id: 'ganesh-kit', name: 'Ganesh Chaturthi Special Kit', price: 599, originalPrice: 799, image: '{{ asset('images/ganesh-chaturthi.jpg') }}', description: 'Complete puja kit for Ganesh Chaturthi', discount: '25% OFF'})"
                        class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-3 rounded-lg transition-colors">
                        Add to Cart
                    </button>
                </div>
            </div>
            <!-- Add other festival cards here... -->
        </div>

        <!-- Newsletter Subscription - Fixed for Mobile -->
        <div class="bg-gradient-to-r from-vibrant-pink to-purple-600 rounded-2xl p-6 md:p-8 text-white text-center">
            <h2 class="text-xl md:text-2xl font-bold mb-4">Never Miss a Festival</h2>
            <p class="text-purple-100 mb-6 text-sm md:text-base">Subscribe to our festival calendar and get reminders for
                upcoming pujas with special discounts</p>

            <div class="max-w-md mx-auto">
                <!-- Mobile Stack Layout -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <input type="email" placeholder="Enter your email address"
                        class="flex-1 px-4 py-3 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-white text-sm md:text-base w-full">
                    <button
                        class="bg-white text-vibrant-pink font-medium px-6 py-3 rounded-lg hover:bg-gray-100 transition-colors whitespace-nowrap w-full sm:w-auto">
                        Subscribe
                    </button>
                </div>
                <p class="text-purple-200 text-xs md:text-sm mt-3">Get festival reminders, puja guides, and exclusive offers
                </p>
            </div>
        </div>

    </div>

    <!-- Calendar JavaScript -->
    <script>
        // Festival data with specific images for each festival
        const festivals = [{
                id: 'ganesh-kit',
                name: 'Ganesh Chaturthi',
                date: '2025-09-07',
                price: 599,
                originalPrice: 799,
                discount: '25% OFF',
                description: 'Celebrate the birth of Lord Ganesha with our complete puja kit including Ganesh idol, modak, durva grass, and all essential items',
                image: '{{ asset('images/ganesh-chaturthi.jpg') }}',
                icon: '🐘',
                popular: true,
                color: 'orange',
                items: ['Ganesh Idol', 'Modak', 'Durva Grass', 'Red Flowers', 'Incense Sticks', 'Coconut',
                    'Betel Leaves'
                ]
            },
            {
                id: 'navratri-kit',
                name: 'Navratri',
                date: '2025-10-03',
                price: 899,
                originalPrice: 1199,
                discount: '25% OFF',
                description: 'Nine nights of divine celebration with Goddess Durga complete puja essentials',
                image: '{{ asset('images/navratri.jpg') }}',
                icon: '🕉️',
                popular: false,
                color: 'purple',
                items: ['Kalash', 'Coconut', 'Mango Leaves', 'Red Cloth', 'Chunri', 'Flowers', 'Sindoor', 'Rice',
                    'Incense'
                ]
            },
            {
                id: 'karva-kit',
                name: 'Karva Chauth',
                date: '2025-10-20',
                price: 399,
                originalPrice: 499,
                discount: '20% OFF',
                description: 'Sacred fast for married women with moon worship items and karva pot',
                image: '{{ asset('images/karva-chauth.jpg') }}',
                icon: '🌙',
                popular: false,
                color: 'pink',
                items: ['Decorated Karva', 'Sieve', 'Diya', 'Sindoor', 'Rice', 'Sweets', 'Red Dupatta']
            },
            {
                id: 'dhanteras-kit',
                name: 'Dhanteras',
                date: '2025-10-29',
                price: 799,
                originalPrice: 999,
                discount: '20% OFF',
                description: 'Worship of wealth and prosperity with gold kalash set and Lakshmi essentials',
                image: '{{ asset('images/dhanteras.jpg') }}',
                icon: '💰',
                popular: false,
                color: 'yellow',
                items: ['Golden Kalash', 'Silver Coins', 'Marigold Flowers', 'Diya', 'Incense', 'Prasad', 'Red Cloth']
            },
            {
                id: 'diwali-kit',
                name: 'Diwali',
                date: '2025-11-01',
                price: 1299,
                originalPrice: 1599,
                discount: '19% OFF',
                description: 'Festival of lights - complete Lakshmi puja essentials with diyas and rangoli',
                image: '{{ asset('images/diwali.jpg') }}',
                icon: '🪔',
                popular: true,
                color: 'gold',
                items: ['Clay Diyas', 'Lakshmi Idol', 'Gold Coins', 'Rangoli Colors', 'Marigold Garland', 'Sweets',
                    'Incense', 'Oil'
                ]
            },
            {
                id: 'bhai-dooj-kit',
                name: 'Bhai Dooj',
                date: '2025-11-03',
                price: 299,
                originalPrice: 399,
                discount: '25% OFF',
                description: 'Celebrate sibling bond with special tilak, sweets and aarti thali',
                image: '{{ asset('images/bhai-dooj.jpg') }}',
                icon: '👫',
                popular: false,
                color: 'red',
                items: ['Tilak Thali', 'Roli', 'Chawal', 'Sweets', 'Diya', 'Flowers', 'Aarti Items']
            }
        ];

        let currentDate = new Date(2025, 8, 1); // September 2025

        // View toggle functionality
        document.getElementById('gridViewBtn').addEventListener('click', function() {
            document.getElementById('gridView').classList.remove('hidden');
            document.getElementById('calendarView').classList.add('hidden');
            this.classList.add('bg-white', 'text-vibrant-pink', 'shadow-sm');
            this.classList.remove('text-gray-500');
            document.getElementById('calendarViewBtn').classList.remove('bg-white', 'text-vibrant-pink',
                'shadow-sm');
            document.getElementById('calendarViewBtn').classList.add('text-gray-500');
        });

        document.getElementById('calendarViewBtn').addEventListener('click', function() {
            document.getElementById('calendarView').classList.remove('hidden');
            document.getElementById('gridView').classList.add('hidden');
            this.classList.add('bg-white', 'text-vibrant-pink', 'shadow-sm');
            this.classList.remove('text-gray-500');
            document.getElementById('gridViewBtn').classList.remove('bg-white', 'text-vibrant-pink', 'shadow-sm');
            document.getElementById('gridViewBtn').classList.add('text-gray-500');
        });

        // Calendar navigation
        document.getElementById('prevMonth').addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });

        document.getElementById('nextMonth').addEventListener('click', function() {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });

        function renderCalendar() {
            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            document.getElementById('currentMonth').textContent =
                `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;

            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
            const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());

            const calendarGrid = document.getElementById('calendarGrid');
            calendarGrid.innerHTML = '';

            for (let i = 0; i < 42; i++) { // 6 weeks * 7 days
                const cellDate = new Date(startDate);
                cellDate.setDate(startDate.getDate() + i);

                const dayCell = document.createElement('div');
                dayCell.className = `min-h-32 p-2 border border-gray-100 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors ${
                    cellDate.getMonth() !== currentDate.getMonth() ? 'text-gray-300' : ''
                } ${cellDate.toDateString() === new Date().toDateString() ? 'bg-blue-50 border-blue-200' : ''}`;

                const dayNumber = document.createElement('div');
                dayNumber.className = 'text-sm font-medium mb-2';
                dayNumber.textContent = cellDate.getDate();
                dayCell.appendChild(dayNumber);

                // Check for festivals on this date
                const dateString = cellDate.toISOString().split('T')[0];
                const festival = festivals.find(f => f.date === dateString);

                if (festival) {
                    // Create festival event with image and icon
                    const festivalElement = document.createElement('div');
                    festivalElement.className =
                        `festival-event bg-white border-l-4 border-${festival.color}-500 p-2 rounded shadow-sm hover:shadow-md transition-shadow cursor-pointer`;

                    festivalElement.innerHTML = `
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">${festival.icon}</span>
                            <span class="text-xs font-semibold text-gray-800 truncate">${festival.name}</span>
                        </div>
                        <div class="text-xs text-gray-600">₹${festival.price}</div>
                        ${festival.popular ? '<div class="text-xs bg-orange-100 text-orange-600 px-1 rounded mt-1">POPULAR</div>' : ''}
                    `;

                    festivalElement.addEventListener('click', () => showFestivalDetails(festival));
                    dayCell.appendChild(festivalElement);
                }

                calendarGrid.appendChild(dayCell);
            }
        }

        function showFestivalDetails(festival) {
            const detailsPanel = document.getElementById('festivalDetails');
            const content = document.getElementById('festivalContent');

            const daysLeft = Math.ceil((new Date(festival.date) - new Date()) / (1000 * 60 * 60 * 24));

            content.innerHTML = `
                <div class="flex flex-col lg:flex-row gap-6">
                    <div class="lg:w-1/3">
                        <div class="relative">
                            <img src="${festival.image}" alt="${festival.name}" class="w-full h-64 object-cover rounded-xl shadow-lg">
                            <div class="absolute top-4 left-4 bg-white bg-opacity-90 backdrop-blur-sm rounded-full p-2">
                                <span class="text-2xl">${festival.icon}</span>
                            </div>
                            ${festival.popular ? '<div class="absolute top-4 right-4 bg-orange-500 text-white text-xs font-medium px-3 py-1 rounded-full">POPULAR</div>' : ''}
                        </div>
                    </div>
                    <div class="lg:w-2/3">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-3xl font-bold mb-2 text-gray-800">${festival.name}</h3>
                                <p class="text-gray-600 mb-3 leading-relaxed">${festival.description}</p>
                                <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    ${new Date(festival.date).toLocaleDateString('en-US', { 
                                        weekday: 'long', 
                                        year: 'numeric', 
                                        month: 'long', 
                                        day: 'numeric' 
                                    })}
                                </div>
                                ${daysLeft > 0 ? `<p class="text-sm text-orange-600 font-medium">${daysLeft} days left</p>` : ''}
                            </div>
                        </div>
                        
                        <!-- Kit Contents -->
                        <div class="mb-6">
                            <h4 class="font-semibold text-gray-800 mb-3">What's included in the kit:</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                ${festival.items.map(item => `
                                            <div class="bg-gray-50 px-3 py-1 rounded-full text-sm text-gray-700">✓ ${item}</div>
                                        `).join('')}
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-3 mb-6">
                            <span class="text-3xl font-bold text-vibrant-pink">₹${festival.price}</span>
                            <span class="text-xl text-gray-500 line-through">₹${festival.originalPrice}</span>
                            <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">${festival.discount}</span>
                        </div>
                        
                        <button onclick="addToCart({
                            id: '${festival.id}',
                            name: '${festival.name} Special Kit',
                            price: ${festival.price},
                            originalPrice: ${festival.originalPrice},
                            image: '${festival.image}',
                            description: '${festival.description}',
                            discount: '${festival.discount}'
                        })" class="bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium px-8 py-3 rounded-xl transition-colors shadow-lg hover:shadow-xl">
                            Add to Cart
                        </button>
                    </div>
                </div>
            `;

            detailsPanel.classList.remove('hidden');
            detailsPanel.scrollIntoView({
                behavior: 'smooth'
            });
        }

        // Initialize calendar on page load
        document.addEventListener('DOMContentLoaded', function() {
            renderCalendar();
        });
    </script>

    <style>
        .festival-event {
            transition: all 0.2s ease;
        }

        .festival-event:hover {
            transform: translateY(-1px);
        }

        .border-orange-500 {
            border-color: #f97316;
        }

        .border-purple-500 {
            border-color: #a855f7;
        }

        .border-yellow-500 {
            border-color: #eab308;
        }

        .border-pink-500 {
            border-color: #ec4899;
        }

        .border-gold-500 {
            border-color: #d4af37;
        }

        .border-red-500 {
            border-color: #ef4444;
        }

        .min-h-32 {
            min-height: 8rem;
        }

        @media (max-width: 768px) {
            .min-h-32 {
                min-height: 6rem;
            }

            .festival-event {
                font-size: 10px;
                padding: 4px;
            }
        }

        @media (max-width: 640px) {
            .min-h-32 {
                min-height: 5rem;
            }

            #calendarGrid {
                gap: 2px;
            }
        }
    </style>
@endsection
