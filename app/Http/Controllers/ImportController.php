<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImportController extends Controller
{
    public function index()
    {
        return view('imports.index');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'module' => ['required', 'in:papa,objectifs,kpi,taches,alertes'],
            'file' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240'], // Max 10MB
            'skip_errors' => ['nullable', 'boolean'],
            'update_existing' => ['nullable', 'boolean'],
        ]);

        try {
            $file = $request->file('file');
            $module = $validated['module'];
            $skipErrors = $request->has('skip_errors');
            $updateExisting = $request->has('update_existing');

            // TODO: Implement import logic based on module
            // For now, just return success message
            $message = "Import du fichier '{$file->getClientOriginalName()}' pour le module '{$module}' en cours de traitement...";
            
            if ($skipErrors) {
                $message .= " Les erreurs seront ignorées.";
            }
            
            if ($updateExisting) {
                $message .= " Les enregistrements existants seront mis à jour.";
            }

            return redirect()->route('import.index')->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'import: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'import. Veuillez vérifier votre fichier et réessayer.');
        }
    }
}
