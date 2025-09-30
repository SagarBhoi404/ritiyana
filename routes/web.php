<?php

// routes/web.php

use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\Banner\BannerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OtpAuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PujaController;
use App\Http\Controllers\PujaKitController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShopkeeperLoginController;
use App\Http\Controllers\User\AddressController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Vendor\VendorAnalyticsController;
use App\Http\Controllers\Vendor\VendorOrderController;
use App\Http\Controllers\Vendor\VendorProductController;
use App\Http\Controllers\Vendor\VendorProfileController;
use App\Http\Controllers\Vendor\VendorPujaController;
use App\Http\Controllers\Vendor\VendorPujaKitController;
use Illuminate\Support\Facades\Auth;
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
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

// Cart routes (both guest and authenticated users)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::put('/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/{id}', [CartController::class, 'destroy'])->name('remove');
    Route::delete('/', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
    Route::get('/mini', [CartController::class, 'miniCart'])->name('mini');
    Route::post('/add-puja-kit', [CartController::class, 'addPujaKit'])->name('add-puja-kit');
    Route::post('/validate', [CartController::class, 'validate'])->name('validate');
});

// Payment routes (webhook needs to be public)
Route::prefix('payment')->name('payment.')->group(function () {
    Route::post('/process', [PaymentController::class, 'process'])->name('process');
    Route::get('/success', [PaymentController::class, 'success'])->name('success');
    Route::post('/webhook', [PaymentController::class, 'webhook'])->name('webhook'); // Public webhook
});

// API routes for AJAX checks
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/user/check', function () {
        return response()->json([
            'authenticated' => Auth::check(),
            'user' => Auth::check() ? Auth::user()->only(['id', 'name', 'email']) : null,
        ]);
    })->name('user.check');
});

// Static pages
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

// Legacy routes that need proper implementation
Route::get('/my-orders', function () {
    return redirect()->route('orders.index');
})->name('my-orders');

Route::get('/checkout', function () {
    return redirect()->route('checkout.index');
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
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/settings', [UserController::class, 'settings'])->name('settings');
    Route::post('/settings/password', [UserController::class, 'changePassword'])->name('password.change');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile/update', [UserController::class, 'updateProfile'])->name('profile.update');

    // Checkout routes
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::post('/process', [CheckoutController::class, 'processCheckout'])->name('process');
        Route::get('/success/{orderNumber}', [CheckoutController::class, 'success'])->name('success');
    });

    // Order routes
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/{orderNumber}', [OrderController::class, 'show'])->name('show');
        Route::post('/{orderNumber}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::post('/{orderNumber}/return', [OrderController::class, 'requestReturn'])->name('return');
        Route::get('/{orderNumber}/invoice', [OrderController::class, 'downloadInvoice'])->name('invoice');
        Route::get('/{orderNumber}/track', [OrderController::class, 'track'])->name('track');
        Route::post('/{orderNumber}/retry-payment', [OrderController::class, 'retryPayment'])->name('retry-payment');
    });

    // Address management routes
    Route::prefix('addresses')->name('addresses.')->group(function () {
        Route::get('/', [AddressController::class, 'index'])->name('index');
        Route::get('/create', [AddressController::class, 'create'])->name('create');
        Route::post('/', [AddressController::class, 'store'])->name('store');
        Route::get('/{address}/edit', [AddressController::class, 'edit'])->name('edit');
        Route::put('/{address}', [AddressController::class, 'update'])->name('update');
        Route::delete('/{address}', [AddressController::class, 'destroy'])->name('destroy');
        Route::post('/{address}/default', [AddressController::class, 'setDefault'])->name('set-default');
        Route::get('/ajax', [AddressController::class, 'getAddresses'])->name('ajax');
    });

    // ===== ADMIN ROUTES =====
    Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');
        Route::post('/logout', [AdminLoginController::class, 'logout'])->name('logout');

        // Contact Management
        Route::resource('contacts', ContactController::class);

        // Banner Management
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
        // Route::view('/orders', 'admin.orders.index')->name('orders.index');
        Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::patch('orders/{order}/payment-status', [AdminOrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
        Route::get('orders/export', [AdminOrderController::class, 'export'])->name('orders.export');
        Route::post('orders/bulk-action', [AdminOrderController::class, 'bulkAction'])->name('orders.bulk-action');
        Route::get('orders/{order}/invoice', [AdminOrderController::class, 'printInvoice'])->name('orders.invoice');
        Route::get('orders-analytics', [AdminOrderController::class, 'analytics'])->name('orders.analytics');

        // Route::view('/inventory', 'admin.inventory.index')->name('inventory.index');
 Route::get('inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('inventory/{product}', [InventoryController::class, 'show'])->name('inventory.show');
    Route::post('inventory/{product}/update-stock', [InventoryController::class, 'updateStock'])->name('inventory.update-stock');


        Route::view('/analytics', 'admin.analytics.index')->name('analytics.index');
        Route::view('/settings', 'admin.settings.index')->name('settings.index');
    });

    // ===== SHOPKEEPER/VENDOR ROUTES =====
    Route::prefix('shopkeeper')->middleware(['auth', 'role:shopkeeper'])->name('vendor.')->group(function () {
        // Dashboard
        Route::get('/dashboard', [\App\Http\Controllers\Vendor\DashboardController::class, 'index'])->name('dashboard');
        Route::post('/logout', [ShopkeeperLoginController::class, 'logout'])->name('logout');

    // Puja Kit Management
        // Route::resource('puja-kits', PujaKitController::class);

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

        // Static View Routes
        Route::view('/inventory', 'shopkeeper.inventory.index')->name('inventory.index');
        Route::view('/settings', 'shopkeeper.settings.index')->name('settings.index');

        // Puja Management
        Route::resource('pujas', VendorPujaController::class);
        Route::resource('puja-kits', VendorPujaKitController::class);
        // Route::resource('puja-kits', PujaKitController::class);

    });
});
