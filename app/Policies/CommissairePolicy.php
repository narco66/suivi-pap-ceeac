<?php

namespace App\Policies;

use App\Models\Commissaire;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommissairePolicy
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
        return $user->hasPermissionTo('viewAny referentiel.commissaire') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager', 'direction_manager']);
    }

    public function view(User $user, Commissaire $commissaire): bool
    {
        return $user->hasPermissionTo('view referentiel.commissaire') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager', 'direction_manager']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create referentiel.commissaire') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager']);
    }

    public function update(User $user, Commissaire $commissaire): bool
    {
        return $user->hasPermissionTo('update referentiel.commissaire') || 
               $user->hasAnyRole(['admin', 'admin_dsi', 'sg_manager']);
    }

    public function delete(User $user, Commissaire $commissaire): bool
    {
        return $user->hasPermissionTo('delete referentiel.commissaire') || 
               $user->hasAnyRole(['admin', 'admin_dsi']);
    }
}


