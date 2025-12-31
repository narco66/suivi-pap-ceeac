<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CheckAdminPermissions extends Command
{
    protected $signature = 'admin:check-permissions {email?}';
    protected $description = 'Vérifier les permissions d\'un utilisateur admin';

    public function handle()
    {
        $email = $this->argument('email') ?? 'admin@ceeac.int';
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Utilisateur {$email} non trouvé.");
            return 1;
        }

        $this->info("=== Vérification des permissions pour {$user->name} ({$user->email}) ===");
        $this->newLine();

        // Rôles
        $this->info("Rôles assignés:");
        $roles = $user->getRoleNames();
        if ($roles->isEmpty()) {
            $this->warn("  ❌ Aucun rôle assigné");
        } else {
            foreach ($roles as $role) {
                $this->line("  ✓ {$role}");
            }
        }
        $this->newLine();

        // Permissions directes
        $this->info("Permissions directes:");
        $directPermissions = $user->getDirectPermissions();
        if ($directPermissions->isEmpty()) {
            $this->line("  (aucune permission directe)");
        } else {
            foreach ($directPermissions as $permission) {
                $this->line("  ✓ {$permission->name}");
            }
        }
        $this->newLine();

        // Permissions via rôles
        $this->info("Permissions via rôles:");
        $permissionsViaRoles = $user->getPermissionsViaRoles();
        if ($permissionsViaRoles->isEmpty()) {
            $this->warn("  ❌ Aucune permission via rôles");
        } else {
            $this->line("  Total: {$permissionsViaRoles->count()} permissions");
            $adminPermissions = $permissionsViaRoles->filter(fn($p) => str_contains($p->name, 'admin'));
            if ($adminPermissions->isNotEmpty()) {
                $this->line("  Permissions admin: {$adminPermissions->count()}");
                foreach ($adminPermissions->take(10) as $permission) {
                    $this->line("    ✓ {$permission->name}");
                }
                if ($adminPermissions->count() > 10) {
                    $this->line("    ... et " . ($adminPermissions->count() - 10) . " autres");
                }
            }
        }
        $this->newLine();

        // Vérifications spécifiques
        $this->info("Vérifications spécifiques:");
        $checks = [
            'admin.access' => $user->hasPermissionTo('admin.access'),
            'viewAny admin.user' => $user->hasPermissionTo('viewAny admin.user'),
            'hasRole admin_dsi' => $user->hasRole('admin_dsi'),
            'hasRole admin' => $user->hasRole('admin'),
            'hasAnyRole admin|admin_dsi' => $user->hasAnyRole(['admin', 'admin_dsi']),
        ];

        foreach ($checks as $check => $result) {
            $icon = $result ? '✓' : '❌';
            $this->line("  {$icon} {$check}: " . ($result ? 'OUI' : 'NON'));
        }

        $this->newLine();
        
        // Vérifier les rôles dans la base
        $this->info("Rôles disponibles dans la base:");
        $allRoles = Role::all();
        foreach ($allRoles as $role) {
            $permissionsCount = $role->permissions->count();
            $this->line("  - {$role->name} ({$permissionsCount} permissions)");
        }

        return 0;
    }
}


