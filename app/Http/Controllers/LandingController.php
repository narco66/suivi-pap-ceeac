<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Affiche la page d'accueil institutionnelle
     */
    public function index()
    {
        return view('welcome', [
            'config' => config('landing'),
        ]);
    }

    /**
     * Affiche la page des ressources
     * Note: Cette méthode est maintenant gérée par RessourceController::index()
     * Conservée pour compatibilité si nécessaire
     */
    public function ressources()
    {
        // Rediriger vers le nouveau contrôleur
        return redirect()->route('ressources');
    }

    /**
     * Affiche la page de documentation
     */
    public function docs()
    {
        return view('landing.docs', [
            'config' => config('landing'),
        ]);
    }

    /**
     * Affiche le statut de la plateforme
     */
    public function status()
    {
        return view('landing.status', [
            'config' => config('landing'),
        ]);
    }
}
