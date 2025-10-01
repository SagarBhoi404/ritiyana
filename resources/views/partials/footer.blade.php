<footer class="bg-gray-900 text-white pt-12 pb-6">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- Company Info -->
            <div>
                <img src="{{ asset('images/logo.png') }}" alt="PujaKit Logo" class="h-10 md:h-14 w-auto">
                <h3 class="text-xl font-bold mb-4">Shree Samagri</h3>
                <p class="text-gray-300 mb-4">Authentic puja items delivered in Same Day. Your spiritual journey starts
                    here.</p>
                <div class="flex gap-4">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i data-lucide="facebook" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i data-lucide="twitter" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i data-lucide="instagram" class="w-5 h-5"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i data-lucide="youtube" class="w-5 h-5"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="font-semibold mb-4">Quick Links</h4>
                <ul class="space-y-2 text-gray-300">
                    <li><a href="{{ route('home') }}" class="hover:text-white">Home</a></li>
                    <li><a href="{{ route('puja-kits.index') }}" class="hover:text-white">Puja Kits</a></li>
                    <li><a href="{{ route('upcoming-pujas') }}" class="hover:text-white">Upcoming Pujas</a></li>
                    {{-- <li><a href="{{ route('consult') }}" class="hover:text-white">Consult</a></li> --}}
                </ul>
            </div>

            <!-- Legal & Policies -->
            <div>
                <h4 class="font-semibold mb-4">Legal & Policies</h4>
                <ul class="space-y-2 text-gray-300">
                    <li><a href="{{ route('terms') }}" class="hover:text-white">Terms & Conditions</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-white">Privacy Policy</a></li>
                    <li><a href="{{ route('refund') }}" class="hover:text-white">Refund & Cancellation</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white">Contact Us</a></li>
                </ul>
            </div>


            <!-- Contact -->
            <div>
                <h4 class="font-semibold mb-4">Contact</h4>
                <div class="space-y-3 text-gray-300">
                    <div class="flex items-center gap-2">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                        <span>+91 95798 09188</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="mail" class="w-4 h-4"></i>
                        <span>support@shreesamagri.com</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                        <span>Bhalchandra nagari, Ravet, Pimpri Chinchwad<br>Pune, Maharashtra 412101</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-6">
            <div class="flex flex-col md:flex-row justify-center items-center">
                <p class="text-gray-400 text-sm">© 2025 Shree Samagri. All rights reserved.</p>
                {{-- <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white text-sm">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm">Refund Policy</a>
                </div> --}}
            </div>
        </div>
    </div>
</footer>
