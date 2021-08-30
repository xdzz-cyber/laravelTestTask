<?php

namespace App\Http\Livewire;

use App\Models\Position;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AddPositionComponent extends Component
{
    public $positionName;
    public $maxLength;
    public $currentNameLengthCounter;

    public function updated($field, $value){
        $this->validateOnly($field, [
            "positionName"=>"required|max:$this->maxLength"
        ]);

        $this->currentNameLengthCounter = strlen($value);
    }

    public function mount(){
        $this->maxLength = 256;
        $this->currentNameLengthCounter = 0;
    }


    public function addPosition(){

        $this->validate([
           "positionName"=>"required|max:$this->maxLength"
        ]);

        $position = new Position();
        $position->name = $this->positionName;
        $position->admin_created_id = Auth::user()->id;
        $position->admin_updated_id = Auth::user()->id;
        $position->save();

        return redirect()->route("positions");
    }

    public function render()
    {
        return view('livewire.add-position-component');
    }
}
