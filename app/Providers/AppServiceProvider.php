<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

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

        // Esta linea de codigo hace que el "Super-Admin" tenga todos los permisos, mas no tiene todos los Roles
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super-Admin') ? true : null;
            // return $user->isAdmin() ? true : null;
        });
    }
}
