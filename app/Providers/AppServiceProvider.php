<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Remove the database call from here
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::defaultView('vendor.pagination.custom');
        Paginator::defaultSimpleView('vendor.pagination.custom');

        \Carbon\Carbon::setLocale(config('app.locale'));

        // Load the website settings here, after the database connection is established
        $pengaturanWebsite = \App\Models\PengaturanWebsite::first();
        view()->share('global_pengaturan_website', $pengaturanWebsite);
    }
}
