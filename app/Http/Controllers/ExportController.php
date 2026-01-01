<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\PapaExport;
use App\Exports\ActionPrioritaireExport;
use App\Models\Objectif;
use App\Models\Kpi;
use App\Models\Alerte;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ExportController extends Controller
{
    public function index()
    {
        return view('exports.index');
    }
    
    public function export(Request $request)
    {
        $request->validate([
            'type' => 'required|in:excel,pdf',
            'module' => 'required|in:papa,objectifs,kpi,alertes',
        ]);

        $type = $request->input('type');
        $module = $request->input('module');
        $filename = $this->generateFilename($module, $type);
        $user = $request->user();

        try {
            if ($type === 'excel') {
                return $this->exportExcel($module, $filename, $user);
            } else {
                return $this->exportPdf($module, $filename, $user);
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'export: ' . $e->getMessage());
            return redirect()
                ->route('export.index')
                ->with('error', 'Une erreur est survenue lors de l\'export: ' . $e->getMessage());
        }
    }

    private function exportExcel($module, $filename, $user = null)
    {
        // 🔒 SÉCURITÉ : Déterminer le scope selon le rôle
        $isCommissaire = $user && $user->isCommissaire() && !$user->hasAnyRole(['admin', 'admin_dsi']);
        $departmentId = $isCommissaire ? $user->getDepartmentId() : null;
        
        switch ($module) {
            case 'papa':
                return Excel::download(new PapaExport(), $filename);
            
            case 'objectifs':
                $objectifsQuery = Objectif::with(['papaVersion.papa', 'actionPrioritaires']);
                // 🔒 SÉCURITÉ : Scope département pour les commissaires
                if ($isCommissaire && $departmentId) {
                    $objectifsQuery->whereHas('actionsPrioritaires', function($q) use ($departmentId) {
                        $q->forDepartment($departmentId);
                    });
                }
                $objectifs = $objectifsQuery->get();
                return Excel::download(new \App\Exports\ObjectifExport($objectifs), $filename);
            
            case 'kpi':
                $kpisQuery = Kpi::with(['actionPrioritaire', 'actionPrioritaire.objectif']);
                // 🔒 SÉCURITÉ : Scope département pour les commissaires
                if ($isCommissaire && $departmentId) {
                    $kpisQuery->forDepartment($departmentId);
                }
                $kpis = $kpisQuery->get();
                return Excel::download(new \App\Exports\KpiExport($kpis), $filename);
            
            case 'alertes':
                $alertesQuery = Alerte::with(['tache', 'actionPrioritaire', 'creePar', 'assigneeA']);
                // 🔒 SÉCURITÉ : Scope département pour les commissaires
                if ($isCommissaire && $departmentId) {
                    $alertesQuery->forDepartment($departmentId);
                }
                $alertes = $alertesQuery->get();
                return Excel::download(new \App\Exports\AlerteExport($alertes), $filename);
            
            default:
                throw new \Exception('Module non supporté');
        }
    }

    private function exportPdf($module, $filename, $user = null)
    {
        // Pour l'instant, on redirige vers Excel car les vues PDF ne sont pas encore créées
        // TODO: Créer les vues PDF pour chaque module
        return redirect()
            ->route('export.index')
            ->with('info', 'L\'export PDF n\'est pas encore disponible. Veuillez utiliser l\'export Excel.');
    }

    private function getDataForPdf($module, $user = null)
    {
        // 🔒 SÉCURITÉ : Déterminer le scope selon le rôle
        $isCommissaire = $user && $user->isCommissaire() && !$user->hasAnyRole(['admin', 'admin_dsi']);
        $departmentId = $isCommissaire ? $user->getDepartmentId() : null;
        
        switch ($module) {
            case 'papa':
                return ['papas' => \App\Models\Papa::with('versions')->get()];
            
            case 'objectifs':
                $objectifsQuery = Objectif::with(['papaVersion.papa', 'actionPrioritaires']);
                // 🔒 SÉCURITÉ : Scope département pour les commissaires
                if ($isCommissaire && $departmentId) {
                    $objectifsQuery->whereHas('actionsPrioritaires', function($q) use ($departmentId) {
                        $q->forDepartment($departmentId);
                    });
                }
                return ['objectifs' => $objectifsQuery->get()];
            
            case 'kpi':
                $kpisQuery = Kpi::with(['actionPrioritaire']);
                // 🔒 SÉCURITÉ : Scope département pour les commissaires
                if ($isCommissaire && $departmentId) {
                    $kpisQuery->forDepartment($departmentId);
                }
                return ['kpis' => $kpisQuery->get()];
            
            case 'alertes':
                $alertesQuery = Alerte::with(['tache', 'actionPrioritaire', 'creePar', 'assigneeA']);
                // 🔒 SÉCURITÉ : Scope département pour les commissaires
                if ($isCommissaire && $departmentId) {
                    $alertesQuery->forDepartment($departmentId);
                }
                return ['alertes' => $alertesQuery->get()];
            
            default:
                throw new \Exception('Module non supporté');
        }
    }

    private function generateFilename($module, $type)
    {
        $extension = $type === 'excel' ? 'xlsx' : 'pdf';
        $date = now()->format('Y-m-d_His');
        $moduleName = ucfirst($module);
        
        return "export_{$moduleName}_{$date}.{$extension}";
    }
}
