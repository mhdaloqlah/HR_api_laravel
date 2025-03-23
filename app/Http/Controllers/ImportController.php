<?php

namespace App\Http\Controllers;

use App\Models\AttendanceImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class ImportController extends Controller
{
    public function import(Request $request)
    {
        $file = $request->file('file');
        $import = new AttendanceImport();
        Excel::import($import, $file);

        return redirect()->route('attendances.index')->with('success', 'Attendance data imported successfully.');
    }
}
