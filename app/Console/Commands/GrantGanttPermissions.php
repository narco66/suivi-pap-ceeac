<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class GrantGanttPermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gantt:grant-permissions {--user= : ID ou email de l\'utilisateur}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attribuer les permissions Gantt aux rôles et utilisateurs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎯 Attribution des permissions Gantt...');

        // Vérifier que les permissions existent
        $permissions = [
            'gantt.view',
            'gantt.edit_dates',
            'gantt.manage_dependencies',
            'gantt.export',
            'gantt.approve',
        ];

        foreach ($permissions as $permName) {
            $permission = Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
            $this->line("  ✓ Permission '{$permName}' vérifiée");
        }

        // Attribuer aux rôles
        $roles = [
            'admin' => $permissions,
            'admin_dsi' => $permissions,
            'sg_manager' => ['gantt.view', 'gantt.edit_dates', 'gantt.manage_dependencies', 'gantt.export', 'gantt.approve'],
            'direction_manager' => ['gantt.view', 'gantt.export'],
        ];

        foreach ($roles as $roleName => $rolePerms) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                $role->syncPermissions($rolePerms);
                $this->info("  ✓ Rôle '{$roleName}' a reçu les permissions");
            } else {
                $this->warn("  ⚠ Rôle '{$roleName}' n'existe pas");
            }
        }

        // Si un utilisateur spécifique est fourni
        if ($userId = $this->option('user')) {
            $user = is_numeric($userId) 
                ? User::find($userId) 
                : User::where('email', $userId)->first();

            if ($user) {
                $user->givePermissionTo($permissions);
                $this->info("  ✓ Utilisateur '{$user->email}' a reçu toutes les permissions Gantt");
            } else {
                $this->error("  ✗ Utilisateur non trouvé");
            }
        }

        // Vider le cache
        $this->call('permission:cache-reset');
        $this->info('  ✓ Cache des permissions vidé');

        $this->info('✅ Permissions Gantt attribuées avec succès !');
    }
}


