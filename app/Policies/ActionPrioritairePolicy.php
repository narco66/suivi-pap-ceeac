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
        
        // Secrétaire Général : peut voir les actions d'appui uniquement
        // (le scope sera appliqué dans le controller)
        if ($user->isSecretaireGeneral()) {
            return $user->hasPermissionTo('viewAny action') || 
                   $user->hasPermissionTo('view papa');
        }
        
        // Commissaire : peut voir les actions de son département uniquement
        // (le scope sera appliqué dans le controller)
        if ($user->isCommissaire()) {
            return $user->hasPermissionTo('viewAny action') || 
                   $user->hasPermissionTo('view papa');
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
        
        // Secrétaire Général : peut voir uniquement les actions d'appui
        if ($user->isSecretaireGeneral()) {
            // 🔒 SÉCURITÉ : Le SG ne peut voir QUE les actions d'appui
            if (!$actionPrioritaire->isAppui()) {
                return false; // Accès interdit aux actions techniques
            }
            return $user->hasPermissionTo('view action') || 
                   $user->hasPermissionTo('view papa');
        }
        
        // Commissaire : peut voir uniquement les actions de son département
        if ($user->isCommissaire()) {
            $userDepartmentId = $user->getDepartmentId();
            $actionDepartmentId = $actionPrioritaire->getDepartmentId();
            
            // Si l'action n'a pas de département, le commissaire ne peut pas la voir
            if ($actionDepartmentId === null) {
                return false;
            }
            
            // Vérifier que l'action appartient au département du commissaire
            return $userDepartmentId === $actionDepartmentId;
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
        
        // Secrétaire Général : peut modifier uniquement les actions d'appui
        if ($user->isSecretaireGeneral()) {
            // 🔒 SÉCURITÉ : Le SG ne peut modifier QUE les actions d'appui
            if (!$actionPrioritaire->isAppui()) {
                return false; // Accès interdit aux actions techniques
            }
            
            // Vérifier si la version PAPA est verrouillée
            if ($actionPrioritaire->objectif && 
                $actionPrioritaire->objectif->papaVersion && 
                $actionPrioritaire->objectif->papaVersion->verrouille) {
                return false;
            }
            
            return $user->hasPermissionTo('update action') || 
                   $user->hasPermissionTo('edit papa');
        }
        
        // Commissaire : peut modifier uniquement les actions de son département
        if ($user->isCommissaire()) {
            $userDepartmentId = $user->getDepartmentId();
            $actionDepartmentId = $actionPrioritaire->getDepartmentId();
            
            // Si l'action n'a pas de département, le commissaire ne peut pas la modifier
            if ($actionDepartmentId === null || $userDepartmentId !== $actionDepartmentId) {
                return false;
            }
            
            // Vérifier si la version PAPA est verrouillée
            if ($actionPrioritaire->objectif && 
                $actionPrioritaire->objectif->papaVersion && 
                $actionPrioritaire->objectif->papaVersion->verrouille) {
                return false;
            }
            
            return $user->hasPermissionTo('update action') || 
                   $user->hasPermissionTo('edit papa');
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
        
        // Commissaire : ne peut pas supprimer (seuls les admins peuvent)
        if ($user->isCommissaire()) {
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
     * Determine whether the user can validate/arbitrate the action.
     * Spécifique au Secrétaire Général pour validation et arbitrage sur actions d'appui.
     */
    public function validate(User $user, ActionPrioritaire $actionPrioritaire): bool
    {
        // Secrétaire Général : peut valider uniquement les actions d'appui
        if ($user->isSecretaireGeneral()) {
            // 🔒 SÉCURITÉ : Le SG ne peut valider QUE les actions d'appui
            return $actionPrioritaire->isAppui();
        }
        
        // Commissaire : peut valider uniquement les actions de son département
        if ($user->isCommissaire()) {
            $userDepartmentId = $user->getDepartmentId();
            $actionDepartmentId = $actionPrioritaire->getDepartmentId();
            
            // Vérifier que l'action appartient au département du commissaire
            return $actionDepartmentId !== null && $userDepartmentId === $actionDepartmentId;
        }
        
        return false;
    }

    /**
     * Determine whether the user can arbitrate the action.
     */
    public function arbitrate(User $user, ActionPrioritaire $actionPrioritaire): bool
    {
        return $this->validate($user, $actionPrioritaire);
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
