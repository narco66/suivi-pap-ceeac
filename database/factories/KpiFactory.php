<?php

namespace Database\Factories;

use App\Models\Kpi;
use App\Models\ActionPrioritaire;
use Illuminate\Database\Eloquent\Factories\Factory;

class KpiFactory extends Factory
{
    protected $model = Kpi::class;

    private static $libelles = [
        'Taux de réalisation des activités',
        'Nombre de bénéficiaires',
        'Montant des fonds mobilisés',
        'Nombre de formations réalisées',
        'Taux de satisfaction',
        'Nombre de documents produits',
        'Taux de participation',
        'Nombre de missions effectuées',
    ];

    private static $unites = [
        'pourcentage',
        'nombre',
        'millions USD',
        'personnes',
        'documents',
        'missions',
    ];

    public function definition(): array
    {
        $libelle = $this->faker->randomElement(self::$libelles);
        $unite = $this->faker->randomElement(self::$unites);
        $cible = $this->faker->numberBetween(50, 1000);
        $realise = $this->faker->numberBetween(0, $cible * 1.2); // Peut dépasser la cible
        
        return [
            'action_prioritaire_id' => ActionPrioritaire::factory(),
            'code' => 'KPI-' . $this->faker->unique()->numberBetween(1000000, 9999999),
            'libelle' => $libelle,
            'description' => $this->faker->optional()->sentence(),
            'unite' => $unite,
            'valeur_cible' => $cible,
            'valeur_realisee' => $realise,
            'valeur_ecart' => $realise - $cible,
            'pourcentage_realisation' => $cible > 0 ? ($realise / $cible) * 100 : 0,
            'date_mesure' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'statut' => $this->faker->randomElement(['en_cours', 'atteint', 'non_atteint']),
            'created_at' => now()->subMonths(rand(3, 6)),
            'updated_at' => now(),
        ];
    }
}




