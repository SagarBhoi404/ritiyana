<?php
// routes/web.php

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

// Import Vendor Controllers
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Banner\BannerController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorOrderController;
use App\Http\Controllers\Vendor\VendorProfileController;
use App\Http\Controllers\Vendor\VendorAnalyticsController;
use App\Http\Controllers\Vendor\VendorPujaController;
use Illuminate\Support\Facades\Route;

// ===== PUBLIC ROUTES =====
Route::get('/', [HomeController::class, 'home'])->name('home');

// Product routes
Route::get('/product/{product:slug}', [App\Http\Controllers\User\ProductController::class, 'show'])->name('product.show');
Route::get('/products', [App\Http\Controllers\User\ProductController::class, 'index'])->name('products.index');

// Puja Kit routes
Route::get('/puja-kit/{pujaKit:slug}', [App\Http\Controllers\User\PujaKitController::class, 'show'])->name('puja-kits.show');
Route::get('/puja-kits', [App\Http\Controllers\User\PujaKitController::class, 'index'])->name('puja-kits.index');


// Category routes
Route::redirect('/category/{category}', '/products?category={category}', 301)->name('category.show');





// Route::get('/all-kits', function () {
//     return view('all-kits');
// })->name('all-kits');

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

// ===== GUEST AUTHENTICATION ROUTES =====
Route::middleware('guest')->group(function () {
    // Customer OTP Authentication
    Route::get('/login', [OtpAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/auth/send-otp', [OtpAuthController::class, 'sendOtp'])->name('auth.send-otp');
    Route::get('/auth/verify-otp', [OtpAuthController::class, 'showVerifyOtpForm'])->name('auth.verify-otp-form');
    Route::post('/auth/verify-otp', [OtpAuthController::class, 'verifyOtp'])->name('auth.verify-otp');
    Route::post('/auth/resend-otp', [OtpAuthController::class, 'resendOtp'])->name('auth.resend-otp');
    Route::get('/auth/complete-profile', [OtpAuthController::class, 'showCompleteProfileForm'])->name('auth.complete-profile-form');
    Route::post('/auth/complete-profile', [OtpAuthController::class, 'completeProfile'])->name('auth.complete-profile');

    // Admin Authentication
    Route::prefix('admin')->group(function () {
        Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
    });

    // Shopkeeper Authentication
    Route::prefix('shopkeeper')->group(function () {
        Route::get('/login', [ShopkeeperLoginController::class, 'showLoginForm'])->name('shopkeeper.login');
        Route::post('/login', [ShopkeeperLoginController::class, 'login'])->name('shopkeeper.login.submit');
    });
});

// ===== AUTHENTICATED ROUTES =====
Route::middleware('auth')->group(function () {
    // Customer Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [OtpAuthController::class, 'logout'])->name('logout');

    // ===== ADMIN ROUTES =====
    Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');


        Route::resource('banners', BannerController::class);
        Route::patch('banners/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banners.toggle-status');


        // User Management
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

        // Category Management
        Route::resource('categories', CategoryController::class);

        // Product Management
        Route::resource('products', ProductController::class);
        Route::get('/products/vendor-products', [ProductController::class, 'vendorProducts'])->name('products.vendor-products');

        // Puja Management
        Route::resource('pujas', PujaController::class);

        // Puja Kit Management
        Route::resource('puja-kits', PujaKitController::class);

        // Role Management
        Route::resource('roles', RoleController::class);
        Route::patch('/roles/{role}/toggle-status', [RoleController::class, 'toggleStatus'])->name('roles.toggle-status');

        // Vendor Management
        Route::prefix('vendors')->name('vendors.')->group(function () {
            Route::get('/', [VendorController::class, 'index'])->name('index');
            Route::get('/{vendor}', [VendorController::class, 'show'])->name('show');
            Route::patch('/{vendor}/approve', [VendorController::class, 'approve'])->name('approve');
            Route::patch('/{vendor}/reject', [VendorController::class, 'reject'])->name('reject');
            Route::patch('/{vendor}/suspend', [VendorController::class, 'suspend'])->name('suspend');

            // Vendor Product Approvals
            Route::get('/products/approvals', [VendorController::class, 'productApprovals'])->name('products.approvals');
            Route::patch('/products/{product}/approve', [VendorController::class, 'approveProduct'])->name('products.approve');
            Route::patch('/products/{product}/reject', [VendorController::class, 'rejectProduct'])->name('products.reject');
        });

        // Static View Routes
        Route::view('/orders', 'admin.orders.index')->name('orders.index');
        Route::view('/inventory', 'admin.inventory.index')->name('inventory.index');
        Route::view('/analytics', 'admin.analytics.index')->name('analytics.index');
        Route::view('/settings', 'admin.settings.index')->name('settings.index');
    });

    // ===== SHOPKEEPER/VENDOR ROUTES =====
    Route::prefix('shopkeeper')->middleware(['auth', 'role:shopkeeper'])->name('vendor.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Vendor\DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [ShopkeeperLoginController::class, 'logout'])->name('logout');

        // Vendor Profile Management
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::get('/', [VendorProfileController::class, 'show'])->name('show');
            Route::get('/create', [VendorProfileController::class, 'create'])->name('create');
            Route::post('/', [VendorProfileController::class, 'store'])->name('store');
            Route::get('/edit', [VendorProfileController::class, 'edit'])->name('edit');
            Route::put('/', [VendorProfileController::class, 'update'])->name('update');
        });

        // Vendor Product Management
        Route::resource('products', VendorProductController::class);

        // Vendor Order Management
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [VendorOrderController::class, 'index'])->name('index');
            Route::get('/{vendorOrder}', [VendorOrderController::class, 'show'])->name('show');
            Route::patch('/{vendorOrder}/status', [VendorOrderController::class, 'updateStatus'])->name('update-status');
        });

        // Vendor Analytics
        Route::get('/analytics', [VendorAnalyticsController::class, 'index'])->name('analytics.index');

        // Static View Routes (keep your existing ones)
        Route::view('/inventory', 'shopkeeper.inventory.index')->name('inventory.index');
        Route::view('/settings', 'shopkeeper.settings.index')->name('settings.index');


        // Puja Management
        Route::resource('pujas', VendorPujaController::class);
    });
});
