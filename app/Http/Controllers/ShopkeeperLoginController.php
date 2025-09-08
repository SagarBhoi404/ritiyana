<?php
// app/Http/Controllers/Auth/ShopkeeperLoginController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ShopkeeperLoginController extends Controller
{
    // Remove the constructor completely - we'll handle middleware in routes

    public function showLoginForm()
    {
        return view('auth.shopkeeper-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');
        
        // Find user by email
        $user = User::where('email', $credentials['email'])->first();
        
        // Check if user exists and has shopkeeper role
        if ($user && $user->hasRole('shopkeeper') && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            
            // Update last login
            $user->update(['last_login_at' => now()]);
            
            return redirect()->intended('/shopkeeper/dashboard')
                           ->with('success', 'Welcome back, Shopkeeper!');
        }

        return back()->withErrors([
            'email' => 'Invalid shopkeeper credentials or you do not have shopkeeper access.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('shopkeeper.login')
                        ->with('success', 'You have been logged out successfully.');
    }
}
