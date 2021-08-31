<?php

namespace App\Http\Livewire;


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

    //protected $listeners = ["headHasBeenUpdated"=>"selectHeadAndSetIt"];

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

//    public function changeHighlightIndex($increment){
//        if (($increment && $this->highlightIndex == count($this->searchedEmployeeHead) - 1) || (!$increment && $this->highlightIndex == 0)){
//            $this->highlightIndex = 0;
//            return;
//        }
//        $increment ? $this->highlightIndex+=1 : $this->highlightIndex-=1;
//    }

    public function selectHeadAndSetIt(){
        $this->head= $this->searchedEmployeeHead[$this->highlightIndex]['fullname'] ?? $this->head;
        $this->searchedEmployeeHead = [];
        $this->highlightIndex = 0;
    }

    public function mount($employee_id){
        $this->resetValues();
        $this->minLength = 0;
        $this->maxLength = 256;
        $this->currentNameLengthCounter = 0;
        $this->employee_id = $employee_id;

        $employee = Employee::find($this->employee_id);
        $head = Employee::find($employee->boss_id);
        $this->fill(["fullname"=>$employee->fullname, "oldPhoto"=>$employee->photo, "phone"=>$employee->phone,
            "email"=>$employee->email, "salary"=>$employee->salary, "position"=>$employee->position,"head"=>$head->fullname, "dateOfEmployment"=>$employee->dateOfEmployment]);
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


        $this->validateOnly($field,["fullname"=>"required|min:$this->minLength|max:$this->maxLength",
            "phone"=>["required",Rule::phone()->detect()->country('UA')],
            "email"=>"required|email",
            "photo"=>$this->photo ? "required|file|mimes:jpg,png|max:5000|dimensions:min_width=300,min_height=200" : "",
            "salary"=>"required|numeric|between:0,500000",
            "position"=>["required", Rule::in($allPositions)],
            "dateOfEmployment"=>"required|date"]);


        $this->currentNameLengthCounter = $field == "fullname" ? strlen($value) : $this->currentNameLengthCounter;
        //$this->currentNameLengthCounter = $this->highlightIndex;
    }

    public function submit(){

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

        $this->validate(
            ["fullname"=>"required|min:$this->minLength|max:$this->maxLength",
                "phone"=>["required",Rule::phone()->detect()->country('UA')],
                "email"=>"required|email",
                "photo"=>$this->photo ? "required|file|mimes:jpg,png|max:5000|dimensions:min_width=300,min_height=200" : "",
                "salary"=>"required|numeric|between:0,500000",
                "position"=>["required", Rule::in($allPositions)],
                "head"=>["required",Rule::in($allHeads)],
                "dateOfEmployment"=>"required|date"]

        );
    }

    public function editEmployee(\Illuminate\Http\Request $request){

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

        $this->validate(
            ["fullname"=>"required|min:$this->minLength|max:$this->maxLength",
                "phone"=>["required",Rule::phone()->detect()->country('UA')],
                "email"=>"required|email",
                "photo"=>$this->photo ? "required|file|mimes:jpg,png|max:5000|dimensions:min_width=300,min_height=200" : "",
                "salary"=>"required|numeric|between:0,500000",
                "position"=>["required", Rule::in($allPositions)],
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
        $employee->admin_created_id = Auth::user()->id;
        $employee->admin_updated_id = Auth::user()->id;
        $employee->boss_id = Employee::where("fullname",$this->head)->first()->id;
        $employee->dateOfEmployment = $this->dateOfEmployment;

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
