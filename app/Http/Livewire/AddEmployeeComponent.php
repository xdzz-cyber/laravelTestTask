<?php

namespace App\Http\Livewire;


use App\Models\Employee;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddEmployeeComponent extends Component
{
    use WithFileUploads;

    public $maxLength;
    public $minLength;
    public $currentNameLengthCounter;
    public $fullname;
    public $photo;
    public $phone;
    public $email;
    public $salary;
    public $position;
    public $head;
    public $dateOfEmployment;


    public function mount(){
        $this->minLength = 0;
        $this->maxLength = 256;
        $this->currentNameLengthCounter = 0;
    }

    public function updated($field,$value){

        $allHeads = Employee::all("fullname");
        $allPositions = Position::all("name");
        $tmpPositions = "";
        foreach ($allPositions as $position){
            $tmpPositions.= " " .$position['name'];
        }

        $allPositions = explode(" ",$tmpPositions);

        $tmpHeads = "";
        foreach ($allHeads as $head){
            $tmpHeads.= "-".$head['fullname'] . "-";
        }
        $tmpHeads = explode("-",$tmpHeads);
        $allHeads = [];
        for ($i = 0; $i < count($tmpHeads)-1;$i+=2){
            $allHeads[] = $tmpHeads[$i]  . $tmpHeads[$i+1];
        }
        //$allHeads = explode(" ",$tmpHeads);

        $this->validateOnly($field,[
            "fullname"=>"required|min:$this->minLength|max:$this->maxLength",
            "photo"=>"required|file|mimes:jpg,png|max:5000",
            "phone"=>["required",Rule::phone()->detect()->country('UA')],
            "email"=>"required|email",
            "salary"=>"required|numeric|between:0,500000",
            "position"=>["required", Rule::in($allPositions)],
            "head"=>["required",Rule::in($allHeads)],
            "dateOfEmployment"=>"required|date"
        ]);


         $this->currentNameLengthCounter = $field == "fullname" ? strlen($value) : $this->currentNameLengthCounter;

    }

    public function addEmployee(){

        $allHeads = Employee::all("fullname");
        $allPositions = Position::all("name");
        $tmpPositions = "";
        foreach ($allPositions as $position){
            $tmpPositions.= " " .$position['name'];
        }

        $allPositions = explode(" ",$tmpPositions);

        $tmpHeads = "";
        foreach ($allHeads as $head){
            $tmpHeads.= "-".$head['fullname'] . "-";
        }
        $tmpHeads = explode("-",$tmpHeads);
        $allHeads = [];
        for ($i = 0; $i < count($tmpHeads)-1;$i+=2){
            $allHeads[] = $tmpHeads[$i]  . $tmpHeads[$i+1];
        }

        $this->validate([
            "fullname"=>"required|min:$this->minLength|max:$this->maxLength",
            "photo"=>"required|file|mimes:jpg,png|max:5000",
            "phone"=>["required",Rule::phone()->detect()->country('UA')],
            "email"=>"required|email",
            "salary"=>"required|numeric|between:0,500000",
            "position"=>["required", Rule::in($allPositions)],
            "head"=>["required",Rule::in($allHeads)],
            "dateOfEmployment"=>"required|date"
            //"phone"=>"required|phone|regex:/\^\+?3?8?(0[\s\.-]\d{2}[\s\.-]\d{3}[\s\.-]\d{2}[\s\.-]\d{2})$/"
        ]);

        $employee = new Employee();
        $employee->fullname = $this->fullname;
        $imageName = Carbon::now()->timestamp . ".{$this->photo->extension()}";
        $this->photo->storeAs("images",$imageName);
        $employee->photo = $imageName;
        $employee->phone = $this->phone;
        $employee->email = $this->email;
        $employee->salary = $this->salary;
        $employee->position = $this->position;
        $employee->admin_created_id = Auth::user()->id;
        $employee->admin_updated_id = Auth::user()->id;
        $employee->boss_id = Employee::where("fullname",$this->head)->first()->id;
        $employee->dateOfEmployment = $this->dateOfEmployment;
        $employee->save();

        return redirect()->route("employees");
    }

    public function render()
    {
        return view('livewire.add-employee-component');
    }
}
