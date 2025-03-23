<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class FirstSheetImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use Importable;
    public function model(array $row)
    {
        if($row[8]=='Name :'){
            return new User([
            'name'     => $row[10],
            'email'    => $row[10]."@gmail.com", 
            'password' => Hash::make($row[10]),
        ]);
        }
        
    }
}
