<?php

namespace App\Http\Controllers;

use App\Models\Ressource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RessourceController extends Controller
{
    /**
     * Affiche la liste des ressources (page publique)
     */
    public function index(Request $request)
    {
        // Vérifier si la table existe AVANT toute requête
        if (!Schema::hasTable('ressources')) {
            // Créer un objet paginé vide pour éviter l'erreur hasPages()
            $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                12,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            return view('landing.ressources', [
                'ressources' => $emptyPaginator,
                'stats' => [
                    'total' => 0,
                    'par_type' => collect([]),
                    'par_categorie' => collect([]),
                ],
            ])->with('warning', 'La table des ressources n\'existe pas encore. Veuillez exécuter les migrations.');
        }

        // Maintenant on peut exécuter les requêtes en toute sécurité
        try {
            // Double vérification avant d'exécuter la requête
            if (!Ressource::tableExists()) {
                // Retourner directement sans essayer d'utiliser le modèle
                $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                    collect([]),
                    0,
                    12,
                    1,
                    ['path' => $request->url(), 'query' => $request->query()]
                );
                
                return view('landing.ressources', [
                    'ressources' => $emptyPaginator,
                    'stats' => [
                        'total' => 0,
                        'par_type' => collect([]),
                        'par_categorie' => collect([]),
                    ],
                ])->with('warning', 'La table des ressources n\'existe pas encore. Veuillez exécuter : php artisan migrate');
            }
            
            $query = Ressource::active()->public()->with('creePar');

            // Filtres
            if ($request->filled('categorie')) {
                $query->where('categorie', $request->categorie);
            }

            if ($request->filled('type')) {
                $query->where('type', $request->type);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('titre', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $ressources = $query->orderBy('date_publication', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(12);

            // Statistiques
            $stats = [
                'total' => Ressource::active()->public()->count(),
                'par_type' => Ressource::active()->public()
                    ->selectRaw('type, count(*) as total')
                    ->groupBy('type')
                    ->pluck('total', 'type'),
                'par_categorie' => Ressource::active()->public()
                    ->selectRaw('categorie, count(*) as total')
                    ->groupBy('categorie')
                    ->pluck('total', 'categorie'),
            ];

            return view('landing.ressources', compact('ressources', 'stats'));
            
        } catch (\Illuminate\Database\QueryException $e) {
            // Si une erreur survient (table n'existe toujours pas), retourner une vue vide
            $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                12,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            return view('landing.ressources', [
                'ressources' => $emptyPaginator,
                'stats' => [
                    'total' => 0,
                    'par_type' => collect([]),
                    'par_categorie' => collect([]),
                ],
            ])->with('warning', 'La table des ressources n\'existe pas encore. Veuillez exécuter : php artisan migrate');
        } catch (\Exception $e) {
            // Autre erreur
            $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                12,
                1,
                ['path' => $request->url(), 'query' => $request->query()]
            );
            
            return view('landing.ressources', [
                'ressources' => $emptyPaginator,
                'stats' => [
                    'total' => 0,
                    'par_type' => collect([]),
                    'par_categorie' => collect([]),
                ],
            ])->with('error', 'Erreur lors de l\'accès aux ressources : ' . $e->getMessage());
        }
    }

    /**
     * Télécharge une ressource
     */
    public function download(Request $request, $id = null)
    {
        // Vérifier si la table existe
        if (!Schema::hasTable('ressources')) {
            abort(404, 'La table des ressources n\'existe pas encore.');
        }

        try {
            // Récupérer l'ID depuis la route (model binding) ou le paramètre
            $ressourceId = $id ?? $request->route('ressource');
            
            // Si c'est déjà un modèle (model binding réussi), utiliser directement
            if ($ressourceId instanceof Ressource) {
                $ressource = $ressourceId;
            } else {
                // Sinon, chercher par ID
                $ressource = Ressource::findOrFail($ressourceId);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Ressource non trouvée');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération de la ressource: ' . $e->getMessage());
            abort(404, 'Ressource non trouvée');
        }

        // Vérifier que la ressource est accessible
        if (!$ressource->est_actif) {
            abort(404, 'Ressource non disponible');
        }

        if (!$ressource->est_public && !auth()->check()) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que le fichier existe
        if (!$ressource->fichier || !$ressource->fichierExists()) {
            abort(404, 'Fichier non trouvé');
        }

        // Incrémenter le compteur de téléchargements
        try {
            $ressource->incrementerTelechargements();
        } catch (\Exception $e) {
            // Continuer même si l'incrémentation échoue
            \Log::warning('Impossible d\'incrémenter le compteur de téléchargements: ' . $e->getMessage());
        }

        // Retourner le fichier
        try {
            $path = Storage::disk('public')->path($ressource->fichier);
            
            if (!file_exists($path)) {
                abort(404, 'Fichier non trouvé sur le serveur');
            }

            $nomFichier = $ressource->nom_fichier_original ?? 
                         $ressource->titre . '.' . pathinfo($ressource->fichier, PATHINFO_EXTENSION);

            return response()->download($path, $nomFichier);
        } catch (\Exception $e) {
            \Log::error('Erreur lors du téléchargement de la ressource: ' . $e->getMessage());
            abort(500, 'Erreur lors du téléchargement du fichier');
        }
    }

    /**
     * Affiche les détails d'une ressource
     */
    public function show(Ressource $ressource)
    {
        if (!$ressource->est_actif) {
            abort(404, 'Ressource non disponible');
        }

        if (!$ressource->est_public && !auth()->check()) {
            abort(403, 'Accès non autorisé');
        }

        $ressource->load('creePar');

        return view('landing.ressources.show', compact('ressource'));
    }
}

