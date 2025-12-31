<?php

namespace Database\Factories;

use App\Models\Commissaire;
use App\Models\Commission;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommissaireFactory extends Factory
{
    protected $model = Commissaire::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->lastName(),
            'prenom' => $this->faker->firstName(),
            'titre' => $this->faker->randomElement(['M.', 'Mme', 'Dr', 'Prof']),
            'commission_id' => Commission::factory(),
            'pays_origine' => $this->faker->randomElement(['Angola', 'Burundi', 'Cameroun', 'RCA', 'Tchad', 'RDC', 'Congo', 'Guinée Équatoriale', 'Gabon', 'São Tomé-et-Príncipe']),
            'date_nomination' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'actif' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}



