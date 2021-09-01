<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\EmployeeDataTable;
use DataTables;
use App\Models\Employee;
use App\Traits\HeadHierarchyLevel;

class EmployeeController extends Controller
{
    use HeadHierarchyLevel;



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
                $photo = "<img style='border-radius: 50%' width='75' height='75' src='$src' alt='single employee photo'/>";
                return $photo;
            })->rawColumns(['management','photo'])->make(true);
        }
    }

    public function addEmployeePage(){
        return view("addEmployeePage");
    }

    public function editEmployeePage($employee_id){
        return view("editEmployeePage",["employee_id"=>$employee_id]);
    }

    public function changeHeadOnDelete($deleteElementId){
        $willBeDeletedEmployee = Employee::find($deleteElementId);
        $newBossId = $willBeDeletedEmployee->boss_id > 0 ? $willBeDeletedEmployee->boss_id : 0;
        $employeesAboutToBeChanged = Employee::where("boss_id",$deleteElementId)->update(["boss_id"=>$newBossId]);
    }

    public function removeEmployee($employee_id){
        $employee = Employee::find($employee_id);
        $this->changeHeadOnDelete($employee_id);
        unlink("images/{$employee->photo}");
        $employee->delete();
        return redirect()->route("employees");
    }
}
