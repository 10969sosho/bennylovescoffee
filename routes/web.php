<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\FlashSaleController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\FeaturedProductController;
use App\Http\Controllers\BiteshipController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\AdminChatController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.alias');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/featured', [ProductController::class, 'featured'])->name('products.featured');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/articles', [App\Http\Controllers\ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article}', [App\Http\Controllers\ArticleController::class, 'show'])->name('articles.show');

// Authentication routes (excluding register and login to use custom)
Auth::routes(['register' => false, 'login' => false]);

// Custom registration and login
Route::get('/register', [App\Http\Controllers\CustomAuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\CustomAuthController::class, 'register'])->name('register.submit');

// Custom login with email verification
Route::get('/login', [App\Http\Controllers\CustomLoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\CustomLoginController::class, 'login'])->name('login.submit');

// Google OAuth routes
Route::get('/auth/google', [App\Http\Controllers\GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [App\Http\Controllers\GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Email verification routes (no auth required for initial verification)
Route::get('/verify-email', [App\Http\Controllers\EmailVerificationController::class, 'show'])->name('verification.show');
Route::post('/verify-email', [App\Http\Controllers\EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::post('/resend-verification', [App\Http\Controllers\EmailVerificationController::class, 'resend'])->name('verification.resend');
Route::get('/verification-success', [App\Http\Controllers\EmailVerificationController::class, 'success'])->name('verification.success');

// Protected routes (require authentication and email verification)
Route::middleware(['auth', 'verified'])->group(function () {
    // Product detail routes
    
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{slug}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/track', [OrderController::class, 'track'])->name('orders.track');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('orders.checkout');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{order}/upload-payment', [OrderController::class, 'uploadPaymentProof'])->name('orders.uploadPayment');
    Route::post('/orders/{order}/confirm-delivery', [OrderController::class, 'confirmDelivery'])->name('orders.confirmDelivery');
    
    // Biteship Routes
    Route::get('/biteship/search-area', [BiteshipController::class, 'searchArea'])->name('biteship.search_area');
    Route::post('/biteship/rates', [BiteshipController::class, 'getRates'])->name('biteship.rates');
    
    // Message/Chat routes (Client side - chat with admin only)
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::get('/messages/fetch', [MessageController::class, 'fetch'])->name('messages.fetch');
    Route::get('/messages/unread-count', [MessageController::class, 'unreadCount'])->name('messages.unreadCount');
});

// Admin routes (completely separate from customer site)
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin login routes (no middleware to avoid redirect loop)
    Route::get('/login', [App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Admin\AuthController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('logout');
    
    // Protected admin routes
    Route::middleware('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // User Management
        Route::resource('users', UserController::class);
        
        // Category Management
        Route::resource('categories', CategoryController::class);
        
        // Brand Management
        Route::resource('brands', BrandController::class);
        
        // Flash Sale Management
        Route::resource('flash-sales', FlashSaleController::class);
        Route::post('/flash-sales/available-slots', [FlashSaleController::class, 'getAvailableSlots'])->name('flash-sales.available-slots');
        Route::post('/flash-sales/{flashSale}/toggle-status', [FlashSaleController::class, 'toggleStatus'])->name('flash-sales.toggle-status');
        
        // Article Management
        Route::resource('articles', ArticleController::class);
        
        // Banner Management (simplified - no edit route)
        Route::get('/banners', [BannerController::class, 'index'])->name('banners.index');
        Route::get('/banners/create', [BannerController::class, 'create'])->name('banners.create');
        Route::post('/banners', [BannerController::class, 'store'])->name('banners.store');
        Route::delete('/banners/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');
        Route::post('/banners/update-position', [BannerController::class, 'updatePosition'])->name('banners.update-position');
        Route::post('/banners/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])->name('banners.toggle-status');
        
        // Banner Section 2 Management
        Route::get('/banner-section2', [App\Http\Controllers\Admin\BannerSection2Controller::class, 'index'])->name('banner-section2.index');
        Route::get('/banner-section2/create', [App\Http\Controllers\Admin\BannerSection2Controller::class, 'create'])->name('banner-section2.create');
        Route::post('/banner-section2', [App\Http\Controllers\Admin\BannerSection2Controller::class, 'store'])->name('banner-section2.store');
        Route::delete('/banner-section2/{bannerSection2}', [App\Http\Controllers\Admin\BannerSection2Controller::class, 'destroy'])->name('banner-section2.destroy');
        Route::post('/banner-section2/update-position', [App\Http\Controllers\Admin\BannerSection2Controller::class, 'updatePosition'])->name('banner-section2.update-position');
        Route::post('/banner-section2/{bannerSection2}/toggle-status', [App\Http\Controllers\Admin\BannerSection2Controller::class, 'toggleStatus'])->name('banner-section2.toggle-status');
        
        
        // Featured Products Management
        Route::get('/featured-products', [FeaturedProductController::class, 'index'])->name('featured-products.index');
        Route::get('/featured-products/create', [FeaturedProductController::class, 'create'])->name('featured-products.create');
        Route::post('/featured-products', [FeaturedProductController::class, 'store'])->name('featured-products.store');
        Route::post('/featured-products/update-order', [FeaturedProductController::class, 'updateOrder'])->name('featured-products.update-order');
        Route::post('/featured-products/{featuredProduct}/toggle-status', [FeaturedProductController::class, 'toggleStatus'])->name('featured-products.toggle-status');
        Route::delete('/featured-products/{featuredProduct}', [FeaturedProductController::class, 'destroy'])->name('featured-products.destroy');
        Route::get('/featured-products/search-products', [FeaturedProductController::class, 'searchProducts'])->name('featured-products.search-products');
        
        // Product Management
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
        Route::delete('/products/{product}/delete-main-photo', [AdminController::class, 'deleteMainPhoto'])->name('products.delete-main-photo');
        Route::delete('/products/{product}/delete-additional-photo', [AdminController::class, 'deleteAdditionalPhoto'])->name('products.delete-additional-photo');
        
        // Order Management
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::patch('/orders/{order}/tracking', [AdminOrderController::class, 'updateTracking'])->name('orders.update-tracking');
        Route::patch('/orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel');
        Route::patch('/orders/{order}/deliver', [AdminOrderController::class, 'markAsDelivered'])->name('orders.deliver');
        
        // Chat Management (Admin side - chat with multiple users)
        Route::get('/chat', [AdminChatController::class, 'index'])->name('chat.index');
        Route::get('/chat/{user}', [AdminChatController::class, 'show'])->name('chat.show');
        Route::post('/chat/{user}', [AdminChatController::class, 'store'])->name('chat.store');
        Route::get('/chat/{user}/fetch', [AdminChatController::class, 'fetch'])->name('chat.fetch');
        Route::get('/chat/unread-count', [AdminChatController::class, 'unreadCount'])->name('chat.unreadCount');
        
        // Client Management
        Route::get('/clients', [AdminChatController::class, 'clients'])->name('clients.index');

        // Admin Profile & Password Change
    Route::get('/profile', [App\Http\Controllers\Admin\AdminProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/send-code', [App\Http\Controllers\Admin\AdminProfileController::class, 'sendVerificationCode'])->name('profile.password.send_code');
    Route::put('/profile/password', [App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('profile.password.update');
    });
});
