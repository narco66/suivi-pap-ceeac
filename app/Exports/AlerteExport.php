<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AlerteExport implements FromCollection, WithHeadings, WithMapping
{
    protected $alertes;

    public function __construct($alertes)
    {
        $this->alertes = $alertes;
    }

    public function collection()
    {
        return $this->alertes;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Titre',
            'Type',
            'Criticité',
            'Statut',
            'Message',
            'Tâche',
            'Action prioritaire',
            'Créée par',
            'Assignée à',
            'Date création',
            'Date résolution',
        ];
    }

    public function map($alerte): array
    {
        return [
            $alerte->id,
            $alerte->titre ?? '',
            $alerte->type ?? '',
            $alerte->criticite ?? '',
            $alerte->statut ?? '',
            $alerte->message ?? '',
            $alerte->tache->code ?? '',
            $alerte->actionPrioritaire->code ?? '',
            $alerte->creePar->name ?? '',
            $alerte->assigneeA->name ?? '',
            $alerte->date_creation ? $alerte->date_creation->format('d/m/Y H:i') : '',
            $alerte->date_resolution ? $alerte->date_resolution->format('d/m/Y H:i') : '',
        ];
    }
}

