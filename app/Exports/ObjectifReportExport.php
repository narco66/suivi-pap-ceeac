<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ObjectifReportExport implements WithMultipleSheets
{
    protected array $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        return [
            new ObjectifsSheet($this->data['objectifs'] ?? collect()),
            new ActionsSheet($this->data['actions'] ?? collect()),
        ];
    }
}

