<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionsCeeacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔐 Création des permissions CEEAC...');
        
        // Permissions pour PAPA
        $papaPermissions = [
            'viewAny papa',
            'view papa',
            'create papa',
            'update papa',
            'delete papa',
            'import papa',
            'export papa',
            'lock papa',
        ];
        
        // Permissions pour Objectifs
        $objectifPermissions = [
            'viewAny objectif',
            'view objectif',
            'create objectif',
            'update objectif',
            'delete objectif',
        ];
        
        // Permissions pour Actions Prioritaires
        $actionPermissions = [
            'viewAny action',
            'view action',
            'create action',
            'update action',
            'delete action',
        ];
        
        // Permissions pour Tâches
        $tachePermissions = [
            'viewAny tache',
            'view tache',
            'create tache',
            'update tache',
            'delete tache',
        ];
        
        // Permissions pour KPI
        $kpiPermissions = [
            'viewAny kpi',
            'view kpi',
            'create kpi',
            'update kpi',
            'delete kpi',
        ];
        
        // Permissions pour Alertes
        $alertePermissions = [
            'viewAny alerte',
            'view alerte',
            'create alerte',
            'update alerte',
            'delete alerte',
            'resolve alerte',
        ];
        
        // Permissions pour Avancements
        $avancementPermissions = [
            'viewAny avancement',
            'view avancement',
            'create avancement',
            'update avancement',
            'delete avancement',
        ];
        
        // Permissions pour Référentiels
        $legacyReferentielPermissions = [
            'viewAny referentiel',
            'view referentiel',
            'create referentiel',
            'update referentiel',
            'delete referentiel',
        ];
        $referentielEntityPermissions = $this->referentielPermissionsForActions([
            'viewAny',
            'view',
            'create',
            'update',
            'delete',
        ]);
        
        // Permissions pour Audit
        $auditPermissions = [
            'viewAny journal',
            'view journal',
            'export journal',
        ];
        
        // Permissions pour Gantt
        $ganttPermissions = [
            'view gantt',
            'update gantt',
        ];
        
        // Permissions pour Rapports
        $rapportPermissions = [
            'viewAny rapport',
            'view rapport',
            'create rapport',
            'update rapport',
            'delete rapport',
            'restore rapport',
            'forceDelete rapport',
            'generate rapport',
            'download rapport',
        ];
        
        // Créer toutes les permissions
        $allPermissions = array_merge(
            $papaPermissions,
            $objectifPermissions,
            $actionPermissions,
            $tachePermissions,
            $kpiPermissions,
            $alertePermissions,
            $avancementPermissions,
            $legacyReferentielPermissions,
            $referentielEntityPermissions,
            $auditPermissions,
            $ganttPermissions,
            $rapportPermissions
        );
        
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
        
        $this->command->info("    ✓ " . count($allPermissions) . " permissions créées");
        
        // Assigner les permissions aux rôles
        $this->assignPermissionsToRoles();
        
        $this->command->info('✅ Permissions créées et assignées aux rôles');
    }
    
    private function assignPermissionsToRoles(): void
    {
        $referentielViewPermissions = $this->referentielPermissionsForActions(['viewAny', 'view']);
        $referentielCreateUpdatePermissions = $this->referentielPermissionsForActions(['create', 'update']);

        // Présidence: accès complet en lecture, pas de modification
        $presidence = Role::findByName('presidence');
        $presidence->givePermissionTo(array_merge([
            'viewAny papa', 'view papa',
            'viewAny objectif', 'view objectif',
            'viewAny action', 'view action',
            'viewAny tache', 'view tache',
            'viewAny kpi', 'view kpi',
            'viewAny alerte', 'view alerte',
            'viewAny avancement', 'view avancement',
            'viewAny journal', 'view journal',
            'view gantt',
            'export papa',
            'viewAny rapport', 'view rapport', 'download rapport',
        ], $referentielViewPermissions, [
            'viewAny referentiel', 'view referentiel',
        ]));

        // Vice-Présidence: mêmes permissions que Présidence
        $vicePresidence = Role::findByName('vice_presidence');
        $vicePresidence->givePermissionTo(array_merge([
            'viewAny papa', 'view papa',
            'viewAny objectif', 'view objectif',
            'viewAny action', 'view action',
            'viewAny tache', 'view tache',
            'viewAny kpi', 'view kpi',
            'viewAny alerte', 'view alerte',
            'viewAny avancement', 'view avancement',
            'viewAny journal', 'view journal',
            'view gantt',
            'export papa',
            'viewAny rapport', 'view rapport', 'download rapport',
        ], $referentielViewPermissions, [
            'viewAny referentiel', 'view referentiel',
        ]));

        // Secrétaire Général: accès complet sauf suppression
        $sg = Role::findByName('secretaire_general');
        $sg->givePermissionTo(array_merge([
            'viewAny papa', 'view papa', 'create papa', 'update papa', 'import papa', 'export papa',
            'viewAny objectif', 'view objectif', 'create objectif', 'update objectif',
            'viewAny action', 'view action', 'create action', 'update action',
            'viewAny tache', 'view tache', 'create tache', 'update tache',
            'viewAny kpi', 'view kpi', 'create kpi', 'update kpi',
            'viewAny alerte', 'view alerte', 'create alerte', 'update alerte', 'resolve alerte',
            'viewAny avancement', 'view avancement', 'create avancement', 'update avancement',
            'viewAny journal', 'view journal', 'export journal',
            'view gantt', 'update gantt',
            'viewAny rapport', 'view rapport', 'create rapport', 'update rapport', 'generate rapport', 'download rapport',
        ], $referentielViewPermissions, $referentielCreateUpdatePermissions, [
            'viewAny referentiel', 'view referentiel', 'create referentiel', 'update referentiel',
        ]));

        // Commissaires: lecture + modification de leurs départements
        $commissaire = Role::findByName('commissaire');
        $commissaire->givePermissionTo(array_merge([
            'viewAny papa', 'view papa',
            'viewAny objectif', 'view objectif', 'update objectif',
            'viewAny action', 'view action', 'update action',
            'viewAny tache', 'view tache', 'update tache',
            'viewAny kpi', 'view kpi', 'update kpi',
            'viewAny alerte', 'view alerte', 'update alerte', 'resolve alerte',
            'viewAny avancement', 'view avancement', 'create avancement', 'update avancement',
            'view gantt',
            'viewAny rapport', 'view rapport', 'create rapport', 'update rapport', 'generate rapport', 'download rapport',
        ], $referentielViewPermissions, [
            'viewAny referentiel', 'view referentiel',
        ]));

        // Directeurs: gestion complète de leurs directions
        $directeur = Role::findByName('directeur');
        $directeur->givePermissionTo(array_merge([
            'viewAny papa', 'view papa',
            'viewAny objectif', 'view objectif', 'create objectif', 'update objectif',
            'viewAny action', 'view action', 'create action', 'update action',
            'viewAny tache', 'view tache', 'create tache', 'update tache',
            'viewAny kpi', 'view kpi', 'create kpi', 'update kpi',
            'viewAny alerte', 'view alerte', 'create alerte', 'update alerte', 'resolve alerte',
            'viewAny avancement', 'view avancement', 'create avancement', 'update avancement',
            'view gantt', 'update gantt',
            'viewAny rapport', 'view rapport', 'create rapport', 'update rapport', 'generate rapport', 'download rapport',
        ], $referentielViewPermissions, [
            'viewAny referentiel', 'view referentiel',
        ]));

        // Points focaux: gestion de leurs tâches
        $pointFocal = Role::findByName('point_focal');
        $pointFocal->givePermissionTo([
            'viewAny papa', 'view papa',
            'viewAny objectif', 'view objectif',
            'viewAny action', 'view action',
            'viewAny tache', 'view tache', 'update tache',
            'viewAny kpi', 'view kpi',
            'viewAny alerte', 'view alerte',
            'viewAny avancement', 'view avancement', 'create avancement', 'update avancement',
            'view gantt',
        ]);

        // Audit Interne: accès complet en lecture + export
        $audit = Role::findByName('audit_interne');
        $audit->givePermissionTo(array_merge([
            'viewAny papa', 'view papa',
            'viewAny objectif', 'view objectif',
            'viewAny action', 'view action',
            'viewAny tache', 'view tache',
            'viewAny kpi', 'view kpi',
            'viewAny alerte', 'view alerte',
            'viewAny avancement', 'view avancement',
            'viewAny journal', 'view journal', 'export journal',
            'view gantt',
            'export papa',
        ], $referentielViewPermissions, [
            'viewAny referentiel', 'view referentiel',
        ]));

        // ACC: gestion des alertes
        $acc = Role::findByName('acc');
        $acc->givePermissionTo([
            'viewAny papa', 'view papa',
            'viewAny objectif', 'view objectif',
            'viewAny action', 'view action',
            'viewAny tache', 'view tache',
            'viewAny kpi', 'view kpi',
            'viewAny alerte', 'view alerte', 'update alerte', 'resolve alerte',
            'viewAny avancement', 'view avancement',
            'view gantt',
        ]);

        // CFC: contrôle et validation
        $cfc = Role::findByName('cfc');
        $cfc->givePermissionTo(array_merge([
            'viewAny papa', 'view papa', 'update papa',
            'viewAny objectif', 'view objectif', 'update objectif',
            'viewAny action', 'view action', 'update action',
            'viewAny tache', 'view tache', 'update tache',
            'viewAny kpi', 'view kpi', 'update kpi',
            'viewAny alerte', 'view alerte', 'update alerte', 'resolve alerte',
            'viewAny avancement', 'view avancement', 'update avancement',
            'viewAny journal', 'view journal',
            'view gantt', 'update gantt',
        ], $referentielViewPermissions, [
            'viewAny referentiel', 'view referentiel',
        ]));

        // Bureau Liaison: accès en lecture
        $bureauLiaison = Role::findByName('bureau_liaison');
        $bureauLiaison->givePermissionTo(array_merge([
            'viewAny papa', 'view papa',
            'viewAny objectif', 'view objectif',
            'viewAny action', 'view action',
            'viewAny tache', 'view tache',
            'viewAny kpi', 'view kpi',
            'viewAny alerte', 'view alerte',
            'viewAny avancement', 'view avancement',
            'view gantt',
        ], $referentielViewPermissions, [
            'viewAny referentiel', 'view referentiel',
        ]));

        // Admin DSI: accès complet
        $admin = Role::findByName('admin_dsi');
        $admin->givePermissionTo(Permission::all());
    }

    private function referentielResources(): array
    {
        return [
            'referentiel.direction-technique',
            'referentiel.direction-appui',
            'referentiel.departement',
            'referentiel.commission',
            'referentiel.commissaire',
        ];
    }

    private function referentielPermissionsForActions(array $actions): array
    {
        $permissions = [];
        foreach ($this->referentielResources() as $resource) {
            foreach ($actions as $action) {
                $permissions[] = "{$action} {$resource}";
            }
        }

        return $permissions;
    }
}
