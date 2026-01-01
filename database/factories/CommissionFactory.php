<?php

namespace Database\Factories;

use App\Models\Commission;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommissionFactory extends Factory
{
    protected $model = Commission::class;

    private static $noms = [
        'Commission Économique',
        'Commission Politique',
        'Commission Sécurité',
        'Commission Sociale',
    ];

    public function definition(): array
    {
        $nom = $this->faker->unique()->randomElement(self::$noms);
        
        return [
            'code' => 'COM-' . strtoupper(substr($nom, 0, 3)) . '-' . $this->faker->unique()->numberBetween(1, 9),
            'libelle' => $nom,
            'description' => $this->faker->optional()->sentence(),
            'actif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}




