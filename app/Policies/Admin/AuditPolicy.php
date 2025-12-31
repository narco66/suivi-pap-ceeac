<?php

namespace App\Policies\Admin;

use App\Models\AuditLog;
use App\Models\User;

class AuditPolicy
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
               $user->hasPermissionTo('viewAny admin.audit');
    }

    public function view(User $user, AuditLog $auditLog): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('view admin.audit');
    }

    public function export(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'admin_dsi']) || 
               $user->hasPermissionTo('export admin.audit');
    }
}

