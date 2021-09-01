<?php

namespace App\Traits;

use App\Models\Employee;

trait HeadHierarchyLevel
{
    static function getHeadHierarchyLevel($employee_id, $depth = 0){
        while($tmp = Employee::find($employee_id)){
            $depth++;
            $employee_id = Employee::find($employee_id)->boss_id;
        }

        return --$depth;
    }
}
