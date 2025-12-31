<?php

namespace App\Exports;

use App\Models\Papa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PapaExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Papa::with('versions')->get();
    }

    public function headings(): array
    {
        return [
            'Code',
            'Libellé',
            'Année',
            'Statut',
            'Date création',
            'Nombre de versions',
        ];
    }

    public function map($papa): array
    {
        return [
            $papa->code ?? '',
            $papa->libelle ?? '',
            $papa->annee ?? '',
            $papa->statut ?? '',
            $papa->created_at ? $papa->created_at->format('d/m/Y') : '',
            $papa->versions->count() ?? 0,
        ];
    }
}
