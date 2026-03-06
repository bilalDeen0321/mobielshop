<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\PurchaseController as AdminPurchaseController;
use App\Http\Controllers\Admin\ReportsController as AdminReportsController;
use App\Http\Controllers\Admin\ReturnController as AdminReturnController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;
use App\Http\Controllers\Admin\PosController as AdminPosController;
use App\Http\Controllers\Admin\SalesController as AdminSalesController;
use App\Http\Controllers\Admin\SupplierController as AdminSupplierController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Pages
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/collections/all', [ProductController::class, 'index'])->name('collections.all');
Route::get('/collections/{brand}', [ProductController::class, 'index'])->name('collections.brand');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/search/suggest', [ProductController::class, 'suggest'])->name('search.suggest');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');

Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/faqs', [PageController::class, 'faqs'])->name('faqs');
Route::get('/testimonials', [PageController::class, 'testimonials'])->name('testimonials');
Route::get('/track-order', [PageController::class, 'trackOrder'])->name('track-order');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');

Route::get('/cart', [PageController::class, 'cart'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/wishlist', [PageController::class, 'wishlist'])->name('wishlist');
Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Redirect /admin to /admin-panel (so Laravel admin routes work; public/admin is used for theme assets)
Route::get('admin', fn () => redirect()->route('admin.dashboard'))->name('admin.redirect');

// Admin (separate guard) - use 'admin-panel' so /admin can serve theme assets from public/admin
Route::prefix('admin-panel')->name('admin.')->group(function () {
    Route::middleware('admin.guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);
    });
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout')->middleware('admin.auth');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('products', AdminProductController::class)->names('products');
        Route::resource('categories', AdminCategoryController::class)->names('categories');
        Route::resource('brands', AdminBrandController::class)->names('brands');
        Route::get('settings', [AdminSettingsController::class, 'index'])->name('settings.index');
        Route::post('settings/theme', [AdminSettingsController::class, 'updateTheme'])->name('settings.theme');
        Route::post('settings/whatsapp', [AdminSettingsController::class, 'updateWhatsApp'])->name('settings.whatsapp');
        Route::post('settings/slider', [AdminSettingsController::class, 'uploadSlider'])->name('settings.slider.upload');
        Route::delete('settings/slider/{slider}', [AdminSettingsController::class, 'deleteSlider'])->name('settings.slider.delete');
        Route::post('settings/slider/reorder', [AdminSettingsController::class, 'reorderSlider'])->name('settings.slider.reorder');
        Route::get('pos', [AdminPosController::class, 'index'])->name('pos.index');
        Route::get('pos/search-products', [AdminPosController::class, 'searchProducts'])->name('pos.search');
        Route::post('pos/complete-sale', [AdminPosController::class, 'completeSale'])->name('pos.complete');
        Route::get('sales', [AdminSalesController::class, 'index'])->name('sales.index');
        Route::get('sales/{sale}', [AdminSalesController::class, 'show'])->name('sales.show');
        Route::resource('customers', AdminCustomerController::class)->names('customers');
        Route::resource('suppliers', AdminSupplierController::class)->names('suppliers');
        Route::get('purchases', [AdminPurchaseController::class, 'index'])->name('purchases.index');
        Route::get('purchases/create', [AdminPurchaseController::class, 'create'])->name('purchases.create');
        Route::post('purchases', [AdminPurchaseController::class, 'store'])->name('purchases.store');
        Route::get('purchases/{purchase}', [AdminPurchaseController::class, 'show'])->name('purchases.show');
        Route::get('inventory', [AdminInventoryController::class, 'index'])->name('inventory.index');
        Route::get('inventory/{product}/history', [AdminInventoryController::class, 'history'])->name('inventory.history');
        Route::post('inventory/{product}/adjust', [AdminInventoryController::class, 'adjust'])->name('inventory.adjust');
        Route::get('returns', [AdminReturnController::class, 'index'])->name('returns.index');
        Route::get('returns/create', [AdminReturnController::class, 'create'])->name('returns.create');
        Route::post('returns', [AdminReturnController::class, 'store'])->name('returns.store');
        Route::get('returns/{returnModel}', [AdminReturnController::class, 'show'])->name('returns.show');
        Route::get('reports', [AdminReportsController::class, 'index'])->name('reports.index');
        Route::get('reports/daily', [AdminReportsController::class, 'dailySales'])->name('reports.daily');
        Route::get('reports/monthly', [AdminReportsController::class, 'monthlySales'])->name('reports.monthly');
        Route::get('reports/top-products', [AdminReportsController::class, 'topProducts'])->name('reports.top-products');
        Route::get('reports/low-stock', [AdminReportsController::class, 'lowStock'])->name('reports.low-stock');
        Route::get('reports/profit', [AdminReportsController::class, 'profit'])->name('reports.profit');
        Route::post('settings/shop', [AdminSettingsController::class, 'updateShop'])->name('settings.shop');
    });
});
