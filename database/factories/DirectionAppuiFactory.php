<?php

namespace Database\Factories;

use App\Models\DirectionAppui;
use Illuminate\Database\Eloquent\Factories\Factory;

class DirectionAppuiFactory extends Factory
{
    protected $model = DirectionAppui::class;

    private static $noms = [
        'Direction des Ressources Humaines',
        'Direction des Finances & Comptabilité',
        'Direction des Technologies de l\'Information',
        'Direction de la Communication',
        'Direction des Affaires Juridiques',
        'Direction de la Planification & Suivi',
        'Direction de la Coopération',
        'Direction de l\'Administration Générale',
    ];

    public function definition(): array
    {
        $nom = $this->faker->unique()->randomElement(self::$noms);
        
        return [
            'code' => 'DA-' . strtoupper(substr($nom, 0, 2)) . '-' . $this->faker->unique()->numberBetween(10, 99),
            'libelle' => $nom,
            'description' => $this->faker->optional()->sentence(),
            'actif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}



