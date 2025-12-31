<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DiagnoseMenu extends Command
{
    protected $signature = 'menu:diagnose {email?}';
    protected $description = 'Diagnostique l\'affichage du menu pour un utilisateur';

    public function handle()
    {
        $email = $this->argument('email');
        
        if ($email) {
            $user = \App\Models\User::where('email', $email)->first();
            if (!$user) {
                $this->error("Utilisateur non trouvé: {$email}");
                return 1;
            }
        } else {
            $user = Auth::user();
            if (!$user) {
                $this->error("Aucun utilisateur connecté. Utilisez: php artisan menu:diagnose email@example.com");
                return 1;
            }
        }

        $this->info("=== DIAGNOSTIC MENU POUR: {$user->email} ===\n");

        // 1. État d'authentification
        $this->info("1. ÉTAT D'AUTHENTIFICATION:");
        $this->line("   - auth()->check(): " . (auth()->check() ? '✅ OUI' : '❌ NON'));
        $this->line("   - User ID: {$user->id}");
        $this->line("   - User Name: {$user->name}");
        $this->line("   - User Email: {$user->email}");
        $this->line("   - User Active: " . ($user->is_active ?? 'N/A'));

        // 2. Rôles
        $this->info("\n2. RÔLES:");
        $roles = $user->roles->pluck('name');
        if ($roles->isEmpty()) {
            $this->warn("   ❌ Aucun rôle assigné!");
        } else {
            foreach ($roles as $role) {
                $this->line("   ✅ {$role}");
            }
        }

        // 3. Permissions directes
        $this->info("\n3. PERMISSIONS DIRECTES:");
        $permissions = $user->getAllPermissions()->pluck('name');
        if ($permissions->isEmpty()) {
            $this->warn("   ⚠️  Aucune permission directe");
        } else {
            foreach ($permissions->take(10) as $perm) {
                $this->line("   ✅ {$perm}");
            }
            if ($permissions->count() > 10) {
                $this->line("   ... et " . ($permissions->count() - 10) . " autres");
            }
        }

        // 4. Permissions critiques pour le menu
        $this->info("\n4. PERMISSIONS CRITIQUES POUR LE MENU:");
        $criticalPerms = [
            'admin.access',
            'gantt.view',
            'viewAny admin.user',
            'viewAny admin.role',
        ];
        
        foreach ($criticalPerms as $perm) {
            $hasPerm = $user->can($perm);
            $status = $hasPerm ? '✅' : '❌';
            $this->line("   {$status} {$perm}");
        }

        // 5. Vérification des rôles admin
        $this->info("\n5. VÉRIFICATION RÔLES ADMIN:");
        $adminRoles = ['admin', 'admin_dsi', 'super_admin'];
        $hasAdminRole = false;
        foreach ($adminRoles as $roleName) {
            $hasRole = $user->hasRole($roleName);
            $status = $hasRole ? '✅' : '❌';
            $this->line("   {$status} {$roleName}");
            if ($hasRole) {
                $hasAdminRole = true;
            }
        }

        // 6. Routes critiques
        $this->info("\n6. VÉRIFICATION ROUTES:");
        $routes = [
            'dashboard',
            'papa.index',
            'gantt.index',
            'admin.users.index',
            'admin.roles.index',
        ];
        
        foreach ($routes as $routeName) {
            $exists = \Route::has($routeName);
            $status = $exists ? '✅' : '❌';
            $this->line("   {$status} {$routeName}");
        }

        // 7. Recommandations
        $this->info("\n7. RECOMMANDATIONS:");
        $issues = [];
        
        if (!$hasAdminRole && !$user->can('admin.access')) {
            $issues[] = "L'utilisateur n'a pas de rôle admin ni la permission 'admin.access'";
        }
        
        if ($roles->isEmpty()) {
            $issues[] = "Aucun rôle assigné - assignez au moins un rôle";
        }
        
        if (empty($issues)) {
            $this->info("   ✅ Aucun problème détecté");
        } else {
            foreach ($issues as $issue) {
                $this->warn("   ⚠️  {$issue}");
            }
        }

        return 0;
    }
}

