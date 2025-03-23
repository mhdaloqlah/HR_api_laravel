<?php

namespace App\Imports;

use App\Models\Attendance;
use App\Models\Employee;
use DateTime;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Spatie\QueryBuilder\QueryBuilder;

class AttendancesiteImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {



        $year = substr($collection[1][2], 0, 4);


        $nextyear = $year + 1;
        $firstyear = $year;
        $month = substr($collection[1][2], 5, 2);
        $firstmonth = substr($collection[1][2], 5, 2);
        $nextmonth = substr($collection[1][2], 18, 2);

        // error_log($year);
        // error_log($nextyear);
        // error_log($firstyear);
        // error_log($month);
        // error_log($nextmonth);
        // error_log($firstmonth);


        for ($i = 2; $i < count($collection); $i += 4) {

            $employee_name = $collection[$i][11];
            $employee = QueryBuilder::for(Employee::class)->where('finger', $employee_name)->first();

            if ($employee) {


                for ($n = 0; $n < count($collection[$i + 1]); $n++) {


                    if (!$collection[$i + 1][$n] == '') {

                        $timeIn = !substr($collection[$i + 3][$n], 0, 5) ? null : substr($collection[$i + 3][$n], 0, 5);
                        $timeOut = strlen($collection[$i + 3][$n]) > 5 ? substr($collection[$i + 3][$n], 6, 6) : null;


                        if ($collection[$i + 1][$n] == 1) {
                            $month = $nextmonth;
                            if ($month == '01') {
                                $year = $nextyear;
                            }
                        }

                        $checkAttendance = QueryBuilder::for(Attendance::class)
                            ->where('employee_id', $employee->id)
                            ->where('Date', $year . '-' . $month . '-' . $collection[$i + 1][$n])->first();


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
                                'Date' => $year . '-' . $month . '-' . $collection[$i + 1][$n],
                                'Status' => $status,
                                'CheckInTime' => $timeIn,
                                'CheckOutTime' =>  $timeOut
                            ]);
                        }


                        if ($n == count($collection[$i + 1]) - 1) {
                            $month = $firstmonth;
                            $year = $firstyear;
                        }
                    }
                }
            }
        }

        // for ($i = 4; $i < count($collection); $i++) {

        //     $employee_name = $collection[$i][1];

        //     $employee = QueryBuilder::for(Employee::class)->where('finger', $employee_name)->first();

        //     if ($employee) {

        //         $date = $collection[$i][3];
        //         $timein = null;
        //         $timeout = null;
        //         if($collection[$i][4]!="Missed"){
        //             $timein =$collection[$i][4];
        //         }
        //         if($collection[$i][5]!="Missed"){
        //             $timeout =$collection[$i][5];
        //         }
        //         error_log($timein);
        //         $checkAttendance = QueryBuilder::for(Attendance::class)
        //             ->where('employee_id', $employee->id)
        //             ->where('Date', $date)->first();

        //         if (!$checkAttendance) {
        //             Attendance::create([
        //                 'employee_id' => $employee->id,
        //                 'Date' => $date,
        //                 'Status' => 'Present',
        //                 'CheckInTime' => $timein ,
        //                 'CheckOutTime' =>  $timeout
        //             ]);
        //         }
        //     }
        // }
    }
}
