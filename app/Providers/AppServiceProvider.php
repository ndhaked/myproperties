<?php

namespace App\Providers;

use App\Models\Admin;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
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
        Authenticate::redirectUsing(fn ($request) => $request->is('admin/*')
            ? route('admin.login')
            : route('home'));

        RedirectIfAuthenticated::redirectUsing(fn ($request) => $request->is('admin/*')
            ? route('admin.dashboard')
            : route('home'));

        Gate::before(fn ($user, string $ability) => $user instanceof Admin && $user->hasRole('Super Admin')
            ? true
            : null);
    }
}
