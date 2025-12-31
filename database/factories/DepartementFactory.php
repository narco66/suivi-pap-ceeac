<?php

namespace Database\Factories;

use App\Models\Departement;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartementFactory extends Factory
{
    protected $model = Departement::class;

    private static $noms = [
        'Commerce & Douanes',
        'Développement Social',
        'Paix & Sécurité',
        'Infrastructures',
        'Finances & Budget',
        'Affaires Politiques',
        'Environnement & Ressources Naturelles',
        'Agriculture & Développement Rural',
    ];

    public function definition(): array
    {
        $nom = $this->faker->unique()->randomElement(self::$noms);
        
        return [
            'code' => strtoupper(substr($nom, 0, 3)) . '-' . $this->faker->unique()->numberBetween(100, 999),
            'libelle' => $nom,
            'description' => $this->faker->optional()->sentence(),
            'actif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}



