<?php

namespace Database\Factories;

use App\Models\Papa;
use Illuminate\Database\Eloquent\Factories\Factory;

class PapaFactory extends Factory
{
    protected $model = Papa::class;

    public function definition(): array
    {
        $annee = $this->faker->randomElement([2024, 2025]);
        
        return [
            'code' => 'PAPA-' . $annee,
            'libelle' => 'Plan d\'Action Prioritaire ' . $annee,
            'annee' => $annee,
            'description' => 'Plan d\'Action Prioritaire de la CEEAC pour l\'année ' . $annee . '. Ce plan couvre les objectifs stratégiques de l\'institution.',
            'statut' => $this->faker->randomElement(['brouillon', 'en_cours', 'verrouille', 'cloture']),
            'date_debut' => $annee . '-01-01',
            'date_fin' => $annee . '-12-31',
            'created_at' => now()->subMonths(rand(6, 12)),
            'updated_at' => now(),
        ];
    }

    public function annee2024(): static
    {
        return $this->state(fn (array $attributes) => [
            'code' => 'PAPA-2024',
            'libelle' => 'Plan d\'Action Prioritaire 2024',
            'annee' => 2024,
            'date_debut' => '2024-01-01',
            'date_fin' => '2024-12-31',
        ]);
    }

    public function annee2025(): static
    {
        return $this->state(fn (array $attributes) => [
            'code' => 'PAPA-2025',
            'libelle' => 'Plan d\'Action Prioritaire 2025',
            'annee' => 2025,
            'date_debut' => '2025-01-01',
            'date_fin' => '2025-12-31',
        ]);
    }
}



