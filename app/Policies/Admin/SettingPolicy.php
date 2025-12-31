<?php

namespace App\Policies\Admin;

use App\Models\Setting;
use App\Models\User;

class SettingPolicy
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
               $user->hasPermissionTo('viewAny admin.setting');
    }

    public function view(User $user, Setting $setting): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('view admin.setting');
    }

    public function update(User $user, Setting $setting): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('update admin.setting');
    }
}

