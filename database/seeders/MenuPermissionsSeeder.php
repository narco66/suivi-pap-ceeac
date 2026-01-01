<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MenuPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Créer la permission admin.access si elle n'existe pas
        $adminAccessPermission = Permission::firstOrCreate(
            ['name' => 'admin.access', 'guard_name' => 'web'],
            ['name' => 'admin.access', 'guard_name' => 'web']
        );

        // Assigner la permission aux rôles admin
        $adminRoles = ['admin', 'admin_dsi', 'super_admin'];
        
        foreach ($adminRoles as $roleName) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            if ($role) {
                $role->givePermissionTo($adminAccessPermission);
                $this->command->info("Permission 'admin.access' assignée au rôle: {$roleName}");
            } else {
                $this->command->warn("Rôle '{$roleName}' non trouvé");
            }
        }

        $this->command->info('Permissions menu créées avec succès !');
    }
}


