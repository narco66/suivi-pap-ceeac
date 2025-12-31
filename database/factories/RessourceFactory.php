<?php

namespace Database\Factories;

use App\Models\Ressource;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ressource>
 */
class RessourceFactory extends Factory
{
    protected $model = Ressource::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['excel', 'pdf', 'zip', 'doc', 'docx', 'image', 'autre'];
        $categories = ['general', 'import', 'export', 'documentation', 'template', 'autre'];
        $type = $this->faker->randomElement($types);

        return [
            'titre' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(2),
            'type' => $type,
            'categorie' => $this->faker->randomElement($categories),
            'version' => $this->faker->randomElement(['1.0', '1.1', '1.2', '2.0', '2.1']),
            'fichier' => null, // Sera défini dans les états
            'nom_fichier_original' => $this->faker->word() . '.' . match($type) {
                'excel' => 'xlsx',
                'pdf' => 'pdf',
                'zip' => 'zip',
                'doc' => 'doc',
                'docx' => 'docx',
                'image' => 'png',
                default => 'txt',
            },
            'taille_fichier' => $this->faker->numberBetween(1000, 10485760), // 1KB à 10MB
            'mime_type' => match($type) {
                'excel' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'pdf' => 'application/pdf',
                'zip' => 'application/zip',
                'doc' => 'application/msword',
                'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'image' => 'image/png',
                default => 'text/plain',
            },
            'est_public' => $this->faker->boolean(80), // 80% de chance d'être public
            'est_actif' => $this->faker->boolean(90), // 90% de chance d'être actif
            'nombre_telechargements' => $this->faker->numberBetween(0, 500),
            'cree_par_id' => User::factory(),
            'date_publication' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the resource is public.
     */
    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'est_public' => true,
        ]);
    }

    /**
     * Indicate that the resource is private.
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'est_public' => false,
        ]);
    }

    /**
     * Indicate that the resource is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'est_actif' => true,
        ]);
    }

    /**
     * Indicate that the resource is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'est_actif' => false,
        ]);
    }
}


