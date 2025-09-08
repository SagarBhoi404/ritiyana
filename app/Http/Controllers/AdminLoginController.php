<?php
// app/Http/Controllers/Auth/AdminLoginController.php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminLoginController extends Controller
{
    // Remove the constructor completely - we'll handle middleware in routes

    public function showLoginForm()
    {
        return view('auth.admin-login');
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
        
        // Check if user exists and has admin role
        if ($user && $user->hasRole('admin') && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user);
            
            // Update last login
            $user->update(['last_login_at' => now()]);
            
            return redirect()->intended('/admin/dashboard')
                           ->with('success', 'Welcome back, Admin!');
        }

        return back()->withErrors([
            'email' => 'Invalid admin credentials or you do not have admin access.',
        ])->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')
                        ->with('success', 'You have been logged out successfully.');
    }
}
