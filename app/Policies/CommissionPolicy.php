<?php

namespace App\Policies;

use App\Models\Commission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommissionPolicy
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
        return $user->hasPermissionTo('viewAny referentiel.commission') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager', 'direction_manager']);
    }

    public function view(User $user, Commission $commission): bool
    {
        return $user->hasPermissionTo('view referentiel.commission') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager', 'direction_manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create referentiel.commission') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager']);
    }

    public function update(User $user, Commission $commission): bool
    {
        return $user->hasPermissionTo('update referentiel.commission') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager']);
    }

    public function delete(User $user, Commission $commission): bool
    {
        return $user->hasPermissionTo('delete referentiel.commission') || 
               $user->hasAnyRole(['admin', 'admin_dsi']);
    }
}



