<?php

namespace App\Policies;

use App\Models\Kpi;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KpiPolicy
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
        
        return $user->hasPermissionTo('viewAny kpi') || 
               $user->hasPermissionTo('view papa') ||
               $user->can('viewAny kpi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Kpi $kpi): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            return true;
        }
        
        // Secrétaire Général : peut voir uniquement les KPIs d'appui
        if ($user->isSecretaireGeneral()) {
            // 🔒 SÉCURITÉ : Le SG ne peut voir QUE les KPIs d'appui
            if (!$kpi->isAppui()) {
                return false; // Accès interdit aux KPIs techniques
            }
            return $user->hasPermissionTo('view kpi') || 
                   $user->hasPermissionTo('view papa');
        }
        
        // Commissaire : peut voir uniquement les KPIs de son département
        if ($user->isCommissaire()) {
            $userDepartmentId = $user->getDepartmentId();
            $kpiDepartmentId = $kpi->getDepartmentId();
            
            // Si le KPI n'a pas de département, le commissaire ne peut pas le voir
            if ($kpiDepartmentId === null) {
                return false;
            }
            
            // Vérifier que le KPI appartient au département du commissaire
            return $userDepartmentId === $kpiDepartmentId;
        }
        
        return $user->hasPermissionTo('view kpi') || 
               $user->hasPermissionTo('view papa') ||
               $user->can('view kpi');
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
        
        return $user->hasPermissionTo('create kpi') || 
               $user->hasPermissionTo('edit papa') ||
               $user->can('create kpi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kpi $kpi): bool
    {
        // Administrateurs : accès complet
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            return true;
        }
        
        return $user->hasPermissionTo('update kpi') || 
               $user->hasPermissionTo('edit papa') ||
               $user->can('update kpi');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kpi $kpi): bool
    {
        // Seuls les administrateurs peuvent supprimer
        return $user->hasAnyRole(['admin', 'admin_dsi']) || $user->hasPermissionTo('delete kpi');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Kpi $kpi): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || $user->hasPermissionTo('restore kpi');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Kpi $kpi): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']);
    }
}
