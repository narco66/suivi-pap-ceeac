<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuditUsersRolesPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:users-roles-permissions 
                            {--user= : Email de l\'utilisateur à auditer}
                            {--role= : Nom du rôle à auditer}
                            {--export : Exporter le rapport dans un fichier}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit complet des utilisateurs, rôles et permissions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 AUDIT DES UTILISATEURS, RÔLES ET PERMISSIONS');
        $this->info('================================================');
        $this->newLine();

        // Audit des rôles
        $this->auditRoles();

        // Audit des permissions
        $this->auditPermissions();

        // Audit des utilisateurs
        $this->auditUsers();

        // Audit spécifique si demandé
        if ($this->option('user')) {
            $this->auditSpecificUser($this->option('user'));
        }

        if ($this->option('role')) {
            $this->auditSpecificRole($this->option('role'));
        }

        $this->newLine();
        $this->info('✅ Audit terminé');
    }

    private function auditRoles(): void
    {
        $this->info('📋 RÔLES DÉFINIS');
        $this->info('-----------------');
        
        $roles = Role::with('permissions')->get();
        
        $table = [];
        foreach ($roles as $role) {
            $table[] = [
                'Nom' => $role->name,
                'Permissions' => $role->permissions->count(),
                'Utilisateurs' => $role->users->count(),
            ];
        }
        
        $this->table(['Nom', 'Permissions', 'Utilisateurs'], $table);
        $this->newLine();
    }

    private function auditPermissions(): void
    {
        $this->info('🔐 PERMISSIONS DÉFINIES');
        $this->info('----------------------');
        
        $permissions = Permission::with('roles')->get();
        
        $grouped = $permissions->groupBy(function($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'autre';
        });
        
        foreach ($grouped as $module => $perms) {
            $this->info("  Module: {$module}");
            $this->line("    " . $perms->pluck('name')->implode(', '));
        }
        
        $this->info("  Total: {$permissions->count()} permissions");
        $this->newLine();
    }

    private function auditUsers(): void
    {
        $this->info('👥 UTILISATEURS');
        $this->info('---------------');
        
        $users = User::with(['roles', 'permissions'])->get();
        
        $table = [];
        foreach ($users as $user) {
            $roles = $user->roles->pluck('name')->implode(', ');
            $permissionsCount = $user->getAllPermissions()->count();
            
            $table[] = [
                'ID' => $user->id,
                'Nom' => $user->name,
                'Email' => $user->email,
                'Rôles' => $roles ?: 'Aucun',
                'Permissions totales' => $permissionsCount,
            ];
        }
        
        $this->table(['ID', 'Nom', 'Email', 'Rôles', 'Permissions totales'], $table);
        $this->newLine();
        
        // Vérifier les utilisateurs sans rôles
        $usersWithoutRoles = $users->filter(fn($u) => $u->roles->isEmpty());
        if ($usersWithoutRoles->isNotEmpty()) {
            $this->warn('⚠️  Utilisateurs sans rôles:');
            foreach ($usersWithoutRoles as $user) {
                $this->line("    - {$user->email} (ID: {$user->id})");
            }
            $this->newLine();
        }
    }

    private function auditSpecificUser(string $email): void
    {
        $user = User::where('email', $email)->with(['roles', 'permissions'])->first();
        
        if (!$user) {
            $this->error("❌ Utilisateur non trouvé: {$email}");
            return;
        }
        
        $this->info("🔍 AUDIT DÉTAILLÉ: {$user->name} ({$user->email})");
        $this->info('==========================================');
        $this->newLine();
        
        // Rôles
        $this->info('Rôles assignés:');
        if ($user->roles->isEmpty()) {
            $this->warn('  ⚠️  Aucun rôle assigné');
        } else {
            foreach ($user->roles as $role) {
                $this->line("  ✓ {$role->name}");
            }
        }
        $this->newLine();
        
        // Permissions directes
        $this->info('Permissions directes:');
        if ($user->permissions->isEmpty()) {
            $this->line('  Aucune permission directe');
        } else {
            foreach ($user->permissions as $permission) {
                $this->line("  ✓ {$permission->name}");
            }
        }
        $this->newLine();
        
        // Permissions via rôles
        $this->info('Permissions via rôles:');
        $permissionsViaRoles = $user->getPermissionsViaRoles();
        if ($permissionsViaRoles->isEmpty()) {
            $this->warn('  ⚠️  Aucune permission via rôles');
        } else {
            $grouped = $permissionsViaRoles->groupBy(function($perm) {
                $parts = explode(' ', $perm->name);
                return $parts[0] ?? 'autre';
            });
            
            foreach ($grouped as $module => $perms) {
                $this->line("  Module {$module}:");
                foreach ($perms as $perm) {
                    $this->line("    - {$perm->name}");
                }
            }
        }
        $this->newLine();
        
        // Permissions totales
        $allPermissions = $user->getAllPermissions();
        $this->info("Total permissions: {$allPermissions->count()}");
        $this->newLine();
        
        // Test des policies
        $this->info('Test des accès (Policies):');
        $this->testPolicies($user);
    }

    private function auditSpecificRole(string $roleName): void
    {
        $role = Role::where('name', $roleName)->with(['permissions', 'users'])->first();
        
        if (!$role) {
            $this->error("❌ Rôle non trouvé: {$roleName}");
            return;
        }
        
        $this->info("🔍 AUDIT DÉTAILLÉ: Rôle {$roleName}");
        $this->info('==================================');
        $this->newLine();
        
        $this->info("Permissions assignées: {$role->permissions->count()}");
        $grouped = $role->permissions->groupBy(function($perm) {
            $parts = explode(' ', $perm->name);
            return $parts[0] ?? 'autre';
        });
        
        foreach ($grouped as $module => $perms) {
            $this->line("  Module {$module}:");
            foreach ($perms as $perm) {
                $this->line("    - {$perm->name}");
            }
        }
        $this->newLine();
        
        $this->info("Utilisateurs avec ce rôle: {$role->users->count()}");
        foreach ($role->users as $user) {
            $this->line("  - {$user->name} ({$user->email})");
        }
    }

    private function testPolicies(User $user): void
    {
        $policies = [
            'Papa' => 'viewAny',
            'Objectif' => 'viewAny',
            'ActionPrioritaire' => 'viewAny',
            'Tache' => 'viewAny',
            'Kpi' => 'viewAny',
            'Alerte' => 'viewAny',
            'Avancement' => 'viewAny',
        ];
        
        foreach ($policies as $model => $action) {
            $policyClass = "App\\Policies\\{$model}Policy";
            if (class_exists($policyClass)) {
                $policy = new $policyClass();
                $method = $action;
                
                if (method_exists($policy, $method)) {
                    $result = $policy->$method($user);
                    $status = $result ? '✅' : '❌';
                    $this->line("  {$status} {$model}::{$action}");
                }
            }
        }
    }
}



