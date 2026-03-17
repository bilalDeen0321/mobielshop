<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Allow MySQL/MariaDB index key length limit (1000 bytes with utf8mb4)
        Schema::defaultStringLength(191);

        // Skip DB-dependent view data when running in console (e.g. migrations)
        if (! $this->app->runningInConsole()) {
        View::share('navCategories', Category::orderBy('name')->get());
        View::composer('layouts.app', function ($view) {
            $view->with('themePrimary', Setting::get('theme_primary', '#00b4d8'));
            $view->with('themeSecondary', Setting::get('theme_secondary', '#0f172a'));
            $view->with('themeAccent', Setting::get('theme_accent', '#f59e0b'));
            $view->with('whatsappNumber', Setting::get('whatsapp_number', ''));
            $view->with('currency', Setting::get('currency', config('currencies.default', '£')));
        });
        View::share('currency', Setting::get('currency', config('currencies.default', '£')));
        View::composer('admin.layout', function ($view) {
            $lowStockCount = Product::whereColumn('stock_quantity', '<', 'minimum_stock_limit')
                ->where('minimum_stock_limit', '>', 0)->count();
            $pendingWebsiteOrdersCount = Order::where('status', 'pending')->count();
            $view->with('lowStockCount', $lowStockCount);
            $view->with('pendingWebsiteOrdersCount', $pendingWebsiteOrdersCount);
        });
        }
        Paginator::useBootstrapFive();
    }
}
