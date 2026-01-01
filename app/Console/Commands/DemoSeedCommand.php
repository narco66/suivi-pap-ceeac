<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DemoSeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:seed 
                            {--fresh : Drop all tables and re-run migrations}
                            {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Génère un dataset de démonstration complet pour SUIVI-PAPA CEEAC';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (!$this->option('force') && $this->laravel->environment() === 'production') {
            $this->error('Cette commande ne peut pas être exécutée en production sans --force');
            return Command::FAILURE;
        }

        $this->info('🚀 GÉNÉRATION DU DATASET DE DÉMONSTRATION');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->newLine();

        try {
            // 1. Migrations
            if ($this->option('fresh')) {
                $this->info('📋 Étape 1: Migration de la base de données...');
                $this->call('migrate:fresh', ['--force' => true]);
                $this->newLine();
            } else {
                $this->info('📋 Étape 1: Vérification des migrations...');
                $this->call('migrate', ['--force' => true]);
                $this->newLine();
            }

            // 2. Seeding
            $this->info('📋 Étape 2: Génération des données de démonstration...');
            $this->call('db:seed', [
                '--class' => 'Database\\Seeders\\Demo\\MasterDemoSeeder',
                '--force' => true,
            ]);
            $this->newLine();

            // 3. Génération des alertes
            $this->info('📋 Étape 3: Génération des alertes automatiques...');
            try {
                $this->call('papa:generate-alerts');
            } catch (\Exception $e) {
                $this->warn('  ⚠️  Commande papa:generate-alerts non disponible (peut être ignorée)');
            }
            $this->newLine();

            // 4. Validation
            $this->info('📋 Étape 4: Validation des données...');
            $this->validateData();
            $this->newLine();

            // 5. Résumé
            $this->displaySummary();

            $this->newLine();
            $this->info('✅ Dataset de démonstration généré avec succès!');
            $this->newLine();
            $this->info('🔐 Identifiants de connexion:');
            $this->info('   - Admin DSI: admin@ceeac.int / ' . config('seeding.demo_passwords.default', 'password'));
            $this->info('   - Président: president@ceeac.int / ' . config('seeding.demo_passwords.default', 'password'));
            $this->info('   - SG: sg@ceeac.int / ' . config('seeding.demo_passwords.default', 'password'));
            $this->newLine();

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Erreur lors de la génération du dataset: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return Command::FAILURE;
        }
    }

    private function validateData(): void
    {
        $errors = [];

        // Vérifier les PAPA
        $papas = \App\Models\Papa::count();
        if ($papas < 2) {
            $errors[] = "Nombre de PAPA insuffisant: {$papas} (attendu: au moins 2)";
        }

        // Vérifier les versions
        $versions = \App\Models\PapaVersion::count();
        if ($versions < 3) {
            $errors[] = "Nombre de versions insuffisant: {$versions} (attendu: au moins 3)";
        }

        // Vérifier les objectifs
        $objectifs = \App\Models\Objectif::count();
        if ($objectifs < 30) {
            $errors[] = "Nombre d'objectifs insuffisant: {$objectifs} (attendu: au moins 30)";
        }

        // Vérifier les actions
        $actions = \App\Models\ActionPrioritaire::count();
        if ($actions < 150) {
            $errors[] = "Nombre d'actions insuffisant: {$actions} (attendu: au moins 150)";
        }

        // Vérifier les tâches
        $taches = \App\Models\Tache::count();
        if ($taches < 800) {
            $errors[] = "Nombre de tâches insuffisant: {$taches} (attendu: au moins 800)";
        }

        // Vérifier les utilisateurs
        $users = \App\Models\User::count();
        if ($users < 30) {
            $errors[] = "Nombre d'utilisateurs insuffisant: {$users} (attendu: au moins 30)";
        }

        if (empty($errors)) {
            $this->info('  ✅ Toutes les validations sont passées');
        } else {
            foreach ($errors as $error) {
                $this->warn("  ⚠️  {$error}");
            }
        }
    }

    private function displaySummary(): void
    {
        $this->info('═══════════════════════════════════════════════════════════');
        $this->info('📊 RÉSUMÉ DU DATASET');
        $this->info('═══════════════════════════════════════════════════════════');
        $this->newLine();

        $this->table(
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
    }
}



