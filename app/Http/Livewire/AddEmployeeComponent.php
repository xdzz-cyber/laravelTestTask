<?php

namespace App\Http\Livewire;


use App\Http\Controllers\EmployeeController;
use App\Models\Employee;
use App\Models\Position;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Image;

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
        $this->minLength = 2;
        $this->maxLength = 256;
        $this->currentNameLengthCounter = 0;
    }

    public function getParsedHeads(){
        $allHeads = Employee::all("fullname");
        $tmpHeads = "";
        foreach ($allHeads as $head){
            $tmpHeads.= "-".$head['fullname'] . "-";
        }
        $tmpHeads = explode("-",$tmpHeads);
        $allHeads = [];
        for ($i = 0; $i < count($tmpHeads)-1;$i+=2){
            $allHeads[] = $tmpHeads[$i]  . $tmpHeads[$i+1];
        }

        return $allHeads;
    }

    public function getParsedPositions(){
        $allPositions = Position::all("name");
        $tmpPositions = "";
        foreach ($allPositions as $position){
            $tmpPositions.= " " .$position['name'];
        }

        $allPositions = explode(" ",$tmpPositions);

        return $allPositions;
    }

    public function updated($field,$value){

        $allHeads = $this->getParsedHeads();
        $allPositions = $this->getParsedPositions();

        $this->validateOnly($field,[
            "fullname"=>"required|min:$this->minLength|max:$this->maxLength",
            "photo"=>"required|file|mimes:jpg,png|max:5000|dimensions:min_width=300,min_height=200",
            "phone"=>["required",Rule::phone()->detect()->country('UA')],
            "email"=>"required|email",
            "salary"=>"required|numeric|between:0,500000",
            "position"=>["required", Rule::in($allPositions)],
            "head"=>["required",function ($attribute, $value, $fail) use ($allHeads) {

                if (!in_array($value, $allHeads) || EmployeeController::getHeadHierarchyLevel(Employee::where("fullname",$this->head)->first()->id) > 5){
                    $fail('Head must be correct and head cannot be more than 5 level of hierarchy');
                }

            }],
            "dateOfEmployment"=>"required|date"
        ]);


         $this->currentNameLengthCounter = $field == "fullname" ? strlen($value) : $this->currentNameLengthCounter;

    }


    public function addEmployee(){

        $allHeads = $this->getParsedHeads();
        $allPositions = $this->getParsedPositions();


        $this->validate([
            "fullname"=>"required|min:$this->minLength|max:$this->maxLength",
            "photo"=>"required|file|mimes:jpg,png|max:5000|dimensions:min_width=300,min_height=200",
            "phone"=>["required",Rule::phone()->detect()->country('UA')],
            "email"=>"required|email",
            "salary"=>"required|numeric|between:0,500000",
            "position"=>["required", Rule::in($allPositions)],
            "head"=>["required",function ($attribute, $value, $fail) use ($allHeads) {
                // your logic

                // if fails you can throw and error message
                if (!in_array($value, $allHeads) || EmployeeController::getHeadHierarchyLevel(Employee::where("fullname",$this->head)->first()->id) > 5){
                    $fail('Head must be correct and head cannot be more than 5 level of hierarchy');
                }

            }],
            "dateOfEmployment"=>"required|date"

        ]);

        $employee = new Employee();
        $employee->fullname = $this->fullname;
        $newImage = Image::make($this->photo->getRealPath(),80);
        $newImage->encode("jpg")->resize(300,300)->fit(300,300);
        $thumbnail_image_name = pathinfo($this->photo->getClientOriginalName(), PATHINFO_FILENAME);
        $newImage->save(public_path("/images/") . $thumbnail_image_name . ".jpg","80","jpg");
        $filename = $thumbnail_image_name . ".jpg";
        $employee->photo = $filename;
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
