<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class CreateGanttPermissions extends Command
{
    protected $signature = 'gantt:create-permissions';
    protected $description = 'Créer les permissions Gantt avec le bon guard_name';

    public function handle()
    {
        $this->info('🔧 Création des permissions Gantt...');

        $permissions = [
            'gantt.view',
            'gantt.edit_dates',
            'gantt.manage_dependencies',
            'gantt.export',
            'gantt.approve',
        ];

        DB::transaction(function () use ($permissions) {
            foreach ($permissions as $permName) {
                // Supprimer d'abord si elle existe avec un mauvais guard
                Permission::where('name', $permName)
                    ->where('guard_name', '!=', 'web')
                    ->delete();

                // Créer ou mettre à jour avec le bon guard
                $permission = Permission::firstOrCreate(
                    ['name' => $permName, 'guard_name' => 'web'],
                    ['name' => $permName, 'guard_name' => 'web']
                );

                $this->line("  ✓ Permission '{$permName}' créée/vérifiée (guard: web)");
            }
        });

        // Attribuer aux rôles
        $adminRole = Role::where('name', 'admin')->where('guard_name', 'web')->first();
        $adminDsiRole = Role::where('name', 'admin_dsi')->where('guard_name', 'web')->first();

        if ($adminRole) {
            $adminRole->syncPermissions($permissions);
            $this->info("  ✓ Permissions attribuées au rôle 'admin'");
        }

        if ($adminDsiRole) {
            $adminDsiRole->syncPermissions($permissions);
            $this->info("  ✓ Permissions attribuées au rôle 'admin_dsi'");
        }

        // Vider tous les caches
        $this->call('permission:cache-reset');
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('route:clear');
        $this->call('view:clear');

        $this->info('✅ Permissions Gantt créées avec succès !');
        
        // Vérification finale
        $this->info("\n📋 Vérification des permissions créées:");
        foreach ($permissions as $permName) {
            $perm = Permission::where('name', $permName)->where('guard_name', 'web')->first();
            if ($perm) {
                $this->line("  ✓ {$permName} (ID: {$perm->id}, Guard: {$perm->guard_name})");
            } else {
                $this->error("  ✗ {$permName} - NON TROUVÉE");
            }
        }

        return 0;
    }
}


