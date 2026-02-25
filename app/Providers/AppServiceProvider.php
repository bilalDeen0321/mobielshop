<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Setting;
use Illuminate\Pagination\Paginator;
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
        View::share('navCategories', Category::orderBy('name')->get());
        View::composer('layouts.app', function ($view) {
            $view->with('themePrimary', Setting::get('theme_primary', '#00b4d8'));
            $view->with('themeSecondary', Setting::get('theme_secondary', '#0f172a'));
            $view->with('themeAccent', Setting::get('theme_accent', '#f59e0b'));
        });
        Paginator::useBootstrapFive();
    }
}
