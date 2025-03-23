<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithUpsertColumns;
use Maatwebsite\Excel\Concerns\WithUpserts;

class UsersImport implements  WithUpserts, WithUpsertColumns, WithMultipleSheets
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    use Importable;
    public function model(array $row)
    {
        // if (!$row[0]) {
        //     return null;
        // }
        return new User([
            'name'     => $row[0],
            'email'    => $row[1],
            'password' => Hash::make($row[2]),
        ]);
    }

    public function uniqueBy()
    {
        return 'email';
    }

    public function upsertColumns()
    {
        return ['name'];
    }

    public function sheets(): array
    {
        return [
            'log' => new FirstSheetImport(),

        ];
    }
}
