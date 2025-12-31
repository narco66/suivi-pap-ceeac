<?php

namespace App\Policies;

use App\Models\DirectionTechnique;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DirectionTechniquePolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('admin_dsi') || $user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('viewAny referentiel.direction-technique') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager', 'direction_manager']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DirectionTechnique $directionTechnique): bool
    {
        return $user->hasPermissionTo('view referentiel.direction-technique') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager', 'direction_manager']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create referentiel.direction-technique') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DirectionTechnique $directionTechnique): bool
    {
        return $user->hasPermissionTo('update referentiel.direction-technique') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DirectionTechnique $directionTechnique): bool
    {
        return $user->hasPermissionTo('delete referentiel.direction-technique') || 
               $user->hasAnyRole(['admin', 'admin_dsi']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DirectionTechnique $directionTechnique): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DirectionTechnique $directionTechnique): bool
    {
        return false;
    }
}
