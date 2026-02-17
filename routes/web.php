<?php

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
