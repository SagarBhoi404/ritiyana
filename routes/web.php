<?php

use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ShopkeeperLoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OtpAuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PujaController;
use App\Http\Controllers\PujaKitController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/all-kits', function () {
    return view('all-kits');
})->name('all-kits');

Route::get('/upcoming-pujas', function () {
    return view('upcoming-pujas');
})->name('upcoming-pujas');

Route::get('/consult', function () {
    return view('consult');
})->name('consult');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::get('/product/{id}', function ($id) {
    return view('product-detail', compact('id'));
})->name('product.detail');

Route::get('/my-orders', function () {
    return view('my-orders');
})->name('my-orders');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

Route::get('/test', function () {
    return view('checkout');
})->name('test');


// Customer OTP Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [OtpAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/auth/send-otp', [OtpAuthController::class, 'sendOtp'])->name('auth.send-otp');
    Route::get('/auth/verify-otp', [OtpAuthController::class, 'showVerifyOtpForm'])->name('auth.verify-otp-form');
    Route::post('/auth/verify-otp', [OtpAuthController::class, 'verifyOtp'])->name('auth.verify-otp');
    Route::post('/auth/resend-otp', [OtpAuthController::class, 'resendOtp'])->name('auth.resend-otp');
    Route::get('/auth/complete-profile', [OtpAuthController::class, 'showCompleteProfileForm'])->name('auth.complete-profile-form');
    Route::post('/auth/complete-profile', [OtpAuthController::class, 'completeProfile'])->name('auth.complete-profile');
});

// Admin Authentication Routes - Apply guest middleware properly
Route::prefix('admin')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    });
});

// Shopkeeper Authentication Routes - Apply guest middleware properly
Route::prefix('shopkeeper')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [ShopkeeperLoginController::class, 'showLoginForm'])->name('shopkeeper.login');
        Route::post('/login', [ShopkeeperLoginController::class, 'login'])->name('shopkeeper.login.submit');
    });
});

// Dashboard Routes
Route::middleware('auth')->group(function () {
    // Customer Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Dashboard and Authentication Routes
    Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

        // Admin Logout
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        // Orders Route (using view helper for now)
        Route::view('/orders', 'admin.orders.index')->name('orders.index');

        // Users Management Routes (FIXED - removed duplicates)
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        // Products routes
        // Route::view('/products', 'admin.products.index')->name('products.index');
        // Route::view('/products/create', 'admin.products.create')->name('products.create');

        // Categories routes
        // Route::view('/categories', 'admin.categories.index')->name('categories.index');
        // Categories - Full CRUD
        Route::resource('categories', CategoryController::class);

        // Products - Full CRUD  
        Route::resource('products', ProductController::class);

        // Pujas Management
        Route::resource('pujas', PujaController::class);

        // Puja Kits Management
        Route::resource('puja-kits', PujaKitController::class);

        // Inventory routes
        Route::view('/inventory', 'admin.inventory.index')->name('inventory.index');

        // Role routes
        // Route::view('/roles', 'admin.roles.index')->name('roles.index');
        Route::resource('roles', RoleController::class);
        Route::patch('/roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');

        // Puja Kits routes
        // Route::view('/puja-kits', 'admin.puja-kits.index')->name('puja-kits.index');

        // Analytics routes
        Route::view('/analytics', 'admin.analytics.index')->name('analytics.index');

        // Settings routes
        Route::view('/settings', 'admin.settings.index')->name('settings.index');
    });

    // Shopkeeper Dashboard and Logout
    Route::prefix('shopkeeper')->middleware('role:shopkeeper')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'shopkeeper'])->name('shopkeeper.dashboard');
        Route::post('/logout', [ShopkeeperLoginController::class, 'logout'])->name('shopkeeper.logout');

        // Products routes
        Route::view('/products', 'shopkeeper.products.index')->name('shopkeeper.products.index');
        Route::view('/products/create', 'shopkeeper.products.create')->name('shopkeeper.products.create');

        // Orders routes
        Route::view('/orders', 'shopkeeper.orders.index')->name('shopkeeper.orders.index');

        // Inventory routes
        Route::view('/inventory', 'shopkeeper.inventory.index')->name('shopkeeper.inventory.index');


        // Analytics routes
        Route::view('/analytics', 'shopkeeper.analytics.index')->name('shopkeeper.analytics.index');

        // Settings routes
        Route::view('/settings', 'shopkeeper.settings.index')->name('shopkeeper.settings.index');
    });

    // Customer Logout
    Route::post('/logout', [OtpAuthController::class, 'logout'])->name('logout');
});

// // Optional: Login selection page
// Route::get('/', function () {
//     return view('auth.login-selection');
// })->name('home');
