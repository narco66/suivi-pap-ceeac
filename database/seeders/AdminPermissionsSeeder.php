<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔐 Création des permissions Administration...');
        
        // Permissions pour la gestion des utilisateurs
        $userPermissions = [
            'viewAny admin.user',
            'view admin.user',
            'create admin.user',
            'update admin.user',
            'delete admin.user',
        ];
        
        // Permissions pour la gestion des rôles
        $rolePermissions = [
            'viewAny admin.role',
            'view admin.role',
            'create admin.role',
            'update admin.role',
            'delete admin.role',
        ];
        
        // Permissions pour les paramètres
        $settingPermissions = [
            'viewAny admin.setting',
            'view admin.setting',
            'update admin.setting',
        ];
        
        // Permissions pour les structures
        $structurePermissions = [
            'viewAny admin.structure',
            'view admin.structure',
            'create admin.structure',
            'update admin.structure',
            'delete admin.structure',
        ];
        
        // Permissions pour l'audit
        $auditPermissions = [
            'viewAny admin.audit',
            'view admin.audit',
            'export admin.audit',
        ];
        
        // Permissions pour les ressources
        $ressourcePermissions = [
            'viewAny ressource',
            'view ressource',
            'create ressource',
            'update ressource',
            'delete ressource',
        ];
        
        // Permissions pour l'accès admin général
        $adminPermissions = [
            'admin.access',
        ];
        
        // Créer la permission admin.access si elle n'existe pas
        Permission::firstOrCreate(['name' => 'admin.access']);
        
        // Créer toutes les permissions
        $allPermissions = array_merge(
            $userPermissions,
            $rolePermissions,
            $settingPermissions,
            $structurePermissions,
            $auditPermissions,
            $ressourcePermissions,
            $adminPermissions
        );
        
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        $this->command->info("    ✓ " . count($allPermissions) . " permissions créées");
        
        // Assigner toutes les permissions admin au rôle admin_dsi
        $adminDsiRole = Role::firstOrCreate(['name' => 'admin_dsi']);
        $adminDsiRole->givePermissionTo($allPermissions);
        
        // Créer aussi le rôle 'admin' et lui assigner les permissions
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo($allPermissions);
        
        $this->command->info('✅ Permissions Administration créées et assignées aux rôles admin_dsi et admin');
    }
}

