<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

/**
 * Export Excel pour rapport de synthèse institutionnel
 */
class SyntheseReportExport implements WithMultipleSheets
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        $sheets = [];
        
        // Feuille Synthèse
        $sheets[] = new SyntheseSheet($this->data);
        
        // Feuille Objectifs
        if (isset($this->data['objectifs']) && ($this->data['objectifs'] instanceof Collection ? $this->data['objectifs']->count() > 0 : count($this->data['objectifs']) > 0)) {
            $sheets[] = new ObjectifsSheet($this->data['objectifs']);
        }
        
        // Feuille Actions
        if (isset($this->data['actions']) && ($this->data['actions'] instanceof Collection ? $this->data['actions']->count() > 0 : count($this->data['actions']) > 0)) {
            $sheets[] = new ActionsSheet($this->data['actions']);
        }
        
        // Feuille KPI
        if (isset($this->data['kpis']) && ($this->data['kpis'] instanceof Collection ? $this->data['kpis']->count() > 0 : count($this->data['kpis']) > 0)) {
            $sheets[] = new KpisSheet($this->data['kpis']);
        }
        
        // Feuille Alertes
        if (isset($this->data['alertes']) && ($this->data['alertes'] instanceof Collection ? $this->data['alertes']->count() > 0 : count($this->data['alertes']) > 0)) {
            $sheets[] = new AlertesSheet($this->data['alertes']);
        }
        
        return $sheets;
    }
}

// Feuille Synthèse
class SyntheseSheet implements FromArray, WithTitle, WithStyles
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function array(): array
    {
        $rapport = $this->data['rapport'] ?? null;
        $stats = $this->data['stats'] ?? [];
        
        return [
            ['RAPPORT DE SYNTHÈSE INSTITUTIONNEL'],
            [''],
            ['Période', $this->data['period_start']->format('d/m/Y') . ' - ' . $this->data['period_end']->format('d/m/Y')],
            ['Généré le', $this->data['generated_at']->format('d/m/Y H:i')],
            ['Généré par', $this->data['generated_by']->name ?? ''],
            [''],
            ['STATISTIQUES GLOBALES'],
            [''],
            ['Objectifs', 'Total', $stats['objectifs_total'] ?? 0],
            ['', 'En cours', $stats['objectifs_en_cours'] ?? 0],
            [''],
            ['Actions prioritaires', 'Total', $stats['actions_total'] ?? 0],
            ['', 'En cours', $stats['actions_en_cours'] ?? 0],
            [''],
            ['Tâches', 'Total', $stats['taches_total'] ?? 0],
            ['', 'Terminées', $stats['taches_terminees'] ?? 0],
            ['', 'En retard', $stats['taches_en_retard'] ?? 0],
            [''],
            ['KPI', 'Total', $stats['kpis_total'] ?? 0],
            ['', 'Sous seuil', $stats['kpis_sous_seuil'] ?? 0],
            [''],
            ['Alertes', 'Total', $stats['alertes_total'] ?? 0],
            ['', 'Ouvertes', $stats['alertes_ouvertes'] ?? 0],
        ];
    }

    public function title(): string
    {
        return 'Synthèse';
    }

    public function styles(Worksheet $sheet)
    {
        // Style en-tête
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 16],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);
        
        $sheet->getStyle('A8')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
        ]);
        
        return [];
    }
}

// Feuille Objectifs
class ObjectifsSheet implements FromArray, WithTitle
{
    protected $objectifs;

    public function __construct($objectifs)
    {
        $this->objectifs = $objectifs;
    }

    public function array(): array
    {
        $rows = [['Code', 'Libellé', 'Statut', 'PAPA', 'Date début', 'Date fin', 'Actions']];
        
        foreach ($this->objectifs as $objectif) {
            $rows[] = [
                $objectif->code ?? '',
                $objectif->libelle ?? '',
                $objectif->statut ?? '',
                $objectif->papaVersion->papa->libelle ?? '',
                $objectif->date_debut ? $objectif->date_debut->format('d/m/Y') : '',
                $objectif->date_fin ? $objectif->date_fin->format('d/m/Y') : '',
                $objectif->actionsPrioritaires->count() ?? 0,
            ];
        }
        
        return $rows;
    }

    public function title(): string
    {
        return 'Objectifs';
    }
}

// Feuille Actions
class ActionsSheet implements FromArray, WithTitle
{
    protected $actions;

    public function __construct($actions)
    {
        $this->actions = $actions;
    }

    public function array(): array
    {
        $rows = [['Code', 'Libellé', 'Statut', 'Priorité', 'Avancement %', 'Objectif', 'Direction']];
        
        foreach ($this->actions as $action) {
            $rows[] = [
                $action->code ?? '',
                $action->libelle ?? '',
                $action->statut ?? '',
                $action->priorite ?? '',
                $action->pourcentage_avancement ?? 0,
                $action->objectif->code ?? '',
                $action->directionTechnique->libelle ?? $action->directionAppui->libelle ?? '',
            ];
        }
        
        return $rows;
    }

    public function title(): string
    {
        return 'Actions';
    }
}

// Feuille KPI
class KpisSheet implements FromArray, WithTitle
{
    protected $kpis;

    public function __construct($kpis)
    {
        $this->kpis = $kpis;
    }

    public function array(): array
    {
        $rows = [['Code', 'Libellé', 'Valeur cible', 'Valeur réalisée', 'Réalisation %', 'Statut', 'Action']];
        
        foreach ($this->kpis as $kpi) {
            $rows[] = [
                $kpi->code ?? '',
                $kpi->libelle ?? '',
                $kpi->valeur_cible ?? 0,
                $kpi->valeur_realisee ?? 0,
                $kpi->pourcentage_realisation ?? 0,
                $kpi->statut ?? '',
                $kpi->actionPrioritaire->code ?? '',
            ];
        }
        
        return $rows;
    }

    public function title(): string
    {
        return 'KPI';
    }
}

// Feuille Alertes
class AlertesSheet implements FromArray, WithTitle
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
        return 'Alertes';
    }
}

