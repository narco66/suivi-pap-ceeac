<?php

namespace App\Policies;

use App\Models\Alerte;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AlertePolicy
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
        
        return $user->hasPermissionTo('viewAny alerte') || 
               $user->hasPermissionTo('view papa') ||
               $user->can('viewAny alerte');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Alerte $alerte): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            return true;
        }
        
        // Utilisateur assigné à l'alerte
        if ($alerte->assignee_a_id && $alerte->assignee_a_id === $user->id) {
            return true;
        }
        
        // Utilisateur qui a créé l'alerte
        if ($alerte->cree_par_id && $alerte->cree_par_id === $user->id) {
            return true;
        }
        
        return $user->hasPermissionTo('view alerte') || 
               $user->hasPermissionTo('view papa') ||
               $user->can('view alerte');
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
        
        return $user->hasPermissionTo('create alerte') || 
               $user->hasPermissionTo('edit papa') ||
               $user->can('create alerte');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Alerte $alerte): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            return true;
        }
        
        // Utilisateur assigné à l'alerte
        if ($alerte->assignee_a_id && $alerte->assignee_a_id === $user->id) {
            return true;
        }
        
        return $user->hasPermissionTo('update alerte') || 
               $user->hasPermissionTo('edit papa') ||
               $user->can('update alerte');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Alerte $alerte): bool
    {
        // Seuls les administrateurs peuvent supprimer
        return $user->hasAnyRole(['admin', 'admin_dsi']) || $user->hasPermissionTo('delete alerte');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Alerte $alerte): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || $user->hasPermissionTo('restore alerte');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Alerte $alerte): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']);
    }
}
