<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Model3D;
use App\Observers\Model3DObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model3D::observe(Model3DObserver::class);
    }
}
