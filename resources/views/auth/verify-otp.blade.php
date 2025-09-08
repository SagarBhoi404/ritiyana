<!-- resources/views/auth/verify-otp.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl border border-gray-200 p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold mb-2">Verify OTP</h1>
            <p class="text-gray-600">We've sent a 6-digit code to</p>
            <p class="text-vibrant-pink font-medium">{{ session('email') }}</p>
        </div>

        <!-- Display Errors -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                @foreach ($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- OTP Form -->
        <form method="POST" action="{{ route('auth.verify-otp') }}">
            @csrf
            <input type="hidden" name="email" value="{{ session('email') }}">
            
            <div class="mb-6">
                <label for="otp" class="block text-sm font-medium text-gray-700 mb-2">Enter OTP</label>
                <input type="text" 
                       id="otp" 
                       name="otp" 
                       placeholder="Enter 6-digit code" 
                       maxlength="6" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink focus:border-transparent text-center text-2xl tracking-widest"
                       required>
            </div>
            
            <!-- Verify Button - Always Enabled -->
            <button type="submit" 
                    id="verify-btn"
                    class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-3 rounded-lg transition-colors mb-4">
                Verify OTP
            </button>
        </form>
        
        <!-- Resend OTP Section -->
        <div class="text-center">
            <!-- Timer Display -->
            <div id="timer-section" class="mb-4">
                <p class="text-sm text-gray-500">
                    Resend OTP in <span id="timer" class="font-medium text-vibrant-pink">60</span> seconds
                </p>
            </div>

            <!-- Resend Form -->
            <form method="POST" action="{{ route('auth.resend-otp') }}" id="resend-form" style="display: none;">
                @csrf
                <input type="hidden" name="email" value="{{ session('email') }}">
                <button type="submit" 
                        id="resend-btn"
                        class="text-vibrant-pink hover:underline text-sm font-medium">
                    Didn't receive code? Resend OTP
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Countdown timer - only affects resend button, not verify button
let timeLeft = 60;
const timer = document.getElementById('timer');
const timerSection = document.getElementById('timer-section');
const resendForm = document.getElementById('resend-form');

const countdown = setInterval(() => {
    timeLeft--;
    timer.textContent = timeLeft;
    
    if (timeLeft <= 0) {
        clearInterval(countdown);
        // Show resend form and hide timer
        timerSection.style.display = 'none';
        resendForm.style.display = 'block';
    }
}, 1000);

// Auto-focus OTP input
document.getElementById('otp').focus();

// Auto-format OTP input
document.getElementById('otp').addEventListener('input', function(e) {
    // Only allow numbers
    this.value = this.value.replace(/[^0-9]/g, '');
    
    // Auto-submit when 6 digits entered (optional)
    if (this.value.length === 6) {
        // Optionally auto-submit the form
        // document.getElementById('verify-form').submit();
    }
});
</script>
@endsection
