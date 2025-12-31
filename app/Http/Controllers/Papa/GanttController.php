<?php

namespace App\Http\Controllers\Papa;

use App\Http\Controllers\Controller;
use App\Models\Papa;
use Illuminate\Http\Request;

class GanttController extends Controller
{
    /**
     * Afficher la vue Gantt
     */
    public function index(Request $request)
    {
        // Autorisation via Gate
        if (!\Illuminate\Support\Facades\Gate::allows('viewGantt')) {
            abort(403, 'Vous n\'avez pas la permission d\'accéder au diagramme de Gantt.');
        }

        $papaId = $request->get('papa_id');
        $versionId = $request->get('version_id');
        
        // Récupérer tous les PAPA pour le filtre avec leurs versions
        $papas = Papa::with('versions')->orderBy('annee', 'desc')->get();
        
        // Préparer les données pour JavaScript (évite les problèmes de syntaxe Blade)
        $papasData = [];
        foreach ($papas as $papa) {
            $versions = [];
            foreach ($papa->versions as $version) {
                $versions[] = [
                    'id' => $version->id,
                    'libelle' => $version->libelle,
                ];
            }
            $papasData[$papa->id] = [
                'id' => $papa->id,
                'versions' => $versions,
            ];
        }
        
        // Vérifier si l'utilisateur peut éditer
        $editable = $request->user()->can('editDates');
        
        return view('gantt.index', [
            'papas' => $papas,
            'papasData' => $papasData,
            'selectedPapaId' => $papaId,
            'selectedVersionId' => $versionId,
            'editable' => $editable,
        ]);
    }
}
