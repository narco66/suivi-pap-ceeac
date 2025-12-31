<?php

namespace App\Policies;

use App\Models\Departement;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DepartementPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('admin_dsi') || $user->hasRole('admin')) {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('viewAny referentiel.departement') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager', 'direction_manager']);
    }

    public function view(User $user, Departement $departement): bool
    {
        return $user->hasPermissionTo('view referentiel.departement') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager', 'direction_manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create referentiel.departement') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager']);
    }

    public function update(User $user, Departement $departement): bool
    {
        return $user->hasPermissionTo('update referentiel.departement') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager']);
    }

    public function delete(User $user, Departement $departement): bool
    {
        return $user->hasPermissionTo('delete referentiel.departement') || 
               $user->hasAnyRole(['admin', 'admin_dsi']);
    }
}


