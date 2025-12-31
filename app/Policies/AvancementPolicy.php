<?php

namespace App\Policies;

use App\Models\Avancement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AvancementPolicy
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
        
        return $user->hasPermissionTo('viewAny avancement') || 
               $user->hasPermissionTo('view papa') ||
               $user->can('viewAny avancement');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Avancement $avancement): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            return true;
        }
        
        // Utilisateur qui a soumis l'avancement
        if ($avancement->soumis_par_id && $avancement->soumis_par_id === $user->id) {
            return true;
        }
        
        // Responsable de la tâche
        if ($avancement->tache && $avancement->tache->responsable_id && $avancement->tache->responsable_id === $user->id) {
            return true;
        }
        
        return $user->hasPermissionTo('view avancement') || 
               $user->hasPermissionTo('view papa') ||
               $user->can('view avancement');
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
        
        return $user->hasPermissionTo('create avancement') || 
               $user->hasPermissionTo('edit papa') ||
               $user->can('create avancement');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Avancement $avancement): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            return true;
        }
        
        // Utilisateur qui a soumis l'avancement
        if ($avancement->soumis_par_id && $avancement->soumis_par_id === $user->id) {
            return true;
        }
        
        return $user->hasPermissionTo('update avancement') || 
               $user->hasPermissionTo('edit papa') ||
               $user->can('update avancement');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Avancement $avancement): bool
    {
        // Seuls les administrateurs peuvent supprimer
        return $user->hasAnyRole(['admin', 'admin_dsi']) || $user->hasPermissionTo('delete avancement');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Avancement $avancement): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || $user->hasPermissionTo('restore avancement');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Avancement $avancement): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']);
    }
}
