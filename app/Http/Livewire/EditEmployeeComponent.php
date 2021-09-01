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


class EditEmployeeComponent extends Component
{
    use WithFileUploads;

    //public $employee_id;
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
    public $employee_id;
    public $oldPhoto;
    public $created_at;
    public $updated_at;
    public $admin_created_id;
    public $admin_updated_id;

    public $searchedEmployeeHead;
    public $highlightIndex;

    public function updatingHead(){
        $this->searchedEmployeeHead = Employee::where("fullname","like", "%{$this->head}%")->get()->toArray();
    }


    public function resetValues(){
        $this->searchedEmployeeHead = [];
        $this->highlightIndex = 0;
        $this->head = "";
    }

    public function incrementHighlightIndex(){
        if($this->highlightIndex === count($this->searchedEmployeeHead) - 1){
            $this->highlightIndex = 0;
            return;
        }

        $this->highlightIndex+=1;
    }

    public function decrementHighlightIndex(){
        if($this->highlightIndex === 0){
            $this->highlightIndex = count($this->searchedEmployeeHead) - 1;
            return;
        }

        $this->highlightIndex-=1;
    }


    public function selectHeadAndSetIt(){
        $this->resetErrorBag('head');
        $this->head= $this->searchedEmployeeHead[$this->highlightIndex]['fullname'] ?? $this->head;
        $this->searchedEmployeeHead = [];
        $this->highlightIndex = 0;
    }

    public function mount($employee_id){
        $this->resetValues();
        $this->minLength = 2;
        $this->maxLength = 256;
        $this->currentNameLengthCounter = 0;
        $this->employee_id = $employee_id;

        $employee = Employee::find($this->employee_id);
        $head = Employee::find($employee->boss_id);
        $this->fill(["fullname"=>$employee->fullname, "oldPhoto"=>$employee->photo, "phone"=>$employee->phone,
            "email"=>$employee->email, "salary"=>$employee->salary, "position"=>$employee->position,"head"=>$head->fullname,
            "dateOfEmployment"=>$employee->dateOfEmployment, "created_at"=>$employee->created_at,
            "updated_at"=>$employee->updated_at, "admin_created_id"=>$employee->admin_created_id, "admin_updated_id"=>$employee->admin_updated_id]);

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

        $allHeads = array_filter($allHeads, function ($val){
           if (EmployeeController::getHeadHierarchyLevel(Employee::where("fullname",$val)->first()->id)){
               return true;
           }
           return false;
        });

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

        $this->validateOnly($field,["fullname"=>"required|min:$this->minLength|max:$this->maxLength",
            "phone"=>["required",Rule::phone()->detect()->country('UA')],
            "email"=>"required|email",
            "photo"=>$this->photo ? "required|file|mimes:jpg,png|max:5000|dimensions:min_width=300,min_height=200" : "",
            "salary"=>"required|numeric|between:0,500000",
            "position"=>["required", Rule::in($allPositions)],
            "dateOfEmployment"=>"required|date"]);

        $this->currentNameLengthCounter = EmployeeController::getHeadHierarchyLevel($this->employee_id);
    }


    public function editEmployee(\Illuminate\Http\Request $request){

        $allHeads = $this->getParsedHeads();
        $allPositions = $this->getParsedPositions();

        $this->validate(
            ["fullname"=>"required|min:$this->minLength|max:$this->maxLength",
                "phone"=>["required",Rule::phone()->detect()->country('UA')],
                "email"=>"required|email",
                "photo"=>$this->photo ? "required|file|mimes:jpg,png|max:5000|dimensions:min_width=300,min_height=200" : "",
                "salary"=>"required|numeric|between:0,500000",
                "position"=>["required", Rule::in($allPositions)],
                "head"=>["required",function ($attribute, $value, $fail) use ($allHeads) {
                    // your logic

                    // if fails you can throw and error message
                    if (!in_array($value, $allHeads) || EmployeeController::getHeadHierarchyLevel(Employee::where("fullname",$this->head)->first()->id) > 5){
                        $fail('Head must be correct and head cannot be more than 5 level of hierarchy');
                    }

                }],
                "dateOfEmployment"=>"required|date"]

        );

        $employee = Employee::find($this->employee_id);
        $employee->fullname = $this->fullname;
        if($this->photo){
            $newImage = Image::make($this->photo->getRealPath(),80);
            $newImage->encode("jpg")->resize(300,300)->fit(300,300);
            $thumbnail_image_name = pathinfo($this->photo->getClientOriginalName(), PATHINFO_FILENAME);
            $newImage->save(public_path("/images/") . $thumbnail_image_name . ".jpg","80","jpg");
            $filename = $thumbnail_image_name . ".jpg";
            $employee->photo = $filename;
        }

        $employee->phone = $this->phone;
        $employee->email = $this->email;
        $employee->salary = $this->salary;
        $employee->position = $this->position;
        $employee->boss_id = Employee::where("fullname",$this->head)->first()->id;
        $employee->dateOfEmployment = $this->dateOfEmployment;
        $employee->updated_at = Carbon::now();
        $employee->admin_updated_id = Auth::user()->id;

        if ($this->photo){
            unlink("images/{$this->oldPhoto}");
        }

        $employee->save();

        return redirect()->route("employees");
    }



    public function render()
    {
        return view('livewire.edit-employee-component');
    }
}
