<?php

namespace Database\Factories;

use App\Models\Journal;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JournalFactory extends Factory
{
    protected $model = Journal::class;

    private static $actions = [
        'creation',
        'modification',
        'suppression',
        'changement_statut',
        'verrouillage',
        'deverrouillage',
        'export',
        'import',
        'traitement_alerte',
        'validation',
        'rejet',
        'escalade',
    ];

    private static $entites = [
        'papa',
        'papa_version',
        'objectif',
        'action_prioritaire',
        'tache',
        'kpi',
        'alerte',
        'avancement',
    ];

    public function definition(): array
    {
        $action = $this->faker->randomElement(self::$actions);
        $entite = $this->faker->randomElement(self::$entites);
        
        return [
            'action' => $action,
            'entite_type' => $entite,
            'entite_id' => $this->faker->numberBetween(1, 1000),
            'utilisateur_id' => User::factory(),
            'description' => $this->faker->sentence(),
            'donnees_avant' => $this->faker->optional(0.3)->json(),
            'donnees_apres' => $this->faker->optional(0.3)->json(),
            'ip_address' => $this->faker->ipv4(),
            'user_agent' => $this->faker->userAgent(),
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'updated_at' => now(),
        ];
    }
}




