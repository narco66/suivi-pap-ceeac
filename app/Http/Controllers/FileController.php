<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class FileController extends Controller
{
    /**
     * Servir un fichier de manière sécurisée
     */
    public function serve(Request $request, $path)
    {
        // Décoder le chemin
        $path = urldecode($path);
        
        // Bloquer les chemins absolus Windows ou Unix
        if (preg_match('/^[A-Z]:\\\\|^\/[^\/]|^\.\./', $path)) {
            abort(403, 'Accès interdit : chemin invalide');
        }
        
        // Si le chemin commence par storage/, on le retire
        $path = str_replace('storage/', '', $path);
        $path = str_replace('public/', '', $path);
        
        // Vérifier que le fichier existe dans le storage public
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'Fichier non trouvé');
        }
        
        // Obtenir le chemin complet du fichier
        $filePath = Storage::disk('public')->path($path);
        
        // Vérifier que le fichier existe physiquement
        if (!File::exists($filePath)) {
            abort(404, 'Fichier non trouvé');
        }
        
        // Obtenir le type MIME
        $mimeType = File::mimeType($filePath);
        
        // Retourner le fichier avec les headers appropriés
        return response()->file($filePath, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }
}



