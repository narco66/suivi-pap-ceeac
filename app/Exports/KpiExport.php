<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KpiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $kpis;

    public function __construct($kpis)
    {
        $this->kpis = $kpis;
    }

    public function collection()
    {
        return $this->kpis;
    }

    public function headings(): array
    {
        return [
            'Code',
            'Libellé',
            'Valeur cible',
            'Valeur actuelle',
            'Unité',
            'Statut',
            'Objectif',
            'Créé par',
            'Date création',
        ];
    }

    public function map($kpi): array
    {
        return [
            $kpi->code ?? '',
            $kpi->libelle ?? '',
            $kpi->valeur_cible ?? '',
            $kpi->valeur_actuelle ?? '',
            $kpi->unite ?? '',
            $kpi->statut ?? '',
            $kpi->objectif->code ?? '',
            $kpi->creePar->name ?? '',
            $kpi->created_at ? $kpi->created_at->format('d/m/Y') : '',
        ];
    }
}

