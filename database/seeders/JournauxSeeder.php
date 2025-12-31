<?php

namespace Database\Seeders;

use App\Models\Journal;
use App\Models\User;
use App\Models\Papa;
use App\Models\PapaVersion;
use App\Models\Objectif;
use App\Models\ActionPrioritaire;
use App\Models\Tache;
use App\Models\Kpi;
use App\Models\Alerte;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as FakerFactory;

class JournauxSeeder extends Seeder
{
    private $faker;
    
    public function run(): void
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->faker->seed(config('seeding.seed', 12345));
        
        $config = config('seeding.volumes');
        $count = $config['journaux_total'];
        
        $this->command->info("📝 Création de {$count} entrées de journaux d'audit...");
        
        $users = User::all();
        $papas = Papa::all();
        $versions = PapaVersion::all();
        $objectifs = Objectif::all();
        $actions = ActionPrioritaire::all();
        $taches = Tache::all();
        $kpis = Kpi::all();
        $alertes = Alerte::all();
        
        $entites = [
            ['type' => 'papa', 'ids' => $papas->pluck('id')->toArray()],
            ['type' => 'papa_version', 'ids' => $versions->pluck('id')->toArray()],
            ['type' => 'objectif', 'ids' => $objectifs->pluck('id')->toArray()],
            ['type' => 'action_prioritaire', 'ids' => $actions->pluck('id')->toArray()],
            ['type' => 'tache', 'ids' => $taches->pluck('id')->toArray()],
            ['type' => 'kpi', 'ids' => $kpis->pluck('id')->toArray()],
            ['type' => 'alerte', 'ids' => $alertes->pluck('id')->toArray()],
        ];
        
        $actionsList = [
            'creation',
            'modification',
            'suppression',
            'changement_statut',
            'verrouillage',
            'deverrouillage',
            'export',
            'import',
            'traitement_alerte',
            'validation',
            'rejet',
            'escalade',
        ];
        
        DB::transaction(function () use ($count, $users, $entites, $actionsList) {
            $bar = $this->command->getOutput()->createProgressBar($count);
            $bar->start();
            
            $chunks = array_chunk(range(1, $count), 500); // Par lots de 500
            
            foreach ($chunks as $chunk) {
                $journaux = [];
                
                foreach ($chunk as $i) {
                    $entite = $this->faker->randomElement($entites);
                    $entiteId = !empty($entite['ids']) ? $this->faker->randomElement($entite['ids']) : 1;
                    
                    $journaux[] = [
                        'action' => $this->faker->randomElement($actionsList),
                        'entite_type' => $entite['type'],
                        'entite_id' => $entiteId,
                        'utilisateur_id' => $users->random()->id,
                        'description' => $this->faker->sentence(),
                        'donnees_avant' => $this->generateRandomAuditData(),
                        'donnees_apres' => $this->generateRandomAuditData(),
                        'ip_address' => $this->faker->ipv4(),
                        'user_agent' => $this->faker->userAgent(),
                        'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
                        'updated_at' => now(),
                    ];
                }
                
                Journal::insert($journaux);
                $bar->advance(count($chunk));
            }
            
            $bar->finish();
            $this->command->newLine();
        });
        
        $this->command->info("✅ {$count} entrées de journaux créées!");
    }

    private function generateRandomAuditData(): ?string
    {
        if (!$this->faker->boolean(30)) {
            return null;
        }

        return json_encode([
            'champ' => $this->faker->word(),
            'valeur' => $this->faker->sentence(),
            'montant' => $this->faker->randomFloat(2, 0, 10000),
        ], JSON_UNESCAPED_UNICODE);
    }
}

