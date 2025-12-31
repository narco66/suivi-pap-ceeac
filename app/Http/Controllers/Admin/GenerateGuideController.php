<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ressource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class GenerateGuideController extends Controller
{
    use AuthorizesRequests;

    /**
     * Génère le guide d'import PDF
     */
    public function generateImportGuide()
    {
        $this->authorize('create', Ressource::class);

        try {
            $html = $this->getGuideHtml();
            
            // Générer le PDF avec DomPDF
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadHTML($html);
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOption('isHtml5ParserEnabled', true);
            $pdf->setOption('isRemoteEnabled', true);
            
            // Sauvegarder le PDF
            $filename = 'guide_import_v1.2_' . date('Ymd') . '.pdf';
            $destination = 'ressources/' . $filename;
            
            Storage::disk('public')->makeDirectory('ressources');
            $pdf->save(storage_path('app/public/' . $destination));
            
            // Mettre à jour ou créer la ressource
            $admin = User::where('email', 'admin@ceeac.int')->first();
            
            $ressource = Ressource::updateOrCreate(
                ['titre' => 'Guide d\'import Excel'],
                [
                    'description' => 'Documentation complète pour l\'import de données Excel dans la plateforme. Ce guide explique étape par étape comment préparer et importer vos fichiers.',
                    'type' => 'pdf',
                    'categorie' => 'documentation',
                    'version' => '1.2',
                    'fichier' => $destination,
                    'nom_fichier_original' => 'Guide_d_import_Excel_v1.2.pdf',
                    'taille_fichier' => Storage::disk('public')->size($destination),
                    'mime_type' => 'application/pdf',
                    'est_public' => true,
                    'est_actif' => true,
                    'cree_par_id' => $admin?->id,
                    'date_publication' => '2024-02-20',
                ]
            );

            return redirect()->route('admin.ressources.index')
                ->with('success', 'Guide d\'import généré avec succès !');
                
        } catch (\Exception $e) {
            return redirect()->route('admin.ressources.index')
                ->with('error', 'Erreur lors de la génération : ' . $e->getMessage());
        }
    }

    /**
     * Retourne le contenu HTML du guide
     */
    private function getGuideHtml(): string
    {
        return view('pdf.guide-import')->render();
    }
}


