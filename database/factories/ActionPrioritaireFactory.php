<?php

namespace Database\Factories;

use App\Models\ActionPrioritaire;
use App\Models\Objectif;
use App\Models\DirectionTechnique;
use App\Models\DirectionAppui;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActionPrioritaireFactory extends Factory
{
    protected $model = ActionPrioritaire::class;

    private static $libelles = [
        'Mise en place d\'un mécanisme de suivi des échanges commerciaux',
        'Renforcement des capacités des forces de sécurité',
        'Organisation d\'élections transparentes',
        'Construction de routes transfrontalières',
        'Mise en place d\'un système d\'alerte précoce',
        'Formation des agents de développement',
        'Renforcement des systèmes d\'information',
        'Mobilisation de fonds auprès des partenaires',
        'Protection des écosystèmes transfrontaliers',
        'Signature d\'accords de coopération',
    ];

    public function definition(): array
    {
        $libelle = $this->faker->randomElement(self::$libelles);
        $type = $this->faker->randomElement(['technique', 'appui']);
        
        return [
            'objectif_id' => Objectif::factory(),
            'code' => 'ACT-' . $this->faker->unique()->numberBetween(10000, 99999),
            'libelle' => $libelle,
            'description' => $this->faker->paragraph(2),
            'type' => $type,
            'direction_technique_id' => $type === 'technique' ? DirectionTechnique::factory() : null,
            'direction_appui_id' => $type === 'appui' ? DirectionAppui::factory() : null,
            'statut' => $this->faker->randomElement(['brouillon', 'en_cours', 'en_retard', 'bloque', 'termine', 'annule']),
            'priorite' => $this->faker->randomElement(['basse', 'normale', 'haute', 'critique']),
            'criticite' => $this->faker->randomElement(['normal', 'vigilance', 'critique']),
            'date_debut_prevue' => $this->faker->dateTimeBetween('-6 months', '+1 month'),
            'date_fin_prevue' => $this->faker->dateTimeBetween('+1 month', '+12 months'),
            'date_debut_reelle' => $this->faker->optional(0.6)->dateTimeBetween('-6 months', 'now'),
            'date_fin_reelle' => $this->faker->optional(0.2)->dateTimeBetween('-3 months', 'now'),
            'pourcentage_avancement' => $this->faker->numberBetween(0, 100),
            'bloque' => $this->faker->boolean(10), // 10% bloquées
            'raison_blocage' => $this->faker->optional(0.1)->sentence(),
            'created_at' => now()->subMonths(rand(3, 6)),
            'updated_at' => now(),
        ];
    }

    public function enRetard(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'en_retard',
            'criticite' => $this->faker->randomElement(['vigilance', 'critique']),
            'date_fin_prevue' => $this->faker->dateTimeBetween('-60 days', '-1 day'),
        ]);
    }

    public function critique(): static
    {
        return $this->state(fn (array $attributes) => [
            'criticite' => 'critique',
            'statut' => 'en_retard',
            'date_fin_prevue' => $this->faker->dateTimeBetween('-90 days', '-31 days'),
        ]);
    }

    public function bloquee(): static
    {
        return $this->state(fn (array $attributes) => [
            'statut' => 'bloque',
            'bloque' => true,
            'raison_blocage' => $this->faker->sentence(),
        ]);
    }
}



