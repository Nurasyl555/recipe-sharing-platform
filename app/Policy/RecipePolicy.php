<?php

namespace App\Policies;

use App\Models\Recipe;
use App\Models\User;

class RecipePolicy
{
    /**
     * Admins bypass all checks (defined in AppServiceProvider Gate::before).
     */

    /**
     * Any authenticated user can view published recipes.
     */
    public function view(?User $user, Recipe $recipe): bool
    {
        if ($recipe->isPublished()) {
            return true;
        }

        // Draft/rejected — only owner or admin can see
        return $user?->id === $recipe->user_id;
    }

    /**
     * Any authenticated user can create recipes.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the owner (or admin via Gate::before) can update.
     */
    public function update(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }

    /**
     * Only the owner (or admin) can delete.
     */
    public function delete(User $user, Recipe $recipe): bool
    {
        return $user->id === $recipe->user_id;
    }
}
