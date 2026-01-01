<?php

namespace Database\Seeders;

use App\Models\Departement;
use App\Models\DirectionTechnique;
use App\Models\DirectionAppui;
use App\Models\Commission;
use App\Models\Commissaire;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReferentielsSeeder extends Seeder
{
    public function run(): void
    {
        $config = config('seeding.volumes');
        
        $this->command->info('🌍 Création des référentiels institutionnels...');
        
        DB::transaction(function () use ($config) {
            // Départements
            $this->command->info('  → Création des départements...');
            $departements = Departement::factory($config['departements'])->create();
            $this->command->info("    ✓ {$departements->count()} départements créés");
            
            // Directions Techniques
            $this->command->info('  → Création des directions techniques...');
            $directionsTechniques = collect();
            foreach ($departements as $departement) {
                $count = (int)($config['directions_techniques'] / $departements->count()) + 1;
                $directions = DirectionTechnique::factory($count)
                    ->state(['departement_id' => $departement->id])
                    ->create();
                $directionsTechniques = $directionsTechniques->merge($directions);
            }
            $this->command->info("    ✓ {$directionsTechniques->count()} directions techniques créées");
            
            // Directions d'Appui
            $this->command->info('  → Création des directions d\'appui...');
            $directionsAppui = DirectionAppui::factory($config['directions_appui'])->create();
            $this->command->info("    ✓ {$directionsAppui->count()} directions d'appui créées");
            
            // Commissions
            $this->command->info('  → Création des commissions...');
            $commissions = Commission::factory($config['commissions'])->create();
            $this->command->info("    ✓ {$commissions->count()} commissions créées");
            
            // Commissaires
            $this->command->info('  → Création des commissaires...');
            $commissaires = collect();
            foreach ($commissions as $commission) {
                $count = (int)($config['commissaires'] / $commissions->count()) + 1;
                $comms = Commissaire::factory($count)
                    ->state(['commission_id' => $commission->id])
                    ->create();
                $commissaires = $commissaires->merge($comms);
            }
            $this->command->info("    ✓ {$commissaires->count()} commissaires créés");
        });
        
        $this->command->info('✅ Référentiels créés avec succès!');
    }
}




