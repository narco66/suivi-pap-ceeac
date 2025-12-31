<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class VerifyAdminAccess extends Command
{
    protected $signature = 'admin:verify {email=admin@ceeac.int}';
    protected $description = 'Vérifier l\'accès admin d\'un utilisateur';

    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("❌ Utilisateur {$email} non trouvé.");
            return 1;
        }

        $this->info("🔍 Vérification de l'accès admin pour {$user->name} ({$user->email})");
        $this->newLine();

        // Vérifier les rôles
        $this->info("📋 Rôles:");
        $roles = $user->getRoleNames();
        if ($roles->isEmpty()) {
            $this->error("  ❌ Aucun rôle assigné");
        } else {
            foreach ($roles as $role) {
                $isAdmin = in_array($role, ['admin_dsi', 'admin']);
                $icon = $isAdmin ? '✅' : '  ';
                $this->line("  {$icon} {$role}");
            }
        }

        // Vérifier les permissions clés
        $this->newLine();
        $this->info("🔑 Permissions clés:");
        $keyChecks = [
            'admin.access' => $user->hasPermissionTo('admin.access'),
            'viewAny admin.user' => $user->hasPermissionTo('viewAny admin.user'),
            'viewAny admin.role' => $user->hasPermissionTo('viewAny admin.role'),
        ];

        foreach ($keyChecks as $permission => $has) {
            $icon = $has ? '✅' : '❌';
            $this->line("  {$icon} {$permission}");
        }

        // Vérifier les conditions d'affichage du menu
        $this->newLine();
        $this->info("🎯 Conditions d'affichage du menu Administration:");
        
        $hasRoleAdminDsi = $user->hasRole('admin_dsi');
        $hasRoleAdmin = $user->hasRole('admin');
        $hasPermissionAccess = $user->can('admin.access');
        $hasPermissionViewUsers = $user->can('viewAny admin.user');
        $hasPermissionViewRoles = $user->can('viewAny admin.role');
        
        $conditions = [
            'hasRole(admin_dsi)' => $hasRoleAdminDsi,
            'hasRole(admin)' => $hasRoleAdmin,
            'can(admin.access)' => $hasPermissionAccess,
            'can(viewAny admin.user)' => $hasPermissionViewUsers,
            'can(viewAny admin.role)' => $hasPermissionViewRoles,
        ];

        foreach ($conditions as $condition => $result) {
            $icon = $result ? '✅' : '❌';
            $this->line("  {$icon} {$condition}");
        }

        // Résultat final
        $this->newLine();
        $willShowMenu = $hasRoleAdminDsi || $hasRoleAdmin || $hasPermissionAccess || $hasPermissionViewUsers || $hasPermissionViewRoles;
        
        if ($willShowMenu) {
            $this->info("✅ Le menu Administration DEVRAIT être visible pour cet utilisateur.");
        } else {
            $this->error("❌ Le menu Administration NE SERA PAS visible pour cet utilisateur.");
            $this->newLine();
            $this->warn("💡 Solution: Exécutez la commande suivante pour corriger:");
            $this->line("   php artisan admin:grant-full-access {$email}");
        }

        return 0;
    }
}


