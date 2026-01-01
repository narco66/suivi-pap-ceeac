<?php

namespace Database\Factories;

use App\Models\Avancement;
use App\Models\Tache;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AvancementFactory extends Factory
{
    protected $model = Avancement::class;

    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-6 months', 'now');
        $pourcentage = $this->faker->numberBetween(0, 100);
        
        return [
            'tache_id' => Tache::factory(),
            'date_avancement' => $date,
            'pourcentage_avancement' => $pourcentage,
            'commentaire' => $this->faker->optional(0.7)->paragraph(),
            'fichier_joint' => $this->faker->optional(0.3)->filePath(),
            'soumis_par_id' => User::factory(),
            'valide_par_id' => $this->faker->optional(0.6)->randomElement([User::factory()]),
            'date_validation' => $this->faker->optional(0.6)->dateTimeBetween($date, 'now'),
            'statut' => $this->faker->randomElement(['brouillon', 'soumis', 'valide', 'rejete']),
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}




