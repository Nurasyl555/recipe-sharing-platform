<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // The admin can do anything
        Gate::before(function (User $user, string $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        // Who can manage users
        Gate::define('manage-users', function (User $user) {
            return $user->isAdmin();
        });

        // Who can manage other users' recipes
        Gate::define('manage-recipes', function (User $user) {
            return $user->isAdmin();
        });
    }
}
