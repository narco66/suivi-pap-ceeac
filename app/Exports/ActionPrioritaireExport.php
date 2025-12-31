<?php

namespace App\Exports;

use App\Models\ActionPrioritaire;
use Maatwebsite\Excel\Concerns\FromCollection;

class ActionPrioritaireExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ActionPrioritaire::all();
    }
}
