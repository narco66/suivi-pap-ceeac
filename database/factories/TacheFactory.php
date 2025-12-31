<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\Tache;
use App\Models\ActionPrioritaire;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TacheFactory extends Factory
{
    protected $model = Tache::class;

    private static $libelles = [
        'Réaliser une étude de faisabilité',
        'Organiser une réunion de coordination',
        'Préparer un document de synthèse',
        'Valider le budget alloué',
        'Former les équipes concernées',
        'Mettre en place un système de suivi',
        'Effectuer une mission sur le terrain',
        'Rédiger un rapport d\'activité',
        'Organiser un atelier de sensibilisation',
        'Finaliser la documentation technique',
    ];

    public function definition(): array
    {
        $libelle = $this->faker->randomElement(self::$libelles);
        
        // Générer des dates cohérentes (début avant fin, et fin pas trop dans le futur)
        $now = Carbon::now();
        $dateDebut = Carbon::instance($this->faker->dateTimeBetween('-6 months', $now));
        $maxDateFin = min($now->copy()->addMonths(12), $dateDebut->copy()->addMonths(6));
        $dateFin = Carbon::instance($this->faker->dateTimeBetween($dateDebut, $maxDateFin));
        
        return [
            'action_prioritaire_id' => ActionPrioritaire::factory(),
            'tache_parent_id' => null, // Sera défini dans le seeder pour les sous-tâches
            'code' => 'TACH-' . $this->faker->unique()->numberBetween(100000, 999999),
            'libelle' => $libelle,
            'description' => $this->faker->optional()->paragraph(),
            'statut' => $this->faker->randomElement(['brouillon', 'en_cours', 'en_retard', 'bloque', 'termine', 'annule']),
            'priorite' => $this->faker->randomElement(['basse', 'normale', 'haute', 'critique']),
            'criticite' => $this->faker->randomElement(['normal', 'vigilance', 'critique']),
            'date_debut_prevue' => $dateDebut,
            'date_fin_prevue' => $dateFin,
            'date_debut_reelle' => $this->faker->optional(0.6)->dateTimeBetween($dateDebut, 'now'),
            'date_fin_reelle' => $this->faker->optional(0.2)->dateTimeBetween($dateDebut, 'now'),
            'pourcentage_avancement' => $this->faker->numberBetween(0, 100),
            'responsable_id' => User::factory(),
            'bloque' => $this->faker->boolean(10),
            'raison_blocage' => $this->faker->optional(0.1)->sentence(),
            'est_jalon' => $this->faker->boolean(20), // 20% sont des jalons
            'created_at' => now()->subMonths(rand(3, 6)),
            'updated_at' => now(),
        ];
    }

    public function sousTache(int $parentId): static
    {
        return $this->state(fn (array $attributes) => [
            'tache_parent_id' => $parentId,
        ]);
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
}

