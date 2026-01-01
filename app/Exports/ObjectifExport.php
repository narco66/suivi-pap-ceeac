<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ObjectifExport implements FromCollection, WithHeadings, WithMapping
{
    protected $objectifs;

    public function __construct($objectifs)
    {
        $this->objectifs = $objectifs;
    }

    public function collection()
    {
        return $this->objectifs;
    }

    public function headings(): array
    {
        return [
            'Code',
            'Libellé',
            'Statut',
            'PAPA',
            'Version',
            'Date début',
            'Date fin',
            'Nombre d\'actions',
        ];
    }

    public function map($objectif): array
    {
        return [
            $objectif->code ?? '',
            $objectif->libelle ?? '',
            $objectif->statut ?? '',
            $objectif->papaVersion->papa->libelle ?? '',
            $objectif->papaVersion->libelle ?? '',
            $objectif->date_debut ? $objectif->date_debut->format('d/m/Y') : '',
            $objectif->date_fin ? $objectif->date_fin->format('d/m/Y') : '',
            $objectif->actionPrioritaires->count() ?? 0,
        ];
    }
}


