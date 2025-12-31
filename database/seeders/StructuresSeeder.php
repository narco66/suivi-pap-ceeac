<?php

namespace Database\Seeders;

use App\Models\Structure;
use Illuminate\Database\Seeder;

class StructuresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏢 Création des structures organisationnelles...');

        // Structures principales (niveau 1)
        $presidence = Structure::firstOrCreate(
            ['code' => 'PRES'],
            [
                'name' => 'Présidence',
                'type' => 'direction',
                'description' => 'Présidence de la CEEAC',
                'is_active' => true,
                'order' => 1,
            ]
        );

        $vicePresidence = Structure::firstOrCreate(
            ['code' => 'VICE-PRES'],
            [
                'name' => 'Vice-Présidence',
                'type' => 'direction',
                'description' => 'Vice-Présidence de la CEEAC',
                'is_active' => true,
                'order' => 2,
            ]
        );

        $secretariatGeneral = Structure::firstOrCreate(
            ['code' => 'SG'],
            [
                'name' => 'Secrétariat Général',
                'type' => 'direction',
                'parent_id' => $presidence->id,
                'description' => 'Secrétariat Général de la CEEAC',
                'is_active' => true,
                'order' => 3,
            ]
        );

        // Directions Techniques
        $directionsTechniques = [
            ['code' => 'DT-COM', 'name' => 'Direction du Commerce et de l\'Intégration', 'type' => 'direction'],
            ['code' => 'DT-INFRA', 'name' => 'Direction des Infrastructures', 'type' => 'direction'],
            ['code' => 'DT-PAIX', 'name' => 'Direction de la Paix et de la Sécurité', 'type' => 'direction'],
            ['code' => 'DT-ENV', 'name' => 'Direction de l\'Environnement', 'type' => 'direction'],
            ['code' => 'DT-FIN', 'name' => 'Direction des Finances', 'type' => 'direction'],
        ];

        foreach ($directionsTechniques as $index => $dir) {
            Structure::firstOrCreate(
                ['code' => $dir['code']],
                [
                    'name' => $dir['name'],
                    'type' => $dir['type'],
                    'parent_id' => $secretariatGeneral->id,
                    'description' => "Direction technique de la CEEAC",
                    'is_active' => true,
                    'order' => 10 + $index,
                ]
            );
        }

        // Directions d'Appui
        $directionsAppui = [
            ['code' => 'DA-ADM', 'name' => 'Direction de l\'Administration', 'type' => 'direction'],
            ['code' => 'DA-RH', 'name' => 'Direction des Ressources Humaines', 'type' => 'direction'],
            ['code' => 'DA-FIN', 'name' => 'Direction Financière', 'type' => 'direction'],
            ['code' => 'DA-COM', 'name' => 'Direction de la Communication', 'type' => 'direction'],
        ];

        foreach ($directionsAppui as $index => $dir) {
            Structure::firstOrCreate(
                ['code' => $dir['code']],
                [
                    'name' => $dir['name'],
                    'type' => $dir['type'],
                    'parent_id' => $secretariatGeneral->id,
                    'description' => "Direction d'appui de la CEEAC",
                    'is_active' => true,
                    'order' => 20 + $index,
                ]
            );
        }

        // Services rattachés
        $services = [
            ['code' => 'SRV-AUDIT', 'name' => 'Service Audit Interne', 'type' => 'service'],
            ['code' => 'SRV-ACC', 'name' => 'Service ACC', 'type' => 'service'],
            ['code' => 'SRV-CFC', 'name' => 'Service CFC', 'type' => 'service'],
            ['code' => 'SRV-LIAISON', 'name' => 'Bureau de Liaison', 'type' => 'bureau'],
        ];

        foreach ($services as $index => $service) {
            Structure::firstOrCreate(
                ['code' => $service['code']],
                [
                    'name' => $service['name'],
                    'type' => $service['type'],
                    'parent_id' => $secretariatGeneral->id,
                    'description' => "Service rattaché au Secrétariat Général",
                    'is_active' => true,
                    'order' => 30 + $index,
                ]
            );
        }

        $count = Structure::count();
        $this->command->info("    ✓ {$count} structures créées/vérifiées");
        $this->command->info('✅ Structures organisationnelles créées avec succès!');
    }
}


