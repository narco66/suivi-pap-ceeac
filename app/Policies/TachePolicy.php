<?php

namespace App\Policies;

use App\Models\Tache;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TachePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Administrateurs et utilisateurs avec permission
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('view tache') ||
               $user->hasPermissionTo('view papa') ||
               $user->can('viewAny tache');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tache $tache): bool
    {
        // Administrateurs DSI : accès complet
        if ($user->hasRole('admin_dsi') || $user->hasRole('admin')) {
            return true;
        }
        
        // Secrétaire Général : peut voir uniquement les tâches d'appui
        if ($user->isSecretaireGeneral()) {
            // 🔒 SÉCURITÉ : Le SG ne peut voir QUE les tâches d'appui
            if (!$tache->isAppui()) {
                return false; // Accès interdit aux tâches techniques
            }
            return $user->hasPermissionTo('view tache') || 
                   $user->hasPermissionTo('view papa');
        }
        
        // Commissaire : peut voir uniquement les tâches de son département
        if ($user->isCommissaire()) {
            $userDepartmentId = $user->getDepartmentId();
            $tacheDepartmentId = $tache->getDepartmentId();
            
            // Si la tâche n'a pas de département, le commissaire ne peut pas la voir
            if ($tacheDepartmentId === null) {
                return false;
            }
            
            // Vérifier que la tâche appartient au département du commissaire
            return $userDepartmentId === $tacheDepartmentId;
        }
        
        // Utilisateurs avec permission explicite
        if ($user->hasPermissionTo('view tache') || $user->hasPermissionTo('view papa')) {
            return true;
        }
        
        // Responsable de la tâche
        if ($tache->responsable_id && $tache->responsable_id === $user->id) {
            return true;
        }
        
        // Par défaut, autoriser tous les utilisateurs authentifiés à voir (pour le développement)
        // TODO: Restreindre selon les besoins métier
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('create tache') ||
               $user->hasPermissionTo('edit papa') ||
               $user->can('create tache');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tache $tache): bool
    {
        // Administrateurs et utilisateurs avec permission
        if ($user->hasAnyRole(['admin', 'admin_dsi']) || 
            $user->hasPermissionTo('update tache') || 
            $user->hasPermissionTo('edit papa') ||
            $user->can('update tache')) {
            // Vérifier si la version PAPA est verrouillée
            if ($tache->actionPrioritaire && 
                $tache->actionPrioritaire->objectif && 
                $tache->actionPrioritaire->objectif->papaVersion && 
                $tache->actionPrioritaire->objectif->papaVersion->verrouille) {
                // Même les admins ne peuvent pas modifier une version verrouillée
                return false;
            }
            return true;
        }
        
        // Responsable de la tâche
        if ($tache->responsable_id && $tache->responsable_id === $user->id) {
            // Vérifier si la version PAPA est verrouillée
            if ($tache->actionPrioritaire && 
                $tache->actionPrioritaire->objectif && 
                $tache->actionPrioritaire->objectif->papaVersion && 
                $tache->actionPrioritaire->objectif->papaVersion->verrouille) {
                return false;
            }
            return true;
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tache $tache): bool
    {
        // Seuls les administrateurs peuvent supprimer
        if (!$user->hasAnyRole(['admin', 'admin_dsi']) && !$user->hasPermissionTo('delete tache')) {
            return false;
        }
        
        // Vérifier si la version PAPA est verrouillée
        if ($tache->actionPrioritaire && 
            $tache->actionPrioritaire->objectif && 
            $tache->actionPrioritaire->objectif->papaVersion && 
            $tache->actionPrioritaire->objectif->papaVersion->verrouille) {
            return false;
        }
        
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tache $tache): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || $user->hasPermissionTo('restore tache');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tache $tache): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']);
    }
}
