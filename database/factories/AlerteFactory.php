<?php

namespace Database\Factories;

use App\Models\Alerte;
use App\Models\Tache;
use App\Models\ActionPrioritaire;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AlerteFactory extends Factory
{
    protected $model = Alerte::class;

    private static $types = [
        'echeance_depassee',
        'retard_critique',
        'blocage',
        'anomalie',
        'escalade',
        'kpi_non_atteint',
    ];

    public function definition(): array
    {
        $type = $this->faker->randomElement(self::$types);
        $criticite = $this->faker->randomElement(['normal', 'vigilance', 'critique']);
        
        return [
            'type' => $type,
            'titre' => $this->faker->sentence(),
            'message' => $this->faker->paragraph(),
            'criticite' => $criticite,
            'statut' => $this->faker->randomElement(['ouverte', 'en_cours', 'resolue', 'fermee']),
            'tache_id' => $this->faker->optional(0.7)->randomElement([Tache::factory()]),
            'action_prioritaire_id' => $this->faker->optional(0.5)->randomElement([ActionPrioritaire::factory()]),
            'niveau_escalade' => $this->faker->randomElement(['direction', 'sg', 'commissaire', 'presidence']),
            'cree_par_id' => User::factory(),
            'assignee_a_id' => $this->faker->optional(0.6)->randomElement([User::factory()]),
            'date_creation' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'date_resolution' => $this->faker->optional(0.4)->dateTimeBetween('-2 months', 'now'),
            'created_at' => $this->faker->dateTimeBetween('-3 months', 'now'),
            'updated_at' => now(),
        ];
    }

    public function critique(): static
    {
        return $this->state(fn (array $attributes) => [
            'criticite' => 'critique',
            'type' => $this->faker->randomElement(['retard_critique', 'escalade']),
            'niveau_escalade' => $this->faker->randomElement(['sg', 'commissaire', 'presidence']),
        ]);
    }
}



