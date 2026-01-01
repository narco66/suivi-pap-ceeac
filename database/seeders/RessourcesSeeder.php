<?php

namespace Database\Seeders;

use App\Models\Ressource;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RessourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('📁 Création des ressources de démonstration...');

        $admin = User::where('email', 'admin@ceeac.int')->first();

        $ressources = [
            [
                'titre' => 'Modèle Excel PAPA (exemple)',
                'description' => 'Template Excel pour l\'import de Plans d\'Action Prioritaires. Ce modèle contient toutes les colonnes nécessaires pour importer vos données PAPA dans la plateforme.',
                'type' => 'excel',
                'categorie' => 'template',
                'version' => '1.0',
                'est_public' => true,
                'est_actif' => true,
            ],
            [
                'titre' => 'Guide d\'import Excel',
                'description' => 'Documentation complète pour l\'import de données Excel dans la plateforme. Ce guide explique étape par étape comment préparer et importer vos fichiers.',
                'type' => 'pdf',
                'categorie' => 'documentation',
                'version' => '1.2',
                'est_public' => true,
                'est_actif' => true,
            ],
            [
                'titre' => 'Guide utilisateur complet',
                'description' => 'Manuel d\'utilisation complet de la plateforme SUIVI-PAPA CEEAC. Ce guide couvre toutes les fonctionnalités : création de PAPA, gestion des objectifs, suivi des actions, etc.',
                'type' => 'pdf',
                'categorie' => 'documentation',
                'version' => '2.0',
                'est_public' => true,
                'est_actif' => true,
            ],
            [
                'titre' => 'Charte sécurité / accès',
                'description' => 'Politique de sécurité et règles d\'accès à la plateforme. Ce document définit les règles de sécurité, les niveaux d\'accès et les bonnes pratiques.',
                'type' => 'pdf',
                'categorie' => 'documentation',
                'version' => '1.0',
                'est_public' => true,
                'est_actif' => true,
            ],
            [
                'titre' => 'Modèle de rapport d\'avancement',
                'description' => 'Template Word pour la rédaction de rapports d\'avancement des Plans d\'Action Prioritaires.',
                'type' => 'docx',
                'categorie' => 'template',
                'version' => '1.0',
                'est_public' => true,
                'est_actif' => true,
            ],
            [
                'titre' => 'Guide d\'export des données',
                'description' => 'Documentation pour exporter les données de la plateforme au format Excel, PDF ou CSV.',
                'type' => 'pdf',
                'categorie' => 'export',
                'version' => '1.0',
                'est_public' => true,
                'est_actif' => true,
            ],
            [
                'titre' => 'Modèle de fiche action prioritaire',
                'description' => 'Template Excel pour créer des fiches d\'actions prioritaires standardisées.',
                'type' => 'excel',
                'categorie' => 'template',
                'version' => '1.1',
                'est_public' => true,
                'est_actif' => true,
            ],
            [
                'titre' => 'Procédure de validation',
                'description' => 'Document expliquant les procédures de validation des avancements et des actions dans la plateforme.',
                'type' => 'pdf',
                'categorie' => 'documentation',
                'version' => '1.0',
                'est_public' => false,
                'est_actif' => true,
            ],
        ];

        foreach ($ressources as $index => $data) {
            $ressource = Ressource::firstOrCreate(
                ['titre' => $data['titre']],
                array_merge($data, [
                    'cree_par_id' => $admin?->id,
                    'date_publication' => now()->subDays(rand(1, 90)),
                    'nombre_telechargements' => rand(0, 150),
                ])
            );

            // Générer un fichier factice si le fichier n'existe pas
            if (!$ressource->fichier) {
                $extension = match($data['type']) {
                    'excel' => 'xlsx',
                    'pdf' => 'pdf',
                    'docx' => 'docx',
                    'doc' => 'doc',
                    default => 'txt',
                };

                $filename = Str::slug($data['titre']) . '_' . time() . '.' . $extension;
                $path = 'ressources/' . $filename;

                // Créer un fichier texte factice (pour la démo)
                Storage::disk('public')->put($path, "Fichier de démonstration : {$data['titre']}\n\nCe fichier est généré automatiquement pour les besoins de démonstration.");

                $ressource->update([
                    'fichier' => $path,
                    'nom_fichier_original' => $data['titre'] . '.' . $extension,
                    'taille_fichier' => Storage::disk('public')->size($path),
                    'mime_type' => match($data['type']) {
                        'excel' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'pdf' => 'application/pdf',
                        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        default => 'text/plain',
                    },
                ]);
            }
        }

        $count = Ressource::count();
        $this->command->info("    ✓ {$count} ressources créées/vérifiées");
        $this->command->info('✅ Ressources créées avec succès!');
    }
}



