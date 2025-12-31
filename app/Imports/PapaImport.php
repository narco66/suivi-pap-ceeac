<?php

namespace App\Imports;

use App\Models\Papa;
use Maatwebsite\Excel\Concerns\ToModel;

class PapaImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Papa([
            //
        ]);
    }
}
