<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Redirect based on role
        if ($user->hasRole('admin')) {
            return redirect('/admin/dashboard');
        } elseif ($user->hasRole('shopkeeper')) {
            return redirect('/shopkeeper/dashboard');
        }
        
        return view('dashboard.customer', compact('user'));
    }

    public function admin()
    {
        $user = Auth::user();
        return view('dashboard.admin', compact('user'));
    }

    public function shopkeeper()
    {
        $user = Auth::user();
        return view('dashboard.shopkeeper', compact('user'));
    }
}
