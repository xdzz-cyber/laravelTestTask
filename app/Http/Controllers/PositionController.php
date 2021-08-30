<?php

namespace App\Http\Controllers;

use App\DataTables\PositionDataTable;
use App\Models\Position;
use Illuminate\Http\Request;
use DataTables;

class PositionController extends Controller
{
    public function index(PositionDataTable $dataTable){
        return $dataTable->render("positions");
    }

    public function getPositions(){
        $model = \App\Models\Position::query();
        if (request()->ajax()){
            return DataTables::of($model)->addIndexColumn()->addColumn("management", function ($row){
                $actionBtn = "<a href='/admin/positions/edit/{$row['id']}' class='mx-3'><i class='fas fa-edit'></i></a> <a id='removeSweetAlertButton' href='/admin/positions/remove/{$row['id']}' class='mx-1'><i class='fas fa-trash-alt'></i></a>";
                return $actionBtn;
            })->rawColumns(['management'])->make(true);
        }

    }

    public function editPositionPage($position_id){
        return view("editPositionPage", ["positionId"=>$position_id]);
    }

    public function removePosition($position_id){
        $position = Position::find($position_id);
        $position->delete();
        return redirect()->route("positions");
    }

}


