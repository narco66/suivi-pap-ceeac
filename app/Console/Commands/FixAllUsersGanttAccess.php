<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class FixAllUsersGanttAccess extends Command
{
    protected $signature = 'gantt:fix-all-users';
    protected $description = 'Attribuer l\'accès Gantt à tous les utilisateurs admin';

    public function handle()
    {
        $this->info('🔧 Correction de l\'accès Gantt pour tous les utilisateurs...');

        // Récupérer les rôles admin
        $adminRole = Role::where('name', 'admin')->first();
        $adminDsiRole = Role::where('name', 'admin_dsi')->first();

        // Récupérer la permission
        $ganttViewPerm = Permission::where('name', 'gantt.view')->first();

        if (!$ganttViewPerm) {
            $this->error('La permission gantt.view n\'existe pas. Exécutez d\'abord: php artisan gantt:grant-permissions');
            return 1;
        }

        // Attribuer la permission à tous les utilisateurs avec rôle admin ou admin_dsi
        $users = User::role(['admin', 'admin_dsi'])->get();
        
        $this->info("Trouvé {$users->count()} utilisateur(s) avec rôle admin/admin_dsi");

        foreach ($users as $user) {
            if (!$user->hasPermissionTo('gantt.view')) {
                $user->givePermissionTo('gantt.view');
                $this->line("  ✓ Permission gantt.view attribuée à {$user->email}");
            } else {
                $this->line("  - {$user->email} a déjà la permission");
            }
        }

        // Aussi attribuer directement à tous les utilisateurs admin (au cas où)
        if ($adminRole) {
            $adminUsers = User::whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })->get();
            
            foreach ($adminUsers as $user) {
                if (!$user->hasPermissionTo('gantt.view')) {
                    $user->givePermissionTo('gantt.view');
                    $this->line("  ✓ Permission gantt.view attribuée à {$user->email} (admin)");
                }
            }
        }

        // Vider le cache
        $this->call('permission:cache-reset');
        $this->call('cache:clear');
        $this->call('config:clear');
        
        $this->info('✅ Correction terminée !');
        return 0;
    }
}


