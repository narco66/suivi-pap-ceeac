<?php

namespace App\Policies;

use App\Models\Rapport;
use App\Models\User;

class RapportPolicy
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
        return $user->hasAnyRole(['admin', 'admin_dsi', 'president', 'vice_president', 'secretaire_general']) || 
               $user->hasPermissionTo('viewAny rapport');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Rapport $rapport): bool
    {
        // Le créateur peut toujours voir son rapport
        if ($rapport->cree_par_id === $user->id) {
            return true;
        }

        return $user->hasAnyRole(['admin', 'admin_dsi', 'president', 'vice_president', 'secretaire_general']) || 
               $user->hasPermissionTo('view rapport');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi', 'secretaire_general', 'commissaire', 'directeur']) || 
               $user->hasPermissionTo('create rapport');
    }

    /**
     * Determine whether the user can generate a report with a specific scope.
     */
    public function generateWithScope(User $user, string $scopeLevel): bool
    {
        // Admin peut générer tous les scopes
        if ($user->hasAnyRole(['admin', 'admin_dsi'])) {
            return true;
        }

        // Commissaire peut générer uniquement des rapports COMMISSAIRE
        if ($scopeLevel === 'COMMISSAIRE' && $user->isCommissaire()) {
            return true;
        }

        // SG peut générer uniquement des rapports SG
        if ($scopeLevel === 'SG' && $user->isSecretaireGeneral()) {
            return true;
        }

        // Présidence peut générer des rapports GLOBAL
        if ($scopeLevel === 'GLOBAL' && $user->hasAnyRole(['president', 'vice_president'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rapport $rapport): bool
    {
        // Le créateur peut modifier son rapport si non généré
        if ($rapport->cree_par_id === $user->id && $rapport->statut === 'brouillon') {
            return true;
        }

        return $user->hasAnyRole(['admin', 'admin_dsi', 'secretaire_general']) || 
               $user->hasPermissionTo('update rapport');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rapport $rapport): bool
    {
        // Le créateur peut supprimer son rapport si non généré
        if ($rapport->cree_par_id === $user->id && $rapport->statut === 'brouillon') {
            return true;
        }

        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('delete rapport');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Rapport $rapport): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('restore rapport');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Rapport $rapport): bool
    {
        return $user->hasRole('admin_dsi') || 
               $user->hasPermissionTo('forceDelete rapport');
    }

    /**
     * Determine whether the user can generate the report.
     */
    public function generate(User $user, Rapport $rapport): bool
    {
        return $this->update($user, $rapport);
    }

    /**
     * Determine whether the user can download the report.
     */
    public function download(User $user, Rapport $rapport): bool
    {
        return $this->view($user, $rapport);
    }
}
