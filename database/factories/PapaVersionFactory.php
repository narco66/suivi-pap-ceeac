<?php

namespace Database\Factories;

use App\Models\PapaVersion;
use App\Models\Papa;
use Illuminate\Database\Eloquent\Factories\Factory;

class PapaVersionFactory extends Factory
{
    protected $model = PapaVersion::class;

    public function definition(): array
    {
        $numero = $this->faker->numberBetween(1, 3);
        
        return [
            'papa_id' => Papa::factory(),
            'numero' => $numero,
            'libelle' => 'Version ' . $numero,
            'description' => $this->faker->optional()->sentence(),
            'statut' => $this->faker->randomElement(['brouillon', 'active', 'verrouille', 'archive']),
            'date_creation' => now()->subMonths(rand(3, 6)),
            'date_verrouillage' => $this->faker->optional(0.3)->dateTimeBetween('-3 months', 'now'),
            'verrouille' => $this->faker->boolean(30), // 30% verrouillées
            'created_at' => now()->subMonths(rand(3, 6)),
            'updated_at' => now(),
        ];
    }

    public function verrouillee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'verrouille',
            'verrouille' => true,
            'date_verrouillage' => $this->faker->dateTimeBetween('-3 months', 'now'),
        ]);
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'active',
            'verrouille' => false,
            'date_verrouillage' => null,
        ]);
    }
}




