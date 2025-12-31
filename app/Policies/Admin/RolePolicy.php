<?php

namespace App\Policies\Admin;

use App\Models\User;
use Spatie\Permission\Models\Role;

class RolePolicy
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

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('viewAny admin.role');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('view admin.role');
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('create admin.role');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('update admin.role');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('delete admin.role');
    }
}

