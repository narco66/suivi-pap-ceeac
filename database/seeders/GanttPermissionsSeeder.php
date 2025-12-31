<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class GanttPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions Gantt
        $permissions = [
            'gantt.view' => 'Voir le diagramme de Gantt',
            'gantt.edit_dates' => 'Modifier les dates dans le Gantt (drag & drop)',
            'gantt.manage_dependencies' => 'Gérer les dépendances entre tâches',
            'gantt.export' => 'Exporter le Gantt en PDF/PNG',
            'gantt.approve' => 'Approuver les modifications sensibles',
        ];

        foreach ($permissions as $name => $description) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['name' => $name, 'guard_name' => 'web']
            );
        }

        // Attribuer les permissions aux rôles existants
        $adminRole = Role::where('name', 'admin')->first();
        $adminDsiRole = Role::where('name', 'admin_dsi')->first();
        $sgManagerRole = Role::where('name', 'sg_manager')->first();
        $directionManagerRole = Role::where('name', 'direction_manager')->first();

        if ($adminRole) {
            $adminRole->givePermissionTo(array_keys($permissions));
        }

        if ($adminDsiRole) {
            $adminDsiRole->givePermissionTo(array_keys($permissions));
        }

        if ($sgManagerRole) {
            $sgManagerRole->givePermissionTo(['gantt.view', 'gantt.edit_dates', 'gantt.manage_dependencies', 'gantt.export']);
        }

        if ($directionManagerRole) {
            $directionManagerRole->givePermissionTo(['gantt.view', 'gantt.export']);
        }

        $this->command->info('Permissions Gantt créées avec succès !');
    }
}
