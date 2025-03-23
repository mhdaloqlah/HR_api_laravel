<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceImport extends Model
{
    use HasFactory;
    // use Importable;

    public function model(array $row)
    {
        return new Attendance([
            'employee_id' => $row[0],
            'Date' => $row[1],
            'Status' => $row[2],
            'CheckInTime' => $row[3],
            'CheckOutTime' => $row[4],
        ]);
    }
}
