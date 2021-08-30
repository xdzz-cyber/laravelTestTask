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

Route::get("/admin/employees", [EmployeeController::class, "index"])->name("employees");

Route::get("/admin/getEmployees", [EmployeeController::class,"getEmployees"])->name('getEmployees');

Route::get("/admin/employees/add",[EmployeeController::class,"addEmployeePage"])->name("employees.add");

Route::get("/admin/positions", [PositionController::class,"index"])->name("positions");

Route::get("/admin/getPositions", [PositionController::class,"getPositions"])->name("getPositions");

Route::get("/admin/positions/edit/{position_id}", [PositionController::class,"editPositionPage"])->name("positions.edit");

Route::get("/admin/positions/remove/{position_id}", [PositionController::class,"removePosition"])->name("positions.remove");

//Route::get("/admin/positions/add", \App\Http\Livewire\AddPositionComponent::class)->name("positions.add"); //"positions.add"
Route::view("/admin/positions/add", "addPositionPage")->name("positions.add");

//Route::livewire("/admin/positions/add", "last-component");

//Route::get("/admin/positions/add", [\App\Http\Controllers\PositionController::class,"addPage"]);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
