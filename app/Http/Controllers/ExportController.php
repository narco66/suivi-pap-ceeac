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

        try {
            if ($type === 'excel') {
                return $this->exportExcel($module, $filename);
            } else {
                return $this->exportPdf($module, $filename);
            }
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'export: ' . $e->getMessage());
            return redirect()
                ->route('export.index')
                ->with('error', 'Une erreur est survenue lors de l\'export: ' . $e->getMessage());
        }
    }

    private function exportExcel($module, $filename)
    {
        switch ($module) {
            case 'papa':
                return Excel::download(new PapaExport(), $filename);
            
            case 'objectifs':
                $objectifs = Objectif::with(['papaVersion.papa', 'actionPrioritaires'])->get();
                return Excel::download(new \App\Exports\ObjectifExport($objectifs), $filename);
            
            case 'kpi':
                $kpis = Kpi::with(['objectif', 'creePar'])->get();
                return Excel::download(new \App\Exports\KpiExport($kpis), $filename);
            
            case 'alertes':
                $alertes = Alerte::with(['tache', 'actionPrioritaire', 'creePar', 'assigneeA'])->get();
                return Excel::download(new \App\Exports\AlerteExport($alertes), $filename);
            
            default:
                throw new \Exception('Module non supporté');
        }
    }

    private function exportPdf($module, $filename)
    {
        // Pour l'instant, on redirige vers Excel car les vues PDF ne sont pas encore créées
        // TODO: Créer les vues PDF pour chaque module
        return redirect()
            ->route('export.index')
            ->with('info', 'L\'export PDF n\'est pas encore disponible. Veuillez utiliser l\'export Excel.');
    }

    private function getDataForPdf($module)
    {
        switch ($module) {
            case 'papa':
                return ['papas' => \App\Models\Papa::with('versions')->get()];
            
            case 'objectifs':
                return ['objectifs' => Objectif::with(['papaVersion.papa', 'actionPrioritaires'])->get()];
            
            case 'kpi':
                return ['kpis' => Kpi::with(['objectif', 'creePar'])->get()];
            
            case 'alertes':
                return ['alertes' => Alerte::with(['tache', 'actionPrioritaire', 'creePar', 'assigneeA'])->get()];
            
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
