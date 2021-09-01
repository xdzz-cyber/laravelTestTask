<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::group(["middleware"=>["auth"]], function(){
    Route::get("/admin/getEmployees", [EmployeeController::class,"getEmployees"])->name('getEmployees');

    Route::get("/admin/employees", [EmployeeController::class, "index"])->name("employees");

    Route::get("/admin/employees/add",[EmployeeController::class,"addEmployeePage"])->name("employees.add");

    Route::get("/admin/employees/edit/{employee_id}",[EmployeeController::class,"editEmployeePage"])->name("employees.edit");

    Route::get("/admin/employees/remove/{employee_id}",[EmployeeController::class,"removeEmployee"])->name("employees.remove");

    Route::get("/admin/positions", [PositionController::class,"index"])->name("positions");

    Route::get("/admin/getPositions", [PositionController::class,"getPositions"])->name("getPositions");

    Route::get("/admin/positions/edit/{position_id}", [PositionController::class,"editPositionPage"])->name("positions.edit");

    Route::get("/admin/positions/remove/{position_id}", [PositionController::class,"removePosition"])->name("positions.remove");

    Route::view("/admin/positions/add", "addPositionPage")->name("positions.add");
});




