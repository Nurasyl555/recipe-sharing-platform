<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Recipe;
use App\Policies\RecipePolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register model policies.
     */
    protected $policies = [
        Recipe::class => RecipePolicy::class,
    ];

    public function boot(): void
    {
        // Register policies
        $this->registerPolicies();

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

    /**
     * Helper to register policies array.
     */
    protected function registerPolicies(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
