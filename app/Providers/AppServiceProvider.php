<?php

namespace App\Providers;

/* @chisel-passkeys */
use App\Http\Controllers\UserProfileController;
/* @end-chisel-passkeys */
use Illuminate\Support\ServiceProvider;
/* @chisel-passkeys */
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController as JetstreamUserProfileController;

/* @end-chisel-passkeys */

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /* @chisel-passkeys */
        $this->app->bind(JetstreamUserProfileController::class, UserProfileController::class);
        /* @end-chisel-passkeys */
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
