<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CashfreeService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(CashfreeService::class, function ($app) {
            return new CashfreeService();
        });
    }

    public function boot()
    {
        //
    }
}
