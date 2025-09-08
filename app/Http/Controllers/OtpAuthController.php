<?php
// app/Http/Controllers/Auth/OtpAuthController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OtpAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $otp = rand(100000, 999999); // Generate 6-digit OTP

        // Store OTP in cache for 10 minutes
        Cache::put("otp_{$email}", $otp, now()->addMinutes(10));
        
        // Store email in session
        session(['email' => $email]);

        // Send OTP via email
        try {
            Mail::send('emails.otp', ['otp' => $otp], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Ritiyana - Your Login OTP');
            });

            return redirect()->route('auth.verify-otp-form')
                           ->with('success', 'OTP sent successfully to your email address.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Failed to send OTP. Please try again.']);
        }
    }

    public function showVerifyOtpForm()
    {
        if (!session('email')) {
            return redirect()->route('login');
        }

        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6'
        ]);

        $email = $request->email;
        $inputOtp = $request->otp;
        $cachedOtp = Cache::get("otp_{$email}");

        if (!$cachedOtp || $cachedOtp != $inputOtp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        // Clear OTP from cache
        Cache::forget("otp_{$email}");

        // Check if user exists
        $user = User::where('email', $email)->first();

        if ($user) {
            // User exists, log them in
            Auth::login($user);
            
            // Update last login
            $user->update(['last_login_at' => now()]);
            
            // Redirect based on role
            if ($user->hasRole('admin')) {
                return redirect('/admin/dashboard')->with('success', 'Welcome back, Admin!');
            } elseif ($user->hasRole('shopkeeper')) {
                return redirect('/shopkeeper/dashboard')->with('success', 'Welcome back, Shopkeeper!');
            } else {
                return redirect('/dashboard')->with('success', 'Welcome back to Ritiyana!');
            }
        } else {
            // New user, redirect to profile completion
            session(['verified_email' => $email]);
            return redirect()->route('auth.complete-profile-form');
        }
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        return $this->sendOtp($request);
    }

    public function showCompleteProfileForm()
    {
        if (!session('verified_email')) {
            return redirect()->route('login');
        }

        return view('auth.complete-profile');
    }

    public function completeProfile(Request $request)
    {
        if (!session('verified_email')) {
            return redirect()->route('login');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
        ]);

        $email = session('verified_email');

        // Create new user
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'password' => bcrypt(Str::random(16)), // Random password since we use OTP
            'email_verified_at' => now(),
            'status' => 'active',
            'last_login_at' => now(),
        ]);

        // Assign default role (user/customer) - FIXED
        $customerRole = Role::where('name', 'user')->first();
        if ($customerRole) {
            $user->roles()->attach($customerRole->id);
        }

        // Log in the user
        Auth::login($user);

        // Clear session data
        session()->forget(['verified_email', 'email']);

        return redirect('/dashboard')
                       ->with('success', 'Welcome to Ritiyana! Your account has been created successfully.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('home')
                        ->with('success', 'You have been logged out successfully.');
    }
}
