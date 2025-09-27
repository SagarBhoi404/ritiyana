@extends('layouts.app')

@section('title', 'Account Settings - Shree Samagri')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header with Breadcrumbs -->
    {{-- <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex py-3 text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-vibrant-pink transition-colors">Dashboard</a>
                <i data-lucide="chevron-right" class="w-4 h-4 mx-2"></i>
                <span class="text-gray-900">Settings</span>
            </nav>
            
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i data-lucide="settings" class="w-6 h-6 mr-3 text-vibrant-pink"></i>
                        Account Settings
                    </h1>
                    <p class="text-gray-600 mt-1">Manage your account preferences and security settings</p>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Customer Navigation Component -->
        <x-customer-navigation />

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 flex items-center animate-fade-in">
                <div class="flex">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-400 mr-3 mt-0.5"></i>
                    <div>
                        <h4 class="text-green-800 font-medium">Success!</h4>
                        <p class="text-green-700 text-sm">{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="ml-auto text-green-400 hover:text-green-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        <!-- Settings Grid -->
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Settings Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i data-lucide="menu" class="w-5 h-5 mr-2 text-vibrant-pink"></i>
                            Settings Menu
                        </h3>
                    </div>
                    <div class="p-6">
                        <nav class="space-y-2">
                            <a href="#security" onclick="showSection('security')" 
                               class="settings-tab active flex items-center p-3 rounded-lg text-vibrant-pink bg-pink-50 transition-all">
                                <i data-lucide="shield" class="w-4 h-4 mr-3"></i>
                                <span class="font-medium">Security</span>
                            </a>
                            <a href="#preferences" onclick="showSection('preferences')" 
                               class="settings-tab flex items-center p-3 rounded-lg text-gray-700 hover:text-vibrant-pink hover:bg-pink-50 transition-all">
                                <i data-lucide="sliders" class="w-4 h-4 mr-3"></i>
                                <span class="font-medium">Preferences</span>
                            </a>
                            <a href="#notifications" onclick="showSection('notifications')" 
                               class="settings-tab flex items-center p-3 rounded-lg text-gray-700 hover:text-vibrant-pink hover:bg-pink-50 transition-all">
                                <i data-lucide="bell" class="w-4 h-4 mr-3"></i>
                                <span class="font-medium">Notifications</span>
                            </a>
                            <a href="#privacy" onclick="showSection('privacy')" 
                               class="settings-tab flex items-center p-3 rounded-lg text-gray-700 hover:text-vibrant-pink hover:bg-pink-50 transition-all">
                                <i data-lucide="eye" class="w-4 h-4 mr-3"></i>
                                <span class="font-medium">Privacy</span>
                            </a>
                        </nav>
                    </div>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="lg:col-span-2">
                <!-- Security Section -->
                <div id="security-section" class="settings-section">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i data-lucide="shield" class="w-5 h-5 mr-2 text-vibrant-pink"></i>
                                Password & Security
                            </h3>
                        </div>
                        <div class="p-6">
                            <!-- Change Password Form -->
                            <form action="{{ route('password.change') }}" method="POST" id="passwordChangeForm">
                                @csrf
                                <div class="space-y-6">
                                    <div class="grid grid-cols-1 gap-6">
                                        <!-- Current Password -->
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Current Password <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input type="password" 
                                                       name="current_password" 
                                                       id="current_password"
                                                       class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-vibrant-pink focus:border-vibrant-pink transition-all @error('current_password') border-red-500 @enderror"
                                                       placeholder="Enter your current password"
                                                       required>
                                                <i data-lucide="lock" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                                <button type="button" onclick="togglePassword('current_password')" 
                                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                                    <i data-lucide="eye" id="current_password_eye"></i>
                                                </button>
                                            </div>
                                            @error('current_password') 
                                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                                    {{ $message }}
                                                </p> 
                                            @enderror
                                        </div>

                                        <!-- New Password -->
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                New Password <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input type="password" 
                                                       name="password" 
                                                       id="new_password"
                                                       class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-vibrant-pink focus:border-vibrant-pink transition-all @error('password') border-red-500 @enderror"
                                                       placeholder="Enter new password"
                                                       minlength="8"
                                                       required>
                                                <i data-lucide="key" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                                <button type="button" onclick="togglePassword('new_password')" 
                                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                                    <i data-lucide="eye" id="new_password_eye"></i>
                                                </button>
                                            </div>
                                            @error('password') 
                                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                                    {{ $message }}
                                                </p> 
                                            @enderror
                                        </div>

                                        <!-- Confirm New Password -->
                                        <div class="form-group">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Confirm New Password <span class="text-red-500">*</span>
                                            </label>
                                            <div class="relative">
                                                <input type="password" 
                                                       name="password_confirmation" 
                                                       id="confirm_password"
                                                       class="block w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-vibrant-pink focus:border-vibrant-pink transition-all @error('password_confirmation') border-red-500 @enderror"
                                                       placeholder="Confirm new password"
                                                       minlength="8"
                                                       required>
                                                <i data-lucide="key" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                                <button type="button" onclick="togglePassword('confirm_password')" 
                                                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                                    <i data-lucide="eye" id="confirm_password_eye"></i>
                                                </button>
                                            </div>
                                            @error('password_confirmation') 
                                                <p class="text-red-500 text-sm mt-1 flex items-center">
                                                    <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                                    {{ $message }}
                                                </p> 
                                            @enderror
                                            <div id="password_match_message" class="text-sm mt-1"></div>
                                        </div>
                                    </div>

                                    <!-- Password Strength Indicator -->
                                    <div class="password-strength-container" style="display: none;">
                                        <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                            <span>Password Strength:</span>
                                            <span id="strength-text" class="font-medium">Weak</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="strength-bar" class="h-2 rounded-full transition-all duration-300 bg-red-500" style="width: 0%"></div>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-2">
                                            Password should contain at least 8 characters with uppercase, lowercase, numbers and special characters.
                                        </div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="flex justify-end pt-4 border-t border-gray-200">
                                        <button type="submit" 
                                                class="inline-flex items-center px-6 py-3 bg-vibrant-pink hover:bg-vibrant-pink-dark text-white rounded-lg focus:ring-2 focus:ring-offset-2 focus:ring-vibrant-pink transition-all disabled:opacity-50"
                                                id="passwordSubmitButton">
                                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                                            <span id="passwordSubmitText">Change Password</span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                  
                </div>

                <!-- Preferences Section -->
                <div id="preferences-section" class="settings-section" style="display: none;">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i data-lucide="sliders" class="w-5 h-5 mr-2 text-vibrant-pink"></i>
                                Account Preferences
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-6">
                                <!-- Language Preference -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Language</p>
                                        <p class="text-xs text-gray-500">Choose your preferred language</p>
                                    </div>
                                    <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-vibrant-pink focus:border-vibrant-pink">
                                        <option value="en">English</option>
                                        <option value="hi">हिंदी</option>
                                        <option value="mr">मराठी</option>
                                    </select>
                                </div>

                                <!-- Currency Preference -->
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Currency</p>
                                        <p class="text-xs text-gray-500">Your preferred currency for pricing</p>
                                    </div>
                                    <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-vibrant-pink focus:border-vibrant-pink">
                                        <option value="INR">₹ INR</option>
                                        <option value="USD">$ USD</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notifications Section -->
                <div id="notifications-section" class="settings-section" style="display: none;">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i data-lucide="bell" class="w-5 h-5 mr-2 text-vibrant-pink"></i>
                                Notification Settings
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Email Notifications -->
                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Email Notifications</p>
                                        <p class="text-xs text-gray-500">Receive order updates via email</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-vibrant-pink"></div>
                                    </label>
                                </div>

                                <!-- SMS Notifications -->
                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">SMS Notifications</p>
                                        <p class="text-xs text-gray-500">Receive order updates via SMS</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-vibrant-pink"></div>
                                    </label>
                                </div>

                                <!-- Marketing Emails -->
                                <div class="flex items-center justify-between py-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Marketing Emails</p>
                                        <p class="text-xs text-gray-500">Receive promotional offers and updates</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-vibrant-pink"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Privacy Section -->
                <div id="privacy-section" class="settings-section" style="display: none;">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i data-lucide="eye" class="w-5 h-5 mr-2 text-vibrant-pink"></i>
                                Privacy Settings
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <!-- Profile Visibility -->
                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Profile Visibility</p>
                                        <p class="text-xs text-gray-500">Make your profile visible to other users</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-vibrant-pink"></div>
                                    </label>
                                </div>

                                <!-- Data Collection -->
                                <div class="flex items-center justify-between py-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Analytics Data Collection</p>
                                        <p class="text-xs text-gray-500">Help us improve by sharing usage data</p>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" checked>
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-pink-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-vibrant-pink"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fade-in {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}

.settings-tab.active {
    color: #ec4899;
    background-color: #fdf2f8;
}

.password-strength-container {
    transition: all 0.3s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength checker
    const newPassword = document.getElementById('new_password');
    const confirmPassword = document.getElementById('confirm_password');
    const strengthContainer = document.querySelector('.password-strength-container');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');
    const passwordMatchMessage = document.getElementById('password_match_message');

    function checkPasswordStrength(password) {
        let strength = 0;
        let text = 'Very Weak';
        let color = '#ef4444';

        if (password.length >= 8) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        switch (strength) {
            case 0:
            case 1:
                text = 'Very Weak';
                color = '#ef4444';
                break;
            case 2:
                text = 'Weak';
                color = '#f97316';
                break;
            case 3:
                text = 'Fair';
                color = '#eab308';
                break;
            case 4:
                text = 'Good';
                color = '#22c55e';
                break;
            case 5:
                text = 'Strong';
                color = '#16a34a';
                break;
        }

        return { strength, text, color };
    }

    newPassword.addEventListener('input', function() {
        if (this.value.length > 0) {
            strengthContainer.style.display = 'block';
            const result = checkPasswordStrength(this.value);
            strengthBar.style.width = (result.strength * 20) + '%';
            strengthBar.style.backgroundColor = result.color;
            strengthText.textContent = result.text;
            strengthText.style.color = result.color;
        } else {
            strengthContainer.style.display = 'none';
        }
        
        checkPasswordMatch();
    });

    confirmPassword.addEventListener('input', checkPasswordMatch);

    function checkPasswordMatch() {
        if (confirmPassword.value.length > 0) {
            if (newPassword.value === confirmPassword.value) {
                passwordMatchMessage.innerHTML = '<i data-lucide="check" class="w-4 h-4 inline mr-1"></i>Passwords match';
                passwordMatchMessage.className = 'text-green-600 text-sm mt-1 flex items-center';
            } else {
                passwordMatchMessage.innerHTML = '<i data-lucide="x" class="w-4 h-4 inline mr-1"></i>Passwords do not match';
                passwordMatchMessage.className = 'text-red-600 text-sm mt-1 flex items-center';
            }
            
            // Re-render lucide icons
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        } else {
            passwordMatchMessage.innerHTML = '';
        }
    }

    // Form submission
    document.getElementById('passwordChangeForm').addEventListener('submit', function() {
        const submitButton = document.getElementById('passwordSubmitButton');
        const submitText = document.getElementById('passwordSubmitText');
        
        submitButton.disabled = true;
        submitText.textContent = 'Changing Password...';
    });

    // Auto-hide success messages
    const successMessages = document.querySelectorAll('.bg-green-50');
    successMessages.forEach(function(message) {
        setTimeout(function() {
            message.style.transition = 'opacity 0.5s ease-out';
            message.style.opacity = '0';
            setTimeout(function() {
                message.style.display = 'none';
            }, 500);
        }, 5000);
    });
});

function showSection(sectionName) {
    // Hide all sections
    const sections = document.querySelectorAll('.settings-section');
    sections.forEach(section => section.style.display = 'none');
    
    // Show selected section
    document.getElementById(sectionName + '-section').style.display = 'block';
    
    // Update active tab
    const tabs = document.querySelectorAll('.settings-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    event.target.closest('.settings-tab').classList.add('active');
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const eye = document.getElementById(fieldId + '_eye');
    
    if (field.type === 'password') {
        field.type = 'text';
        eye.setAttribute('data-lucide', 'eye-off');
    } else {
        field.type = 'password';
        eye.setAttribute('data-lucide', 'eye');
    }
    
    // Re-render lucide icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
}

function toggle2FA(element) {
    if (element.checked) {
        alert('2FA setup will be implemented in future updates.');
        element.checked = false;
    }
}
</script>
@endsection
