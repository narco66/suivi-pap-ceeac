<?php

namespace Database\Seeders\Demo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Faker\Factory as FakerFactory;

/**
 * Master Seeder pour générer un dataset de démonstration complet
 * Orchestre tous les seeders de démo dans le bon ordre
 */
class MasterDemoSeeder extends Seeder
{
    protected $faker;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->faker = FakerFactory::create('fr_FR');
        $seed = config('seeding.seed', 12345);
        $this->faker->seed($seed);

        $this->command->info('🚀 DÉMARRAGE DU SEEDING DE DÉMONSTRATION COMPLET');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->newLine();

        $startTime = microtime(true);

        try {
            // 1. Permissions (déjà créées normalement, mais on vérifie)
            $this->command->info('📋 Étape 1/8: Vérification des permissions...');
            $this->call(\Database\Seeders\PermissionsCeeacSeeder::class);
            $this->command->newLine();

            // 2. Référentiels institutionnels
            $this->command->info('📋 Étape 2/8: Création des référentiels...');
            $this->call(\Database\Seeders\ReferentielsSeeder::class);
            $this->command->newLine();

            // 3. Utilisateurs avec rôles
            $this->command->info('📋 Étape 3/8: Création des utilisateurs...');
            $this->call(\Database\Seeders\UsersSeeder::class);
            $this->command->newLine();

            // 4. Scénario A: PAPA 2025 v1 (verrouillée) - PRINCIPAL
            $this->command->info('📋 Étape 4/8: Scénario A - PAPA 2025 v1 (verrouillée)...');
            $this->call(PapaScenarioASeeder::class);
            $this->command->newLine();

            // 5. Scénario B: PAPA 2025 v2 (brouillon)
            $this->command->info('📋 Étape 5/8: Scénario B - PAPA 2025 v2 (brouillon)...');
            $this->call(PapaScenarioBSeeder::class);
            $this->command->newLine();

            // 6. Scénario C: PAPA 2024 (archivée)
            $this->command->info('📋 Étape 6/8: Scénario C - PAPA 2024 (archivée)...');
            $this->call(PapaScenarioCSeeder::class);
            $this->command->newLine();

            // 7. Génération des alertes automatiques
            $this->command->info('📋 Étape 7/8: Génération des alertes automatiques...');
            $this->call(AlertesAutoSeeder::class);
            $this->command->newLine();

            // 8. Génération des journaux d'audit
            $this->command->info('📋 Étape 8/8: Génération des journaux d\'audit...');
            $this->call(\Database\Seeders\JournauxSeeder::class);
            $this->command->newLine();

            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);

            // Afficher le résumé
            $this->displaySummary($duration);

            // Générer les alertes via commande
            $this->command->info('🔔 Génération des alertes automatiques...');
            Artisan::call('papa:generate-alerts');
            $this->command->info('  ✅ Alertes générées');

        } catch (\Exception $e) {
            $this->command->error('❌ Erreur lors du seeding: ' . $e->getMessage());
            $this->command->error($e->getTraceAsString());
            throw $e;
        }
    }

    private function displaySummary(float $duration): void
    {
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->info('📊 RÉSUMÉ DU DATASET DE DÉMONSTRATION');
        $this->command->info('═══════════════════════════════════════════════════════════');
        $this->command->newLine();

        $this->command->table(
            ['Entité', 'Nombre'],
            [
                ['Utilisateurs', \App\Models\User::count()],
                ['Départements', \App\Models\Departement::count()],
                ['Directions Techniques', \App\Models\DirectionTechnique::count()],
                ['Directions d\'Appui', \App\Models\DirectionAppui::count()],
                ['PAPA', \App\Models\Papa::count()],
                ['Versions PAPA', \App\Models\PapaVersion::count()],
                ['Objectifs', \App\Models\Objectif::count()],
                ['Actions Prioritaires', \App\Models\ActionPrioritaire::count()],
                ['Tâches', \App\Models\Tache::count()],
                ['KPI', \App\Models\Kpi::count()],
                ['Avancements', \App\Models\Avancement::count()],
                ['Alertes', \App\Models\Alerte::count()],
                ['Journaux', \App\Models\Journal::count()],
            ]
        );

        $this->command->newLine();
        $this->command->info("⏱️  Durée totale: {$duration} secondes");
        $this->command->newLine();
        $this->command->info('✅ Dataset de démonstration créé avec succès!');
        $this->command->newLine();
        $this->command->info('🔐 Identifiants de connexion:');
        $this->command->info('   - Admin DSI: admin@ceeac.int / ' . config('seeding.demo_passwords.default', 'password'));
        $this->command->info('   - Président: president@ceeac.int / ' . config('seeding.demo_passwords.default', 'password'));
        $this->command->info('   - SG: sg@ceeac.int / ' . config('seeding.demo_passwords.default', 'password'));
        $this->command->newLine();
    }
}



