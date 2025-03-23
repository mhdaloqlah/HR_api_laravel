<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\JobHistoryController;
use App\Http\Controllers\WordController;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::get('jobHistory/getview',[JobHistoryController::class,'getview']);
Route::get('jobHistory/jobhistoryByEmployee/{id}',[JobHistoryController::class,'jobhistoryByEmployee']);
Route::post('jobHistory/delete/{id}',[JobHistoryController::class,'deleteJob']);

Route::apiResource('address', AddressController::class)->only(
    [
        'index', 'show','store','update','destroy'
    ]
);

Route::apiResource('department', DepartmentController::class)->only(
    [
        'index', 'show','store','update','destroy'
    ]
);
Route::apiResource('job', JobController::class)->only(
    [
        'index', 'show','store','update','destroy'
    ]
);
Route::apiResource('company', CompanyController::class)->only(
    [
        'index', 'show','store','update','destroy',
    ]
);
Route::apiResource('employee', EmployeeController::class)->only(
    [
        'index', 'show','store','update','destroy',
    ]
);

Route::apiResource('jobHistory', JobHistoryController::class)->only(
    [
        'index', 'show','store','update','destroy'
    ]
);


Route::get('employee/getwithstatus/{status}',[EmployeeController::class,'getwithstatus']);
Route::post('employee/updateStatus',[EmployeeController::class,'updateStatus']);


Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);
  

});


Route::get('users/export/', [AuthController::class, 'export']);
Route::get('users/import/', [AuthController::class, 'import']);
Route::post('attendances/import/', [AttendanceController::class, 'import']);
Route::post('attendances/importSite/', [AttendanceController::class, 'import2']);
Route::get('employees/export/', [EmployeeController::class, 'export']);
Route::get('companies/export/', [CompanyController::class, 'export']);

Route::get('employeeattendance',[AttendanceController::class,'employeeattendance']);
Route::get('allemployeeattendance',[AttendanceController::class,'allemployeeattendance']);
Route::get('allemployeeattendance2',[AttendanceController::class,'allemployeeattendance2']);
Route::post('updateAttendance',[AttendanceController::class,'updateAttendance']);
Route::post('generateWord', [WordController::class, 'generateWord']);
Route::post('generateCvWord', [WordController::class, 'generateCvWord']);
Route::post('generateMultiCvWord', [WordController::class, 'generateMultiCvWord']);
Route::post('generateAlliCvWord', [WordController::class, 'generateAlliCvWord']);
Route::get('makedoc', [WordController::class, 'makedoc']);
