<?php

namespace App\Policies;

use App\Models\ActionPrioritaire;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ActionPrioritairePolicy
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
        
        return $user->hasPermissionTo('viewAny action') || 
               $user->hasPermissionTo('view papa') ||
               $user->can('viewAny action');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ActionPrioritaire $actionPrioritaire): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            return true;
        }
        
        return $user->hasPermissionTo('view action') || 
               $user->hasPermissionTo('view papa') ||
               $user->can('view action');
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
        
        return $user->hasPermissionTo('create action') || 
               $user->hasPermissionTo('edit papa') ||
               $user->can('create action');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ActionPrioritaire $actionPrioritaire): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            // Vérifier si la version PAPA est verrouillée
            if ($actionPrioritaire->objectif && 
                $actionPrioritaire->objectif->papaVersion && 
                $actionPrioritaire->objectif->papaVersion->verrouille) {
                return false;
            }
            return true;
        }
        
        if ($user->hasPermissionTo('update action') || 
            $user->hasPermissionTo('edit papa') ||
            $user->can('update action')) {
            // Vérifier si la version PAPA est verrouillée
            if ($actionPrioritaire->objectif && 
                $actionPrioritaire->objectif->papaVersion && 
                $actionPrioritaire->objectif->papaVersion->verrouille) {
                return false;
            }
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ActionPrioritaire $actionPrioritaire): bool
    {
        // Seuls les administrateurs peuvent supprimer
        if (!$user->hasAnyRole(['admin', 'admin_dsi']) && !$user->hasPermissionTo('delete action')) {
            return false;
        }
        
        // Vérifier si la version PAPA est verrouillée
        if ($actionPrioritaire->objectif && 
            $actionPrioritaire->objectif->papaVersion && 
            $actionPrioritaire->objectif->papaVersion->verrouille) {
            return false;
        }
        
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ActionPrioritaire $actionPrioritaire): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || $user->hasPermissionTo('restore action');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ActionPrioritaire $actionPrioritaire): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']);
    }
}
