<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Database\Seeders\StructuresSeeder;

class SeedStructures extends Command
{
    protected $signature = 'admin:seed-structures';
    protected $description = 'Créer les structures organisationnelles de base';

    public function handle()
    {
        $this->info('🏢 Création des structures organisationnelles...');
        
        $seeder = new StructuresSeeder();
        $seeder->setCommand($this);
        $seeder->run();
        
        $this->newLine();
        $this->info('✅ Structures créées avec succès!');
        
        return 0;
    }
}



