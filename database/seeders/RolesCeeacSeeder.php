<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesCeeacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('ÐY"? Création des rôles CEEAC...');
        $roles = [
            'presidence',
            'vice_presidence',
            'secretaire_general',
            'commissaire',
            'directeur',
            'point_focal',
            'audit_interne',
            'acc',
            'cfc',
            'bureau_liaison',
            'admin_dsi',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web',
            ]);
        }

        $this->command->info("    ƒo" . count($roles) . " rôles créés");
    }
}
