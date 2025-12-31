<?php

namespace Database\Factories;

use App\Models\Objectif;
use App\Models\PapaVersion;
use Illuminate\Database\Eloquent\Factories\Factory;

class ObjectifFactory extends Factory
{
    protected $model = Objectif::class;

    private static $libelles = [
        'Renforcer l\'intégration économique régionale',
        'Promouvoir la paix et la sécurité',
        'Améliorer la gouvernance démocratique',
        'Développer les infrastructures régionales',
        'Renforcer la coopération en matière de sécurité alimentaire',
        'Promouvoir le développement social',
        'Renforcer les capacités institutionnelles',
        'Améliorer la mobilisation des ressources',
        'Promouvoir l\'environnement et le développement durable',
        'Renforcer la coopération internationale',
    ];

    public function definition(): array
    {
        $libelle = $this->faker->randomElement(self::$libelles);
        
        return [
            'papa_version_id' => PapaVersion::factory(),
            'code' => 'OBJ-' . $this->faker->unique()->numberBetween(1000, 9999),
            'libelle' => $libelle,
            'description' => $this->faker->paragraph(),
            'statut' => $this->faker->randomElement(['brouillon', 'en_cours', 'termine', 'annule']),
            'priorite' => $this->faker->randomElement(['basse', 'normale', 'haute', 'critique']),
            'date_debut_prevue' => $this->faker->dateTimeBetween('-6 months', '+1 month'),
            'date_fin_prevue' => $this->faker->dateTimeBetween('+1 month', '+12 months'),
            'date_debut_reelle' => $this->faker->optional(0.6)->dateTimeBetween('-6 months', 'now'),
            'date_fin_reelle' => $this->faker->optional(0.2)->dateTimeBetween('-3 months', 'now'),
            'pourcentage_avancement' => $this->faker->numberBetween(0, 100),
            'created_at' => now()->subMonths(rand(3, 6)),
            'updated_at' => now(),
        ];
    }
}



