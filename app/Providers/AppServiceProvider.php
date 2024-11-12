<?php

namespace App\Providers;

use App\Models\PremiumUser;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        //
        Inertia::share([
            'premium_users' => function(){
                return PremiumUser::get();
            }
        ]);
    }
}
