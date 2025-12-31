<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Faker\Factory as FakerFactory;

class DatabaseSeeder extends Seeder
{
    protected $faker;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Configurer Faker avec seed stable pour reproductibilité
        $seed = config('seeding.seed', 12345);
        $this->faker = FakerFactory::create('fr_FR');
        $this->faker->seed($seed);

        $this->command->info('🚀 Démarrage du seeding complet de SUIVI-PAPA CEEAC...');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->newLine();

        $startTime = microtime(true);

        try {
            // 1. Référentiels institutionnels
            $this->call(ReferentielsSeeder::class);
            $this->command->newLine();

            // 2. Rôles CEEAC
            $this->call(RolesCeeacSeeder::class);
            $this->command->newLine();

            // 3. Permissions CEEAC
            $this->call(PermissionsCeeacSeeder::class);
            $this->command->newLine();
            
            // 3.1. Permissions Administration
            $this->call(AdminPermissionsSeeder::class);
            $this->command->newLine();
            
            // 3.2. Structures organisationnelles
            $this->call(StructuresSeeder::class);
            $this->command->newLine();
            
            // 3.3. Ressources
            $this->call(RessourcesSeeder::class);
            $this->command->newLine();
            
            // 4. Utilisateurs avec rôles
            $this->call(UsersSeeder::class);
            $this->command->newLine();

            // 5. Hiérarchie PAPA complète
            $this->call(PapaHierarchieSeeder::class);
            $this->command->newLine();

            // 6. Journaux d'audit
            $this->call(JournauxSeeder::class);
            $this->command->newLine();

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);

            // Afficher le résumé
            $this->displaySummary($duration);

        } catch (\Exception $e) {
            $this->command->error('❌ Erreur lors du seeding: ' . $e->getMessage());
            $this->command->error($e->getTraceAsString());
            throw $e;
        }
    }

    private function displaySummary(float $duration): void
    {
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('📊 RÉSUMÉ DU DATASET GÉNÉRÉ');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->newLine();

        $this->command->table(
            ['Entité', 'Nombre'],
            [
                ['Utilisateurs', \App\Models\User::count()],
                ['Départements', \App\Models\Departement::count()],
                ['Directions Techniques', \App\Models\DirectionTechnique::count()],
                ['Directions d\'Appui', \App\Models\DirectionAppui::count()],
                ['Commissions', \App\Models\Commission::count()],
                ['Commissaires', \App\Models\Commissaire::count()],
                ['PAPA', \App\Models\Papa::count()],
                ['Versions PAPA', \App\Models\PapaVersion::count()],
                ['Objectifs', \App\Models\Objectif::count()],
                ['Actions Prioritaires', \App\Models\ActionPrioritaire::count()],
                ['Tâches', \App\Models\Tache::count()],
                ['KPI', \App\Models\Kpi::count()],
                ['Avancements', \App\Models\Avancement::count()],
                ['Alertes', \App\Models\Alerte::count()],
                ['Anomalies', \App\Models\Anomalie::count()],
                ['Journaux', \App\Models\Journal::count()],
            ]
        );

        $this->command->newLine();
        $this->command->info("⏱️  Durée totale: {$duration} secondes");
        $this->command->newLine();
        $this->command->info('✅ Seeding terminé avec succès!');
        $this->command->newLine();
        $this->command->info('🔐 Identifiants de connexion:');
        $this->command->info('   - Admin DSI: admin@ceeac.int / ' . config('seeding.demo_passwords.default', 'password'));
        $this->command->info('   - Président: president@ceeac.int / ' . config('seeding.demo_passwords.default', 'password'));
        $this->command->info('   - SG: sg@ceeac.int / ' . config('seeding.demo_passwords.default', 'password'));
        $this->command->info('   - Tous les utilisateurs: [email]@ceeac.int / ' . config('seeding.demo_passwords.default', 'password'));
        $this->command->newLine();
    }
}
