<?php

namespace Database\Factories;

use App\Models\Anomalie;
use App\Models\Tache;
use App\Models\ActionPrioritaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnomalieFactory extends Factory
{
    protected $model = Anomalie::class;

    private static $types = [
        'date_incoherente',
        'dependance_circulaire',
        'pourcentage_invalide',
        'echeance_passee_sans_statut',
        'kpi_negatif',
        'action_sans_responsable',
    ];

    public function definition(): array
    {
        $type = $this->faker->randomElement(self::$types);
        
        return [
            'type' => $type,
            'titre' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'severite' => $this->faker->randomElement(['faible', 'moyenne', 'elevee', 'critique']),
            'statut' => $this->faker->randomElement(['detectee', 'en_cours', 'corrigee', 'ignoree']),
            'tache_id' => $this->faker->optional(0.6)->randomElement([Tache::factory()]),
            'action_prioritaire_id' => $this->faker->optional(0.4)->randomElement([ActionPrioritaire::factory()]),
            'date_detection' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'date_correction' => $this->faker->optional(0.3)->dateTimeBetween('-1 month', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-2 months', 'now'),
            'updated_at' => now(),
        ];
    }
}




