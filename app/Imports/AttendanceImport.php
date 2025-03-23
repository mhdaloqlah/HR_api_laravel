<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\User;
use DateTime;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Spatie\QueryBuilder\QueryBuilder;

class AttendanceImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $employee_id = null;

        // error_log('mohammad');
        $year = substr($collection[2][2], 0, 4);
        $nextyear=$year+1;
        $firstyear=$year;
        $month = substr($collection[2][2], 5, 2);
        $firstmonth = substr($collection[2][2], 5, 2);
        $nextmonth = substr($collection[2][2], 13, 2);
        error_log($nextyear);

        for ($i = 4; $i < count($collection); $i += 3) {

            $employee_name = $collection[$i][10];
            $employee = QueryBuilder::for(Employee::class)->where('finger', $employee_name)->first();

            if ($employee) {




                for ($n = 0; $n < count($collection[$i - 1]); $n++) {
                    $timeIn = !substr($collection[$i + 1][$n], 0, 5) ? null : substr($collection[$i + 1][$n], 0, 5);
                    $timeOut = strlen($collection[$i + 1][$n]) >= 12 ? substr($collection[$i + 1][$n], 6, 6) : null;
                    //   error_log($timeOut);

                    if ($collection[$i - 1][$n] == 1) {
                        $month = $nextmonth;
                        if($month=='01'){
                            $year= $nextyear;
                        }
                    }

                    $checkAttendance = QueryBuilder::for(Attendance::class)
                        ->where('employee_id', $employee->id)
                        ->where('Date', $year . '-' . $month . '-' . $collection[$i - 1][$n])->first();

                        
                    if (!$checkAttendance) {
                        $date = new DateTime($year . '-' . $month . '-' . $collection[$i + 1][$n]);
                        $status = 'Present';
                        // Get the day of the week
                        $dayOfWeek = $date->format('l');

                       
                        if(!$timeIn && !$timeOut) {
                            $status = 'Absent';
                        } 

                        if($timeIn && !$timeOut) {
                            $status = 'Leave';
                        }
                        
                        if ($dayOfWeek == 'Sunday') {
                            $status = 'Weekend';
                        } 

                        Attendance::create([
                            'employee_id' => $employee->id,
                            'Date' => $year . '-' . $month . '-' . $collection[$i - 1][$n],
                            'Status' => $status,
                            'CheckInTime' => $timeIn,
                            'CheckOutTime' =>  $timeOut
                        ]);
                    }


                    if ($n == count($collection[$i - 1]) - 1) {
                        $month = $firstmonth;
                        $year = $firstyear;
                    }
                }
            }
        }
    }
}
