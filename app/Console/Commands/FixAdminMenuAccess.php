<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class FixAdminMenuAccess extends Command
{
    protected $signature = 'admin:fix-menu-access {email?}';
    protected $description = 'Corrige l\'accès au menu admin pour un utilisateur';

    public function handle()
    {
        $email = $this->argument('email');
        
        if ($email) {
            $user = User::where('email', $email)->first();
            if (!$user) {
                $this->error("Utilisateur non trouvé: {$email}");
                return 1;
            }
        } else {
            // Prendre le premier admin trouvé
            $user = User::role(['admin', 'admin_dsi'])->first();
            if (!$user) {
                $this->error("Aucun utilisateur admin trouvé. Utilisez: php artisan admin:fix-menu-access email@example.com");
                return 1;
            }
        }

        $this->info("Correction de l'accès menu pour: {$user->email}");

        // 1. S'assurer que la permission existe
        $permission = Permission::firstOrCreate(
            ['name' => 'admin.access', 'guard_name' => 'web'],
            ['name' => 'admin.access', 'guard_name' => 'web']
        );
        $this->info("✅ Permission 'admin.access' créée/vérifiée");

        // 2. Assigner les rôles admin si nécessaire
        $adminRoles = ['admin', 'admin_dsi'];
        $hasAdminRole = false;
        
        foreach ($adminRoles as $roleName) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            if ($role) {
                if (!$user->hasRole($roleName)) {
                    $user->assignRole($role);
                    $this->info("✅ Rôle '{$roleName}' assigné");
                } else {
                    $this->info("✅ Rôle '{$roleName}' déjà assigné");
                }
                $hasAdminRole = true;
                
                // Assigner la permission au rôle
                if (!$role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                    $this->info("✅ Permission assignée au rôle '{$roleName}'");
                }
            }
        }

        // 3. Assigner la permission directement à l'utilisateur
        if (!$user->hasPermissionTo($permission)) {
            $user->givePermissionTo($permission);
            $this->info("✅ Permission 'admin.access' assignée directement");
        } else {
            $this->info("✅ Permission 'admin.access' déjà assignée");
        }

        // 4. Vérification finale
        $this->info("\n=== VÉRIFICATION FINALE ===");
        $this->line("Rôles: " . $user->roles->pluck('name')->join(', '));
        $this->line("Permission admin.access: " . ($user->can('admin.access') ? '✅ OUI' : '❌ NON'));
        $this->line("HasRole admin: " . ($user->hasRole('admin') ? '✅ OUI' : '❌ NON'));
        $this->line("HasRole admin_dsi: " . ($user->hasRole('admin_dsi') ? '✅ OUI' : '❌ NON'));

        // 5. Nettoyer le cache
        $this->call('permission:cache-reset');
        $this->info("✅ Cache des permissions nettoyé");

        $this->info("\n✅ Correction terminée ! Le menu devrait maintenant s'afficher.");

        return 0;
    }
}

