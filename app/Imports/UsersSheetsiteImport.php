<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UsersSheetsiteImport implements WithMultipleSheets
{
    /**
    * @param Collection $collection
    */
   

    public function sheets(): array
    {
        return [
           'Attend. Logs'=> new AttendancesiteImport()
        ];
    }
}
