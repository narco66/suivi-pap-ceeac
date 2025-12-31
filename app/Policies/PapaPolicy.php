<?php

namespace App\Policies;

use App\Models\Papa;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PapaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            return true;
        }
        
        return $user->hasPermissionTo('viewAny papa') || 
               $user->can('viewAny papa');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Papa $papa): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            return true;
        }
        
        return $user->hasPermissionTo('view papa') || 
               $user->can('view papa');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            return true;
        }
        
        return $user->hasPermissionTo('create papa') || 
               $user->can('create papa');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Papa $papa): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            return true;
        }
        
        return $user->hasPermissionTo('update papa') || 
               $user->can('update papa');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Papa $papa): bool
    {
        // Seuls les administrateurs peuvent supprimer
        return $user->hasAnyRole(['admin', 'admin_dsi']) || $user->hasPermissionTo('delete papa');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Papa $papa): bool
    {
        return $user->hasRole('admin_dsi');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Papa $papa): bool
    {
        return $user->hasRole('admin_dsi');
    }
    
    /**
     * Determine whether the user can import PAPA.
     */
    public function import(User $user): bool
    {
        return $user->can('import papa');
    }
    
    /**
     * Determine whether the user can export PAPA.
     */
    public function export(User $user): bool
    {
        return $user->can('export papa');
    }
    
    /**
     * Determine whether the user can lock PAPA version.
     */
    public function lock(User $user, Papa $papa): bool
    {
        return $user->can('lock papa');
    }
}
