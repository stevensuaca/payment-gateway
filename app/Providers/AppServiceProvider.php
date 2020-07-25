<?php

namespace App\Providers;

use Dnetix\Redirection\PlacetoPay;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        return $this->app->singleton(PlacetoPay::class, function ($app) {
            return new PlacetoPay([
                'login' => env('KEY_PLACETOPAY'),
                'tranKey' => env('TRANKEY_PLACETOPAY'),
                'url' => env('URL_PLACETOPAY'),
            ]);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    public function provides()
    {
        return [
            PlacetoPay::class,
        ];
    }
}
