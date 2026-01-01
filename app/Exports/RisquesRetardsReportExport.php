<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class RisquesRetardsReportExport implements WithMultipleSheets
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new TachesRetardSheet($this->data['taches_en_retard'] ?? collect()),
            new AlertesCritiquesSheet($this->data['alertes_critiques'] ?? collect()),
        ];
    }
}

// Feuille Tâches en retard
class TachesRetardSheet implements FromArray, WithTitle
{
    protected $taches;

    public function __construct($taches)
    {
        $this->taches = $taches;
    }

    public function array(): array
    {
        $rows = [['Code', 'Libellé', 'Statut', 'Date fin prévue', 'Jours de retard', 'Action', 'Responsable']];
        
        foreach ($this->taches as $tache) {
            $joursRetard = $tache->date_fin_prevue ? now()->diffInDays($tache->date_fin_prevue, false) : 0;
            $rows[] = [
                $tache->code ?? '',
                $tache->libelle ?? '',
                $tache->statut ?? '',
                $tache->date_fin_prevue ? $tache->date_fin_prevue->format('d/m/Y') : '',
                $joursRetard,
                $tache->actionPrioritaire->code ?? '',
                $tache->responsable->name ?? '',
            ];
        }
        
        return $rows;
    }

    public function title(): string
    {
        return 'Tâches en retard';
    }
}

// Feuille Alertes critiques
class AlertesCritiquesSheet implements FromArray, WithTitle
{
    protected $alertes;

    public function __construct($alertes)
    {
        $this->alertes = $alertes;
    }

    public function array(): array
    {
        $rows = [['Code', 'Libellé', 'Criticité', 'Statut', 'Date création', 'Action', 'Tâche']];
        
        foreach ($this->alertes as $alerte) {
            $rows[] = [
                $alerte->code ?? '',
                $alerte->libelle ?? '',
                $alerte->criticite ?? '',
                $alerte->statut ?? '',
                $alerte->date_creation ? $alerte->date_creation->format('d/m/Y') : '',
                $alerte->actionPrioritaire->code ?? '',
                $alerte->tache->code ?? '',
            ];
        }
        
        return $rows;
    }

    public function title(): string
    {
        return 'Alertes critiques';
    }
}

