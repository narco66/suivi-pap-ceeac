<?php

namespace App\Policies;

use App\Models\Objectif;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ObjectifPolicy
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
        
        return $user->hasPermissionTo('viewAny objectif') || 
               $user->hasPermissionTo('view papa') ||
               $user->can('viewAny objectif');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Objectif $objectif): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            return true;
        }
        
        return $user->hasPermissionTo('view objectif') || 
               $user->hasPermissionTo('view papa') ||
               $user->can('view objectif');
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
        
        return $user->hasPermissionTo('create objectif') || 
               $user->hasPermissionTo('edit papa') ||
               $user->can('create objectif');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Objectif $objectif): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            // Vérifier si la version PAPA est verrouillée
            if ($objectif->papaVersion && $objectif->papaVersion->verrouille) {
                return false;
            }
            return true;
        }
        
        if ($user->hasPermissionTo('update objectif') || 
            $user->hasPermissionTo('edit papa') ||
            $user->can('update objectif')) {
            // Vérifier si la version PAPA est verrouillée
            if ($objectif->papaVersion && $objectif->papaVersion->verrouille) {
                return false;
            }
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Objectif $objectif): bool
    {
        // Seuls les administrateurs peuvent supprimer
        if (!$user->hasAnyRole(['admin', 'admin_dsi']) && !$user->hasPermissionTo('delete objectif')) {
            return false;
        }
        
        // Vérifier si la version PAPA est verrouillée
        if ($objectif->papaVersion && $objectif->papaVersion->verrouille) {
            return false;
        }
        
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Objectif $objectif): bool
    {
        return $user->hasRole('admin_dsi');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Objectif $objectif): bool
    {
        return $user->hasRole('admin_dsi');
    }
}

