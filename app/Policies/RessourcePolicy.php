<?php

namespace App\Policies;

use App\Models\Ressource;
use App\Models\User;

class RessourcePolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Les admins DSI ont tous les droits
        if ($user->hasRole('admin_dsi')) {
            return true;
        }

        return null; // Continue avec les autres vérifications
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('viewAny ressource');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ressource $ressource): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('view ressource');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('create ressource');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ressource $ressource): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('update ressource');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ressource $ressource): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('delete ressource');
    }
}


