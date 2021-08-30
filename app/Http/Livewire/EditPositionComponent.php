<?php

namespace App\Http\Livewire;

use App\Models\Position;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EditPositionComponent extends Component
{
    public $positionName;
    public $maxLength;
    public $currentNameLengthCounter;
    public $positionId;
    public $admin_created_id;
    public $admin_updated_id;
    public $created_at;
    public $updated_at;

    public function updated($field, $value){
        $this->validateOnly($field, [
            "positionName"=>"required|max:$this->maxLength"
        ]);

        $this->currentNameLengthCounter = strlen($value);
    }

    public function mount(){
        $position = Position::find($this->positionId);
        $this->positionName = $position->name;
        $this->admin_created_id = $position->admin_created_id;
        $this->admin_updated_id = $position->admin_updated_id;
        $this->created_at = $position->created_at;
        $this->updated_at = $position->updated_at;
        $this->maxLength = 256;
        $this->currentNameLengthCounter = 0;
    }

    public function editPosition(){
        $this->validate([
            "positionName"=>"required|max:$this->maxLength"
        ]);

        $position = Position::find($this->positionId);
        $position->name = $this->positionName;
        $position->admin_created_id = Auth::user()->id;
        $position->admin_updated_id = Auth::user()->id;
        $position->save();

        return redirect()->route("positions");
    }

    public function render()
    {
        return view('livewire.edit-position-component');
    }
}
