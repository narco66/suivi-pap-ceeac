<?php

namespace App\Policies;

use App\Models\Tache;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GanttTaskPolicy
{
    /**
     * Determine whether the user can view the Gantt chart.
     */
    public function viewGantt(User $user): bool
    {
        // Vérifier d'abord les rôles (plus rapide) avec guard explicite
        if ($user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager', 'direction_manager'], 'web')) {
            return true;
        }
        
        // Ensuite vérifier la permission avec guard explicite
        try {
            return $user->hasPermissionTo('gantt.view', 'web');
        } catch (\Exception $e) {
            // Si la permission n'existe pas encore, retourner false
            \Log::warning('Erreur lors de la vérification de la permission gantt.view', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Determine whether the user can view any tasks.
     */
    public function viewAny(User $user): bool
    {
        return $this->viewGantt($user);
    }

    /**
     * Determine whether the user can view the task.
     */
    public function view(User $user, Tache $tache): bool
    {
        return $this->viewGantt($user);
    }

    /**
     * Determine whether the user can create tasks.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('gantt.edit_dates', 'web') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager'], 'web');
    }

    /**
     * Determine whether the user can update the task.
     */
    public function update(User $user, Tache $tache): bool
    {
        return $user->hasPermissionTo('gantt.edit_dates', 'web') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager'], 'web');
    }

    /**
     * Determine whether the user can delete the task.
     */
    public function delete(User $user, Tache $tache): bool
    {
        return $user->hasPermissionTo('gantt.edit_dates', 'web') || 
               $user->hasAnyRole(['admin', 'admin_dsi'], 'web');
    }

    /**
     * Determine whether the user can edit dates in Gantt (drag & drop).
     */
    public function editDates(User $user): bool
    {
        return $user->hasPermissionTo('gantt.edit_dates', 'web') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager'], 'web');
    }

    /**
     * Determine whether the user can manage dependencies.
     */
    public function manageDependencies(User $user): bool
    {
        return $user->hasPermissionTo('gantt.manage_dependencies', 'web') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager'], 'web');
    }

    /**
     * Determine whether the user can export Gantt.
     */
    public function export(User $user): bool
    {
        return $user->hasPermissionTo('gantt.export', 'web') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager', 'direction_manager'], 'web');
    }
}
