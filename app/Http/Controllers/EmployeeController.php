<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\EmployeeDataTable;
use DataTables;
use App\Models\Employee;
class EmployeeController extends Controller
{
    public function index(EmployeeDataTable $dataTable){
        return $dataTable->render("employees");
    }

    public function getEmployees(){
        if (request()->ajax()){
            $model = Employee::query();
            return DataTables::of($model)->addIndexColumn()->addColumn("management", function ($row){
                $actionBtn = "<a href='/admin/employees/edit/{$row['id']}' class='mx-3'><i class='fas fa-edit'></i></a> <a id='removeSweetAlertButtonEmployee' href='/admin/employees/remove/{$row['id']}' class='mx-1'><i class='fas fa-trash-alt'></i></a>";
                return $actionBtn;
            })->addIndexColumn()->addColumn("photo",function ($row){
                $src = url("/images") . "/" . $row['photo'];
                $photo = "<img style='border-radius: 50%' width='50' height='50' src='$src' alt='single employee photo'/>";
                return $photo;
            })->rawColumns(['management','photo'])->make(true);
        }
    }

    public function addEmployeePage(){
        return view("addEmployeePage");
    }
}
