<?php

namespace Database\Factories;

use App\Models\DirectionTechnique;
use App\Models\Departement;
use Illuminate\Database\Eloquent\Factories\Factory;

class DirectionTechniqueFactory extends Factory
{
    protected $model = DirectionTechnique::class;

    private static $noms = [
        'Direction du Commerce Intra-Communautaire',
        'Direction des Douanes',
        'Direction de la Sécurité Alimentaire',
        'Direction de la Protection Sociale',
        'Direction de la Prévention des Conflits',
        'Direction de la Sécurité Transfrontalière',
        'Direction des Infrastructures Routières',
        'Direction des Infrastructures Énergétiques',
        'Direction de la Planification Budgétaire',
        'Direction de la Mobilisation des Ressources',
        'Direction des Relations Politiques',
        'Direction de la Gouvernance Démocratique',
    ];

    public function definition(): array
    {
        $nom = $this->faker->unique()->randomElement(self::$noms);
        
        return [
            'code' => 'DT-' . strtoupper(substr($nom, 0, 2)) . '-' . $this->faker->unique()->numberBetween(10, 99),
            'libelle' => $nom,
            'departement_id' => Departement::factory(),
            'description' => $this->faker->optional()->sentence(),
            'actif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}




